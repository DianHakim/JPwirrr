<!-- Sidebar -->
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <!-- header: logo + (no text) -->
        <div class="sidebar-header d-flex justify-content-between align-items-center px-3 py-3">
            <a href="{{ route('dashboard') }}" class="sidebar-brand d-flex align-items-center">
                {{-- Keep ratio: set height, width:auto --}}
                <img src="{{ asset('img/logo.png') }}" alt="JP Wear" style="height:48px; width:auto; display:block;">
            </a>

            <!-- collapse and theme buttons are in navbar per request; still keep small collapse for desktop -->
            <button id="sidebarToggleBtn" onclick="toggleSidebar()" class="btn btn-sm border-0 d-none d-xl-inline-flex" title="Collapse sidebar">
                <i class="bi bi-chevron-left"></i>
            </button>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="sidebar-link d-flex align-items-center" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 fs-5"></i>
                        <span class="ms-2 sidebar-text">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center" href="#">
                        <i class="bi bi-cart-check fs-5"></i>
                        <span class="ms-2 sidebar-text">Transaksi</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center" href="#">
                        <i class="bi bi-list-check fs-5"></i>
                        <span class="ms-2 sidebar-text">Kategori</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center" href="#">
                        <i class="bi bi-bag fs-5"></i>
                        <span class="ms-2 sidebar-text">Produk</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center" href="#">
                        <i class="bi bi-graph-up fs-5"></i>
                        <span class="ms-2 sidebar-text">Laporan</span>
                    </a>
                </li>

                <li class="sidebar-title mt-3">Akun</li>

                <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center" href="#">
                        <i class="bi bi-person fs-5"></i>
                        <span class="ms-2 sidebar-text">Akun</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link d-flex align-items-center text-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                        <span class="ms-2 sidebar-text">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </li>
            </ul>
        </div>
    </div>
</div>
