@extends('layouts.app')

@section('title', 'My Wishlist - Chile Mart')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">My Wishlist</h1>
            <p class="text-muted">{{ count($wishlistItems) }} items</p>
        </div>
    </div>

    @if(count($wishlistItems) == 0)
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-bookmark-heart-fill fa-3x text-muted mb-3"></i>
                        <h3>Your wishlist is empty</h3>
                        <p class="text-muted">Save your favorite items here for later</p>
                        <a href="{{ route('products') }}" class="btn btn-primary">Browse Products</a>
                    </div>
                </div>
            </div>
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
    @endif
</div>
@endsection