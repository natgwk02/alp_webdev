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

    public function insertProduct(Request $request, Product $product)
    {
        $request->validate([
            'products_name' => 'required|string|max:50',
            'unit_price' => 'required|integer|min:0',
            'products_stock' => 'required|integer|min:0',
            'products_description' => 'nullable|string',
            'categories_id' => 'required|exists:categories,categories_id',
            'products_image' => 'nullable|string|max:100',
            'hover_image' => 'nullable|string|max:255',
            'orders_price' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'status_del' => 'nullable|boolean',
        ], [
            'products_name.required' => 'Product name is required.',
            'products_name.max' => 'Product name may not be greater than 50 characters.',

            'unit_price.required' => 'Price is required.',
            'unit_price.integer' => 'Price must be a whole number.',
            'unit_price.min' => 'Price cannot be negative.',

            'products_stock.required' => 'Stock is required.',
            'products_stock.integer' => 'Stock must be a whole number.',
            'products_stock.min' => 'Stock cannot be negative.',

            'categories_id.required' => 'Product category is required.',

            'products_image.max' => 'Image URL may not be greater than 100 characters.',
            'hover_image.max' => 'Hover image URL may not be greater than 255 characters.',

            'orders_price.integer' => 'Order price must be a number.',
            'orders_price.min' => 'Order price cannot be negative.',

            'rating.numeric' => 'Rating must be a number.',
            'rating.min' => 'Rating cannot be less than 0.',
            'rating.max' => 'Rating cannot be more than 5.',

            'status_del.boolean' => 'Invalid status format.',
        ]);

        $product = new Product();

        $product->name = $request->name;
        $product->unit_price = $request->unit_price;
        $product->stock = $request->stock;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->image = $request->image;
        $product->hover_image = $request->hover_image;
        $product->orders_price = $request->orders_price;
        $product->rating = $request->rating;
        $product->status_del = $request->status_del;

        $product->save();

        return redirect(route('admin.products'))
            ->with('success', 'Product added successfully!');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'products_name' => 'required|string|max:50',
            'unit_price' => 'required|integer|min:0',
            'products_stock' => 'required|integer|min:0',
            'products_description' => 'nullable|string',
            'categories_id' => 'required|exists:categories,categories_id',
            'products_image' => 'nullable|string|max:100',
            'hover_image' => 'nullable|string|max:255',
            'orders_price' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'status_del' => 'nullable|boolean',
        ], [
            'products_name.required' => 'Product name is required.',
            'products_name.max' => 'Product name may not be greater than 50 characters.',

            'unit_price.required' => 'Price is required.',
            'unit_price.integer' => 'Price must be a whole number.',
            'unit_price.min' => 'Price cannot be negative.',

            'products_stock.required' => 'Stock is required.',
            'products_stock.integer' => 'Stock must be a whole number.',
            'products_stock.min' => 'Stock cannot be negative.',

            'categories_id.required' => 'Product category is required.',

            'products_image.max' => 'Image URL may not be greater than 100 characters.',
            'hover_image.max' => 'Hover image URL may not be greater than 255 characters.',

            'orders_price.integer' => 'Order price must be a number.',
            'orders_price.min' => 'Order price cannot be negative.',

            'rating.numeric' => 'Rating must be a number.',
            'rating.min' => 'Rating cannot be less than 0.',
            'rating.max' => 'Rating cannot be more than 5.',

            'status_del.boolean' => 'Invalid status format.',
        ]);

        $product = new Product();

        $product->name = $request->name;
        $product->unit_price = $request->unit_price;
        $product->stock = $request->stock;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->image = $request->image;
        $product->hover_image = $request->hover_image;
        $product->orders_price = $request->orders_price;
        $product->rating = $request->rating;
        $product->status_del = $request->status_del;

        $product->save();

        return redirect(route('admin.products'))
            ->with('success', 'Product updated successfully!');
    }

    public function deleteProduct(Request $request, Product $product)
    {
        // $request->validate([
        //     'id' => 'required|exists:products,products_id',
        // ], [
        //     'id.required' => 'Product ID is required.',
        //     'id.exists' => 'Product not found.',
        // ]);

        // Soft delete the product
        {
            // Product::findOrFail($id)->delete();

            return redirect(route('admin.products'))
                ->with('success', 'Product deleted successfully!');
        }
    }
}
