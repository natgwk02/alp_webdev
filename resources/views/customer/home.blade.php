@extends('layouts.app')

@section('content')
    <style>
        .hero-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }

        .product-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

    </style>

    {{--  --}}
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-2">Welcome to Chillé Mart</h1>
                    <h3 class="mb-4">Keep it cool, keep it chillé!</h3>
                    <p class="lead mb-4">Deliciously frozen, always ready. Discover your next favorite meal today!</p>
                    <a href="" class="btn btn-dark btn-lg">Let's Chill</a>
                </div>
                <div class="col-lg-6">
                    <img src="https://via.placeholder.com/600x400" alt="Comming" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Featured Products</h2>
            <div class="row">
                <!-- Product 1 -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100">
                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</span>
                        <img src="https://via.placeholder.com/300" class="card-img-top" alt="Product 1">
                        <div class="card-body">
                            <h5 class="card-title">Wireless Headphones</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">$79.99 <small
                                        class="text-decoration-line-through text-muted">$99.99</small></p>
                                <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div>
                            </div>
                            <button class="btn btn-outline-dark w-100">Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100">
                        <img src="https://via.placeholder.com/300" class="card-img-top" alt="Product 2">
                        <div class="card-body">
                            <h5 class="card-title">Smart Watch</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">$149.99</p>
                                <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                </div>
                            </div>
                            <button class="btn btn-outline-dark w-100">Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100">
                        <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                        <img src="https://via.placeholder.com/300" class="card-img-top" alt="Product 3">
                        <div class="card-body">
                            <h5 class="card-title">Portable Speaker</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">$59.99</p>
                                <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </div>
                            </div>
                            <button class="btn btn-outline-dark w-100">Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100">
                        <img src="https://via.placeholder.com/300" class="card-img-top" alt="Product 4">
                        <div class="card-body">
                            <h5 class="card-title">Laptop Backpack</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">$45.99</p>
                                <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                </div>
                            </div>
                            <button class="btn btn-outline-dark w-100">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="#" class="btn btn-dark">View All Products</a>
            </div>
        </div>
    </section>

@endsection
