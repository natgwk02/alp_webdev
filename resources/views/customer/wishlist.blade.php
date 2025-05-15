@extends('layouts.app')

@section('title', 'Your Wishlist')

@section('content')

<div class="container">
    <h1 class="fw-bold mb-4">Your Wishlist</h1>

    <!-- Display success/error messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @else
        <div class="row">
            @foreach($wishlistItems as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm position-relative">
                    @if(!$item['in_stock'])
                        <div class="badge bg-danger position-absolute m-2">Out of Stock</div>
                    @endif
                    <img src="{{ asset('images/products-img/' . $item['image']) }}" 
                         class="card-img-top p-3 product-img" 
                         alt="{{ $item['product_name'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item['product_name'] }}</h5>
                        <h5 class="text-primary mb-3">Rp {{ number_format($item['price'], 0, ',', '.') }}</h5>
                        <div class="d-flex justify-content-between">
                            @if($item['in_stock'])
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                </button>
                            @else
                                <button class="btn btn-sm btn-outline-secondary disabled">
                                    Not Available
                                </button>
                            @endif
                            <form action="{{ route('wishlist.remove', $item['id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h2 class="fw-bold mt-5">Wishlist Items</h2>
    <div class="row">
        @forelse(session('wishlist', []) as $item)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="text-center p-3">
                        <img src="{{ asset('images/products-img/' . $item['image']) }}" alt="{{ $item['name'] }}" class="img-fluid" style="max-height: 200px; object-fit: contain;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title fw-semibold">{{ $item['name'] }}</h5>
                        <p class="card-text text-muted mb-2">{{ $item['category'] }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <h5 class="text-primary mb-0">Rp {{ number_format($item['price'], 0, ',', '.') }}</h5>

                            <!-- Remove from Wishlist Button -->
                            <form action="{{ route('wishlist.remove', $item['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Remove from Wishlist</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>No products in your wishlist. Start adding some!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
