@extends('layouts.app')

@section('title', $product['name'] . ' - Chile Mart')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('images/products/' . $product['image']) }}" class="img-fluid rounded shadow-sm" alt="{{ $product['name'] }}">
        </div>
        <div class="col-md-6">
            <h1 class="fw-bold">{{ $product['name'] }}</h1>
            <p class="text-muted mb-1">{{ $product['category'] }}</p>
            <h4 class="text-primary mb-3">${{ number_format($product['price'], 2) }}</h4>

            <p class="mb-3">Delicious and fresh, our {{ strtolower($product['name']) }} is a customer favorite sourced from high-quality suppliers.</p>

            <form action="" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control w-25">
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
