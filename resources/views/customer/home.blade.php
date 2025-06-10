@extends('layouts.app')

@section('title', 'Chille Mart')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<script>
    function scrollWithOffset(e) {
        e.preventDefault();
        const target = document.querySelector('#shop-category');
        const offset = -100; // scroll lebih atas agar "Shop by Category" tetap terlihat

        if (target) {
            const bodyRect = document.body.getBoundingClientRect().top;
            const elementRect = target.getBoundingClientRect().top;
            const elementPosition = elementRect - bodyRect;
            const offsetPosition = elementPosition + offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    }
</script>

@section('content')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    @if (session('success'))
        <div id="successAlert"
            class="alert alert-success alert-dismissible fade show position-fixed top-20 end-0 m-3 shadow-lg z-3"
            role="alert" style="min-width: 300px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <style>
        
        body {
            color: #052659;
        }

        .hero-section {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .bg-video {
            position: absolute;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        /* .hero-section {
                background-color: #C1E8FF;
                padding: 80px 0;
                color: #052659;
            } */

        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            text-align: center;
            color: white;
            padding: 0 20px;
        }

        .hero-text h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .hero-text p {
            font-size: 1.25rem;
            margin-bottom: 25px;
        }

        .hero-section .container {
            position: relative;
            z-index: 2;
        }

        .hero-section .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.45);
            z-index: 1;
        }

        .btn-lets-chill {
            background-color: #ffffff;
            color: #052659;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
        }

.btn-lets-chill:hover {
    background-color: #f0f0f0; /* soft hover */
    color: #052659;
    border: 2px solid #052659; /* optional hover border */
}
        .btn-lets-chill:hover {
            background-color: #326fcb;
        }

        .product-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .special-offer {
            background-color: #C1E8FF;
            color: white;
            padding: 40px 0;
        }

        .special-offer:hover .btn {
            background-color: #003366;
            color: #ffdd00;
            transform: scale(1.05);
        }

        .special-offer .btn {
            transition: all 0.3s ease-in-out;
        }

        .special-offer .container {
            padding: 30px 25px;
        }


        .special-offer .container:hover {
            box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.2);
        }

        .category {
    background-color: #f8f9fa;
    padding: 3rem 0;
}

.category-carousel {
    position: relative;
}

.category-carousel .carousel-inner {
    padding-bottom: 60px; /* Space for indicators */
}

.category-carousel .carousel-item {
    padding: 0 15px;
    min-height: 320px; /* Ensure consistent height */
}

.category-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    height: 100%;
    transition: transform 0.3s ease-in-out;
}

.category-item img {
    height: 40vh; /* Ukuran asli seperti kode sebelumnya */
    width: 100%;
    object-fit: cover;
    transition: transform 0.3s ease-in-out;
}

.category-item:hover {
    transform: translateY(-5px);
}

.category-item:hover img {
    transform: scale(1.05);
}

.category-text {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    padding: 10px;
    text-align: center;
    background: rgba(102, 204, 255, 0.8);
    color: white;
    font-size: 1.2rem;
    font-weight: bold;
}

.category-item:hover .category-text {
    background: rgba(102, 204, 255, 1);
}

/* Carousel Controls */
.category-carousel .carousel-control-prev,
.category-carousel .carousel-control-next {
    width: 50px;
    height: 50px;
    background-color: rgba(5, 38, 89, 0.8);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.8;
    border: none;
}

.category-carousel .carousel-control-prev {
    left: -25px;
}

.category-carousel .carousel-control-next {
    right: -25px;
}

.category-carousel .carousel-control-prev:hover,
.category-carousel .carousel-control-next:hover {
    opacity: 1;
    background-color: rgba(5, 38, 89, 1);
}

.category-carousel .carousel-control-prev-icon,
.category-carousel .carousel-control-next-icon {
    width: 20px;
    height: 20px;
}

/* Carousel Indicators */
.category-carousel .carousel-indicators {
    bottom: -50px;
    margin-bottom: 0;
}

.category-carousel .carousel-indicators button {
    background-color: #4ed1f2;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 5px;
    border: none;
    opacity: 0.5;
}

.category-carousel .carousel-indicators button.active {
    opacity: 1;
    background-color: #052659;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .category-item img {
        height: 35vh;
    }
    
    .category-text {
        font-size: 1rem;
        padding: 8px;
    }
    
    .category-carousel .carousel-control-prev {
        left: -15px;
    }
    
    .category-carousel .carousel-control-next {
        right: -15px;
    }
}

