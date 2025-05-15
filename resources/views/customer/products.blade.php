@extends('layouts.app')

@section('title', 'Frozen Food Products - Chille Mart')

@section('content')

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">Our Frozen Food Selection</h1>
            <p class="text-muted">Premium quality frozen foods from around the world</p>
        </div>
    </div>

    <div class="row">
        @foreach($products as $product)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm position-relative">
                <form action="{{ route('wishlist', ['productId' => $product['id']]) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm border-0">
                        <i class="fas fa-heart text-danger"></i>
                    </button>
                </form>

                <div class="text-center p-3">
                    <img src="{{ asset('images/products-img/' . $product['image']) }}"
                         alt="{{ $product['name'] }}"
                         class="img-fluid"
                         style="max-height: 200px; object-fit: contain;">
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title fw-semibold">{{ $product['name'] }}</h5>
                    <p class="card-text text-muted mb-2">{{ $product['category'] }}</p>
                    <h5 class="text-primary mb-3">Rp {{ number_format($product['price'], 0, ',', '.') }}</h5>

                    <form action="{{ route('cart.add', ['productId' => $product['id']]) }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('product.detail', $product['id']) }}" class="btn btn-outline-primary">View Details</a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-cart-plus-fill"></i> Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
