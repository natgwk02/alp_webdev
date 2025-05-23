@extends('layouts.app')

@section('title', $product['name'] . ' - Chillé Mart')

@push('styles')
    <style>
        .product-section {
            padding: 60px 0;
            background: linear-gradient(to bottom, #f9fcff, #ffffff);
        }

        .product-image-wrapper {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            background-color: #fff;
        }

        .product-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease-in-out;
        }

        .product-image-wrapper img:hover {
            transform: scale(1.04);
        }

        .product-details h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #052659;
        }

        .product-details .category-badge {
            font-size: 0.75rem;
            background-color: #e0f0ff;
            color: #0077cc;
            padding: 4px 10px;
            border-radius: 12px;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .rating-stars i {
            font-size: 1rem;
            color: #ffc107;
            margin-right: 2px;
        }

        .product-details .price {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0d6efd;
            margin: 1rem 0;
        }

        .product-details .desc {
            font-size: 1rem;
            color: #444;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-control {
            max-width: 100px;
            font-size: 0.95rem;
        }

        .btn-add-to-cart {
            margin-top: 1rem;
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            background-color: #198754;
            color: white;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn-add-to-cart:hover {
            background-color: #157347;
            transform: scale(1.02);
        }

        @media (max-width: 768px) {
            .product-details h1 {
                font-size: 1.5rem;
            }

            .product-details .price {
                font-size: 1.3rem;
            }
        }
    </style>
@endpush

@section('content')
    @if (session('success'))
        <div id="successAlert"
            class="alert alert-success alert-dismissible fade show position-fixed top-20 end-0 m-3 shadow-lg z-3"
            role="alert" style="min-width: 300px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container product-section">
        <div class="row justify-content-center align-items-center g-5">
            <div class="col-lg-5 col-md-6">
                <div class="product-image-wrapper">
                    <img src="{{ asset('images/products-img/' . $product['image']) }}" alt="{{ $product['name'] }}">
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="product-details">
                    <h1>{{ $product['name'] }}</h1>
                    <div class="category-badge">{{ $product['category'] }}</div>

                    @if (isset($product['rating']))
                        <div class="rating-stars mb-2">
                            @php
                                $rating = $product['rating'];
                                $fullStars = floor($rating);
                                $halfStar = $rating - $fullStars >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            @endphp

                            @for ($i = 0; $i < $fullStars; $i++)
                                <i class="bi bi-star-fill"></i>
                            @endfor

                            @if ($halfStar)
                                <i class="bi bi-star-half"></i>
                            @endif

                            @for ($i = 0; $i < $emptyStars; $i++)
                                <i class="bi bi-star"></i>
                            @endfor
                        </div>
                    @endif

                    <div class="price">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>

                    <p class="desc">
                        Delicious and fresh, our {{ strtolower($product['name']) }} is a customer favorite sourced from
                        high-quality suppliers.
                    </p>

                    <form action="{{ route('cart.add', ['productId' => $product['id']]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">

                        <div class="mb-2">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        setTimeout(function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, 4000);

        window.addEventListener('pageshow', function(event) {
            const alert = document.getElementById('successAlert');
            if (event.persisted && alert) {
                alert.remove();
            }
        });
    </script>
@endsection
