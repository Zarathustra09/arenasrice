<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-utensils"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Cakify<sup></sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Products -->
    <li class="nav-item {{ Request::routeIs('products.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('products.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span>Products</span></a>
    </li>

    <!-- Nav Item - Categories -->
    <li class="nav-item {{ Request::routeIs('categories.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>Categories</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Sales
    </div>

    <!-- Nav Item - Orders -->
    <li class="nav-item {{ Request::routeIs('admin.order.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('admin.order.index')}}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Orders</span></a>
    </li>

    <!-- Nav Item - Payments -->
{{--    <li class="nav-item {{ Request::routeIs('payments.index') ? 'active' : '' }}">--}}
{{--        <a class="nav-link" href="">--}}
{{--            <i class="fas fa-fw fa-credit-card"></i>--}}
{{--            <span>Payments</span></a>--}}
{{--    </li>--}}

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
