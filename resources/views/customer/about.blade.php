@extends('layouts.app')

@section('content')
<!-- About Hero -->
<section class="py-5" style="background-color: #C1E8FF;">
    <div class="container py-4">
        <h1 class="display-4 fw-bold text-center" style="color: #052659;">About Chille Mart</h1>
        <h4 class="display-10 mt-3 text-center" style="color: #052659;">"Freshness preserved, convenience delivered, happiness guaranteed."</h4>
    </div>
</section>

<!-- Our Story -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="/images/category-img/ready-meals.jpg" alt="Our Story" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h2 class="mb-4" style="color: #052659;">Our Story</h2>
                <p>Founded in 2025, Chille Mart began with a simple mission: to provide high-quality frozen foods with convenience. What started as a small local business has grown into a trusted name in frozen food e-commerce.</p>
                <p>We carefully select our products to ensure they meet the highest standards of quality and freshness.</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #052659;">Our Values</h2>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <i class="fas fa-star fa-3x mb-3" style="color: #052659;"></i>
                        <h5>Quality</h5>
                        <p>We source only the best frozen products with strict quality control.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <i class="fas fa-truck fa-3x mb-3" style="color: #052659;"></i>
                        <h5>Convenience</h5>
                        <p>Fast delivery and easy ordering process for your convenience.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <i class="fas fa-heart fa-3x mb-3" style="color: #052659;"></i>
                        <h5>Customer Care</h5>
                        <p>24/7 support to ensure your complete satisfaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #052659;">Meet Our Team</h2>
        <div class="row">
            <div class="col-md-4 mb-4 text-center">
                <img src="/assets/team1.jpg" alt="Team Member" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h5>Amanda Michelle Darwis</h5>                
            </div>
            <div class="col-md-4 mb-4 text-center">
                <img src="/assets/team1.jpg" alt="Team Member" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h5>Anne Tantan</h5>                
            </div>
            <div class="col-md-4 mb-4 text-center">
                <img src="/assets/team1.jpg" alt="Team Member" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h5>Jessica Laurentia Tedja</h5>                
            </div>
             <div class="col-md-6 mb-4 text-center">
                <img src="/assets/team1.jpg" alt="Team Member" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h5>Natalie Grace Widjaja Kuswanto</h5>                
            </div>
            <div class="col-md-6 mb-4 text-center">
                <img src="/assets/team1.jpg" alt="Team Member" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h5>Sharon Tan</h5>                
            </div>
        </div>
    </div>
</section>
@endsection