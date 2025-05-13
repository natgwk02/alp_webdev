@extends('layouts.app') {{-- nanti ganti ke layouts.admin kalo uda ada --}}

@section('title', 'Dashboard - Chile Mart Admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="fw-bold">Dashboard</h1>
        <p class="text-muted">Overview of your store performance</p>
    </div>
</div>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase">Total Orders</h6>
                        <h2 class="mb-0">{{ $stats['total_orders'] }}</h2>
                    </div>
                    <i class="fas fa-shopping-bag fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase">Total Revenue</h6>
                        <h2 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h2>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase">Total Products</h6>
                        <h2 class="mb-0">{{ $stats['total_products'] }}</h2>
                    </div>
                    <i class="fas fa-box-open fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase">New Customers</h6>
                        <h2 class="mb-0">{{ $stats['new_customers'] }}</h2>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
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
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>CHILE-2025-1003</td>
                                <td>2025-05-12</td>
                                <td>Robert Johnson</td>
                                <td>$64.98</td>
                                <td><span class="badge bg-warning">Processing</span></td>
                            </tr>
                            <tr>
                                <td>CHILE-2025-1002</td>
                                <td>2025-05-11</td>
                                <td>Jane Smith</td>
                                <td>$124.95</td>
                                <td><span class="badge bg-info">Shipped</span></td>
                            </tr>
                            <tr>
                                <td>CHILE-2025-1001</td>
                                <td>2025-05-10</td>
                                <td>John Doe</td>
                                <td>$89.97</td>
                                <td><span class="badge bg-success">Delivered</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <a href="{{-- route('admin.orders') --}}" class="btn btn-sm btn-outline-primary mt-2">View All Orders</a>
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Low Stock Products</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Chilean Sea Bass Fillet
                        <span class="badge bg-danger">2 left</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Alaskan King Crab Legs
                        <span class="badge bg-warning">5 left</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Patagonian Scallops
                        <span class="badge bg-warning">3 left</span>
                    </li>
                </ul>
                <a href="{{ route('admin.products') }}" class="btn btn-sm btn-outline-primary mt-3">Manage Products</a>
            </div>
        </div>
    </div>
</div>
@endsection
