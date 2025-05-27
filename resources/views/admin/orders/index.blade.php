@extends('layouts.admin')

@section('title', 'Order Management - Chile Mart Admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item "><a href="{{ route('admin.dashboard') }}"
                                class="text-decoration-none text-secondary">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="fw-bold">Order Management</h1>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                        <i class="fas fa-filter me-1"></i> Filter Orders
                    </button>
                </div>
            </div>
        </div>

        <div class="collapse mb-4" id="filterCollapse">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.orders') }}" method="GET">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="order_id" class="form-label">Order ID</label>
                                <input type="text" class="form-control" id="order_id" name="order_id"
                                    placeholder="Search order number" value="{{ request('order_id') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>
                                        Delivered</option>
                                    <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>
                                        Processing</option>
                                    <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped
                                    </option>
                                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                    <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Failed
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from"
                                    value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to"
                                    value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method">
                                    <option value="">All Payment Methods</option>
                                    <option value="Credit Card"
                                        {{ request('payment_method') == 'Credit Card' ? 'selected' : '' }}>Credit Card
                                    </option>
                                    <option value="Bank Transfer"
                                        {{ request('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer
                                    </option>
                                    <option value="E-Wallet"
                                        {{ request('payment_method') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                    <option value="Cash on Delivery"
                                        {{ request('payment_method') == 'Cash on Delivery' ? 'selected' : '' }}>Cash on
                                        Delivery</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="amount_min" class="form-label">Minimum Amount (Rp)</label>
                                <input type="number" class="form-control" id="amount_min" name="amount_min"
                                    placeholder="Minimum amount" value="{{ request('amount_min') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="amount_max" class="form-label">Maximum Amount (Rp)</label>
                                <input type="number" class="form-control" id="amount_max" name="amount_max"
                                    placeholder="Maximum amount" value="{{ request('amount_max') }}">
                            </div>
                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="amount_more_than"
                                        name="amount_more_than" {{ request('amount_more_than') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amount_more_than">
                                        More than minimum only
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    Apply Filters
                                </button>
                                <a href="{{ route('admin.orders') }}" class="btn btn-secondary ms-2">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->orders_id }}</td>
                                            <td>
                                                @if ($order->user)
                                                    {{ $order->user->users_name }}
                                                @elseif ($order->first_name)
                                                    {{ $order->first_name }} {{ $order->last_name }}
                                                @else
                                                    Guest or N/A
                                                @endif
                                            </td>
                                            <td>{{ $order->orders_date->format('M d, Y') }}</td>
                                            <td>Rp {{ number_format($order->orders_total_price, 0, ',', '.') }}</td>
                                            <td>{{ $order->payment_method ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge {{ $order->status_badge_class }}">
                                                    {{ $order->orders_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->orders_id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center my-4">
            <div>
                @if ($orders->total() > 0)
                    <p class="mb-0">Showing {{ $orders->firstItem() }} to
                        {{ $orders->lastItem() }} of {{ $orders->total() }} entries</p>
                @else
                    <p class="mb-0">No entries found</p>
                @endif
            </div>
            <nav aria-label="Page navigation">
                {{ $orders->links() }}
            </nav>
        </div>
    </div>
@endsection
