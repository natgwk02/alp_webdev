@extends('layouts.app')

@section('title', 'Shopping Cart - Chile Mart')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold">Shopping Cart</h1>
            @if(count($cartItems) == 0)
                <div class="alert alert-info">
                    Your cart is empty. <a href="{{ route('products') }}">Browse products</a> to add items.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('images/products/' . $item['image']) }}" 
                                             alt="{{ $item['product_name'] }}" 
                                             class="img-thumbnail me-3" 
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                        <div>
                                            <h5 class="mb-1">{{ $item['product_name'] }}</h5>
                                            <small class="text-muted">Stock: {{ $item['stock'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>${{ number_format($item['price'], 2) }}</td>
                                <td>
                                    <input type="number" 
                                           class="form-control" 
                                           value="{{ $item['quantity'] }}" 
                                           min="1" 
                                           max="{{ $item['stock'] }}" 
                                           style="width: 80px;">
                                </td>
                                <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                <td>
    <div class="d-flex flex-column">
        <form action="{{ route('cart.add', ['productId' => $item['product_id']]) }}" method="POST" class="mb-1">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-cart-plus"></i> Add Again
            </button>
        </form>

        <form action="{{ route('cart.remove', ['productId' => $item['product_id']]) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>
</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Order Summary</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>${{ number_format($shippingFee, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax:</span>
                                    <span>${{ number_format($tax, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                                <a href="{{ route('checkout') }}" class="btn btn-primary w-100 mt-3">
                                    Proceed to Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection