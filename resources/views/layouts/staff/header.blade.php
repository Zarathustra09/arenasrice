<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
{{--            <i class="fas fa-utensils"></i>--}}
        </div>
        <div class="sidebar-brand-text mx-3">{{env('APP_NAME')}}<sup></sup></div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ Request::routeIs('pos.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('pos.index') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span></a>
    </li>
</ul>
