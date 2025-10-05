<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sushico Back Office</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icons/logo-sushico.svg') }}">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Inter font (fallbacks to system stack) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap"
        rel="stylesheet">

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('css/backend.css') }}">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="{{ asset('css/swal-backend.css') }}">
    @yield('css_before')
</head>

<body>

    {{-- Navbar (fixed-top) --}}
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid gap-2">
            {{-- Mobile: sidebar toggle --}}
            <button class="btn btn-light d-xl-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>

            <a class="navbar-brand" href="/">🍣 Sushico Admin</a>

            <div class="ms-auto d-flex align-items-center gap-2">
                <span class="text-muted d-none d-sm-inline">Hi, <b>{{ session('full_name') }}</b></span>
                <a href="#" class="btn btn-sm btn-gradient" id="btnLogout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar (Desktop only) --}}
            <aside class="col-xl-2 d-none d-xl-block">
                <div class="sidebar">
                    <div class="menu-section-title">Main</div>
                    <nav class="nav flex-column">
                        {{-- ทุก role ใช้ได้ --}}
                        <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i><span>Home</span>
                        </a>
                        <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                        </a>

                        {{-- Staff + Admin --}}
                        <a href="/reservations" class="nav-link {{ request()->is('reservations*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check"></i><span>Reservations</span>
                        </a>

                        {{-- Admin only --}}
                        @if (auth()->user()->role === 'ADMIN')
                            <a href="/users" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                                <i class="bi bi-people"></i><span>Users</span>
                            </a>
                            <a href="/table" class="nav-link {{ request()->is('table*') ? 'active' : '' }}">
                                <i class="bi bi-table"></i><span>Tables</span>
                            </a>
                            <a href="/menu" class="nav-link {{ request()->is('menu*') ? 'active' : '' }}">
                                <i class="bi bi-journal-text"></i><span>Menu</span>
                            </a>
                            <a href="/store-settings" class="nav-link {{ request()->is('store*') ? 'active' : '' }}">
                                <i class="bi bi-gear"></i><span>Store Settings</span>
                            </a>
                        @endif
                    </nav>
                </div>
            </aside>

            {{-- Main content --}}
            <main class="col-xl-10">
                @yield('header')
                <div class="page-card position-relative">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Mobile / Tablet Sidebar (Offcanvas) --}}
    <div class="offcanvas offcanvas-start d-xl-none" tabindex="-1" id="mobileSidebar"
        aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="mobileSidebarLabel">Sushico Back Office</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body pt-0">
            <div class="menu-section-title">Main</div>
            <nav class="nav flex-column">
                {{-- ทุก role ใช้ได้ --}}
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}" data-bs-dismiss="offcanvas">
                    <i class="bi bi-house-door"></i><span>Home</span>
                </a>
                <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                    data-bs-dismiss="offcanvas">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>

                {{-- Staff + Admin --}}
                <a href="/reservations" class="nav-link {{ request()->is('reservations*') ? 'active' : '' }}"
                    data-bs-dismiss="offcanvas">
                    <i class="bi bi-calendar-check"></i><span>Reservations</span>
                </a>

                {{-- Admin only --}}
                @if (auth()->user()->role === 'ADMIN')
                    <a href="/users" class="nav-link {{ request()->is('users*') ? 'active' : '' }}"
                        data-bs-dismiss="offcanvas">
                        <i class="bi bi-people"></i><span>Users</span>
                    </a>
                    <a href="/table" class="nav-link {{ request()->is('table*') ? 'active' : '' }}"
                        data-bs-dismiss="offcanvas">
                        <i class="bi bi-table"></i><span>Tables</span>
                    </a>
                    <a href="/menu" class="nav-link {{ request()->is('menu*') ? 'active' : '' }}"
                        data-bs-dismiss="offcanvas">
                        <i class="bi bi-journal-text"></i><span>Menu</span>
                    </a>
                    <a href="/store-settings" class="nav-link {{ request()->is('store*') ? 'active' : '' }}"
                        data-bs-dismiss="offcanvas">
                        <i class="bi bi-gear"></i><span>Store Settings</span>
                    </a>
                @endif
            </nav>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="text-center">
        © 2025 <a href="/">Sushico Back Office</a> — All Rights Reserved
    </footer>

    <!-- Vendor JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- App JS -->
    <script src="{{ asset('js/backend.js') }}"></script>

    @yield('js_before')
    @include('sweetalert::alert')
</body>

</html>