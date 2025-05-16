@extends('layouts.app')

@section('title', 'Order #' . ($order['order_number'] ?? $order['id']))

@section('content')
<div class="container py-4">
    <h1 class="fw-bold">Order #{{ $order['order_number'] ?? $order['id'] }}</h1>
    <p class="text-muted">Placed on {{ isset($order['order_date']) ? \Carbon\Carbon::parse($order['order_date'])->format('d M Y H:i') : '-' }}</p>

    <div class="row mb-4">
        <!-- Kiri: Tabel item dengan gambar -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="card-body">
                    <table class="table align-middle">
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
                                <td class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('images/products-img/' . ($item['image'] ?? 'no-image.png')) }}" alt="{{ $item['product_name'] ?? 'Unknown Product' }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                    <span>{{ $item['product_name'] ?? 'Unknown Product' }}</span>
                                </td>
                                <td>Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $item['quantity'] ?? 0 }}</td>
                                <td>Rp {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') }}</td>
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

        <!-- Kanan: Summary -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <p><strong>Shipping Address:</strong><br>
                    @if(isset($order['customer']))
                        {{ $order['customer']['first_name'] ?? '' }} {{ $order['customer']['last_name'] ?? '' }}<br>
                        {{ $order['customer']['address'] ?? '' }}<br>
                        {{ $order['customer']['city'] ?? '' }}, {{ $order['customer']['zip'] ?? '' }}<br>
                        {{ $order['customer']['country'] ?? '' }}<br>
                        Phone: {{ $order['customer']['phone'] ?? '-' }}
                    @else
                        {{ $order['shipping_address'] ?? '-' }}
                    @endif
                    </p>
                    <p><strong>Payment Method:</strong> {{ $order['payment_method'] ?? '-' }}</p>
                    <p><strong>Payment Status:</strong> {{ $order['payment_status'] ?? '-' }}</p>

                    <hr>
                    <div>
                        <div class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span>Rp {{ number_format($order['subtotal'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Shipping Fee:</span>
                            <span>Rp {{ number_format($order['shipping_fee'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tax:</span>
                            <span>Rp {{ number_format($order['tax'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        @if(isset($order['voucher_discount']) && $order['voucher_discount'] > 0)
                        <div class="d-flex justify-content-between text-success">
                            <span>Voucher Discount:</span>
                            <span>- Rp {{ number_format($order['voucher_discount'], 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span>Rp {{ number_format($order['total'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
