@extends('layouts.app')

@section('title', 'Chille Mart')

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
            background-color: #C1E8FF;
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
            background-color: #b5e9f0;
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
            <div class="col-lg-12 text-center mb-4">
                <img src="{{ asset('images/logo-chille.png') }}" alt="Chille-mart"
                    class="img-fluid rounded-circle" style="width: 30%; margin-bottom: 10px; margin-top: -30px;">
            </div>
            <div class="col-lg-12 text-center" style="margin-top: -20px;">
                <h1 class="display-4 fw-bold mb-2">Keep it cool, keep it Chillé!</h1>
                <p class="lead mb-4">Deliciously frozen, always ready. Discover your next favorite meal today!</p>
                <a href="" class="btn btn-primary btn-lg">Let's Chill</a>
            </div>
        </div>
    </div>
    </section>

    {{-- category --}}
                        
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
                                <div class="category-text">
                                    <h5 class="mb-0">Ready Meals</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="category-item">
                                <img src="{{ asset('images/category-img/frozen-veg.jpeg') }}" alt="Frozen Vegetable"
                                    class="img-fluid">
                                <div class="category-text">
                                    <h5 class="mb-0">Frozen Vegetable</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="category-item">
                                <img src="{{ asset('images/category-img/dimsum.jpg') }}" alt="Frozen Dimsum"
                                    class="img-fluid">
                                <div class="category-text">
                                    <h5 class="mb-0">Frozen Dimsum</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="category-item">
                                <img src="{{ asset('images/category-img/meat-fish.jpg') }}" alt="Frozen Meat"
                                    class="img-fluid">
                                <div class="category-text">
                                    <h5 class="mb-0">Frozen Meat</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second slide -->
                <div class="carousel-item">
                    <div class="row">
                        <div class="col-md-3 col-6 mb-4">
                            <div class="category-item">
                                <img src="{{ asset('images/category-img/nugget.jpg') }}" alt="Frozen Nugget"
                                    class="img-fluid">
                                <div class="category-text">
                                    <h5 class="mb-0">Frozen Nugget</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="category-item">
                                <img src="{{ asset('images/category-img/fruits.jpg') }}" alt="Frozen Fruit"
                                    class="img-fluid">
                                <div class="category-text">
                                    <h5 class="mb-0">Frozen Fruit</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="category-item">
                                <img src="{{ asset('images/category-img/seafood.jpeg') }}" alt="Frozen Seafood"
                                    class="img-fluid">
                                <div class="category-text">
                                    <h5 class="mb-0">Frozen Seafood</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="category-item">
                                <img src="{{ asset('images/category-img/dessert.jpg') }}" alt="Dessert"
                                    class="img-fluid">
                                <div class="category-text">
                                    <h5 class="mb-0">Dessert</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel controls -->
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

    <style>
        .category-item {
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        .category-item img {
            width: 100%;
            transition: transform 0.3s ease-in-out;
        }

        .category-item:hover img {
            transform: scale(1.05); /* Slightly enlarge the image on hover */
        }

        .category-text {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            padding: 10px;
            text-align: center;
            background: rgba(102, 204, 255, 0.8); /* Light blue background for contrast */
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .category-item:hover .category-text {
            transform: translateY(-5px); /* Lift the text along with the image */
        }
    </style>


    {{-- special offer --}}
    <section class="special-offer mb-5"> 
    <div class="container text-center" style="background-color:#00000020; border-radius: 20px 20px 0 0; padding: 30px 15px; box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);">
        <div class="mb-3">
        <img src="{{ asset('images/voucher.png') }}" alt="Special Offer" class="img-fluid" style="max-width: 500px; margin-bottom: 10px;">
        </div>

        <h2 class="display-4 fw-bold mb-3" style="font-size: 2.5rem; letter-spacing: 1px; color: #003366;">NEW CHILLÉ'S FRIEND 20%</h2>
        
        <p class="lead mb-4" style="font-size: 1.2rem; font-weight: 600; color: #003366;">Use code <span class="fw-bold" style="color: #ffdd00;">CHILLBRO</span> on your first order over Rp 200.000</p>
        
        <a href="{{ route('products') }}" class="btn btn-light btn-lg" style="background-color: #ffdd00; color: #003366; padding: 12px 30px; border-radius: 50px; font-weight: bold; text-transform: uppercase; font-size: 1rem; transition: all 0.3s ease-in-out;">
            Shop Now
        </a>
    </div>

    <!-- Bottom color section -->
    <div class="container text-center" style="background-color: #00000020; border-radius: 0 0 20px 20px; padding: 20px 15px;">
        <p style="color: #003366;">Exclusive Offer for New Customers</p>
    </div>
</section>

<style>
    .special-offer:hover .btn {
        background-color: #003366;
        color: #ffdd00;
        transform: scale(1.05); /* Slightly enlarge the button on hover */
    }

    .special-offer .btn {
        transition: all 0.3s ease-in-out;
    }

    .special-offer .container {
        padding: 30px 25px;
    }

    /* Box shadow for depth */
    .special-offer .container:hover {
        box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.2);
    }
</style>

    {{-- Top seller --}}
    <section class="best-product py-5">
        <div class="container">
            <h2 class="text-center mb-5">Tasty Picks</h2>
            <div class="row">
                <!-- Product 1 -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100">
                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</span>
                        <img src="{{ asset('images/products-img/kanzler-nugget.jpg') }}" class="card-img-top" alt="Product 1">
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
                        <img src="{{ asset('images/products-img/rm-fiesta-bulgogi.jpg') }}" class="card-img-top" alt="Product 2">
                        <div class="card-body">
                            <h5 class="card-title">Ready Meal Fiesta Beef Bulgogi With Rice</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">Rp 26.999
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
                        <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                        <img src="{{ asset('images/products-img/fish-grilled-salmon.jpg') }}" class="card-img-top" alt="Product 3">
                        <div class="card-body">
                            <h5 class="card-title">Gorton's Classic Grilled Salmon</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">Rp 56.000</p>
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
                        <img src="{{ asset('images/products-img/chicken-fiesta-karage.jpg') }}" class="card-img-top" alt="Product 4">
                        <div class="card-body">
                            <h5 class="card-title">Fiesta Chicken Karage 500gr</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">Rp 51.000</p>
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
                <a href="{{ route('products') }}" class="btn btn-primary">View All Products</a>
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