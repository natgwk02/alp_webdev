@extends('layouts.app')

@section('title', 'Frozen Food Products - Chille Mart')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-20 end-0 m-3 shadow-lg z-3"
        role="alert" style="min-width: 300px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-20 end-0 m-3 shadow-lg z-3"
        role="alert" style="min-width: 300px;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container">
     

    <!-- Product Listing Section -->
    <div class="row mb-4 mt-4">
        <div class="col-12">
            <h1 class="fw-bold">Our Frozen Food Selection</h1>
            <p class="text-muted">Premium quality frozen foods from around the world</p>
        </div>
    </div>

    <div class="row">
        @foreach($products as $product)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm position-relative">
                <!-- Wishlist Button -->
                <form action="{{ isset($wishlist[$product['id']]) ? route('wishlist.remove', $product['id']) : route('wishlist.add', $product['id']) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm border-0 wishlist-btn" data-product-id="{{ $product['id'] }}">
                        <i class="bi bi-bookmark-heart-fill {{ isset($wishlist[$product['id']]) ? 'text-danger' : 'text-dark' }} heart-icon"></i>
                    </button>
                </form>

                <div class="text-center p-3">
                    <img src="{{ asset('images/products-img/' . $product['image']) }}" alt="{{ $product['name'] }}" class="img-fluid" style="max-height: 200px; object-fit: contain;">
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

@section('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).on('click', '.wishlist-btn', function(e) {
            e.preventDefault(); // Prevent the form from submitting immediately

            var productId = $(this).data('product-id');
            var icon = $(this).find('.heart-icon');

            // Change heart color immediately for visual feedback
            if (icon.hasClass('text-dark')) {
                icon.removeClass('text-dark').addClass('text-danger'); // Add 'text-danger' class for red
            } else {
                icon.removeClass('text-danger').addClass('text-dark'); // Add 'text-dark' class for grey
            }

            // Temporarily update the wishlist in the client-side (session/local storage if required)
            var currentWishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
            if (currentWishlist.includes(productId)) {
                // Remove from client-side wishlist
                currentWishlist = currentWishlist.filter(function(item) {
                    return item !== productId;
                });
            } else {
                // Add to client-side wishlist
                currentWishlist.push(productId);
            }

            // Save to local storage
            localStorage.setItem('wishlist', JSON.stringify(currentWishlist));

            // Send AJAX request to update wishlist on the server after the visual change
            $.ajax({
                url: '/wishlist/toggle/' + productId,
                type: 'GET',
                success: function(response) {
                    // Optionally handle the server's response if needed
                    console.log("Wishlist updated on the server");
                }
            });
        });
    </script>
@endsection
