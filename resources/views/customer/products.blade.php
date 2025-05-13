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
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="{{ asset('images/products/' . $product['image']) }}" class="card-img-top" alt="{{ $product['name'] }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
                    <p class="card-text text-muted">{{ $product['category'] }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-primary mb-0">${{ number_format($product['price'], 2) }}</h5>
                        <a href="{{ route('product.detail', $product['id']) }}" class="btn btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
