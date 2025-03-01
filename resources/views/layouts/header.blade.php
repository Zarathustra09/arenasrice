<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #9B734F;">

<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon">
{{--            <i class="fas fa-utensils"></i>--}}

        </div>
        <div class="sidebar-brand-text mx-3"><img src="{{ asset('logoedited.png') }}" alt="Logo" height="200px" width="auto" class="mt-3" ><sup></sup></div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Product Management
    </div>

    <li class="nav-item {{ Request::routeIs('products.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('products.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span>Products</span></a>
    </li>



    <li class="nav-item {{ Request::routeIs('categories.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-list"></i>
            <span>Product Category</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Supply Management
    </div>
    <li class="nav-item {{ Request::routeIs('ingredients.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('ingredients.index') }}">
            <i class="fas fa-fw fa-carrot"></i>
            <span>Ingredients</span></a>
    </li>

    <li class="nav-item {{ Request::routeIs('product-containers.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('product-containers.index') }}">
            <i class="fas fa-fw fa-archive"></i>
            <span>Materials</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Logs
    </div>

    <li class="nav-item {{ Request::routeIs('admin.logs.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.logs.index') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Logs</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Sales
    </div>

    <li class="nav-item {{ Request::routeIs('admin.order.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('admin.order.index')}}">
            <i class="fas fa-fw fa-receipt"></i>
            <span>Orders</span></a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
