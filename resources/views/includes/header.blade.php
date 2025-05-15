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

    .profile-container {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-left: auto;
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
    color: red;
}
</style>

<nav class="navbar navbar-expand-lg navbar-scroll shadow-0">
    <div class="container fw-semibold">
        <!-- Logo dan Brand -->
        <img src='/assets/logoGambar.png' alt="" class="logo me-3" />
        <a class="navbar-brand text-header fw-bold" href="{{ route('home') }}">Chillé Mart</a>

        <!-- Toggle button -->
        <button class="navbar-toggler ps-0" type="button" data-mdb-collapse-init data-mdb-target="#navbarExample01"
            aria-controls="navbarExample01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="d-flex justify-content-start align-items-center">
                <i class="fas fa-bars"></i>
            </span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarExample01">
            <!-- Menu kiri -->
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('products') }}">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('about') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link">Logout</button>
                    </form>
                </li>

                    {{-- <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('order.index') ? 'active' : '' }}"
                            href="{{ route('order.show') }}">My Orders</a>
                    </li> --}}
            </ul>

            <ul class="navbar-nav ms-auto d-flex flex-row align-items-center gap-3">

                {{-- Wishlist --}}
                <li class="nav-item">
                    <a class="nav-link custom-wishlist-color" href="{{ route('wishlist') }}">
                        <i class="fas fa-heart fs-5"></i>
                    </a>
                </li>

                 {{-- Cart --}}
               <li class="nav-item position-relative">
                    <a class="nav-link custom-cart-color" href="{{ route('cart') }}">
                        <i class="fas fa-cart-shopping fs-5 position-relative"></i>
                    </a>
                </li>

                {{-- Profile --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile') }}">
                        <img src='/assets/profile.png' alt="Profile" class="profile" />
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
