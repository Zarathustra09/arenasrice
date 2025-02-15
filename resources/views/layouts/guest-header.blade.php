<!-- Spinner Start -->
<div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>
<!-- Spinner End -->

<!-- Navbar start -->
<div class="container-fluid fixed-top">
    <div class="container topbar bg-primary d-none d-lg-block">
        <div class="d-flex justify-content-between">
            <div class="top-info ps-2">
                <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">347F+Q44, Tanauan, Batangas</a></small>
                <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">
                        terrenalkimlester@gmail.com</a></small>
            </div>
            <div class="top-link pe-2">
                <a href="{{route('contact.index')}}" class="text-white"><small class="text-white mx-2">Contact Us</small></a>
{{--                <a href="#" class="text-white"><small class="text-white mx-2">Privacy Policy</small>/</a>--}}
{{--                <a href="#" class="text-white"><small class="text-white mx-2">Terms of Use</small>/</a>--}}
{{--                <a href="#" class="text-white"><small class="text-white ms-2">Sales and Refunds</small></a>--}}
            </div>
        </div>
    </div>
    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="index.html" class="navbar-brand"><h1 class="text-primary display-6">{{env('APP_NAME')}}</h1></a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="{{ url('/') }}" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
                    <a href="{{ url('guest/shop/index') }}" class="nav-item nav-link {{ Request::is('guest/shop/index') ? 'active' : '' }}">Shop</a>
                    <a href="{{ url('guest/contact/index') }}" class="nav-item nav-link {{ Request::is('guest/contact/index') ? 'active' : '' }}">Contact</a>
                </div>


                <div class="d-flex m-3 me-0">
                    <a href="{{route('cart.index')}}" class="position-relative me-4 my-auto">
                        <i class="fa fa-shopping-bag fa-2x"></i>
                        @php
                            use App\Http\Controllers\CartController;
                            $cartCount = CartController::getCartCount();
                        @endphp

                        <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">{{ $cartCount }}</span>
                    </a>
                    @if(Auth::check())
                        <div class="dropdown">
                            <a href="#" class="my-auto dropdown-toggle" data-bs-toggle="dropdown">
                                 <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{route('order.list')}}">
                                        Orders
                                    </a>
                                </li>
                                <li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                </li>

                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="my-auto">
                            <span>Login</span>
                        </a>
                    @endif
                </div>
            </div>
        </nav>
    </div>
</div>
