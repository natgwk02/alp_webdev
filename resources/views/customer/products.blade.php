@extends('layouts.app')

@section('title', 'Frozen Food Products - Chille Mart')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-20 end-0 m-3 shadow-lg z-3" role="alert" style="min-width: 300px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-20 end-0 m-3 shadow-lg z-3" role="alert" style="min-width: 300px;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container">
    <div class="row mb-4 mt-4">
        <div class="col-12 text-center">
            <h1 class="fw-bold display-5" style="color: #052659;">Our Frozen Food Selection</h1>
            <p class="text-muted">Premium quality frozen foods from around the world</p>
        </div>
    </div>

    <div class="row">
        @foreach($products as $product)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden product-card transition-transform position-relative">
                
                <!-- Wishlist Button -->
                <form action="{{ isset($wishlist[$product['id']]) ? route('wishlist.remove', $product['id']) : route('wishlist.add', $product['id']) }}" method="POST" class="position-absolute top-0 end-0 m-2 z-3">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm border-0 wishlist-btn" data-product-id="{{ $product['id'] }}">
                        <i class="bi bi-bookmark-heart-fill {{ isset($wishlist[$product['id']]) ? 'text-danger' : 'text-dark' }} heart-icon fs-5"></i>
                    </button>
                </form>

                <!-- Product Image with Hover -->
                <div class="text-center p-4 bg-white position-relative product-img-container">
                    <img src="{{ asset('images/products-img/' . $product['image']) }}" 
                         alt="{{ $product['name'] }}" 
                         class="img-fluid main-img" />

                    @if (!empty($product['hover_image']))
                    <img src="{{ asset('images/hoverproducts-img/' . $product['hover_image']) }}" 
                         alt="{{ $product['name'] }} Hover" 
                         class="img-fluid hover-img" />
                    @endif
                </div>

                <!-- Product Info -->
                <div class="card-body px-4 pb-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="card-title fw-bold text-dark mb-0">{{ $product['name'] }}</h5>
                        <div class="rating-stars d-flex">
                            @php
                                $rating = $product['rating'] ?? 0;
                                $fullStars = floor($rating);
                                $halfStar = ($rating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            @endphp

                            @for ($i = 0; $i < $fullStars; $i++)
                                <i class="bi bi-star-fill text-warning small"></i>
                            @endfor

                            @if ($halfStar)
                                <i class="bi bi-star-half text-warning small"></i>
                            @endif

                            @for ($i = 0; $i < $emptyStars; $i++)
                                <i class="bi bi-star text-warning small"></i>
                            @endfor
                        </div>
                    </div>

                    <p class="text-secondary small mb-1">{{ $product['category'] }}</p>
                    <h5 class="text-primary fw-semibold mb-4">Rp {{ number_format($product['price'], 0, ',', '.') }}</h5>

                    <form action="{{ route('cart.add', ['productId' => $product['id']]) }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('product.detail', $product['id']) }}" class="btn btn-outline-primary rounded-pill">View Details</a>
                            <button type="submit" class="btn btn-success rounded-pill">
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

<!-- Custom Styling -->
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
    }

    .product-img-container .main-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        position: relative;
        z-index: 1;
        transition: opacity 0.3s ease-in-out;
    }

    .product-img-container .hover-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        opacity: 0;
        z-index: 2;
        transition: opacity 0.3s ease-in-out;
    }

    .product-img-container:hover .hover-img {
        opacity: 1;
    }

    .product-img-container:hover .main-img {
        opacity: 0;
    }

    .bi-star-fill, .bi-star-half, .bi-star {
        font-size: 0.95rem;
    }

    .rating-stars i {
        margin-left: 1px;
    }

    .card-title {
        font-size: 1.1rem;
        line-height: 1.3;
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.wishlist-btn', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        var icon = $(this).find('.heart-icon');

        if (icon.hasClass('text-dark')) {
            icon.removeClass('text-dark').addClass('text-danger');
        } else {
            icon.removeClass('text-danger').addClass('text-dark');
        }

        var currentWishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        if (currentWishlist.includes(productId)) {
            currentWishlist = currentWishlist.filter(item => item !== productId);
        } else {
            currentWishlist.push(productId);
        }
        localStorage.setItem('wishlist', JSON.stringify(currentWishlist));

        $.ajax({
            url: '/wishlist/toggle/' + productId,
            type: 'GET',
            success: function(response) {
                console.log("Wishlist updated");
            }
        });
    });
</script>
@endsection