@media (max-width: 576px) {
    .category-item img {
        height: 30vh;
    }
    
    .category-text h5 {
        font-size: 0.9rem;
    }
}

.category-item:hover {
    transform: translateY(-5px);
}

.category-item:hover img {
    transform: scale(1.05);
}

.category-text {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 15px;
    text-align: center;
    background: rgba(102, 204, 255, 0.9);
    color: white;
    font-size: 1rem;
    font-weight: bold;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-item:hover .category-text {
    background: rgba(102, 204, 255, 1);
}

/* Carousel Controls */
.category-carousel .carousel-control-prev,
.category-carousel .carousel-control-next {
    width: 50px;
    height: 50px;
    background-color: rgba(5, 38, 89, 0.8);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.8;
    border: none;
}

.category-carousel .carousel-control-prev {
    left: -25px;
}

.category-carousel .carousel-control-next {
    right: -25px;
}

.category-carousel .carousel-control-prev:hover,
.category-carousel .carousel-control-next:hover {
    opacity: 1;
    background-color: rgba(5, 38, 89, 1);
}

.category-carousel .carousel-control-prev-icon,
.category-carousel .carousel-control-next-icon {
    width: 20px;
    height: 20px;
}

/* Carousel Indicators */
.category-carousel .carousel-indicators {
    bottom: -50px;
    margin-bottom: 0;
}

.category-carousel .carousel-indicators button {
    background-color: #4ed1f2;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 5px;
    border: none;
    opacity: 0.5;
}

.category-carousel .carousel-indicators button.active {
    opacity: 1;
    background-color: #052659;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .category-item {
        height: 250px;
    }
    
    .category-item img {
        height: 180px;
    }
    
    .category-text {
        font-size: 0.9rem;
        padding: 10px;
        min-height: 50px;
    }
    
    .category-carousel .carousel-control-prev {
        left: -15px;
    }
    
    .category-carousel .carousel-control-next {
        right: -15px;
    }
}

