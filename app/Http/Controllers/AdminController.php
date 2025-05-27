<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Added for image handling

class AdminController extends Controller
{

    public function dashboard()
    {
        $lowStockThreshold = 20;

        // Basic Stats (Ensure 'orders_total_price' is your revenue column)
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::sum('orders_total_price'),
            'total_products' => Product::where('status_del', 0)->count(),
        ];

        $recentOrders = Order::with('user')
                             ->latest('orders_date')
                             ->take(5)
                             ->get();

        $stockAlertProducts = Product::where('status_del', 0)
                                   ->where('products_stock', '<=', $lowStockThreshold)
                                   ->orderBy('products_stock', 'asc')
                                   ->take(5)
                                   ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'stockAlertProducts','lowStockThreshold'));
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
            $lowStockThreshold = 20;

            if ($status === 'In Stock') {
                $query->where('products_stock', '>', $lowStockThreshold);
            } elseif ($status === 'Low Stock') {
                $query->whereBetween('products_stock', [1, $lowStockThreshold]);
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
            'products_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hover_image' => 'nullable|string|max:255',
            'status_del' => 'nullable|boolean',
            'orders_price' => 'required|integer|min:0'
        ]);

        $product = new Product();
        $product->products_name = $validatedData['products_name'];
        $product->unit_price = $validatedData['unit_price'];
        $product->products_stock = $validatedData['products_stock'];
        $product->products_description = $validatedData['products_description'] ?? '';
        $product->categories_id = $validatedData['categories_id'];
        $product->hover_image = $validatedData['hover_image'] ?? '';
        $product->orders_price = $validatedData['orders_price'];
        $product->status_del = 0;

        // Handle Image Upload
        if ($request->hasFile('products_image')) {
            $path = $request->file('products_image')->store('products', 'public');
            $product->products_image = basename($path); // Store only filename if using asset() like in view
        }

        // Add logic for 'status' (In Stock, Low Stock etc.) if you have that column
        // e.g., $product->status = $this->determineStatus($validatedData['products_stock']);

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
            'hover_image' => 'nullable|string|max:255',
            'status_del' => 'nullable|boolean',
            'orders_price' => 'required|integer|min:0'
        ]);

        $product->products_name = $validatedData['products_name'];
        $product->unit_price = $validatedData['unit_price'];
        $product->products_stock = $validatedData['products_stock'];
        $product->products_description = $validatedData['products_description'] ?? '';
        $product->categories_id = $validatedData['categories_id'];
        $product->hover_image = $validatedData['hover_image'] ?? $product->hover_image;
        $product->orders_price = $validatedData['orders_price'] ?? $product->orders_price;
        $product->status_del = 0;

        // Handle Image Upload (Optional Update)
        if ($request->hasFile('products_image')) {
            // Optional: Delete old image
            // if ($product->products_image) {
            //     Storage::disk('public')->delete('products/' . $product->products_image);
            // }
            $path = $request->file('products_image')->store('products', 'public');
            $product->products_image = basename($path);
        }

        $product->save();

        return redirect(route('admin.products'))
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Fetch product data for AJAX editing.
     *
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductData(Product $product)
    {
        // You can load relationships if needed, but usually just the model is enough
        // $product->load('category');
        return response()->json($product);
    }

    public function deleteProduct(Product $product)
    {
        try {
            // Set kolom status_del menjadi 1
            $product->status_del = 1;

            // Simpan perubahan ke database
            $product->save();

            // Berikan pesan sukses yang sesuai
            return redirect(route('admin.products'))
                ->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            // Tangani jika ada error saat menyimpan
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
            // Set the status_del back to 0 (or null if you prefer)
            $product->status_del = 0;

            // Save the change
            $product->save();

            // Redirect back to the trash view with a success message
            return redirect()->route('admin.products.trash')
                         ->with('success', "Product '{$product->products_name}' has been restored successfully!");

        } catch (\Exception $e) {
            // Handle potential errors
            return redirect()->route('admin.products.trash')
                         ->with('error', 'Could not restore product. ' . $e->getMessage());
        }
    }
}
