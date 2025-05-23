@extends('layouts.app')

@section('title', 'Order #' . ($order['order_number'] ?? 'Unknown Order'))

@section('content')
    <div class="container py-4">
        <h1 class="fw-bold">Order #{{ $order['order_number'] ?? 'Unknown Order' }}</h1>
        <p class="text-muted">Placed on {{ $order['created_at'] }}</p>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Order Items</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($order['items']))
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
                                    @foreach ($order['items'] as $item)
                                        <tr>
                                            <td class="d-flex align-items-center gap-3">
                                                <img src="{{ asset('images/products-img/' . ($item['image'] ?? 'no-image.png')) }}"
                                                    alt="{{ $item['name'] ?? 'Unknown Product' }}"
                                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                                <span>{{ $item['name'] ?? 'Unknown Product' }}</span>
                                            </td>
                                            <td>Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                                            <td>{{ $item['quantity'] ?? 0 }}</td>
                                            <td>Rp
                                                {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No items found in this order.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Shipping Address:</strong><br>
                            {{ $order['customer']['first_name'] ?? 'Unknown' }}
                            {{ $order['customer']['last_name'] ?? '' }}<br>
                            {{ $order['customer']['address'] ?? 'Unknown Address' }}<br>
                            {{ $order['customer']['city'] ?? 'Unknown City' }},
                            {{ $order['customer']['zip'] ?? '00000' }}<br>
                            {{ $order['customer']['country'] ?? 'Unknown Country' }}<br>
                            Phone: {{ $order['customer']['phone'] ?? 'Unknown Phone' }}
                        </p>
                        <p><strong>Payment Method:</strong> {{ ucfirst($order['payment_method'] ?? 'Unknown') }}</p>
                        <p><strong>Payment Status:</strong> {{ ucfirst($order['payment_status'] ?? 'Unpaid') }}</p>

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
                            @if (isset($order['voucher_discount']) && $order['voucher_discount'] > 0)
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
