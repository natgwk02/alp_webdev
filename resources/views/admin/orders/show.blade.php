@extends('layouts.admin')

@section('title', 'Order #' . $order->orders_id . ' - Chile Mart Admin')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
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
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-decoration-none text-secondary">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.orders') }}" class="text-decoration-none text-secondary">Orders</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->orders_id }}</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="fw-bold">Order #{{ $order->orders_id }}</h1>
                    @if (strtolower($order->orders_status) !== 'cancelled' && strtolower($order->orders_status) !== 'delivered')
                        <form action="{{ route('admin.orders.updateStatus', $order->orders_id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to cancel this order?');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Cancelled">
                            <button type="submit" class="btn btn-outline-danger d-flex align-items-center gap-2">
                                <i class="fas fa-trash"></i>
                                <span>Cancel Order</span>
                            </button>
                        </form>
                    @endif
                </div>
                <p class="text-muted">Placed on {{ $order->orders_date->format('F j, Y, g:i a') }}</p>
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
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('images/products-img/' . ($item->product->products_image ?? 'no-image.png')) }}"
                                                        class="img-thumbnail me-3" width="60"
                                                        alt="{{ $item->product->products_name ?? 'Unknown Product' }}">
                                                    <div>
                                                        <h6 class="mb-0">
                                                            {{ $item->product->products_name ?? 'Unknown Product' }}</h6>
                                                        <small class="text-muted">SKU:
                                                            CM-{{ $item->product->products_id ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp.{{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                                            <td>{{ $item->order_details_quantity ?? 1 }}</td>
                                            <td>Rp.{{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
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
                            <p class="mb-1">
                                <strong>
                                    @if ($order->user)
                                        {{ $order->user->users_name }}
                                    @else
                                        {{ $order->first_name }} {{ $order->last_name }}
                                    @endif
                                </strong>
                            </p>
                            <p class="mb-1">
                                @if ($order->user)
                                    {{ $order->user->email ?? 'No email provided' }}
                                @else
                                    No registered user account
                                @endif
                            </p>
                            @if ($order->user)
                            @endif
                        </div>

                        <hr>

                        <div class="mb-3">
                            <h6>Shipping Address</h6>
                            <p class="mb-0">{{ $order->address ?? '-' }}, {{ $order->city ?? '' }}</p>
                            <p class="mb-0">{{ $order->zip ?? '' }}, {{ $order->country ?? '' }}</p>
                            <p class="mb-0">Phone: {{ $order->phone ?? '-' }}</p>
                        </div>

                        @if ($order->notes)
                            <div class="mb-3">
                                <h6>Customer Notes</h6>
                                <p class="mb-0 text-muted">
                                    {{ $order->notes }}
                                </p>
                            </div>
                        @endif

                        <hr>

                        <div class="mb-3">
                            <h6>Payment Method</h6>
                            <p class="mb-0">
                                {{ Str::title(str_replace('_', ' ', $order->payment_method ?? 'Unknown')) }}
                                <span
                                    class="badge {{ $order->payment_status == 'Paid' ? 'bg-success' : 'bg-warning text-dark' }}">{{ Str::title($order->payment_status ?? 'Unknown') }}</span>
                            </p>
                        </div>

                        <form action="{{ route('admin.orders.updateStatus', $order->orders_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <h6>Order Status</h6>
                                <select name="status" class="form-select mb-2" required
                                    {{ strtolower($order->orders_status) === 'delivered' || strtolower($order->orders_status) === 'cancelled' ? 'disabled' : '' }}>
                                    <option value="Pending" {{ $order->orders_status == 'Pending' ? 'selected' : '' }}>
                                        Pending Delivery</option>
                                    <option value="Processing"
                                        {{ $order->orders_status == 'Processing' ? 'selected' : '' }}>Processing Delivery</option>
                                    <option value="Shipped"
                                        {{ strtolower($order->orders_status) == 'Shipped' ? 'selected' : '' }}>Shipped
                                    </option>
                                    <option value="Delivered"
                                        {{ strtolower($order->orders_status) == 'Delivered' ? 'selected' : '' }}>Delivered
                                    </option>
                                    <option value="Cancelled"
                                        {{ strtolower($order->orders_status) == 'Cancelled' ? 'selected' : '' }}>Cancelled
                                    </option>
                                </select>
                                @if (strtolower($order->orders_status) !== 'delivered' && strtolower($order->orders_status) !== 'cancelled')
                                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                                @else
                                    <button type="submit" class="btn btn-primary w-100" disabled>Update Status</button>
                                @endif
                            </div>
                        </form>

                        <hr>

                        <div class="order-totals">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Subtotal:</span>
                                <span>Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Shipping:</span>
                                <span>Rp {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}</span>
                            </div>
                            @if (isset($order->tax) && $order->tax > 0)
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Tax:</span>
                                    <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if (isset($order->voucher_discount) && $order->voucher_discount > 0)
                                <div class="d-flex justify-content-between mb-1 text-success">
                                    <span>Voucher Discount:</span>
                                    <span>- Rp {{ number_format($order->voucher_discount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between fw-bold mt-2 pt-2 border-top">
                                <span>Total:</span>
                                <span>Rp
                                    {{ number_format($order->total ?? ($order->orders_total_price ?? 0), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
