@extends('layouts.app')

@section('title', 'Order #' . ($order['order_number'] ?? 'Unknown Order'))

@section('content')
    <div class="container py-4">
        <h1 class="fw-bold">Order #{{ $order['order_number'] ?? 'Unknown Order' }}</h1>
        <p class="text-muted">Placed on {{ $order['created_at'] }}</p>

        <div class="row mb-4">
            <div class="col-12 col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Order Items</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($order['items']))
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-left">Total</th>
                                            @if (($order['orders_status'] ?? '') === 'Delivered')
                                                <th>Rate</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order['items'] as $item)
                                            <tr>
                                                <td class="d-flex align-items-center gap-3">
                                                    <img src="{{ asset('images/products-img/' . ($item['image_filename'] ?? 'no-image.png')) }}"
                                                        alt="{{ $item['products_name'] ?? 'Unknown Product' }}"
                                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                                    <span>{{ $item['name'] ?? 'Unknown Product' }}</span>
                                                </td>
                                                <td>Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                                                <td class="text-center">{{ $item['quantity'] ?? 0 }}</td>
                                                <td>Rp {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') }}</td>
                                                <td>
                                                    @if (!empty($item['product_id']) && empty($item['is_rated']) && ($order['orders_status'] ?? '') === 'Delivered')
                                                        <form method="POST" action="{{ route('ratings.store') }}">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                                            <div class="d-flex">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <button type="submit" name="rating" value="{{ $i }}" class="btn btn-link p-0 border-0">
                                                                        <i class="bi bi-star"></i>
                                                                    </button>
                                                                @endfor
                                                            </div>
                                                        </form>
                                                    @elseif(!empty($item['is_rated']))
                                                        <span class="text-muted small">Rated</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>No items found in this order.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 mt-4 mt-md-0">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Shipping Address:</strong><br>
                            {{ $order['customer']['first_name'] ?? 'Unknown' }} {{ $order['customer']['last_name'] ?? '' }}<br>
                            {{ $order['customer']['address'] ?? 'Unknown Address' }}<br>
                            {{ $order['customer']['city'] ?? 'Unknown City' }},
                            {{ $order['customer']['zip'] ?? '00000' }}<br>
                            {{ $order['customer']['country'] ?? 'Unknown Country' }}<br>
                            Phone: {{ $order['customer']['phone'] ?? 'Unknown Phone' }}
                        </p>
                        <p><strong>Payment Method:</strong> {{ ucfirst($order['payment_method'] ?? 'Unknown') }}</p>
                        <p><strong>Payment Status:</strong>
                            <span id="payment-status-text"
                                class="badge
                            @if ($order['payment_status'] === 'paid') bg-success
                            @elseif ($order['payment_status'] === 'pending') bg-warning text-dark
                            @elseif ($order['payment_status'] === 'failed') bg-danger
                            @else bg-secondary @endif">
                                {{ ucfirst($order['payment_status']) }}
                            </span>
                        </p>

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
                        <div class="mt-3 d-grid gap-2">
                            <a href="{{ $order['payment_url'] }}" class="btn btn-success w-100">
                                <i class="bi bi-wallet2"></i> Pay Now
                            </a>
                            <div class="mt-3">
                                <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> Back to My Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
