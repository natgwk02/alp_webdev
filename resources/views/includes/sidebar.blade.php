<style>
    .sidebar {
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1000;
        background-color: #052659;
        padding-top: 20px;
        transition: all 0.3s;
    }

    .sidebar .nav-link {
        color: rgba(209, 206, 206, 0.75);
        padding: 10px 20px;
        margin-bottom: 5px;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar .nav-link i {
        margin-right: 10px;
    }

</style>

<div class="sidebar">
    <div class="d-flex justify-content-center align-items-center mb-4 px-3">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
            <span class="fs-4 fw-bold text-white d-flex align-items-center">
                <img src="/assets/logoGambar.png" class="w-25" />
                <span>Chillé Mart</span>
            </span>
        </a>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Products</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i>
                <span>Orders</span>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a href="customers.html" class="nav-link">
                <i class="bi bi-people"></i>
                <span>Customers</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="categories.html" class="nav-link">
                <i class="bi bi-tags"></i>
                <span>Categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="promotions.html" class="nav-link">
                <i class="bi bi-megaphone"></i>
                <span>Promotions</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="reports.html" class="nav-link">
                <i class="bi bi-graph-up"></i>
                <span>Reports</span>
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a href="settings.html" class="nav-link">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </li> --}}
    </ul>
    <div class="position-absolute bottom-0 start-0 w-100 mb-3 ps-4">
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-outline-danger px-1 m-0 text-start text-decoration-none">
            <i class="bi bi-box-arrow-left"></i>
            <span>Sign Out</span>
        </button>
    </form>
</div>
</div>
