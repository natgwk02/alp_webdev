@extends('layouts.app')

@section('title', 'My Wishlist - Chile Mart')

@push('styles')
    <style>
        .product-img {
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 12px;
        }

        .card:hover {
            transform: scale(1.02);
            transition: 0.3s ease-in-out;
        }

        .card-title {
            font-size: 1rem;
            min-height: 2.4em;
        }

        .wishlist-empty i {
            font-size: 3rem;
        }
        
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="fw-bold">My Wishlist</h1>
                <p class="text-muted">{{ count($wishlistItems) }} items</p>
            </div>
        </div>

        @if (count($wishlistItems) == 0)
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm wishlist-empty">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-bookmark-heart-fill text-muted mb-3"></i>
                            <h3>Your wishlist is empty</h3>
                            <p class="text-muted">Save your favorite items here for later</p>
                            <a href="{{ route('products') }}" class="btn btn-primary">Browse Products</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                @foreach ($wishlistItems as $item)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm position-relative d-flex flex-column">
                            @if (!$item['products_stock'])
                                <span class="badge bg-danger position-absolute m-2">Out of Stock</span>
                            @endif

                            <img src="{{ asset('images/products-img/' . $item['products_image']) }}"
                                class="card-img-top product-img p-3" alt="{{ $item['products_name'] }}">

                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title">{{ $item['products_name'] }}</h5>
                                <h6 class="text-primary fw-bold mb-3">Rp {{ number_format($item['orders_price'], 0, ',', '.') }}
                                </h6>

                                <div class="d-flex gap-2 mt-auto">
                                    @if ($item['products_stock'])
                                        <form action="{{ route('cart.add', $item['products_id']) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-primary w-100">
                                                <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary w-100" disabled>
                                            Not Available
                                        </button>
                                    @endif

                                    <form action="{{ route('wishlist.remove', $item['products_id']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
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
