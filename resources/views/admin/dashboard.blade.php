@extends('layouts.admin') {{-- nanti ganti ke layouts.admin kalo uda ada --}}

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
        <!-- Stats Cards -->
            <!-- TOTAL ORDERS -->
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

            <!-- TOTAL REVENUE -->
            <div class="col-lg-4 mb-4">
                <a href="{{ route('admin.orders') }}" class="text-decoration-none text-white">
                    <div class="card stat-card gradient-green text-white rounded-4 h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1">Total Revenue</h6>
                                <h2 class="fw-bold mb-0">Rp. 100.000.000</h2>
                            </div>
                            <div class="icon-circle">
                                <i class="fas fa-dollar-sign fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- TOTAL PRODUCTS -->
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

{{--
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
        </div> --}}
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
                                    <td>Rp. 35.000</td>
                                    <td><span class="badge bg-warning">Processing</span></td>
                                </tr>
                                <tr>
                                    <td>CHILE-2025-1002</td>
                                    <td>2025-05-11</td>
                                    <td>Jane Smith</td>
                                    <td>Rp. 50.000</td>
                                    <td><span class="badge bg-info">Shipped</span></td>
                                </tr>
                                <tr>
                                    <td>CHILE-2025-1001</td>
                                    <td>2025-05-10</td>
                                    <td>John Doe</td>
                                    <td>Rp. 55.000</td>
                                    <td><span class="badge bg-success">Delivered</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary mt-2">View All Orders</a>
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
