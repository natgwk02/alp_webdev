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
        padding: 0.5rem 0.75rem;
    }


    .nav-item.position-relative {
        display: flex;
        align-items: center;
    }

    .nav-link i {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #wishlistBadge,
    #cartBadge {
        position: absolute !important;
        top: -5px !important;
        right: -2px !important;
        transform: none !important;
        left: auto !important;
        width: 18px !important;
        height: 18px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 0.65rem !important;
        line-height: 1 !important;
        background-color: #dc3545 !important;
        color: white !important;
        border-radius: 50% !important;
        font-weight: bold !important;
        border: 2px solid white !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15) !important;
        min-width: 18px !important;
    }


    .badge-spacing-alt #wishlistBadge,
    .badge-spacing-alt #cartBadge {
        top: -8px !important;
        right: -8px !important;
        width: 20px !important;
        height: 20px !important;
        min-width: 20px !important;
    }

    @media (max-width: 768px) {

        #wishlistBadge,
        #cartBadge {
            top: -3px !important;
            right: 0px !important;
        }
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

                <li class="nav-item position-relative">
                    <a id="wishlistLink" class="nav-link custom-wishlist-color" href="{{ route('wishlist') }}">
                        <i class="bi bi-bookmark-heart-fill fs-5"></i>
                        @if ($wishlistCount > 0)
                            <span id="wishlistBadge">{{ $wishlistCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item position-relative">
                    <a id="cartLink" class="nav-link custom-cart-color" href="{{ route('cart.index') }}">
                        <i class="fas fa-cart-shopping fs-5"></i>
                        @if ($cartCount > 0)
                            <span id="cartBadge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false" onclick="event.preventDefault();">
                            <i class="bi bi-person-fill fs-3"></i>
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
                            @can('viewAdminDashboard')
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-gear-fill me-2"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endcan

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
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-person-fill fs-3"></i>
                        </a>
                    </li>
                @endguest

            </ul>
        </div>
    </div>
</nav>
