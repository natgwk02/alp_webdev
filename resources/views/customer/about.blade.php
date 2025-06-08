@extends('layouts.app')
<style>
    .text-navy {
        color: #052659 !important;
    }

    .value-box {
        transition: all 0.3s ease;
    }

    img:hover {
        transform: scale(1.02);
        transition: transform 0.4s ease;
    }
</style>
@section('content')
    <section class="py-5" style="background-color: #C1E8FF;">
        <div class="container py-4">
            <h1 class="display-4 fw-bold text-center" style="color: #052659;">About Chillé Mart</h1>
            <h4 class="display-10 mt-3 text-center" style="color: #052659;">"Freshness preserved, convenience delivered,
                happiness guaranteed."</h4>
        </div>
    </section>

    <section class="py-5" style="background: linear-gradient(to bottom right, #e6f4ff, #ffffff);">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-md-6">
                    <img src="/images/category-img/ready-meals.jpg" alt="Chillé Mart Story"
                        class="img-fluid rounded-4 shadow-sm">
                </div>

                <div class="col-md-6">
                    <h2 class="mb-3" style="color: #052659;">The Story Behind Chillé Mart</h2>
                    <p class="text-secondary fs-6 mb-3">
                        Since <strong>2025</strong>, Chillé Mart has been on a mission to deliver
                        <strong>convenient</strong>, <strong>high-quality</strong> frozen meals that fit modern lifestyles.
                    </p>
                    <p class="text-secondary fs-6 mb-3">
                        What began as a small local business has grown into a trusted platform for premium frozen products —
                        from quick ready-to-eat meals to cooking essentials for your kitchen.
                    </p>
                    <p class="text-secondary fs-6 mb-4">
                        We believe frozen food should be more than just practical — it should also be <span
                            class="fw-semibold text-dark">fresh, flavorful, and reliable</span> every time.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="text-navy mb-5">Our Values</h2>

            <div class="row justify-content-center g-4 mb-4">
                <div class="col-md-6 col-lg-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm h-100 value-box">
                        <i class="bi bi-star-fill fs-1 text-navy mb-3 d-block"></i>
                        <h5 class="fw-bold">Quality</h5>
                        <p class="small text-muted mb-0">Only the best frozen products with strict quality control.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm h-100 value-box">
                        <i class="bi bi-truck-front-fill fs-1 text-navy mb-3 d-block"></i>
                        <h5 class="fw-bold">Convenience</h5>
                        <p class="small text-muted mb-0">Fast delivery and easy ordering process for your convenience.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm h-100 value-box">
                        <i class="bi bi-heart-fill fs-1 text-navy mb-3 d-block"></i>
                        <h5 class="fw-bold">Customer Care</h5>
                        <p class="small text-muted mb-0">24/7 support to ensure your complete satisfaction.</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm h-100 value-box">
                        <i class="bi bi-shield-shaded fs-1 text-navy mb-3 d-block"></i>
                        <h5 class="fw-bold">Safety</h5>
                        <p class="small text-muted mb-0">All items handled with high hygiene standards and care.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm h-100 value-box">
                        <i class="bi bi-globe-americas fs-1 text-navy mb-3 d-block"></i>
                        <h5 class="fw-bold">Sustainability</h5>
                        <p class="small text-muted mb-0">Committed to eco-friendly sourcing and packaging.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="py-5" style="background: linear-gradient(to bottom, #f0f8ff, #ffffff);">
        <div class="container">
            <h2 class="text-center mb-5" style="color: #052659; font-size: 2.5rem;">Meet Our Team</h2>

            <div class="row justify-content-center mb-4 gy-4">
                <div class="col-12 col-sm-6 col-md-4 text-center">
                    <div class="team-member">
                        <img src="/images/aboutus/ameena.jpg" alt="Amanda" class="rounded-circle team-img mb-3">
                        <h6 class="fw-semibold">Amanda Michelle Darwis</h6>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 text-center">
                    <div class="team-member">
                        <img src="/images/aboutus/anne.jpg" alt="Anne" class="rounded-circle team-img mb-3">
                        <h6 class="fw-semibold">Anne Tantan</h6>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 text-center">
                    <div class="team-member">
                        <img src="/images/aboutus/cipung.jpg" alt="Jessica" class="rounded-circle team-img mb-3">
                        <h6 class="fw-semibold">Jessica Laurentia Tedja</h6>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center gy-4">
                <div class="col-12 col-sm-6 col-md-4 text-center">
                    <div class="team-member">
                        <img src="/images/aboutus/Abe Cekut.jpg" alt="Natalie" class="rounded-circle team-img mb-3">
                        <h6 class="fw-semibold">Natalie Grace Widjaja Kuswanto</h6>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 text-center">
                    <div class="team-member">
                        <img src="/images/aboutus/el.jpg" alt="Sharon" class="rounded-circle team-img mb-3">
                        <h6 class="fw-semibold">Sharon Tan</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
    .team-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .team-img:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    }

    .team-member h6 {
        font-size: 1rem;
        margin-top: 10px;
        color: #052659;
    }

    @media (max-width: 576px) {
        .team-img {
            width: 120px;
            height: 120px;
        }
    }
</style>
