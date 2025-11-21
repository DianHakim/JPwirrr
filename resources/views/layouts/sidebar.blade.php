<div id="sidebar" class="active">
    <div class="sidebar-wrapper active collapse show" id="sidebarMenu">

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

                <li class="sidebar-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <a href="{{ route('profile') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-person-circle me-2"></i>
                        <span>Akun</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tombol Logout -->
        <div class="p-3 border-top">
            <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                @csrf
                <button type="button" id="openLogoutModal" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-right me-2"></i> <span>Logout</span>
                </button>
            </form>
        </div>

    </div>
</div>

<!-- LOGOUT MODAL (DI LUAR SIDEBAR WAJIB!) -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0">Apakah kamu yakin ingin logout?</p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-danger" id="confirmLogoutBtn">Logout</button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const openModalBtn = document.getElementById("openLogoutModal");
    const confirmBtn = document.getElementById("confirmLogoutBtn");
    const logoutForm = document.getElementById("logoutForm");

    // Buka modal
    openModalBtn.addEventListener("click", function () {
        const modal = new bootstrap.Modal(document.getElementById('logoutModal'));
        modal.show();
    });

    // Konfirmasi logout
    confirmBtn.addEventListener("click", function () {
        logoutForm.submit();
    });

});
</script>
