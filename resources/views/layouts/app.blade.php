<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title','Dashboard') - JP Wear</title>

    {{-- Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Core CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    {{-- Custom (collapse + logo + hover glow) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    {{-- Dark theme (overrides) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}">

    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon" />
</head>

<body>
    <div id="app" class="">
        @include('layouts.sidebar')

        <div id="main" class="layout-navbar">
            @include('layouts.navbar')

            <div class="page-heading">
                <h3>@yield('page-title')</h3>
            </div>

            <div class="page-content">
                @yield('content')
            </div>

            <footer class="mt-4">
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2025 &copy; JP Wear</p>
                    </div>
                    <div class="float-end">
                        <p>Built with dian</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        (function() {
            const html = document.documentElement;
            const sidebar = document.getElementById('sidebar');
            const themeBtn = document.getElementById('themeToggleBtn');
            const sidebarToggle = document.getElementById('sidebarToggleBtn');
            if (window.innerWidth < 1200) {
                document.getElementById('sidebar').classList.toggle('active');
            }

            // Apply saved theme
            if (localStorage.getItem('theme') === 'dark') {
                html.classList.add('theme-dark');
            }

            // Apply saved sidebar collapsed state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.body.classList.add('sidebar-collapsed');
            }

            // Theme toggle
            window.toggleTheme = function() {
                const isDark = html.classList.toggle('theme-dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');

                // switch icons if exist
                document.querySelectorAll('.light-icon').forEach(e => e.classList.toggle('d-none', isDark));
                document.querySelectorAll('.dark-icon').forEach(e => e.classList.toggle('d-none', !isDark));
            };

            // Sidebar collapse toggle
            window.toggleSidebar = function() {
                const collapsed = document.body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');
            };

            // wire navbar buttons (if present)
            if (themeBtn) themeBtn.addEventListener('click', toggleTheme);
            if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);

            // set icons initial state
            document.addEventListener('DOMContentLoaded', () => {
                const isDark = html.classList.contains('theme-dark');
                document.querySelectorAll('.light-icon').forEach(e => e.classList.toggle('d-none', isDark));
                document.querySelectorAll('.dark-icon').forEach(e => e.classList.toggle('d-none', !isDark));
            });
        })();
    </script>

</body>

</html>