<!doctype html>
<html lang="th" data-bs-theme="light" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sushico</title>
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content="Sushico — Taste the Art of Sushi. ซูชิโค่ คัดวัตถุดิบสดใหม่ทุกวัน">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Kaushan+Script&family=Poppins:wght@300;400;600&family=Noto+Sans+Thai:wght@300;400;600&family=Shippori+Mincho:wght@600&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">

    <!-- SweetAlert2 CSS -->
    @include('sweetalert::alert')
    <link rel="stylesheet" href="{{ asset('css/swal-sushico.css') }}">

    @yield('css_before')
</head>

<body>
    <!-- ===== Floating Navbar ===== -->
    <header class="floating-header" id="floatingHeader">
        <div class="nav-shell">
            <nav class="navbar navbar-expand-lg navbar-light" aria-label="Floating navbar">
                <div class="container-xxl navbar-grid">

                    <!-- โลโก้ซ้าย -->
                    <a class="navbar-brand" href="/">Sushico</a>

                    <!-- toggler -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- collapse -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <!-- เมนูตรงกลาง -->
                        <ul class="navbar-nav nav-center mb-2 mb-lg-0 text-center nav-center-desktop">
                            <li class="nav-item">
                                <a class="nav-link {{ url()->current() === url('/') ? 'active' : '' }}" href="/"
                                    @if (url()->current() === url('/')) aria-current="page" @endif>หน้าแรก</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('about-us*') ? 'active' : '' }}" href="/about-us"
                                    @if (request()->is('about-us*')) aria-current="page" @endif>เกี่ยวกับเรา</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('menus*') ? 'active' : '' }}" href="/menus"
                                    @if (request()->is('menus*')) aria-current="page" @endif>เมนู</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('contact-us*') ? 'active' : '' }}"
                                    href="/contact-us"
                                    @if (request()->is('contact-us*')) aria-current="page" @endif>ติดต่อเรา</a>
                            </li>

                            @if (Auth::guard('user')->check() && in_array(Auth::guard('user')->user()->role, ['ADMIN', 'STAFF']))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"
                                        href="/dashboard"
                                        @if (request()->is('dashboard*')) aria-current="page" @endif>จัดการหลังบ้าน</a>
                                </li>
                            @endif
                        </ul>


                        <!-- ปุ่มด้านขวา -->
                        <div class="navbar-actions ms-auto d-flex align-items-center gap-2">
                            @if (Auth::guard('user')->check())
                                @php
                                    $u = Auth::guard('user')->user();
                                    $displayName = $u->full_name ?? ($u->phone ?? 'บัญชีของฉัน');
                                @endphp

                                @php
                                    $u = Auth::guard('user')->user();
                                    $displayName = $u->full_name ?? ($u->phone ?? 'บัญชีของฉัน');
                                    $initial = function_exists('mb_substr')
                                        ? mb_substr($displayName, 0, 1, 'UTF-8')
                                        : substr($displayName, 0, 1);
                                    $role = $u->role ?? '';
                                @endphp

                                <div class="dropdown w-100 account-menu" data-bs-display="static">
                                    <button
                                        class="btn btn-ghost w-100 d-flex align-items-center justify-content-center gap-2"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-circle flex-shrink-0"></i>
                                        <span class="username d-block text-truncate" style="min-width:0;">
                                            {{ \Illuminate\Support\Str::limit($displayName, 10) }}
                                        </span>
                                        <i class="bi bi-chevron-down small opacity-75 flex-shrink-0"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm account-dropdown">
                                        {{-- Header --}}
                                        <li class="px-3 pt-3 pb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="avatar-blob">{{ $initial }}</span>
                                                <div class="min-w-0">
                                                    <div class="fw-bold text-truncate">
                                                        {{ \Illuminate\Support\Str::limit($displayName, 10) }}</div>
                                                    @if ($role)
                                                        <div class="role-chip text-uppercase">{{ $role }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <hr class="dropdown-divider my-2">
                                        </li>

                                        {{-- Items --}}
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2" href="/">
                                                <i class="bi bi-person"></i><span>ข้อมูลบัญชี</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                                href="{{ route('reservations.history') }}">
                                                <i class="bi bi-clock-history"></i><span>ประวัติการจอง</span>
                                            </a>
                                        </li>

                                        <li>
                                            <hr class="dropdown-divider my-2">
                                        </li>

                                        <li>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="m-0">
                                                @csrf
                                                <button type="submit"
                                                    class="dropdown-item text-danger d-flex align-items-center gap-2">
                                                    <i class="bi bi-box-arrow-right"></i><span>ออกจากระบบ</span>
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <button type="button" class="btn btn-ghost" data-bs-toggle="modal"
                                    data-bs-target="#loginModal">
                                    เข้าสู่ระบบ/สมัครสมาชิก
                                </button>
                            @endif

                            <a href="/reserve" class="btn reserve-table-btn">จองโต๊ะ</a>
                        </div>
                    </div>
                </div>
            </nav>

        </div>
    </header>
    <!-- ===== /Floating Navbar ===== -->

    <main>
        <!-- Banner -->
        <section class="banner">
            <!-- ===== Video background layer ===== -->
            <div class="video-bg" aria-hidden="true">
                <video autoplay muted loop playsinline preload="auto">
                    <source src="{{ asset('assets/videos/banner.mp4') }}" type="video/mp4">
                </video>
            </div>

            <div class="container" data-aos="zoom-in" data-aos-delay="80">
                @php
                    use Illuminate\Support\Facades\Route;

                    $defaultTitle = 'Taste the Art of Sushi';
                    $defaultLead = 'วัตถุดิบสดใหม่ คัดพิเศษทุกวัน — Nigiri, Sashimi & Signature Rolls';

                    $routeTitles = [
                        'home.index' => 'Taste the Art of Sushi',
                        'home.about' => 'About Us',
                        'home.contact' => 'Contact Us',
                        'menu.index' => 'Our Menu',
                        'reserve.index' => 'Reservation',
                    ];

                    $routeLeads = [
                        'home.index' => $defaultLead,
                        'home.about' => 'จากตลาดปลา ถึงจานตรงหน้าคุณ — เรื่องเล่าจากครัวของเรา',
                        'home.contact' => 'เปิดทุกวัน • โทร 02-xxx-xxxx • Line @sushico',
                        'menu.index' => 'คัดวัตถุดิบสดใหม่ทุกวัน — Nigiri, Sashimi & Signature Rolls',
                        'reserve.index' => 'สำรองที่นั่งล่วงหน้า เพื่อช่วงเวลาที่ลงตัว',
                    ];

                    $current = Route::currentRouteName();

                    if (!$current) {
                        $path = request()->path();
                        $pathMap = [
                            '' => 'home.index',
                            'about-us' => 'home.about',
                            'contact-us' => 'home.contact',
                            'menus' => 'menu.index',
                            'menus/search' => 'menu.search',
                            'reserve' => 'reserve.index',
                        ];
                        $current = $pathMap[$path] ?? null;
                    }

                    $bannerTitle =
                        $bannerTitle ??
                        ($current && isset($routeTitles[$current]) ? $routeTitles[$current] : $defaultTitle);
                    $bannerLead =
                        $bannerLead ??
                        ($current && isset($routeLeads[$current]) ? $routeLeads[$current] : $defaultLead);
                @endphp

                <h1>{{ $bannerTitle }}</h1>
                <p class="lead mt-2" data-aos="fade-up" data-aos-delay="200">{{ $bannerLead }}</p>

                <div class="hero-cta" data-aos="fade-up" data-aos-delay="320">
                    <a href="/reserve" class="btn btn-salmon">จองโต๊ะ</a>
                    <a href="/about-us" class="btn btn-ghost">เรื่องราวของเรา</a>
                </div>

                {{-- Hero Search (โชว์เฉพาะหน้าเมนู) --}}
                @if (request()->is('menus*'))
                    <form action="{{ route('menu.search') }}" method="GET" class="mt-4" role="search"
                        aria-label="ค้นหาเมนู">
                        <div class="container-xxl">
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-8 col-lg-6">
                                    <div class="search-hero shadow-none">
                                        <input type="text" name="keyword" class="form-control search-input"
                                            placeholder="ค้นหาเช่น ซูชิแซลมอน อูนางิ เผ็ด …"
                                            value="{{ request('keyword') }}">
                                        <button class="btn btn-salmon search-btn" type="submit">ค้นหา</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </section>

        @yield('navbar')

        <!-- Content -->
        <div class="container-xxl mt-4 mt-md-5">
            @yield('body')
        </div>
    </main>

    <!-- Location / Hours -->
    @yield('footer')

    <!-- Footer -->
    <footer class="mt-5">
        <div>Sushico © 2025 — Crafted Daily • Tokyo-inspired • Bangkok</div>
    </footer>

    <!-- Vendor JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- App JS -->
    <script src="{{ asset('js/frontend.js') }}"></script>

    {{-- Login Modal --}}
    <x-auth.login-modal id="loginModal" :open-on-error="true" />

    {{-- Register Modal --}}
    <x-auth.register-modal id="registerModal" :open-on-error="true" />

    @yield('js_before')
</body>

</html>