@media (max-width: 576px) {
    .category-item {
        height: 220px;
    }
    
    .category-item img {
        height: 160px;
    }
    
    .category-text h5 {
        font-size: 0.85rem;
    }
}

        .category-item:hover img {
            transform: scale(1.05);
        }

        .category-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(130, 230, 245, 0.8);
            color: white;
            padding: 15px;
        }

        .category-item:hover .category-text {
            transform: translateY(-5px);

        }

        .why-chille {
            background-color: white;
        }

        .category-carousel .carousel-control-prev,
        .category-carousel .carousel-control-next {
            width: 40px;
            height: 40px;
            background-color: rgba(5, 38, 89, 0.7);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.8;
        }

        .category-carousel .carousel-control-prev:hover,
        .category-carousel .carousel-control-next:hover {
            opacity: 1;
            background-color: rgba(5, 38, 89, 0.9);
        }

        .category-carousel .carousel-indicators {
            bottom: -40px;
        }

        .category-carousel .carousel-indicators button {
            background-color: #4ed1f2;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 5px;
        }

        .category-carousel .carousel-item {
            padding: 0 15px;
        }

        .category-carousel .carousel-inner {
            padding-bottom: 20px;
        }

        .feature-icon {
            color: #4ed1f2;
        }

        html {
            scroll-behavior: smooth;
        }

        .voucher-hero {
            background: linear-gradient(to bottom right, #C1E8FF, #C1E8FF);
            padding: 40px 20px;
        }


        .voucher-box {
            max-width: 1100px;
            width: 100%;
            background-color: #fff;
            border-radius: 20px;
            overflow: hidden;
        }

        .voucher-img {
            flex: 1;
            min-height: 280px;
            max-height: 450px;
        }

        .voucher-img img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }

        .voucher-content {
            flex: 1;
            padding: 40px;
        }

        .voucher-box {
            max-width: 1100px;
            margin: 0 auto;
        }

        .btn-best-product {
            transition: all 0.1s ease-in-out;
            background-color: #052659;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
        }

        .btn-best-product:hover {
            background-color: #326fcb;

        }


        .product-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex: 1;
            min-height: 180px; /* atau sesuaikan */
        }

        .product-card img {
            height: 200px;
            object-fit: contain;
        }

        /* yutub */
        .py-5 {
            padding-top: 60px;
            padding-bottom: 60px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h2 {
            font-size: 2.5rem;
            color: #052659;
            margin-bottom: 30px;
        }

        iframe {
            width: 80%;
            height: 80%;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .text-center {
            text-align: center;
        }

        .testimonial-bubble {
            border-radius: 20px 20px 20px 0;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .testimonial-bubble:hover {
            transform: translateY(-5px);
        }

        .text-purple {
            color: #052659;
        }

        .testimonial-item {
            min-height: 350px;
            /* atau atur sesuai kebutuhan */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .text-primary {
            color: #052659 !important;
        }

        .bi-star-fill,
        .bi-star-half,
        .bi-star {
            color:#ffdd00 !important;
        }

        .star-group {
            display: flex;
            gap: 2px;
            flex-wrap: nowrap;
            flex-direction: row;
        }

        /* Responsive: Bintang 3 atas, 2 bawah */
        @media (max-width: 576px) {
            .star-group {
                justify-content: start; /* atau center */
                margin-top: 4px;
            }

            .star-group i {
                font-size: 1rem;
            }
        }

        
    </style>

    <section class="hero-section">
        <video autoplay muted loop playsinline class="bg-video">
            <source src="{{ asset('videos/chillevideo.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div class="overlay"></div>

        <div class="hero-text">
            <h1>Keep it cool, keep it Chillé!</h1>
            <p>Deliciously frozen, always ready. Discover your next favorite meal today!</p>
            <a href="#shop-category" class="btn btn-lets-chill btn-lg border-0" onclick="scrollWithOffset(event)">
                Let's Chill
            </a>

        </div>
    </section>


    {{-- category --}}
<section class="category py-5">
    <div class="container">
        <section id="shop-category">
            <h2 class="text-center mb-4">Shop by Category</h2>
        </section>

        <div id="categoryCarousel" class="carousel slide category-carousel" data-bs-ride="carousel"
            data-bs-interval="4000" data-bs-pause="hover">
            
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#categoryCarousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#categoryCarousel" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
            </div>

            <!-- Carousel Inner -->
            <div class="carousel-inner">
                <!-- First Slide -->
                <div class="carousel-item active">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('products', ['category' => '1']) }}" class="text-decoration-none text-reset">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/ready-meals.jpg') }}" alt="Ready Meals" class="img-fluid">
                                    <div class="category-text">
                                        <h5 class="mb-0">Ready Meals</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('products', ['category' => '2']) }}" class="text-decoration-none text-reset">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/vegetable.jpeg') }}" alt="Frozen Vegetable" class="img-fluid">
                                    <div class="category-text">
                                        <h5 class="mb-0">Frozen Vegetable</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('products', ['category' => '3']) }}" class="text-decoration-none text-reset">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/dimsum.jpg') }}" alt="Frozen Dimsum" class="img-fluid">
                                    <div class="category-text">
                                        <h5 class="mb-0">Frozen Dimsum</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('products', ['category' => '4']) }}" class="text-decoration-none text-reset">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/meat.jpg') }}" alt="Frozen Meat" class="img-fluid">
                                    <div class="category-text">
                                        <h5 class="mb-0">Frozen Meat</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Second Slide -->
                <div class="carousel-item">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('products', ['category' => '5']) }}" class="text-decoration-none text-reset">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/nugget.jpg') }}" alt="Frozen Nugget" class="img-fluid">
                                    <div class="category-text">
                                        <h5 class="mb-0">Frozen Nugget</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('products', ['category' => '6']) }}" class="text-decoration-none text-reset">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/fruit.jpeg') }}" alt="Frozen Fruit" class="img-fluid">
                                    <div class="category-text">
                                        <h5 class="mb-0">Frozen Fruit</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('products', ['category' => '7']) }}" class="text-decoration-none text-reset">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/seafood.jpg') }}" alt="Frozen Seafood" class="img-fluid">
                                    <div class="category-text">
                                        <h5 class="mb-0">Frozen Seafood</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('products', ['category' => '8']) }}" class="text-decoration-none text-reset">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/dessert.jpg') }}" alt="Dessert" class="img-fluid">
                                    <div class="category-text">
                                        <h5 class="mb-0">Dessert</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

    {{-- special offer --}}
    <section class="voucher-hero d-flex align-items-center justify-content-center py-5 px-3">
        <div class="voucher-box shadow-lg rounded-4 d-flex flex-column flex-md-row overflow-hidden">

            <div class="voucher-img bg-light">
                <img src="/images/voucher1.png" alt="Voucher Banner" class="img-fluid w-100 h-100 object-fit-cover">
            </div>

            <div
                class="voucher-content p-4 p-md-5 d-flex flex-column justify-content-center text-center text-md-start bg-white">

                <h2 class="text-blue fw-bold mb-2">🎉 Welcome to Chillé – Get Rp50.000 Off! 🎉</h2>

                <p class="mb-3 text-dark fs-5">Enjoy all your favorite frozen food with a minimum spend of Rp200.000</p>

                <p class="mb-4 text-muted">Use code <span class="text-warning fw-bold">CHILLBRO</span> at checkout for
                    your first order</p>

                <a href="{{ route('products') }}"
                    class="btn btn-warning px-4 py-2 fw-semibold rounded-pill shadow-sm text-dark">Shop Now</a>
            </div>

        </div>
    </section>

    {{-- Top seller --}}
    <section class="best-product py-5">
    <div class="container">
        <h2 class="text-center mb-5">Tasty Picks</h2>
        <div class="row">
            @foreach ($products as $product)
                <div class="col-lg-3 col-md-4 col-6 mb-4">
                    <div class="card product-card h-100 position-relative" data-aos="zoom-in" data-aos-duration="700">
                        @if($product->is_on_sale)
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</span>
                        @elseif($product->is_new)
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                        @endif

                        <img src="{{ asset('images/products-img/' . $product->products_image) }}" class="card-img-top" alt="{{ $product->products_name }}">

                        <div class="card-body d-flex flex-column h-100">
                            <h5 class="card-title">{{ $product->products_name }}</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-1 fw-semibold text-dark" style="font-size: 0.95rem;">
                                    Rp {{ number_format($product->orders_price, 0, ',', '.') }}
                                </p>
                            <div class="star-group mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $product->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            </div>
                            <form action="{{ route('cart.add', ['productId' => $product->products_id]) }}" method="POST" class="add-to-cart-form">
                            @csrf
                                <input type="hidden" name="product_id" value="{{ $product->products_id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-outline-primary w-100 rounded-pill">
                                    <i class="bi bi-cart-plus-fill"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('products') }}" class="btn text-white btn-best-product">
                View All Products
            </a>
        </div>
    </div>
