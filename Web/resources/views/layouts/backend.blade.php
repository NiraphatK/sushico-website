<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sushico Back Office</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
            font-family: "Inter", "Poppins", sans-serif;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(90deg, #ffffffcc, #f8f9faee);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e5e5e5;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.2rem;
            color: #0d6efd !important;
        }

        .navbar .btn-primary {
            border-radius: 12px;
            padding: 6px 14px;
            font-weight: 500;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #e5e5e5;
            padding: 1.2rem 1rem;
            position: sticky;
            top: 0;
        }

        .sidebar .nav-link {
            color: #555;
            font-weight: 500;
            border-radius: 12px;
            padding: 12px 16px;
            margin: 6px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.25s ease;
        }

        .sidebar .nav-link:hover {
            background: #f1f3f5;
            color: #0d6efd;
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #0d6efd, #5b86e5);
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        /* Main Content */
        main {
            padding: 2rem;
        }

        .page-card {
            border: none;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            padding: 2.5rem;
            margin-bottom: 2rem;
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer */
        footer {
            font-size: 0.9rem;
            color: #666;
            padding: 1rem;
            border-top: 1px solid #e5e5e5;
            background: #fff;
        }

        footer a {
            color: #0d6efd;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
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
                <a href="/logout" class="btn btn-sm btn-primary">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            {{-- Sidebar --}}
            <aside class="col-md-3 col-lg-2 sidebar">
                <nav class="nav flex-column">

                    <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i> Home
                    </a>

                    <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>

                    <a href="/users" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Users
                    </a>

                    <a href="/table" class="nav-link {{ request()->is('table*') ? 'active' : '' }}">
                        <i class="bi bi-table"></i> Tables
                    </a>

                    <a href="/reservations" class="nav-link {{ request()->is('reservations*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i> Reservations
                    </a>

                    <a href="/menu" class="nav-link {{ request()->is('menu*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i> Menu
                    </a>

                    <a href="/store-settings" class="nav-link {{ request()->is('store*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i> Store Settings
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
        © 2025 <a href="/">Sushico Back Office</a> — All Rights Reserved
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    @yield('js_before')
    @include('sweetalert::alert')
</body>

</html>
