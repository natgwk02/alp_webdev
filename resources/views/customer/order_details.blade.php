@extends('layouts.app')

@section('title', 'Order #' . $order['order_number'] . ' - Chile Mart')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fw-bold">Order #{{ $order['order_number'] }}</h1>
                <a href="{{ route('orders') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Orders
                </a>
            </div>
            <p class="text-muted">Placed on {{ $order['order_date'] }}</p>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                Your order is <strong>{{ $order['status'] }}</strong>.
                @if($order['status'] == 'Processing')
                    We're preparing your order for shipment.
                @endif
            </div>
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
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order['items'] as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/products/product-' . $item['product_id'] . '.jpg') }}" 
                                                 class="img-thumbnail me-3" 
                                                 width="60" 
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

            @if(isset($order['tracking_info']))
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Shipping Information</h5>
                </div>
                <div class="card-body">
                    <div class="tracking-progress">
                        <div class="steps">
                            <div class="step {{ $order['status'] == 'Processing' ? 'active' : 'completed' }}">
                                <div class="step-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="step-label">Processing</div>
                            </div>
                            <div class="step {{ $order['status'] == 'Shipped' ? 'active' : ($order['status'] == 'Delivered' ? 'completed' : '') }}">
                                <div class="step-icon">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                                <div class="step-label">Shipped</div>
                            </div>
                            <div class="step {{ $order['status'] == 'Delivered' ? 'active' : '' }}">
                                <div class="step-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="step-label">Delivered</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p>
                            <strong>Carrier:</strong> {{ $order['tracking_info']['carrier'] }}<br>
                            <strong>Tracking Number:</strong> {{ $order['tracking_info']['tracking_number'] }}<br>
                            <strong>Estimated Delivery:</strong> {{ $order['tracking_info']['estimated_delivery'] }}
                        </p>
                        <a href="#" class="btn btn-outline-primary">Track Package</a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Shipping Address</h6>
                        <p class="mb-0">{{ $order['shipping_address'] }}</p>
                    </div>

                    <div class="mb-3">
                        <h6>Payment Method</h6>
                        <p class="mb-0">
                            {{ $order['payment_method'] }} 
                            <span class="badge bg-success">{{ $order['payment_status'] }}</span>
                        </p>
                    </div>

                    <hr>

                    <div class="order-totals mb-3">
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

                    <hr>

                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-print me-2"></i>Print Invoice
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-shopping-bag me-2"></i>Reorder Items
                        </button>
                        @if($order['status'] == 'Processing')
                        <button class="btn btn-outline-danger">
                            <i class="fas fa-times-circle me-2"></i>Cancel Order
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tracking-progress .steps {
        display: flex;
        justify-content: space-between;
        position: relative;
    }
    .tracking-progress .steps:before {
        content: "";
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
    }
    .tracking-progress .step {
        text-align: center;
        position: relative;
        z-index: 2;
    }
    .tracking-progress .step-icon {
        width: 40px;
        height: 40px;
        background: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
    }
    .tracking-progress .step.active .step-icon {
        background: var(--primary-color);
        color: white;
    }
    .tracking-progress .step.completed .step-icon {
        background: #28a745;
        color: white;
    }
    .tracking-progress .step-label {
        font-size: 0.875rem;
    }
</style>
@endsection