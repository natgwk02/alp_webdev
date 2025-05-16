<!-- SIDEBAR CSS -->
<style>
    .sidebar {
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1000;
        background: linear-gradient(180deg, #052659, #0A2647);
        padding-top: 20px;
        box-shadow: 3px 0 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s;
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;
    }

    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.75);
        padding: 12px 24px;
        margin: 6px 0;
        font-weight: 500;
        display: flex;
        align-items: center;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
        font-weight: 600;
        box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.05);
    }

    .sidebar .nav-link i {
        margin-right: 12px;
        font-size: 1.2rem;
    }

    .sidebar .logo img {
        width: 40px;
        margin-right: 10px;
    }

    .sidebar .logo span {
        font-size: 1.3rem;
        font-weight: bold;
        color: #ffffff;
        letter-spacing: 0.5px;
    }

    .sidebar .sign-out {
        padding-left: 24px;
    }

    .sidebar .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
        font-weight: 500;
        transition: 0.2s ease;
        border-radius: 8px;
    }

    .sidebar .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }

    .logout-btn {
    font-size: 0.85rem;
    padding: 6px 10px;
    border-radius: 6px;
    transition: 0.2s ease;
    color: #dc3545;
    border: 1px solid #dc3545;
    background-color: transparent;
}

    .logout-btn:hover {
    background-color: #dc3545;
    color: #fff;
}

</style>

<!-- SIDEBAR HTML -->
<div class="sidebar">
    <div class="d-flex justify-content-start align-items-center mb-4 px-3 logo">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
            <img src="/assets/logoGambar.png" alt="Logo">
            <span>Chillé Mart</span>
        </a>
    </div>

    <ul class="nav flex-column px-2">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i> Orders
            </a>
        </li>
    </ul>

        <div class="position-absolute bottom-0 start-0 w-100 mb-3 px-3">
        <form action="{{ route('logout') }}" method="POST" class="w-100">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2 w-100 justify-content-center logout-btn">
                <i class="bi bi-box-arrow-left"></i>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</div>
