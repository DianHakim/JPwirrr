<div id="sidebar" class="active">
    <div class="sidebar-wrapper active collapse show" id="sidebarMenu">

        <!-- TOMBOL COLLAPSE KIRI -->
        <div class="position-absolute top-0 start-0 m-3">
            <button class="btn p-0 border-0 bg-transparent toggle-btn">
                <i class="bi bi-list fs-2"></i>
            </button>
        </div>
        <!-- END TOMBOL COLLAPSE -->

        <div class="sidebar-header text-center position-relative mt-5">
            <div class="logo">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="JP Wear" style="width: 75px; height: 75px; border-radius: 10px;">
                </a>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu list-unstyled px-3">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-grid-fill me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('productcategory.*') ? 'active' : '' }}">
                    <a href="{{ route('productcategory.index') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-tags-fill me-2"></i>
                        <span>Kategori</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-box-seam me-2"></i>
                        <span>Produk</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <a href="{{ route('transactions.index') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-cash-stack me-2"></i>
                        <span>Transaksi</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <a href="{{ route('reports.index') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="p-3 border-top">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-right me-2"></i> <span>Logout</span>
                </button>
            </form>
        </div>

    </div>
</div>

<style>
    /* Sidebar normal */
    #sidebar {
        width: 260px;
        transition: width .3s ease;
        overflow: hidden;
    }

    /* Sidebar mengecil (collapse) */
    #sidebar.collapsed {
        width: 70px !important;
    }

    /* Teks hilang, tetapi icon tidak berubah posisi */
    #sidebar.collapsed .sidebar-menu span,
    #sidebar.collapsed .sidebar-title,
    #sidebar.collapsed .p-3 form button span {
        display: none !important;
    }

    /* JANGAN PUSATKAN MENU — biarkan tetap rata kiri */
    #sidebar .sidebar-link {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Saat collapsed, ikon tetap di kiri */
    #sidebar.collapsed .sidebar-link {
        justify-content: flex-start !important;
        padding-left: 20px !important;
    }

    /* Jangan ubah margin icon */
    #sidebar.collapsed .sidebar-menu i {
        margin-right: 0 !important;
    }

    /* Logo kecil, tapi tetap di tempat sama (tidak ke tengah) */
    #sidebar.collapsed .sidebar-header {
        text-align: left !important;
        padding-left: 20px;
    }

    #sidebar.collapsed .logo img {
        width: 45px;
        height: 45px;
    }

    /* Logout tetap di bawah rata kiri */
    #sidebar.collapsed .p-3 form button {
        justify-content: flex-start !important;
        padding-left: 20px !important;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("sidebar");
        const toggleBtn = document.querySelector(".toggle-btn");

        toggleBtn.addEventListener("click", function() {
            sidebar.classList.toggle("collapsed");
        });
    });
</script>