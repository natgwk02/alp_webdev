@extends('layouts.app')

@section('title', 'Order #' . $order['order_number'])

@section('content')
<div class="container py-4">
    <h1 class="fw-bold">Order #{{ $order['order_number'] }}</h1>
    <p class="text-muted">Placed on {{ $order['order_date'] }}</p>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order['items'] as $item)
                            <tr>
                                <td>{{ $item['product_name'] }}</td>
                                <td>${{ number_format($item['price'], 2) }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>${{ number_format($item['total'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if(isset($order['tracking_info']))
            <div class="card mt-4 shadow-sm">
                <div class="card-header">
                    <h5>Shipping Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Carrier:</strong> {{ $order['tracking_info']['carrier'] }}</p>
                    <p><strong>Tracking Number:</strong> {{ $order['tracking_info']['tracking_number'] }}</p>
                    <p><strong>Estimated Delivery:</strong> {{ $order['tracking_info']['estimated_delivery'] }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <p><strong>Shipping Address:</strong> {{ $order['shipping_address'] }}</p>
                    <p><strong>Payment Method:</strong> {{ $order['payment_method'] }}</p>
                    <p><strong>Payment Status:</strong> {{ $order['payment_status'] }}</p>

                    <hr>

                    <div>
                        <div class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span>${{ number_format($order['subtotal'], 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Shipping Fee:</span>
                            <span>${{ number_format($order['shipping_fee'], 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tax:</span>
                            <span>${{ number_format($order['tax'], 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span>${{ number_format($order['total_amount'], 2) }}</span>
                        </div>
                    </div>

                    <hr>

                    <button class="btn btn-outline-primary">Print Invoice</button>
                    <button class="btn btn-outline-secondary">Reorder Items</button>
                    @if($order['status'] == 'Processing')
                    <button class="btn btn-outline-danger">Cancel Order</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
