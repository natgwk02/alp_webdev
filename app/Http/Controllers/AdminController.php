<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    }

    public function products()
    {
        // Hardcoded products for admin view
        $products = [
            [
                'id' => 1,
                'name' => 'Chilean Sea Bass Fillet',
                'price' => 24.99,
                'stock' => 50,
                'category' => 'Fish',
                'status' => 'Active'
            ],
            // More products...
        ];

        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = ['Fish', 'Shellfish', 'Meat', 'Vegetables', 'Prepared Meals'];
        return view('admin.products.create', compact('categories'));
    }

    public function editProduct($id)
    {
        // Hardcoded product data
        $product = [
            'id' => $id,
            'name' => 'Chilean Sea Bass Fillet',
            'price' => 24.99,
            'stock' => 50,
            'category' => 'Fish',
            'description' => 'Premium Chilean sea bass fillets, wild-caught from the cold waters of Chile.',
            'status' => 'Active'
        ];

        $categories = ['Fish', 'Shellfish', 'Meat', 'Vegetables', 'Prepared Meals'];
        
        return view('admin.products.edit', compact('product', 'categories'));
    }
}