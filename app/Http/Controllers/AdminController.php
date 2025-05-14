<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Hardcoded stats for dashboard
        $stats = [
            'total_orders' => 42,
            'total_revenue' => 1250.75,
            'total_products' => 15,
            'new_customers' => 8
        ];

        return view('admin.dashboard', compact('stats'));

        if (!session('is_admin') && Auth::check()) {
        return redirect()->route('login.show');
    }

        return view('admin.dashboard');
    }

    public function products()
    {
        // Sample products data matching your view structure
        $products = [
            [
                'id' => 'PRD001',
                'name' => 'Gourmet Frozen Pizza',
                'image' => 'https://via.placeholder.com/60',
                'category' => 'Ready Meals',
                'price' => 25000,
                'stock' => 125,
                'status' => 'In Stock',
                'updated_at' => '2025-05-10'
            ],
            [
                'id' => 'PRD002',
                'name' => 'Organic Mixed Vegetables',
                'image' => 'https://via.placeholder.com/60',
                'category' => 'Frozen Vegetables',
                'price' => 36000,
                'stock' => 210,
                'status' => 'In Stock',
                'updated_at' => '2025-05-12'
            ],
            [
                'id' => 'PRD003',
                'name' => 'Premium Vanilla Ice Cream',
                'image' => 'https://via.placeholder.com/60',
                'category' => 'Ice Cream & Desserts',
                'price' => 43000,
                'stock' => 78,
                'status' => 'In Stock',
                'updated_at' => '2025-05-11'
            ],
            [
                'id' => 'PRD004',
                'name' => 'Chicken Alfredo Meal',
                'image' => 'https://via.placeholder.com/60',
                'category' => 'Ready Meals',
                'price' => 39000,
                'stock' => 15,
                'status' => 'Low Stock',
                'updated_at' => '2025-05-09'
            ],
            [
                'id' => 'PRD005',
                'name' => 'Frozen Salmon Fillets',
                'image' => 'https://via.placeholder.com/60',
                'category' => 'Frozen Meat & Fish',
                'price' => 74999,
                'stock' => 0,
                'status' => 'Out of Stock',
                'updated_at' => '2025-05-08'
            ],
            [
                'id' => 'PRD006',
                'name' => 'Chocolate Chip Cookie Dough',
                'image' => 'https://via.placeholder.com/60',
                'category' => 'Ice Cream & Desserts',
                'price' => 24999,
                'stock' => 89,
                'status' => 'In Stock',
                'updated_at' => '2025-05-07'
            ]
        ];

        // Get unique categories for the filter dropdown
        $categories = array_unique(array_column($products, 'category'));

        // For pagination information
        $totalProducts = count($products);
        $currentPage = 1;
        $perPage = 6;
        $totalPages = ceil($totalProducts / $perPage);

        return view('admin.products.index', compact('products', 'categories', 'totalProducts', 'currentPage', 'perPage', 'totalPages'));
    }

    public function createProduct()
    {
        $categories = ['Ready Meals', 'Frozen Vegetables', 'Ice Cream & Desserts', 'Frozen Meat & Fish'];
        return view('admin.products.index', compact('categories'));
    }

    public function editProduct($id)
    {
        // Find the product by ID

        $product = [
            'id' => $id,
            'name' => 'Gourmet Frozen Pizza',
            'price' => 25000,
            'stock' => 125,
            'description' => 'Delicious gourmet pizza with premium toppings, ready to bake from frozen.',
            'weight' => '400g',
            'category' => 'Ready Meals',
            'image' => 'https://via.placeholder.com/100',
            'storage_temp' => 'freezer',
            'featured' => true,
            'status' => 'In Stock'
        ];

        $categories = ['Ready Meals', 'Frozen Vegetables', 'Ice Cream & Desserts', 'Frozen Meat & Fish'];

        return view('admin.products.index', compact('product', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        return redirect()->route('admin.products')->with('success', 'Product added successfully!');
    }

    public function updateProduct(Request $request, $id)
    {
        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }
}
