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

class AdminController extends Controller
{
    public function dashboard()
    {
        $lowStockThreshold = 20;

        // Basic Stats
        $stats = [
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_active_products' => Product::where('status_del', 0)->count(),
            'total_revenue' => Order::sum('orders_total_price'),
        ];

        $recentOrders = Order::with('user')
            ->latest('orders_date')
            ->take(5)
            ->get();

        // Get low stock products based on category type
        $stockAlertProducts = Product::with('category')
            ->where('status_del', 0)
            ->where(function ($query) {
                $query->whereIn(DB::raw('(SELECT categories_name FROM categories WHERE categories_id = products.categories_id)'), $this->fastMovingCategories)
                    ->where('products_stock', '<=', 20)
                    ->orWhereIn(DB::raw('(SELECT categories_name FROM categories WHERE categories_id = products.categories_id)'), $this->slowMovingCategories)
                    ->where('products_stock', '<=', 10);
            })
            ->orderBy('products_stock', 'asc')
            ->take(5)
            ->get();

        $orderStatusCounts = Order::query()
            ->selectRaw('orders_status, count(*) as total_count')
            ->groupBy('orders_status')
            ->pluck('total_count', 'orders_status');

        $definedOrderStatuses = [
            'Pending'    => ['badge_class' => 'bg-secondary', 'name' => 'Pending'],
            'Processing' => ['badge_class' => 'bg-warning text-dark', 'name' => 'Processing'],
            'Shipped'    => ['badge_class' => 'bg-info', 'name' => 'Shipped'],
            'Delivered'  => ['badge_class' => 'bg-success', 'name' => 'Delivered'],
            'Cancelled'  => ['badge_class' => 'bg-danger', 'name' => 'Cancelled'],
        ];

        $orderStatusOverview = [];
        foreach ($definedOrderStatuses as $statusKey => $statusData) {
            $orderStatusOverview[] = (object)[
                'name' => $statusData['name'],
                'count' => $orderStatusCounts->get($statusKey, 0),
                'badge_class' => $statusData['badge_class']
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentOrders', 'stockAlertProducts', 'lowStockThreshold', 'orderStatusOverview'));
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
        $query->where(function($q) {
            $q->where('products_stock', '>', DB::raw('IFNULL(low_stock_threshold, 10)'));
        });
    } elseif ($status === 'Low Stock') {
        $query->where(function($q) {
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

        // Handle Image Upload (Optional Update)
        if ($request->hasFile('products_image')) {
            // Delete old image if exists
            if ($product->products_image && file_exists(public_path('images/products-img/' . $product->products_image))) {
                unlink(public_path('images/products-img/' . $product->products_image));
            }

            $image = $request->file('products_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products-img'), $imageName);
            $product->products_image = $imageName;
        }

        // Handle Hover Image Update
        if ($request->hasFile('hover_image')) {
            // Delete old hover image if exists
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
}

