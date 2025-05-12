<nav class="navbar navbar-expand-lg navbar-scroll shadow-0" style="background-color: #4FC3F7;">
    <div class="container">
      <a class="navbar-brand" href="#">our products</a>
      <button class="navbar-toggler ps-0" type="button" data-mdb-collapse-init data-mdb-target="#navbarExample01"
        aria-controls="navbarExample01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="d-flex justify-content-start align-items-center">
          <i class="fas fa-bars"></i>
        </span>
      </button>
      <div class="collapse navbar-collapse" id="navbarExample01">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item active">
            <a class="nav-link px-3 {{ request()->routeIs('home.show') ? 'active' : '' }}" href="">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 {{ request()->routeIs('store') ? 'active' : '' }}" href="#!">Shop Here</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 {{ request()->routeIs('about') ? 'active' : '' }}" href="#!">About Us</a>
          </li>
        </ul>
  
        <ul class="navbar-nav flex-row">
          <li class="nav-item">
            <a class="nav-link pe-3" href="#!">
              <i class="fab fa-youtube"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3" href="#!">
              <i class="fab fa-facebook-f"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link ps-3" href="#!">
              <i class="fab fa-instagram"></i>
            </a>
          </li>
          <li class="nav-item">

            @auth
              <li class="nav-item">
                <a class="nav-link ps-3" href="{{ route('view_cart') }}">
                  <i class="fas fa-cart-shopping"></i>
                </a>
              </li>
            @endauth
            @auth
              <li class="nav-item">
                <a class="nav-link px-3 {{ request()->routeIs('order.index') ? 'active' : '' }}" href="{{ route('order.index') }}">My Orders</a>
              </li>
            @endauth
            @auth
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link">Logout</button>
              </form>
            @endauth

            
          </li>
        </ul>
      </div>
    </div>
  </nav>