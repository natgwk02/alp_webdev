@extends('layouts.app')

@section('title', $product['name'] . ' - Chillé Mart')

@push('styles')
<style>
    .product-detail {
        padding: 50px 0;
    }

    .product-image-container {
        width: 100%;
        aspect-ratio: 4 / 5;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .product-image-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        transition: transform 0.3s ease;
    }

    .product-image-container img:hover {
        transform: scale(1.03);
    }

    .product-info-card {
        background: #fff;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.05);
    }

    .product-info-card h1 {
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 5px;
    }

    .product-info-card .category {
        font-size: 0.95rem;
        color: #6c757d;
        margin-bottom: 12px;
    }

    .product-info-card .price {
        color: #0d6efd;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .product-info-card .desc {
        font-size: 1rem;
        color: #333;
        margin-bottom: 24px;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .quantity-control button {
        width: 32px;
        height: 32px;
        border: 1px solid #ccc;
        background-color: #f8f9fa;
        font-weight: bold;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .quantity-control input {
        width: 60px;
        text-align: center;
        font-size: 1rem;
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .btn-buy {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-buy button {
        padding: 10px 24px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        border: none;
        transition: background 0.3s;
    }

    .btn-add {
        background-color: #198754;
        color: white;
    }

    .btn-add:hover {
        background-color: #157347;
    }

    .btn-buy-now {
        background-color: #212529;
        color: white;
    }

    .btn-buy-now:hover {
        background-color: #000;
    }
</style>
@endpush

@section('content')
<div class="container product-detail">
    <div class="row g-5">
        <!-- Left: Product Image -->
        <div class="col-md-5">
            <div class="product-image-container">
                <img src="{{ asset('images/products-img/' . $product['image']) }}" alt="{{ $product['name'] }}">
            </div>
        </div>

        <!-- Right: Product Info -->
        <div class="col-md-7">
            <div class="product-info-card">
                <h1>{{ $product['name'] }}</h1>
                <div class="category">{{ $product['category'] }}</div>
                <div class="price">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>

                <p class="desc">Delicious and fresh, our {{ strtolower($product['name']) }} is a customer favorite sourced from high-quality suppliers.</p>

                <form method="POST" action="">
                    @csrf
                    <div class="quantity-control">
                        <label class="form-label me-2">Quantity:</label>
                        <button type="button" onclick="updateQuantity(-1)">−</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1">
                        <button type="button" onclick="updateQuantity(1)">+</button>
                    </div>

                    <div class="btn-buy">
                        <button type="submit" class="btn-add"><i class="bi bi-cart-plus me-1"></i>Add to Cart</button>
                        <button type="button" class="btn-buy-now">Buy it now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateQuantity(amount) {
        const input = document.getElementById("quantity");
        let value = parseInt(input.value);
        if (!isNaN(value)) {
            value += amount;
            if (value < 1) value = 1;
            input.value = value;
        }
    }
</script>
@endsection
