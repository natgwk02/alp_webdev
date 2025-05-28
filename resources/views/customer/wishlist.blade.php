@extends('layouts.app')

@section('title', 'My Wishlist - Chille Mart')

@push('styles')
    <style>
        .product-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease-in-out;
        }

        .wishlist-btn:hover .heart-icon {
            transform: scale(1.2);
            transition: transform 0.2s;
        }

        .product-img-container {
            position: relative;
            width: 100%;
            height: 230px;
            overflow: hidden;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .main-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            z-index: 1;
            transition: opacity 0.3s ease-in-out;
        }

        .rating-stars i {
            font-size: 0.95rem;
            margin-left: 1px;
        }

        .card-title {
            font-size: 1.1rem;
            line-height: 1.3;
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
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden product-card transition-transform position-relative">

                            {{-- Wishlist toggle/remove --}}
                            <form action="{{ route('wishlist.remove', $item['products_id']) }}"
                                method="POST" class="position-absolute top-0 end-0 m-2 z-3">
                                @csrf
                                <button type="submit" class="btn btn-light btn-sm border-0 wishlist-btn" data-product-id="{{ $item['products_id'] }}">
                                    <i class="bi bi-bookmark-heart-fill text-danger heart-icon fs-5"></i>
                                </button>
                            </form>

                            {{-- Product image --}}
                            <div class="text-center p-4 bg-white position-relative product-img-container">
                                <img src="{{ asset('images/products-img/' . $item['products_image']) }}"
                                     alt="{{ $item['products_name'] }}" class="img-fluid main-img" />
                            </div>

                            {{-- Card body --}}
                            <div class="card-body px-4 pb-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 class="card-title fw-bold text-dark mb-0">{{ $item['products_name'] }}</h5>
                                    <div class="rating-stars d-flex">
                                        @for ($i = 0; $i < 4; $i++)
                                            <i class="bi bi-star-fill text-warning small"></i>
                                        @endfor
                                        <i class="bi bi-star text-warning small"></i>
                                    </div>
                                </div>

                                <p class="text-secondary small mb-1">{{ $item['categories_name'] ?? '-' }}</p>
                                <h5 class="text-primary fw-semibold mb-4">Rp {{ number_format($item['orders_price'], 0, ',', '.') }}</h5>

                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <a href="{{ route('product.detail', $item['products_id']) }}"
                                       class="btn btn-outline-primary rounded-pill">View Details</a>

                                    @if ($item['products_stock'])
                                        <form action="{{ route('cart.add', ['productId' => $item['products_id']]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success rounded-pill">
                                                <i class="bi bi-cart-plus-fill"></i> Add
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-outline-secondary rounded-pill" disabled>
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
