<link rel="stylesheet" href="{{ asset('assets/css/nav.css') }}">

<header class="header">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    
    <!-- Mobile logo -->
    <div class="d-md-none logo">
      <a href="{{route('index')}}"><img src="/assets/images/logo2.jpg" alt="Logo"></a>
    </div>

    <!-- Mobile toggler -->
    <button class="btn btn-light d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="Toggle navigation">
      <i class="bi bi-list"></i>
    </button>

    <!-- Desktop nav -->
    <ul class="nav d-none d-md-flex align-items-center mb-0 w-100 nav-underline">
      <li class="nav-item"><a href="{{ auth()->check() ? (auth()->user()->role == 'admin' ? route('admin.home') : (auth()->user()->role == 'kitchen' ? route('kitchen.home') : route('profile'))) : route('register')}}"><i class="bi bi-person-circle"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{route('index')}}">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
      <li class="nav-item"><a class="nav-link" href="{{route('menu')}}">Menu</a></li>
      <li class="nav-item logo"><a href="{{route('index')}}"><img src="/assets/images/logo2.jpg" alt="Logo"></a></li>
      <li class="nav-item"><a class="nav-link" href="#">Macro Calculator</a></li>
      <li class="nav-item"><button type="button" class="btn plan-button">GET MY PLAN!</button></li>
      <li class="nav-item"><a href="{{route('cart')}}"><i class="bi bi-cart-fill"></i></a></li>
    </ul>
  </div>

  <!-- Mobile offcanvas -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="mobileMenuLabel">Main Menu</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="{{route('index')}}">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="{{route('menu')}}">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Macro Calculator</a></li>
      </ul>
      <div class="mt-3">
        <button class="btn plan-button w-100">GET MY PLAN!</button>
      </div>
      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ auth()->check() ? (auth()->user()->role == 'admin' ? route('admin.home') : (auth()->user()->role == 'kitchen' ? route('kitchen.home') : route('profile'))) : route('register')}}"><i class="bi bi-person-circle"></i></a>
        <a href="{{route('cart')}}"><i class="bi bi-cart-fill"></i></a>
      </div>
    </div>
  </div>
</header>