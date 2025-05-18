@extends('layouts.app')

@section('title', 'Frozen Food Products - Chille Mart')

@section('content')

    @if (session('success'))
    <div id="successAlert" class="alert alert-success alert-dismissible fade show position-fixed top-20 end-0 m-3 shadow-lg z-3"
        role="alert" style="min-width: 300px;">
        {{ session('success') }}
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

        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <form action="{{ route('products') }}" method="GET" id="searchFilterForm">
                            <div class="row">
                                <!-- Search Bar - Expanded -->
                                <div class="col-md-9 col-12 mb-md-0 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-search text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0" name="search"
                                            id="searchInput" placeholder="Search frozen food..."
                                            value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                    </div>
                                </div>

                                <!-- Filter Dropdown Button -->
                                <div class="col-md-3 col-12 d-flex justify-content-md-end">
                                    <div class="dropdown filter-dropdown w-100">
                                        <button class="btn btn-outline-primary dropdown-toggle w-100" type="button"
                                            id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-funnel me-1"></i> Filter
                                            <span class="badge bg-primary ms-2 filter-badge" id="activeFiltersBadge"
                                                style="display: none;">0</span>
                                        </button>
                                        <div class="dropdown-menu p-3 filter-dropdown-menu shadow"
                                            aria-labelledby="filterDropdown">
                                            <!-- Category Filter -->
                                            <div class="mb-3">
                                                <label for="categoryFilter" class="form-label small">Category</label>
                                                <select class="form-select" name="category" id="categoryFilter">
                                                    <option value="">All Categories</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category }}"
                                                            {{ request('category') == $category ? 'selected' : '' }}>
                                                            {{ $category }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Price Range Filter -->
                                            <div class="mb-3">
                                                <label class="form-label small">Price Range</label>
                                                <div class="d-flex align-items-center gap-2 price-range-inputs">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white">Rp</span>
                                                        <input type="number" class="form-control" name="min_price"
                                                            id="minPrice" placeholder="Min" min="0"
                                                            value="{{ request('min_price') }}">
                                                    </div>
                                                    <div class="small text-muted">to</div>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white">Rp</span>
                                                       <input type="number" class="form-control" name="max_price"
                                                            id="maxPrice" placeholder="Max" min="0"
                                                            value="{{ request('max_price') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('products') }}" class="btn btn-sm btn-outline-secondary">
                                                    Reset Filters
                                                </a>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Apply Filters
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @if (count($products) > 0)
                @foreach ($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div
                            class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden product-card transition-transform position-relative">

                            <!-- Wishlist Button -->
                            <form
                                action="{{ isset($wishlist[$product['id']]) ? route('wishlist.remove', $product['id']) : route('wishlist.add', $product['id']) }}"
                                method="POST" class="position-absolute top-0 end-0 m-2 z-3">
                                @csrf
                                <button type="submit" class="btn btn-light btn-sm border-0 wishlist-btn"
                                    data-product-id="{{ $product['id'] }}">
                                    <i
                                        class="bi bi-bookmark-heart-fill {{ isset($wishlist[$product['id']]) ? 'text-danger' : 'text-dark' }} heart-icon fs-5"></i>
                                </button>
                            </form>

                            <!-- Product Image with Hover -->
                            <div class="text-center p-4 bg-white position-relative product-img-container">
                                <img src="{{ asset('images/products-img/' . $product['image']) }}"
                                    alt="{{ $product['name'] }}" class="img-fluid main-img" />

                                @if (!empty($product['hover_image']))
                                    <img src="{{ asset('images/hoverproducts-img/' . $product['hover_image']) }}"
                                        alt="{{ $product['name'] }} Hover" class="img-fluid hover-img" />
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
                                            $halfStar = $rating - $fullStars >= 0.5;
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
                                <h5 class="text-primary fw-semibold mb-4">Rp
                                    {{ number_format($product['price'], 0, ',', '.') }}</h5>

                                <form action="{{ route('cart.add', ['productId' => $product['id']]) }}" method="POST"
                                    class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product['id'] }}">

                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('product.detail', $product['id']) }}"
                                            class="btn btn-outline-primary rounded-pill">View Details</a>
                                        <button type="submit" class="btn btn-success rounded-pill">
                                            <i class="bi bi-cart-plus-fill"></i> Add
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                    <h3 class="mt-3 text-muted">No products found</h3>
                    <p class="text-muted">Try adjusting your search or filter criteria</p>
                    <a href="{{ route('products') }}" class="btn btn-outline-primary mt-2">
                        View All Products
                    </a>
                </div>
            @endif
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
            object-fit: cover;
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

        .bi-star-fill,
        .bi-star-half,
        .bi-star {
            font-size: 0.95rem;
        }

        .rating-stars i {
            margin-left: 1px;
        }

        .card-title {
            font-size: 1.1rem;
            line-height: 1.3;
        }

        /* Filter dropdown styling */
        .filter-dropdown-menu {
            width: 320px;
            max-width: 100%;
        }

        .dropdown-toggle::after {
            margin-left: 8px;
        }

        .filter-badge {
            font-size: 0.75rem;
        }

        @media (max-width: 576px) {
            .filter-dropdown-menu {
                width: 100%;
            }
        }

        /* Price range input styling */
        .price-range-inputs .input-group-text {
            border-right: 0;
        }

        .price-range-inputs input.form-control {
            border-left: 0;
        }

        .price-range-inputs input::-webkit-outer-spin-button,
        .price-range-inputs input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        .price-range-inputs input[type=number] {
            -moz-appearance: textfield;
        }

        input:focus,
        input:focus-visible,
        button:focus,
        button:focus-visible,
        .btn:focus,
        .btn:focus-visible {
            outline: none !important;
            box-shadow: none !important;
            border-color: #ced4dae8 !important;
        }
    </style>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       $(document).ready(function() {
    // Auto-hide success alert after 5 seconds
    if ($('#successAlert').length) {
        setTimeout(function() {
            $('#successAlert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    if ($('#errorAlert').length) {
        setTimeout(function() {
            $('#errorAlert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Wishlist functionality
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
        // Remove alert lama kalau ada
        $('#successAlert').remove();

        // Tambahkan alert baru ke body atau ke container tertentu di halaman kamu
        var alertHtml = `<div id="successAlert" class="alert alert-success alert-dismissible fade show position-fixed top-20 end-0 m-3 shadow-lg z-3" 
            role="alert" style="min-width: 300px; z-index: 1055;">
            ${response.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;

        // Contoh append ke atas konten produk (ganti selector sesuai layout kamu)
        $('body').prepend(alertHtml);

        // Auto hide alert setelah 5 detik
        setTimeout(function() {
            $('#successAlert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    },
    error: function() {
        alert('Failed to update wishlist.');
    }
});
    });

    // Price range input validation
    $('#minPrice, #maxPrice').on('input', function() {
        const minPriceVal = parseInt($('#minPrice').val()) || 0;
        const maxPriceVal = parseInt($('#maxPrice').val()) || 1000000;
        // Validasi logika
    });
});


        function updateActiveFiltersBadge() {
    let count = 0;

    const search = $('#searchInput').val().trim();
    const category = $('#categoryFilter').val();
    const minPrice = $('#minPrice').val().trim();
    const maxPrice = $('#maxPrice').val().trim();

    if (search) count++;
    if (category) count++;
    if (minPrice) count++;
    if (maxPrice) count++;

    if (count > 0) {
        $('#activeFiltersBadge').text(count).show();
    } else {
        $('#activeFiltersBadge').hide();
    }
}

// Run on page load
$(document).ready(function() {
    updateActiveFiltersBadge();
});

    </script>
@endsection