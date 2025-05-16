@extends('layouts.app')

@section('title', $product['name'] . ' - Chillé Mart')

@push('styles')
<style>
    .product-section {
        padding: 40px 0;
    }

    .product-card {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .product-image-wrapper {
        width: 100%;
        max-width: 450px;
        aspect-ratio: 4 / 5;
        border-radius: 12px;
        overflow: hidden;
        margin: auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .product-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.3s ease-in-out;
    }

    .product-image-wrapper img:hover {
        transform: scale(1.03);
    }

    .product-details h1 {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.2rem;
    }

    .product-details .category {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0.8rem;
    }

    .product-details .price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 1rem;
    }

    .product-details .desc {
        font-size: 1rem;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .product-details .form-label {
        font-weight: 500;
        font-size: 0.9rem;
    }

    .product-details input[type="number"] {
        max-width: 100px;
        padding: 6px 10px;
        font-size: 0.9rem;
    }

    .btn-add-to-cart {
        margin-top: 10px;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: #198754;
        color: white;
        border: none;
        transition: background 0.3s;
    }

    .btn-add-to-cart:hover {
        background-color: #157347;
    }

    @media (max-width: 768px) {
        .product-card {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="container product-section">
    <div class="row justify-content-center g-5">
        <div class="col-md-5">
            <div class="product-image-wrapper">
                <img src="{{ asset('images/products-img/' . $product['image']) }}" 
                     alt="{{ $product['name'] }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="product-card">
                <div class="product-details">
                    <h1>{{ $product['name'] }}</h1>
                    <div class="category">{{ $product['category'] }}</div>
                    <div class="price">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>

                    <p class="desc">
                        Delicious and fresh, our {{ strtolower($product['name']) }} is a customer favorite sourced from high-quality suppliers.
                    </p>

                    <form action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control">
                        </div>
                        <button type="submit" class="btn-add-to-cart">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
