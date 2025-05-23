<style>
    header {

        background-color: transparent !important;
    }

    .text-header {
        color: #052659;
    }

    .logo {
        width: 2.5%;
    }

    .profile {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    .icon-link {
        font-size: 1.25rem;
        position: relative;
        color: inherit;
    }


    .cart-badge {
        position: absolute;
        top: -6px;
        right: -10px;
        background-color: red;
        color: white;
        font-size: 0.7rem;
        font-weight: bold;
        border-radius: 50%;
        padding: 0.2rem 0.45rem;
        line-height: 1;
    }

    .navbar-toggler {
        border: none;
    }

    .navbar-collapse {
        justify-content: space-between;
    }

    .custom-cart-color {
        color: #052659;
    }

    .custom-wishlist-color {
        color: #052659;
    }

    .dropdown-toggle::after {
        display: none;
    }

    .badge {
        font-size: 0.7rem;
        padding: 4px 7px;
    }

    .navbar-nav>li {
        display: inline-flex;
        align-items: center;
    }

    .profile {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        display: inline-block;
        vertical-align: middle;
    }

    .nav-link {
        display: inline-flex;
        align-items: center;
        color: inherit;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-scroll shadow-0 bg-white">
    <div class="container fw-semibold">

        <img src='{{ asset('assets/logoGambar.png') }}' alt="Logo" class="logo me-3" />
        <a class="navbar-brand text-header fw-bold" href="{{ route('home') }}">Chillé Mart</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarExample01"
            aria-controls="navbarExample01" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarExample01">

            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('products') }}">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('about') }}">About Us</a>
                </li>
                <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('order.index') ? 'active' : '' }}"
                            href="{{ route('orders.index') }}">My Orders</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto d-flex flex-row align-items-center gap-3">

                <li class="nav-item">
                    <a class="nav-link custom-wishlist-color" href="{{ route('wishlist.index') }}">
                        <i class="bi bi-bookmark-heart-fill fs-5"></i>
                    </a>
                </li>

                <li class="nav-item position-relative">
                    @php
                        // hitung jumlah macam item (bukan total quantity)
                        $cartCount = is_array(session('cart')) ? count(session('cart')) : 0;
                    @endphp

                    <a class="nav-link custom-cart-color" href="{{ route('cart.index') }}">
                        <i class="fas fa-cart-shopping fs-5"></i>

                        @if ($cartCount > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </li>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false" onclick="event.preventDefault();">
                        <i class="bi bi-person-fill fs-3"></i>
                        {{-- <img src="{{ asset('assets/profile.png') }}" alt="Profile" class="profile" /> --}}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="fa fa-user me-2"></i> Edit Profile
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out-alt me-2"></i> Sign Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>