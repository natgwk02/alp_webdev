<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {

        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('orders_status', '!=', 'Cancelled')->sum('orders_total_price'),
            'total_products' => Product::where('status_del', 0)->count()
        ];


        $stockAlertProducts = Product::with('category')
            ->where('status_del', 0)
            ->where(function ($query) {
                $query->where('products_stock', '<=', 0)
                    ->orWhere(function ($q) {
                        $q->where('products_stock', '>', 0)
                            ->whereRaw('products_stock <= IFNULL(low_stock_threshold, 10)'); //low stok = 10
                    });
            })
            ->orderBy('products_stock', 'asc')
            ->get();

        $recentOrders = Order::with('user')
            ->orderBy('orders_date', 'desc')
            ->take(5)
            ->get();

        $orderStatusOverview = Order::selectRaw('orders_status as name, count(*) as count')
            ->groupBy('orders_status')
            ->get()
            ->map(function ($item) {
                $item->badge_class = $this->getStatusBadgeClass($item->name);
                return $item;
            });

        $periodStartDate = Carbon::now()->subDays(30);
        $periodEndDate = Carbon::now();
        $topLimit = 5;

        // Top products by REVENUE
        $topProductsByRevenue = DB::table('order_details')
            ->join('products', 'order_details.products_id', '=', 'products.products_id')
            ->join('orders', 'order_details.orders_id', '=', 'orders.orders_id')
            ->select('products.products_name', DB::raw('SUM(order_details.order_details_quantity * price) as total_revenue'))
            ->where('orders_status', '!=', 'Cancelled')
            ->whereBetween('orders.orders_date', [$periodStartDate, $periodEndDate])
            ->where('products.status_del', 0)
            ->groupBy('products.products_id', 'products.products_name')
            ->orderByDesc('total_revenue')
            ->take($topLimit)
            ->get();

        // Top products by QUANTITY SOLD
        $topProductsByQuantity = DB::table('order_details')
            ->join('products', 'order_details.products_id', '=', 'products.products_id')
            ->join('orders', 'order_details.orders_id', '=', 'orders.orders_id')
            ->select('products.products_name', DB::raw('SUM(order_details.order_details_quantity) as total_quantity'))
            ->whereBetween('orders.orders_date', [$periodStartDate, $periodEndDate])
            ->where('products.status_del', 0)
            ->groupBy('products.products_id', 'products.products_name')
            ->orderByDesc('total_quantity')
            ->take($topLimit)
            ->get();

        $salesTrendDays = 30;
        $trendEndDate = Carbon::now()->endOfDay();
        $trendStartDate = Carbon::now()->subDays($salesTrendDays - 1)->startOfDay();

        $dailyRevenue = Order::select(
            DB::raw('DATE(orders_date) as date'),
            DB::raw('SUM(orders_total_price) as total_revenue')
        )
            ->where('orders_status', '!=', 'Cancelled')
            ->whereBetween('orders_date', [$trendStartDate, $trendEndDate])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->keyBy('date');

        $salesTrendLabels = [];
        $salesTrendRevenueData = [];
        $currentDateIterator = $trendStartDate->copy();

        while ($currentDateIterator <= $trendEndDate) {
            $dateString = $currentDateIterator->toDateString();
            $salesTrendLabels[] = $currentDateIterator->format('M d');
            $salesTrendRevenueData[] = $dailyRevenue->has($dateString) ? $dailyRevenue[$dateString]->total_revenue : 0;
            $currentDateIterator->addDay();
        }

        return view('admin.dashboard', compact(
            'stats',
            'stockAlertProducts',
            'recentOrders',
            'orderStatusOverview',
            'topProductsByRevenue',
            'topProductsByQuantity',
            'topLimit',
            'periodStartDate',
            'periodEndDate',
            'salesTrendLabels',
            'salesTrendRevenueData',

        ));
    }

    private function getStatusBadgeClass($status)
    {
        switch (strtolower($status)) {
            case 'completed':
                return 'bg-success';
            case 'processing delivery':
                return 'bg-primary';
            case 'pending delivery':
                return 'bg-warning text-dark';
            case 'cancelled':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    public function products(Request $request)
    {
        $query = Product::with('category')
            ->where(function ($q) {
                $q->where('status_del', 0)
                    ->orWhereNull('status_del');
            })
            ->orderBy('products_id');

        if ($request->filled('search')) {
            $query->where('products_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category') && $request->category !== 'All Categories') {
            $query->where('categories_id', $request->category);
        }

        if ($request->filled('status')) {
            $status = $request->status;

            if ($status === 'In Stock') {
                $query->where(function ($q) {
                    $q->where('products_stock', '>', DB::raw('IFNULL(low_stock_threshold, 10)'));
                });
            } elseif ($status === 'Low Stock') {
                $query->where(function ($q) {
                    $q->where('products_stock', '>', 0)
                        ->where('products_stock', '<=', DB::raw('IFNULL(low_stock_threshold, 10)'));
                });
            } elseif ($status === 'Out of Stock') {
                $query->where('products_stock', '<=', 0);
            }
        }

        $products = $query->paginate(10);
        $categories = Category::pluck('categories_name', 'categories_id')->all();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories,
            'current_search' => $request->search,
            'current_category' => $request->category,
            'current_status' => $request->status,
        ]);
    }

    public function insertProduct(Request $request)
    {
        $validatedData = $request->validate([
            'products_name' => 'required|string|max:50',
            'unit_price' => 'required|integer|min:0',
            'products_stock' => 'required|integer|min:0',
            'products_description' => 'nullable|string',
            'categories_id' => 'required|exists:categories,categories_id',
            'products_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'orders_price' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1'

        ]);

        $product = new Product();
        $product->products_name = $validatedData['products_name'];
        $product->unit_price = $validatedData['unit_price'];
        $product->products_stock = $validatedData['products_stock'];
        $product->products_description = $validatedData['products_description'] ?? '';
        $product->categories_id = $validatedData['categories_id'];
        $product->orders_price = $validatedData['orders_price'];
        $product->status_del = 0;
        $product->low_stock_threshold = $validatedData['low_stock_threshold'];


        // Handle Image Upload
        if ($request->hasFile('products_image')) {
            $image = $request->file('products_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products-img'), $imageName);
            $product->products_image = $imageName;
        }

        if ($request->hasFile('hover_image')) {
            $hoverImage = $request->file('hover_image');
            $hoverImageName = time() . '_hover_' . $hoverImage->getClientOriginalName();
            $hoverImage->move(public_path('images/hoverproducts-img'), $hoverImageName);
            $product->hover_image = $hoverImageName;
        }

        $product->save();

        return redirect(route('admin.products'))
            ->with('success', 'Product added successfully!');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'products_name' => 'required|string|max:50',
            'unit_price' => 'required|integer|min:0',
            'products_stock' => 'required|integer|min:0',
            'products_description' => 'nullable|string',
            'categories_id' => 'required|exists:categories,categories_id',
            'products_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status_del' => 'nullable|boolean',
            'orders_price' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1'

        ]);

        $product->products_name = $validatedData['products_name'];
        $product->unit_price = $validatedData['unit_price'];
        $product->products_stock = $validatedData['products_stock'];
        $product->products_description = $validatedData['products_description'] ?? '';
        $product->categories_id = $validatedData['categories_id'];
        $product->orders_price = $validatedData['orders_price'];
        $product->status_del = 0;
        $product->low_stock_threshold = $validatedData['low_stock_threshold']; // This should be present

        if ($request->hasFile('products_image')) {
            if ($product->products_image && file_exists(public_path('images/products-img/' . $product->products_image))) {
                unlink(public_path('images/products-img/' . $product->products_image));
            }

            $image = $request->file('products_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products-img'), $imageName);
            $product->products_image = $imageName;
        }

        if ($request->hasFile('hover_image')) {
            if ($product->hover_image && file_exists(public_path('images/hoverproducts-img/' . $product->hover_image))) {
                unlink(public_path('images/hoverproducts-img/' . $product->hover_image));
            }

            $hoverImage = $request->file('hover_image');
            $hoverImageName = time() . '_hover_' . $hoverImage->getClientOriginalName();
            $hoverImage->move(public_path('images/hoverproducts-img'), $hoverImageName);
            $product->hover_image = $hoverImageName;
        }

        $product->save();

        return redirect(route('admin.products'))
            ->with('success', 'Product updated successfully!');
    }

    public function getProductData(Product $product)
    {
        return response()->json([
            'products_name' => $product->products_name,
            'categories_id' => $product->categories_id,
            'unit_price' => $product->unit_price,
            'orders_price' => $product->orders_price,
            'products_stock' => $product->products_stock,
            'low_stock_threshold' => $product->low_stock_threshold,

            'products_description' => $product->products_description,
            'products_image' => $product->products_image,
            'hover_image' => $product->hover_image,
        ]);
    }

    public function deleteProduct(Product $product)
    {
        try {
            $product->status_del = 1;
            $product->save();

            return redirect(route('admin.products'))
                ->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return redirect(route('admin.products'))
                ->with('error', 'Could not delete product.' . $e->getMessage());
        }
    }

    public function trash(Request $request)
    {
        $query = Product::with('category')
            ->where('status_del', 1)
            ->orderBy('updated_at', 'desc');

        $trashedProducts = $query->paginate(10)->withQueryString();
        $categories = Category::pluck('categories_name', 'categories_id')->all();

        return view('admin.products.trash', compact('trashedProducts', 'categories'));
    }

    public function restore(Product $product)
    {
        try {
            $product->status_del = 0;
            $product->save();

            return redirect()->route('admin.products.trash')
                ->with('success', "Product '{$product->products_name}' has been restored successfully!");
        } catch (\Exception $e) {
            return redirect()->route('admin.products.trash')
                ->with('error', 'Could not restore product. ' . $e->getMessage());
        }
    }

    public function editData($productId)
    {
        $product = Product::with('category')->findOrFail($productId);

        return response()
            ->json($product)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function getSalesTrendData(Request $request)
    {
        $periodInput = $request->input('period', '30d');
        $dataType = $request->input('dataType', 'revenue');

        $labels = [];
        $dataValues = [];
        $datasetLabel = '';
        $labelFormat = 'M d';

        $endDate = Carbon::now()->endOfDay();

        if ($periodInput === '7d') {
            $days = 7;
            $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
            $datasetLabelSuffix = 'Last 7 Days';
        } elseif ($periodInput === '30d') {
            $days = 30;
            $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
            $datasetLabelSuffix = 'Last 30 Days';
        } elseif (str_starts_with($periodInput, 'monthly_')) {
            $numberOfMonths = (int) str_replace('monthly_', '', $periodInput);
            if ($numberOfMonths <= 0) $numberOfMonths = 6;
            $datasetLabelSuffix = "Last {$numberOfMonths} Months";
            $labelFormat = 'M Y';

            for ($i = $numberOfMonths - 1; $i >= 0; $i--) {
                $monthCarbon = Carbon::now()->subMonths($i);
                $labels[] = $monthCarbon->format($labelFormat);

                if ($dataType === 'revenue') {
                    $value = Order::where('orders_status', '!=', 'Cancelled')
                        ->whereYear('orders_date', $monthCarbon->year)
                        ->whereMonth('orders_date', $monthCarbon->month)
                        ->sum('orders_total_price');
                } else { // 'orders'
                    $value = Order::whereYear('orders_date', $monthCarbon->year)
                        ->whereMonth('orders_date', $monthCarbon->month)
                        ->count();
                }
                $dataValues[] = $value;
            }

            $datasetLabel = ($dataType === 'revenue' ? 'Revenue' : 'Orders') . " ({$datasetLabelSuffix})";
            return response()->json([
                'labels' => $labels,
                'datasets' => [[
                    'label' => $datasetLabel,
                    'data' => $dataValues,
                    'borderColor' => $dataType === 'revenue' ? 'rgb(54, 162, 235)' : 'rgb(255, 99, 132)',
                    'backgroundColor' => $dataType === 'revenue' ? 'rgba(54, 162, 235, 0.2)' : 'rgba(255, 99, 132, 0.2)',
                    'tension' => 0.2,
                    'fill' => true,
                ]],
                'dataType' => $dataType,
                'period' => $periodInput
            ]);
        } else {
            $days = 30;
            $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
            $datasetLabelSuffix = 'Last 30 Days';
        }

        $queryBase = Order::select(DB::raw("DATE(orders_date) as point"));

        if ($dataType === 'revenue') {
            $queryBase->addSelect(DB::raw('SUM(orders_total_price) as total_value'))
                ->where('orders_status', '!=', 'Cancelled');
            $datasetLabel = "Revenue ({$datasetLabelSuffix})";
        } else {
            $queryBase->addSelect(DB::raw('COUNT(*) as total_value'));
            $datasetLabel = "Orders ({$datasetLabelSuffix})";
        }

        $queryData = $queryBase->whereBetween('orders_date', [$startDate, $endDate])
            ->groupBy('point')
            ->orderBy('point', 'ASC')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->point)->format('Y-m-d');
            });

        $currentDateIterator = $startDate->copy();
        while ($currentDateIterator <= $endDate) {
            $dateString = $currentDateIterator->format('Y-m-d');
            $labels[] = $currentDateIterator->format($labelFormat);
            $dataValues[] = $queryData->has($dateString) ? $queryData[$dateString]->total_value : 0;
            $currentDateIterator->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [[
                'label' => $datasetLabel,
                'data' => $dataValues,
                'borderColor' => $dataType === 'revenue' ? 'rgb(54, 162, 235)' : 'rgb(255, 99, 132)',
                'backgroundColor' => $dataType === 'revenue' ? 'rgba(54, 162, 235, 0.2)' : 'rgba(255, 99, 132, 0.2)',
                'tension' => 0.2,
                'fill' => true,
            ]],
            'dataType' => $dataType,
            'period' => $periodInput
        ]);
    }
}
