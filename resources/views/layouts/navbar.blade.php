<nav class="navbar navbar-expand navbar-light" id="main-navbar">
    <div class="container-fluid">
        <!-- left: mobile sidebar toggle -->
        <button class="btn btn-sm d-xl-none me-2" onclick="toggleSidebar()" title="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>

        <div class="ms-auto d-flex align-items-center gap-2">
            <!-- Theme toggle (sun/moon) -->
            <button id="themeToggleBtn" class="btn btn-sm btn-outline-secondary" title="Toggle theme" onclick="toggleTheme()">
                <i class="bi bi-brightness-high-fill light-icon"></i>
                <i class="bi bi-moon-fill dark-icon d-none"></i>
            </button>

            <!-- user dropdown -->
            <div class="dropdown">
                <a class="dropdown-toggle d-flex align-items-center text-white-50" data-bs-toggle="dropdown" href="#">
                    <span class="me-2 fw-semibold text-white">{{ Auth::user()->name ?? 'User' }}</span>
                    <i class="bi bi-person-circle fs-4 text-white-50"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="#">Profil</a></li>
                    <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#"
                           onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                            Logout
                        </a>
                        <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
