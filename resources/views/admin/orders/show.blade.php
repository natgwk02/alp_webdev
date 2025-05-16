@extends('layouts.admin')

@section('title', 'Order #' . $order['order_number'] . ' - Chile Mart Admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.orders') }}" class="text-decoration-none text-secondary">Orders</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Order #{{ $order['order_number'] }}</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fw-bold">Order #{{ $order['order_number'] }}</h1>

                {{-- Cancel Order Form --}}
                <form action="{{ route('admin.orders.updateStatus', $order['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="Cancelled">
                    <button type="submit" class="btn btn-outline-danger d-flex align-items-center gap-2">
                        <i class="fas fa-trash"></i>
                        <span>Cancel Order</span>
                    </button>
                </form>
            </div>
            <p class="text-muted">Placed on {{ $order['order_date'] }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order['items'] as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/products-img/' . $item['image']) }}"
                                                class="img-thumbnail me-3" width="60"
                                                alt="{{ $item['product_name'] }}">
                                            <div>
                                                <h6 class="mb-0">{{ $item['product_name'] }}</h6>
                                                <small class="text-muted">SKU: CM-{{ $item['product_id'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp.{{ number_format($item['price'], 2, ',', '.') }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>Rp.{{ number_format($item['total'], 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Customer Information</h6>
                        <p class="mb-1"><strong>{{ $order['customer_name'] }}</strong></p>
                        <p class="mb-1">{{ $order['customer_email'] ?? '-' }}</p>
                        <a href="#" class="small">View customer profile</a>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6>Shipping Address</h6>
                        <p class="mb-0">{{ $order['shipping_address'] ?? '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <h6>Billing Address</h6>
                        <p class="mb-0">{{ $order['billing_address'] ?? '-' }}</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6>Payment Method</h6>
                        <p class="mb-0">
                            {{ $order['payment_method'] ?? 'Unknown' }}
                            <span class="badge bg-success">{{ $order['payment_status'] ?? 'Unknown' }}</span>
                        </p>
                    </div>

                    <form action="{{ route('admin.orders.updateStatus', $order['id']) }}" method="POST">
                        @csrf
                        @method('PUT') 
                        <div class="mb-3">
                            <h6>Order Status</h6>
                            <select name="status" class="form-select mb-2" required>
                                <option value="Pending" {{ $order['status'] == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Processing" {{ $order['status'] == 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option value="Shipped" {{ $order['status'] == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="Delivered" {{ $order['status'] == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="Cancelled" {{ $order['status'] == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-primary w-100">Update Status</button>
                        </div>
                    </form>

                    <hr>

                    <div class="order-totals">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp.{{ number_format($order['subtotal'] ?? 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>Rp.{{ number_format($order['shipping_fee'] ?? 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span>Rp.{{ number_format($order['total_amount'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
