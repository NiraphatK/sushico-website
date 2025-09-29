<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sushico Back Office</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Inter font (fallbacks to system stack) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Layout */
            --nav-h: 64px;

            /* Brand palette */
            --salmon: #ff6a6a;
            /* sushi salmon */
            --wasabi: #3bcc7a;
            /* wasabi green */
            --soy: #704214;
            /* soy brown */
            --ocean: #5b86e5;
            /* ocean blue */
            --plum: #764ba2;
            /* plum purple */

            /* Surface + text (light) */
            --bg-app: linear-gradient(135deg, #f5f7fb, #e9effa);
            --surface: #ffffff;
            --text-1: #0f172a;
            --text-2: #475569;
            --muted: #94a3b8;
            --outline: rgba(0, 0, 0, 0.06);
            --shadow: 0 10px 26px rgba(0, 0, 0, 0.06);

            /* Elevation glass */
            --glass: rgba(255, 255, 255, .78);
            --blur: blur(18px) saturate(180%);

            /* Gradients */
            --grad-primary: linear-gradient(135deg, #0d6efd, var(--ocean), var(--plum));
            --grad-info: linear-gradient(135deg, #36d1dc, var(--ocean));
            --grad-primary-soft: linear-gradient(135deg, #667eea, var(--plum));
            --grad-success: linear-gradient(135deg, #11998e, #38ef7d);
            --grad-danger: linear-gradient(135deg, #f00000, #dc281e);

            /* Radii */
            --r-lg: 20px;
            --r-md: 14px;

            /* Focus */
            --focus: 0 0 0 3px rgba(13, 110, 253, .25);
        }

        html,
        body {
            height: 100%;
        }

        body {
            background: var(--bg-app);
            font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, "Noto Sans Thai", sans-serif;
            color: var(--text-1);
            padding-top: var(--nav-h);
            /* keep content clear of navbar */
            position: relative;
            overflow-x: hidden;
        }

        /* Seigaiha pattern (very subtle) */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: .06;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='70' viewBox='0 0 140 70'%3E%3Cdefs%3E%3Cpattern id='seigaiha' x='0' y='0' width='140' height='70' patternUnits='userSpaceOnUse'%3E%3Cpath d='M0 70c35-70 105-70 140 0M-70 70c35-70 105-70 140 0M70 70c35-70 105-70 140 0' fill='none' stroke='%2394a3b8' stroke-width='1'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23seigaiha)'/%3E%3C/svg%3E");
            background-size: 320px 160px;
            z-index: -2;
        }

        /* Film grain */
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: .08;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120' viewBox='0 0 120 120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.15'/%3E%3C/svg%3E");
            z-index: -1;
        }

        /* Navbar */
        .navbar {
            height: var(--nav-h);
            background: var(--glass);
            backdrop-filter: var(--blur);
            border-bottom: 1px solid var(--outline);
            box-shadow: var(--shadow);
        }

        .navbar-brand {
            font-size: 1.1rem;
            font-weight: 800;
            letter-spacing: -0.2px;
            background: var(--grad-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .btn-gradient {
            border-radius: 14px;
            padding: 6px 14px;
            font-weight: 700;
            background: var(--grad-primary);
            border: none;
            transition: transform .2s ease, box-shadow .2s ease;
            box-shadow: 0 3px 10px rgba(13, 110, 253, .18);
            color: #fff;
        }

        .btn-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(13, 110, 253, .25);
            color: #fff;
        }

        .btn-gradient:focus-visible {
            box-shadow: var(--focus);
            outline: none;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, var(--surface), color-mix(in oklab, var(--surface) 86%, #eef2ff));
            border-right: 1px solid var(--outline);
            height: calc(100vh - var(--nav-h));
            position: sticky;
            top: var(--nav-h);
            padding: 1.25rem 1rem;
            box-shadow: var(--shadow);
            border-top-right-radius: var(--r-lg);
        }

        .menu-section-title {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--muted);
            margin: .5rem 0 .25rem;
            padding: 0 .25rem;
            font-weight: 700;
        }

        .sidebar .nav-link,
        .offcanvas .nav-link {
            display: flex;
            gap: 12px;
            align-items: center;
            border-radius: 12px;
            padding: 10px 12px;
            color: var(--text-2);
            font-weight: 600;
            transition: transform .15s ease, background-color .15s ease, color .15s ease;
        }

        .sidebar .nav-link i,
        .offcanvas .nav-link i {
            opacity: .9;
            transition: transform .15s ease;
        }

        .sidebar .nav-link:hover,
        .offcanvas .nav-link:hover {
            background: rgba(13, 110, 253, .08);
            color: #0d6efd;
            transform: translateX(3px);
        }

        .sidebar .nav-link:hover i,
        .offcanvas .nav-link:hover i {
            transform: translateX(2px);
        }

        .sidebar .nav-link.active,
        .offcanvas .nav-link.active {
            background: var(--grad-primary);
            color: #fff !important;
            box-shadow: 0 6px 16px rgba(13, 110, 253, .28);
        }

        /* Main Content */
        main {
            padding: clamp(1rem, 3vw, 2rem);
        }

        .page-card {
            border-radius: var(--r-lg);
            background: var(--surface);
            padding: clamp(1.25rem, 2vw, 2rem);
            box-shadow: var(--shadow);
            animation: fadeInUp .45s ease;
            border: 1px solid color-mix(in oklab, var(--outline) 85%, transparent);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer */
        footer {
            font-size: .9rem;
            color: var(--muted);
            border-top: 1px solid var(--outline);
            background: color-mix(in oklab, var(--surface) 80%, transparent);
            backdrop-filter: blur(10px);
            padding: 1rem;
            margin-top: .75rem;
        }

        footer a {
            color: #0d6efd;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Card utilities (if used in child views) */
        .chart-card,
        .stat-card {
            border: none;
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: clamp(1rem, 2.2vw, 1.6rem);
            background: var(--surface);
            height: 100%;
            border: 1px solid color-mix(in oklab, var(--outline) 85%, transparent);
        }

        /* Focus visibility */
        a:focus-visible,
        button:focus-visible {
            outline: none;
            box-shadow: var(--focus);
            border-radius: 10px;
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                transition: none !important;
                animation-duration: .001ms !important;
                animation-iteration-count: 1 !important;
            }
        }

        /* Scrollbar (WebKit) - subtle */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: color-mix(in oklab, #64748b 30%, transparent);
            border-radius: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>

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
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body pt-0">
            <div class="menu-section-title">Main</div>
            <nav class="nav flex-column">
                {{-- ทุก role ใช้ได้ --}}
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                    data-bs-dismiss="offcanvas">
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Close offcanvas then navigate (mobile)
        document.querySelectorAll('#mobileSidebar a.nav-link').forEach(a => {
            a.addEventListener('click', (e) => {
                const href = a.getAttribute('href');
                if (!href || href === '#' || href.startsWith('#')) return;
                e.preventDefault();
                const offcanvasEl = document.getElementById('mobileSidebar');
                const oc = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                offcanvasEl.addEventListener('hidden.bs.offcanvas', () => {
                    window.location.href = href;
                }, {
                    once: true
                });
                oc.hide();
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('btnLogout');
            if (!btn) return;
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'ออกจากระบบ?',
                    text: 'คุณแน่ใจหรือไม่ว่าต้องการออกจากระบบ',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'ใช่, ออกจากระบบ',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            });
        });
    </script>
    @yield('js_before')
    @include('sweetalert::alert')
</body>

</html>
