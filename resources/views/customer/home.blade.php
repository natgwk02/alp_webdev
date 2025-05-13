@extends('layouts.app')

@section('content')
    <style>
        body {
            color: #052659;
        }

        .hero-section {
            background-color: #b5e9f0;
            padding: 80px 0;
            color: #052659;
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
            background-color: #4ed1f2;
            color: white;
            padding: 40px 0;
        }

        .category {
            background-color: #f8f9fa;
            padding: 3rem 0;
        }

        .category-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .category-item img {
            height: 40vh;
            transition: transform 0.3s;
            width: 100%;
            object-fit: cover;
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

        .why-chille {
            background-color: white;
        }

        /* cat carousel */
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

        .category-carousel .carousel-control-prev {
            left: 15px;
        }

        .category-carousel .carousel-control-next {
            right: 15px;
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
    </style>

    {{-- atas   --}}
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-2">Welcome to Chillé Mart</h1>
                    <h3 class="mb-4">Keep it cool, keep it chillé!</h3>
                    <p class="lead mb-4">Deliciously frozen, always ready. Discover your next favorite meal today!</p>
                    <a href="" class="btn btn-primary btn-lg">Let's Chill</a>
                </div>
                <div class="col-lg-6 m-100 text-center">
                    <img src="{{ asset('images/logo-chille.png') }}" alt="Chille-mart"
                        class="img-fluid rounded-circle w-50">
                </div>
            </div>
        </div>
    </section>

    {{-- Category with Carousel --}}
    <section class="category py-5">
        <div class="container">
            <h2 class="text-center mb-5">Shop by Category</h2>

            <div id="categoryCarousel" class="carousel slide category-carousel" data-bs-ride="carousel"
                data-bs-interval="4000" data-bs-pause="hover">
                <!-- Carousel indicators -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#categoryCarousel" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#categoryCarousel" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                </div>

                <div class="carousel-inner">
                    <!-- First slide -->
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-md-3 col-6 mb-4">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/ready-meals.jpg') }}" alt="Ready Meals"
                                        class="img-fluid">
                                    <div class="category-overlay">
                                        <h5 class="mb-0">Ready Meals</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-4">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/frozen-veg.jpeg') }}" alt="Frozen Vegetables"
                                        class="img-fluid">
                                    <div class="category-overlay">
                                        <h5 class="mb-0">Frozen Vegetables</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-4">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/dimsum.jpg') }}" alt="Dimsum"
                                        class="img-fluid">
                                    <div class="category-overlay">
                                        <h5 class="mb-0">Dimsum</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-4">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/meat-fish.jpg') }}" alt="Frozen Meat & Fish"
                                        class="img-fluid">
                                    <div class="category-overlay">
                                        <h5 class="mb-0">Frozen Meat & Fish</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second slide (you can add more categories here) -->
                    <div class="carousel-item">
                        <div class="row">
                            <div class="col-md-3 col-6 mb-4">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/dimsum.jpg') }}" alt="Ice Cream"
                                        class="img-fluid">
                                    <div class="category-overlay">
                                        <h5 class="mb-0">Ice Cream</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-4">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/dimsum.jpg') }}" alt="Frozen Fruits"
                                        class="img-fluid">
                                    <div class="category-overlay">
                                        <h5 class="mb-0">Frozen Fruits</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-4">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/dimsum.jpg') }}" alt="Frozen Seafood"
                                        class="img-fluid">
                                    <div class="category-overlay">
                                        <h5 class="mb-0">Frozen Seafood</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-4">
                                <div class="category-item">
                                    <img src="{{ asset('images/category-img/dimsum.jpg') }}" alt="Desserts"
                                        class="img-fluid">
                                    <div class="category-overlay">
                                        <h5 class="mb-0">Desserts</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carousel controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    {{-- special offer --}}
    <section class="special-offer mb-5">
        <div class="container text-center">
            <h2 class="display-6 fw-bold mb-3">NEW CHILLÉ'S FRIEND 20%</h2>
            <p class="lead mb-4">Use code <span class="fw-bold">CHILLBRO</span> on your first order over Rp 200.000</p>
            <a href="#" class="btn btn-light btn-lg">Shop Now</a>
        </div>
    </section>

    {{-- Top seller --}}
    <section class="best-product py-5">
        <div class="container">
            <h2 class="text-center mb-5">Tasty Picks</h2>
            <div class="row">
                <!-- Product 1 -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100">
                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</span>
                        <img src="" class="card-img-top" alt="Product 1">
                        <div class="card-body">
                            <h5 class="card-title">Kanzler Nugget Crispy</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">Rp 40.999
                                    <small class="text-decoration-line-through text-muted">Rp 50.000</small>
                                </p>
                                <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary w-100">Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100">
                        <img src="" class="card-img-top" alt="Product 2">
                        <div class="card-body">
                            <h5 class="card-title">Product 2</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">Rp ---
                                    {{-- <small class="text-decoration-line-through text-muted">$99.99</small> --}}
                                </p>
                                <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary w-100">Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100">
                        {{-- <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span> --}}
                        <img src="https://via.placeholder.com/300" class="card-img-top" alt="Product 3">
                        <div class="card-body">
                            <h5 class="card-title">Product 3</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">Rp ---</p>
                                <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary w-100">Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100">
                        <img src="https://via.placeholder.com/300" class="card-img-top" alt="Product 4">
                        <div class="card-body">
                            <h5 class="card-title">Product 4</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">Rp ---</p>
                                <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary w-100">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="#" class="btn btn-primary">View All Products</a>
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
                            <h4>Flash-Frozen at Peak Freshness</h4>
                            <p>Our foods are frozen at the peak of freshness to lock in nutrients and flavor that last
                                longer than fresh alternatives.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 h-100 bg-transparent">
                        <div class="card-body text-center">
                            <i class="bi bi-clock-history fs-1 mb-3 feature-icon"></i>
                            <h4>Convenience & Time-Saving</h4>
                            <p>Ready-to-cook meals and ingredients that save you time in the kitchen without sacrificing
                                quality or taste.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 h-100 bg-transparent">
                        <div class="card-body text-center">
                            <i class="bi bi-trash fs-1 mb-3 feature-icon"></i>
                            <h4>Reduce Food Waste</h4>
                            <p>Use only what you need and keep the rest frozen, helping to reduce household food waste and
                                save money.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryCarousel = document.getElementById('categoryCarousel');
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
        });
    </script>
@endsection
