<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::sum('orders_total_price'),
            'total_products' => Product::count(),
        ];

        return view('admin.dashboard', compact('stats'));

        if (!session('is_admin') && Auth::check()) {
            return redirect()->route('login.show');
        }

        return view('admin.dashboard');
    }

    public function products()
    {

        $products = Product::orderBy('updated_at', 'desc')->get();
        $categories = Category::pluck('categories_name', 'categories_id')->toArray();


        $totalProducts = count($products);
        $currentPage = 1;
        $perPage = 10;
        $totalPages = ceil($totalProducts / $perPage);

        return view('admin.products.index', compact('products', 'categories', 'totalProducts', 'currentPage', 'perPage', 'totalPages'));
    }

    public function createProduct()
    {
        $categories = Category::pluck('categories_name', 'categories_id')->toArray();
        $product = null;
        return view('admin.products.index', compact('categories'));
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::pluck('categories_name', 'categories_id')->toArray();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function insertProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'nullable|string',
            'weight' => 'nullable|string',
            'category' => 'required|string',
            'image' => 'nullable|string',
            'storage_temp' => 'nullable|string',
            'featured' => 'boolean',
            'status' => 'required|string',
        ]);

        Product::create($validated);

        return redirect(route('admin.products'))
            ->with('success', 'Product added successfully!');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'nullable|string',
            'weight' => 'nullable|string',
            'category' => 'required|string',
            'image' => 'nullable|string',
            'storage_temp' => 'nullable|string',
            'featured' => 'boolean',
            'status' => 'required|string',
        ]);

        $product->name = $request->name;
        $product->details = $request->details;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->product_category;

        return redirect(route('admin.products'))
            ->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        Product::findOrFail($id)->delete();

        return redirect(route('admin.products'))
            ->with('success', 'Product deleted successfully!');
    }
}
