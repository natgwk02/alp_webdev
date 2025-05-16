@extends('layouts.admin')

@section('title', 'Order #' . $order['order_number'] . ' - Chile Mart Admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders') }}">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order #{{ $order['order_number'] }}</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="fw-bold">Order #{{ $order['order_number'] }}</h1>

                    <button type="button" class="btn btn-outline-danger d-flex align-items-center gap-2" id="orderActions">
                        <i class="fas fa-trash"></i>
                        <span>Cancel Order</span>
                    </button>


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
                                                    <img src="{{ asset('images/products/product-' . $item['product_id'] . '.jpg') }}"
                                                        class="img-thumbnail me-3" width="60"
                                                        alt="{{ $item['product_name'] }}">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item['product_name'] }}</h6>
                                                        <small class="text-muted">SKU: CM-{{ $item['product_id'] }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>${{ number_format($item['price'], 2) }}</td>
                                            <td>{{ $item['quantity'] }}</td>
                                            <td>${{ number_format($item['total'], 2) }}</td>
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
                            <p class="mb-1">{{ $order['customer_email'] }}</p>
                            <a href="#" class="small">View customer profile</a>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <h6>Shipping Address</h6>
                            <p class="mb-0">{{ $order['shipping_address'] }}</p>
                        </div>

                        <div class="mb-3">
                            <h6>Billing Address</h6>
                            <p class="mb-0">{{ $order['billing_address'] }}</p>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <h6>Payment Method</h6>
                            <p class="mb-0">
                                {{ $order['payment_method'] }}
                                <span class="badge bg-success">{{ $order['payment_status'] }}</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6>Order Status</h6>
                            <select class="form-select mb-2">
                                <option {{ $order['status'] == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option {{ $order['status'] == 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option {{ $order['status'] == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                <option {{ $order['status'] == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                <option {{ $order['status'] == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button class="btn btn-primary w-100">Update Status</button>
                        </div>

                        <hr>

                        <div class="order-totals">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>${{ number_format($order['subtotal'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>${{ number_format($order['shipping_fee'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>${{ number_format($order['tax'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span>${{ number_format($order['total_amount'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
