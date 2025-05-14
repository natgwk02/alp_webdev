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
            <div class="card h-100 shadow-sm">
                <div class="text-center p-3">
                    <img src="{{ asset('images/products-img/' . $product['image']) }}"
                         alt="{{ $product['name'] }}"
                         class="img-fluid"
                         style="max-height: 200px; object-fit: contain;">
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title fw-semibold">{{ $product['name'] }}</h5>
                    <p class="card-text text-muted mb-2">{{ $product['category'] }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <h5 class="text-primary mb-0">Rp {{ number_format($product['price'], 0, ',', '.') }}</h5>
                        <a href="{{ route('product.detail', $product['id']) }}" class="btn btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
