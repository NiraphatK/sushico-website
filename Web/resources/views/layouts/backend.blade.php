<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sushico Back Office</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f5f6fa;
            font-family: "Inter", "Poppins", sans-serif;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(90deg, #ffffffee, #f8f9faee);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e5e5e5;
        }

        .navbar-brand {
            font-weight: 600;
            color: #0d6efd !important;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #e5e5e5;
            padding: 1rem;
        }

        .sidebar .nav-link {
            color: #555;
            font-weight: 500;
            border-radius: 10px;
            padding: 10px 14px;
            margin: 6px 0;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover {
            background: #f1f3f5;
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #0d6efd, #5b86e5);
            color: #fff !important;
            box-shadow: 0 3px 8px rgba(13, 110, 253, 0.25);
        }

        /* Main */
        main {
            padding: 2rem;
        }

        .page-card {
            border: none;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        /* Footer */
        footer {
            font-size: 0.9rem;
            color: #888;
            padding: 1rem;
            border-top: 1px solid #e5e5e5;
            background: #fff;
        }
    </style>
    @yield('css_before')
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                🍣 Sushico Admin
            </a>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3 text-muted">Hi, <b>Admin</b></span>
                <a href="/logout" class="btn btn-sm btn-primary">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            {{-- Sidebar --}}
            <aside class="col-md-3 col-lg-2 sidebar">
                <nav class="nav flex-column">

                    <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="bi bi-house-door me-2"></i> Home
                    </a>

                    <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>

                    <a href="/users" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Users
                    </a>

                    <a href="/table" class="nav-link {{ request()->is('table*') ? 'active' : '' }}">
                        <i class="bi bi-table me-2"></i> Table
                    </a>

                    <a href="/reservations" class="nav-link {{ request()->is('reservations*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check me-2"></i> Reservations
                    </a>

                    <a href="/menu" class="nav-link {{ request()->is('menu*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text me-2"></i> Menu
                    </a>

                    <a href="/store-settings" class="nav-link {{ request()->is('store*') ? 'active' : '' }}">
                        <i class="bi bi-gear me-2"></i> Store setting
                    </a>

                </nav>
                @yield('sidebarMenu')
            </aside>


            {{-- Main Content --}}
            <main class="col-md-9 col-lg-10">
                @yield('header')

                <div class="page-card">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    {{-- Footer --}}
    <footer class="text-center">
        © 2025 Sushico Back Office
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    @yield('js_before')
    @include('sweetalert::alert')
</body>

</html>
