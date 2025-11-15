<div id="sidebar" class="active">
    <div class="sidebar-wrapper active collapse show" id="sidebarMenu">
        <div class="sidebar-header text-center position-relative">
            <div class="logo">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="JP Wear" style="width: 75px; height: 75px; border-radius: 10px;">
                </a>
            </div>
            <div class="toggler position-absolute end-0 top-50 translate-middle-y me-3">
                <a href="#sidebarMenu" data-bs-toggle="collapse" class="d-xl-none d-block text-dark">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu list-unstyled px-3">
                <li class="sidebar-title">Menu</li>

                {{-- Dashboard --}}
                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-grid-fill me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Kategori --}}
                <li class="sidebar-item {{ request()->routeIs('productcategory.*') ? 'active' : '' }}">
                    <a href="{{ route('productcategory.index') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-tags-fill me-2"></i>
                        <span>Kategori</span>
                    </a>
                </li>

                {{-- Produk --}}
                <li class="sidebar-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-box-seam me-2"></i>
                        <span>Produk</span>
                    </a>
                </li>
                {{-- Transaksi --}}
                <li class="sidebar-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <a href="{{ route('transactions.index') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-cash-stack me-2"></i>
                        <span>Transaksi</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tombol Logout -->
        <div class="p-3 border-top">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>

        <button class="sidebar-toggler btn x">
            <i data-feather="x"></i>
        </button>
    </div>
</div>