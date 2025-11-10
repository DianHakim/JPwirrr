<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('img/logo.png') }}" alt="JP Wear">
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                {{-- Dashboard --}}
                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Kategori --}}
                <li class="sidebar-item {{ request()->routeIs('productcategory.*') ? 'active' : '' }}">
                    <a href="{{ route('productcategory.index') }}" class="sidebar-link">
                        <i class="bi bi-tags-fill"></i>
                        <span>Kategori</span>
                    </a>
                </li>


            </ul>
        </div>

        <button class="sidebar-toggler btn x">
            <i data-feather="x"></i>
        </button>
    </div>
</div>