@extends('layouts.app')

@section('content')
    <style>
        .hero-section {
            background-color: #b5e1f0;
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

        .category {
            background-color: #f8f9fa;
        }

        .category-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .category-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(13, 110, 253, 0.8);
            color: white;
            padding: 15px;
        }

        .best-product {
            background-color: white;
        }

    </style>

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

    <section class="category py-5">
        <div class="container">
            <h2 class="text-center mb-5">Shop by Category</h2>
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="category-item">
                        <img src="https://via.placeholder.com/300x200" alt="Ready Meals" class="img-fluid">
                        <div class="category-overlay">
                            <h5 class="mb-0">Ready Meals</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="category-item">
                        <img src="https://via.placeholder.com/300x200" alt="Frozen Vegetables" class="img-fluid">
                        <div class="category-overlay">
                            <h5 class="mb-0">Frozen Vegetables</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="category-item">
                        <img src="https://via.placeholder.com/300x200" alt="Ice Cream & Desserts" class="img-fluid">
                        <div class="category-overlay">
                            <h5 class="mb-0">Ice Cream & Desserts</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="category-item">
                        <img src="https://via.placeholder.com/300x200" alt="Frozen Meat & Fish" class="img-fluid">
                        <div class="category-overlay">
                            <h5 class="mb-0">Frozen Meat & Fish</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Best Selling Products Section -->
    <section class="best-product py-5">
        <div class="container">
            <h2 class="text-center mb-5">Tasty Picks</h2>
            <div class="row">
                <!-- Product 1 -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card product-card h-100">
                        {{-- <span class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</span> --}}
                        <img src="" class="card-img-top" alt="Product 1">
                        <div class="card-body">
                            <h5 class="card-title">Product 1</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">Rp ---
                                    {{-- <small class="text-decoration-line-through text-muted">$99.99</small>--}}
                                </p>
                                {{-- <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div> --}}
                            </div>
                            <button class="btn btn-outline-dark w-100">Add to Cart</button>
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
                                    {{-- <small class="text-decoration-line-through text-muted">$99.99</small>--}}
                                </p>
                                {{-- <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div> --}}
                            </div>
                            <button class="btn btn-outline-dark w-100">Add to Cart</button>
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
                                {{-- <div> --}}
                                    {{-- <i class="bi bi-star-fill text-warning"></i> --}}
                                    {{-- <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i> --}}
                                    {{-- <i class="bi bi-star-fill text-warning"></i> --}}
                                    {{-- <i class="bi bi-star-fill text-warning"></i> --}}
                                {{-- </div> --}}
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
                            <h5 class="card-title">Product 4</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-text mb-0">Rp ---</p>
                                {{-- <div>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                </div> --}}
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

    <!-- Why Choose Us Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Why Chillé Mart?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 h-100 bg-transparent">
                        <div class="card-body text-center">
                            <i class="bi bi-snow fs-1 mb-3 feature-icon"></i>
                            <h4>Flash-Frozen at Peak Freshness</h4>
                            <p>Our foods are frozen at the peak of freshness to lock in nutrients and flavor that last longer than fresh alternatives.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 h-100 bg-transparent">
                        <div class="card-body text-center">
                            <i class="bi bi-clock-history fs-1 mb-3 feature-icon"></i>
                            <h4>Convenience & Time-Saving</h4>
                            <p>Ready-to-cook meals and ingredients that save you time in the kitchen without sacrificing quality or taste.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 h-100 bg-transparent">
                        <div class="card-body text-center">
                            <i class="bi bi-trash fs-1 mb-3 feature-icon"></i>
                            <h4>Reduce Food Waste</h4>
                            <p>Use only what you need and keep the rest frozen, helping to reduce household food waste and save money.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
