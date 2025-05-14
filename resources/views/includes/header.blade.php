<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
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
        width: 8%;
    }
     .profile-container {
            margin-left: auto;
        }

    .cart-badge {
    position: absolute;
    top: -8px;
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
        text-align: right;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-scroll shadow-0">
    <div class="container fw-semibold">
        <img src='/assets/logoGambar.png' alt="" class="logo me-3" />
        <a class="navbar-brand text-header fw-bold" href="{{ route('home') }}">Chillé Mart</a>
        <button class="navbar-toggler ps-0" type="button" data-mdb-collapse-init data-mdb-target="#navbarExample01"
            aria-controls="navbarExample01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="d-flex justify-content-start align-items-center">
                <i class="fas fa-bars"></i>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarExample01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('products') }}">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('about') }}">About Us</a>
                </li>

                @auth
                    <!-- Cart Icon with Badge -->
                    <li class="nav-item">
                        <a class="nav-link ps-3" href="{{ route('cart') }}">
                            <i class="fas fa-cart-shopping position-relative">
                               <span class="cart-badge">{{ $cart_count }}</span>
                            </i>
                        </a>
                    </li>

                    <!-- My Orders -->
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('order.index') ? 'active' : '' }}"
                            href="{{ route('order.show') }}">My Orders</a>
                    </li>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link">Logout</button>
                    </form>
                @endauth

                <!-- For guests -->
                {{-- @guest
                    <li class="nav-item">
                        <a class="nav-link pe-3" href="{{ route('login.show') }}">Login</a>
                    </li>
                @endguest --}}
                 {{-- <ul class="navbar-nav ms-auto flex-row">
                    <li class="nav-item">
                        <a class="nav-link ps-3" href="">
                            <img src='/assets/profile.png' alt="" class="profile p-0" />
                        </a>
                    </li>
                 </ul> --}}
                <div class="profile-container">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <img src='/assets/profile.png' alt="Profile" class="profile" />
                            </a>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>
    </div>
</nav>