</section>

    {{-- why us --}}
    <section class="why-chille py-5">
        <div class="container">
            <h2 class="text-center mb-5">Why Chillé Mart?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 h-100 bg-transparent">
                        <div class="card-body text-center">
                            <i class="bi bi-snow fs-1 mb-3 feature-icon"></i>
                            <h4 class="mt-3">Flash-Frozen at Peak Freshness</h4>
                            <p>Our foods are frozen at the peak of freshness to lock in nutrients and flavor that last
                                longer than fresh alternatives.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 h-100 bg-transparent">
                        <div class="card-body text-center">
                            <i class="bi bi-clock-history fs-1 mb-3 feature-icon"></i>
                            <h4 class="mt-3">Convenience & Time-Saving</h4>
                            <p>Ready-to-cook meals and ingredients that save you time in the kitchen without sacrificing
                                quality or taste.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 h-100 bg-transparent">
                        <div class="card-body text-center">
                            <i class="bi bi-trash fs-1 mb-3 feature-icon"></i>
                            <h4 class="mt-3">Reduce Food Waste</h4>
                            <p>Use only what you need and keep the rest frozen, helping to reduce household food waste and
                                save money.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        @if (session('is_guest'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.add-to-cart-form').forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        alert("You must sign in to add products to the cart.");
                    });
                });
            });
        </script>
        @endif

        $(document).ready(function() {
            // Auto-hide success alert after 5 seconds
            if ($('#successAlert').length) {
                setTimeout(function() {
                    $('#successAlert').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 3000);
            }

            if ($('#errorAlert').length) {
                setTimeout(function() {
                    $('#errorAlert').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 3000);
            }

            // Carousel controls
            const categoryCarousel = document.getElementById('categoryCarousel');
            if (categoryCarousel) {
                const carousel = new bootstrap.Carousel(categoryCarousel, {
                    interval: 5000,
                    pause: false
                });

                const carouselControls = document.querySelectorAll(
                    '.carousel-control-prev, .carousel-control-next, .carousel-indicators button');
                carouselControls.forEach(control => {
                    control.addEventListener('click', function() {
                        carousel.pause();
                        setTimeout(function() {
                            carousel.cycle();
                        }, 100);
                    });
                });
            }
        });
    </script>

@endsection
