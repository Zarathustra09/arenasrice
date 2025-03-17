<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #9B734F;">

    <a class="sidebar-brand d-flex align-items-center justify-content-center"
       style="background-color: white; border: 1px solid #9B734F;"
       href="{{ route('home') }}">
        <div class="sidebar-brand-icon">
        </div>
        <div class="sidebar-brand-text mx-3">
            <img src="{{ asset('logoedited.png') }}" alt="Logo" height="250px" width="auto" class="mt-3">
            <sup></sup>
        </div>
    </a>


    <li class="nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">
    <li class="nav-item {{ Request::routeIs('pos.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('pos.index')}}">
            <i class="fas fa-fw fa-money-bill"></i>
            <span>POS</span></a>
    </li>

    <hr class="sidebar-divider">

    <li class="nav-item {{ Request::routeIs('admin.order.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('admin.order.index')}}">
            <i class="fas fa-fw fa-receipt"></i>
            <span>Orders Management</span></a>
    </li>

    <hr class="sidebar-divider">



    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
           aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-warehouse"></i>
            <span>Inventory Management</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="heading" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
{{--                <h6 class="collapse-header">Inventory Management</h6>--}}
                <a class="collapse-item {{ Request::routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">Products</a>
                <a class="collapse-item {{ Request::routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">Categories</a>
                <a class="collapse-item {{ Request::routeIs('ingredients.index') ? 'active' : '' }}" href="{{ route('ingredients.index') }}">Ingredients</a>
                <a class="collapse-item {{ Request::routeIs('product-containers.index') ? 'active' : '' }}" href="{{ route('product-containers.index') }}">Containers</a>
            </div>
        </div>
    </li>




    <hr class="sidebar-divider">


    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
           aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-money-check-alt"></i>
            <span>Sales Report</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::routeIs('sales-report.walk-in') ? 'active' : '' }}" href="{{ route('sales-report.walk-in') }}">Walk-in POS</a>
                <a class="collapse-item {{ Request::routeIs('sales-report.online-transaction') ? 'active' : '' }}" href="{{ route('sales-report.online-transaction') }}">Online Transaction</a>

            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <li class="nav-item {{ Request::routeIs('admin.accounts.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('admin.accounts.index')}}">
            <i class="fas fa-fw fa-user-plus"></i>
            <span>Account Management</span></a>
    </li>


    <hr class="sidebar-divider d-none d-md-block">
    <li class="nav-item {{ Request::routeIs('admin.logs.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.logs.index') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Logs</span></a>
    </li>
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
