@extends('home')

{{-- ===== CSS ===== --}}
@section('css_before')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

{{-- ===== Header บนสุดของหน้าดาชบอร์ด ===== --}}
@section('header')
    <div class="app-surface app-surface--deluxe p-3 p-lg-4 mb-3">
        <div class="page-head page-head--deluxe">
            <div class="ph-left">
                <h2 class="page-title m-0">Sushico Dashboard</h2>
                <div class="page-sub">Daily insights • Reservations • Visitors • Growth</div>
            </div>

            <div class="ph-right d-flex align-items-center gap-2 flex-wrap">
                <!-- Segmented Range Switcher -->
                <div class="segmented" id="rangeSwitcher" role="group" aria-label="Range switcher" data-index="0">
                    <button class="seg-btn btn-toggle active" data-range="12m" aria-pressed="true">12m</button>
                    <button class="seg-btn btn-toggle" data-range="6m" aria-pressed="false">6m</button>
                    <button class="seg-btn btn-toggle" data-range="3m" aria-pressed="false">3m</button>
                    <span class="seg-thumb" aria-hidden="true"></span>
                </div>

                <!-- Refresh -->
                <button class="btn-refresh" id="btnRefresh" type="button">
                    <i class="bi bi-arrow-repeat" aria-hidden="true"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </div>
    </div>
@endsection

