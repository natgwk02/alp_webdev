@extends('layouts.admin')

@section('title', 'Dashboard - Chile Mart Admin')

@section('content')
    <div class="container my-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="fw-bold">Dashboard</h1>
                <p class="text-muted">Overview of your store performance</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <a href="{{ route('admin.orders') }}" class="text-decoration-none text-white">
                    <div class="card stat-card gradient-blue text-white rounded-4 h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1">Total Orders</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['total_orders'] }}</h2>
                            </div>
                            <div class="icon-circle">
                                <i class="fas fa-shopping-bag fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 mb-4">
                <a href="{{ route('admin.orders') }}" class="text-decoration-none text-white">
                    <div class="card stat-card gradient-green text-white rounded-4 h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1">Total Revenue</h6>
                                <h2 class="fw-bold mb-0">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}
                                </h2>
                            </div>
                            <div class="icon-circle">
                                <i class="fas fa-dollar-sign fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 mb-4">
                <a href="{{ route('admin.products') }}" class="text-decoration-none text-white">
                    <div class="card stat-card gradient-cyan text-white rounded-4 h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1">Total Products</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['total_products'] }}</h2>
                            </div>
                            <div class="icon-circle">
                                <i class="fas fa-box-open fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentOrders as $order)
                                        <tr>
                                            <td>{{ $order->orders_id ?? 'N/A' }}</td>
                                            <td>{{ $order->orders_date->format('Y-m-d') }}</td>
                                            <td>{{ $order->user ? $order->user->users_name : 'N/A' }}</td>
                                            <td>Rp {{ number_format($order->orders_total_price ?? 0, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $order->status_badge_class ?? 'bg-secondary' }}">
                                                    {{ $order->orders_status ?? 'Unknown' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No recent orders found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary mt-2">View All
                            Orders</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Stock Alert Products</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse ($stockAlertProducts as $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $product->products_name }}
                                    @php
                                        $stock = $product->products_stock;
                                        $badgeClass = '';

                                        if ($stock <= 0) {
                                            $badgeClass = 'bg-secondary';
                                        }
                                        elseif ($stock < 10) {
                                            $badgeClass = 'bg-danger';
                                        } elseif ($stock <= $lowStockThreshold) {
                                            $badgeClass = 'bg-warning text-dark';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $stock }} left
                                    </span>
                                </li>
                            @empty
                                <li class="list-group-item text-center">No low stock products currently.</li>
                            @endforelse
                        </ul>
                        <a href="{{ route('admin.products') }}" class="btn btn-sm btn-outline-primary mt-3">Manage
                            Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .gradient-blue {
                background: linear-gradient(135deg, #1E90FF, #4682B4);
            }

            .gradient-green {
                background: linear-gradient(135deg, #28a745, #218838);
            }

            .gradient-cyan {
                background: linear-gradient(135deg, #17a2b8, #138496);
            }

            .icon-circle {
                background-color: rgba(255, 255, 255, 0.15);
                border-radius: 50%;
                padding: 15px;
                box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
                transition: transform 0.3s ease;
            }
        </style>
    @endpush

@endsection