{{-- ===== เนื้อหาหลัก (การ์ด + กราฟ) ===== --}}
@section('content')
    <div class="container-fluid">
        {{-- ===== STAT STRIP ===== --}}
        <div class="row g-3 g-lg-4 card-equal row-cols-1 row-cols-sm-2 row-cols-lg-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card h-100">
                    <span class="glow-ring"></span>
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-eyebrow">Reservations</div>
                            <div class="stat-value">{{ number_format($countReservation ?? 0, 0) }}</div>
                            <div class="stat-trend" id="trendReservations">
                                <span class="badge rounded-pill text-bg-light">—</span>
                            </div>
                        </div>
                        <i class="bi bi-calendar-check stat-icon"></i>
                    </div>
                    <div id="sparkReservations" class="spark mt-2"></div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card h-100">
                    <span class="glow-ring"></span>
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-eyebrow">Menus</div>
                            <div class="stat-value">{{ number_format($countMenu ?? 0, 0) }}</div>
                            <div class="stat-trend">
                                <span class="badge rounded-pill"
                                    style="background:rgba(245,158,11,.15); color:var(--gold)">stable</span>
                            </div>
                        </div>
                        <i class="bi bi-journal-text stat-icon"></i>
                    </div>
                    <div id="sparkMenus" class="spark mt-2"></div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card h-100">
                    <span class="glow-ring"></span>
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-eyebrow">Users</div>
                            <div class="stat-value">{{ number_format($countUser ?? 0, 0) }}</div>
                            <div class="stat-trend" id="trendUsers">
                                <span class="badge rounded-pill text-bg-light">—</span>
                            </div>
                        </div>
                        <i class="bi bi-people stat-icon"></i>
                    </div>
                    <div id="sparkUsers" class="spark mt-2"></div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card h-100">
                    <span class="glow-ring"></span>
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-eyebrow">Views</div>
                            <div class="stat-value">{{ number_format($countView ?? 0, 0) }}</div>
                            <div class="stat-trend" id="trendViews">
                                <span class="badge rounded-pill text-bg-light">—</span>
                            </div>
                        </div>
                        <i class="bi bi-eye stat-icon"></i>
                    </div>
                    <div id="sparkViews" class="spark mt-2"></div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        {{-- ===== CHARTS: Visits + Donut ===== --}}
        <div class="row g-3 g-lg-4 mt-1 card-equal">
            <div class="col-lg-8">
                <div class="section-card h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="section-title d-flex align-items-center gap-2">
                            <span class="icon-wrapper">
                                <i class="bi bi-bar-chart-line"></i>
                            </span>
                            Website Visits
                        </h5>
                        <span class="badge rounded-pill" style="background:rgba(251,146,60,.15); color:#fb923c">last
                            months</span>
                    </div>
                    <div id="visitsChart" class="is-skeleton mt-2"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card h-100">
                    <h5 class="section-title d-flex align-items-center gap-2">
                        <span class="icon-wrapper">
                            <i class="bi bi-calendar-check"></i>
                        </span>
                        Reservations by Status
                    </h5>
                    <div id="reservationStatusChart" class="is-skeleton"></div>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <span class="badge rounded-pill text-bg-light">Total:
                            {{ number_format($countReservation ?? 0) }}</span>
                        <span class="badge rounded-pill text-bg-light">Updated now</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== CHARTS: Party + Users ===== --}}
        <div class="row g-3 g-lg-4 mt-1 card-equal">
            <div class="col-lg-6">
                <div class="section-card h-100">
                    <h5 class="section-title d-flex align-items-center gap-2">
                        <span class="icon-wrapper">
                            <i class="bi bi-people"></i>
                        </span>
                        Avg Party Size / Month
                    </h5>
                    <div id="partySizeChart" class="is-skeleton"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-card h-100">
                    <h5 class="section-title d-flex align-items-center gap-2">
                        <span class="icon-wrapper">
                            <i class="bi bi-graph-up-arrow"></i>
                        </span>
                        User Growth
                    </h5>
                    <div id="userChart" class="is-skeleton"></div>
                </div>
            </div>
        </div>

        {{-- ===== CHARTS: Heatmap + Gauges ===== --}}
        <div class="row g-3 g-lg-4 mt-1 card-equal row-cols-1 row-cols-lg-2">
            <div class="col-lg-8">
                <div class="section-card h-100">
                    <h5 class="section-title d-flex align-items-center gap-2">
                        <span class="icon-wrapper">
                            <i class="bi bi-clock-history"></i>
                        </span>
                        Peak Hours Heatmap (Last 8 Weeks)
                    </h5>
                    <div id="peakHeatmap" class="is-skeleton"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-grid gap-3">
                    <div class="section-card">
                        <h5 class="section-title d-flex align-items-center gap-2">
                            <span class="icon-wrapper">
                                <i class="bi bi-x-octagon"></i>
                            </span>
                            No-Show Rate (30d)
                        </h5>
                        <div id="noShowGauge" class="is-skeleton"></div>
                    </div>
                    <div class="section-card">
                        <h5 class="section-title d-flex align-items-center gap-2">
                            <span class="icon-wrapper">
                                <i class="bi bi-grid-3x3-gap-fill"></i>
                            </span>
                            Table Utilization (7d)
                        </h5>
                        <div id="utilGauge" class="is-skeleton"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ส่งข้อมูลจาก Blade → JS ผ่าน JSON แบบปลอดภัย ===== --}}
    <script id="dashboard-data" type="application/json">
        {!! json_encode([
            'visitsArr'        => array_values(($data ?? collect())->toArray() ?? []),
            'visitsLabel'      => $label ?? [],
            'userArr'          => array_values(($userGrowthData ?? collect())->toArray() ?? []),
            'userLabel'        => $userGrowthLabel ?? [],
            'partyArr'         => array_values(($avgPartySizeData ?? collect())->toArray() ?? []),
            'partyLabel'       => $avgPartySizeLabel ?? [],
            'statusSeries'     => $reservationStatusData ?? [],
            'heatmapSeries'    => $heatmapSeries ?? [],
            'noShowRate'       => (float) ($noShowRate ?? 0),
            'utilRate'         => (float) ($utilRate ?? 0),
            'reservArr'        => array_values(($reservationTrendData ?? collect())->toArray() ?? []),
            'reservLabel'      => $reservationTrendLabel ?? [],
            'countReservation' => (int) ($countReservation ?? 0),
            'countMenu'        => (int) ($countMenu ?? 0),
        ], JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script defer src="{{ asset('js/dashboard.js') }}"></script>
@endsection
