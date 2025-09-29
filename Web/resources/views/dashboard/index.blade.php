@extends('home')

@section('css_before')
    <style>
        /* Brand Design Tokens */

        :root {
            /* Brand palette */
            --salmon: #ff7b72;
            /* salmon nigiri */
            --wasabi: #22c55e;
            /* wasabi green */
            --gold: #f59e0b;
            /* gold garnish */
            --soy: #0f172a;
            /* soy ink */
            --ume: #8b5cf6;
            /* purple pickled plum */
            --sky: #38bdf8;
            /* sky */

            /* Surfaces & text (light) */
            --bg-app: #f6f8fb;
            --card-bg: #ffffff;
            --card-border: rgba(2, 6, 23, .08);
            --card-shadow: 0 12px 30px rgba(2, 6, 23, .08);
            --text: #0b1220;
            --muted: #6b7280;
            --axis: #64748b;
            --divider: rgba(2, 6, 23, .08);

            --stat-min-h: 120px;
            --section-min-h: 280px;
        }

        html[data-bs-theme="dark"] {
            --bg-app: #060a13;
            --card-bg: #0b1220;
            --card-border: rgba(148, 163, 184, .16);
            --card-shadow: 0 20px 50px rgba(0, 0, 0, .45);
            --text: #e6e8ee;
            --muted: #9aa4b2;
            --axis: #9aa4b2;
            --divider: rgba(148, 163, 184, .18);
        }

        /* App Surface (Aurora + Seigaiha motif) */
        .app-surface {
            position: relative;
            background:
                radial-gradient(1200px 600px at 80% -20%, rgba(139, 92, 246, .20), transparent 60%),
                radial-gradient(900px 600px at -10% 0%, rgba(56, 189, 248, .22), transparent 55%),
                var(--bg-app);
            border-radius: 20px;
            overflow: hidden;
            isolation: isolate;
        }

        .app-surface::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            opacity: .08;
            background-image:
                radial-gradient(circle at 0 6px, currentColor 2px, transparent 2px),
                radial-gradient(circle at 12px 6px, currentColor 2px, transparent 2px);
            background-size: 24px 12px;
            color: #8aa3c2;
            mix-blend-mode: overlay;
        }

        .app-surface::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            opacity: .05;
            background-image:
                repeating-linear-gradient(0deg, rgba(0, 0, 0, .8) 0 1px, transparent 1px 2px),
                repeating-linear-gradient(90deg, rgba(0, 0, 0, .7) 0 1px, transparent 1px 2px);
            mix-blend-mode: soft-light;
            filter: contrast(.8) brightness(1.1);
        }

        /* Page Header */
        .page-head {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .page-title {
            font-weight: 900;
            letter-spacing: .2px;
        }

        .page-sub {
            color: var(--muted);
        }

        .btn-toggle {
            border: 1px solid var(--card-border)
        }

        .btn-toggle.active {
            color: #fff;
            background: linear-gradient(135deg, var(--salmon), var(--ume));
            border-color: transparent;
            box-shadow: 0 8px 18px rgba(139, 92, 246, .35);
        }

        /* ใช้ :not(.active):hover เพื่อให้ hover ทำงานแค่ตอนยังไม่ active */
        .btn-toggle:not(.active):hover {
            color: #fff;
            background: linear-gradient(135deg,
                    rgba(56, 189, 248, 0.75),
                    rgba(139, 92, 246, 0.75));
            border-color: transparent;
            box-shadow:
                0 4px 12px rgba(56, 189, 248, 0.20),
                0 0 6px rgba(139, 92, 246, 0.20);
            transition: all 0.25s ease;
        }


        /* Cards */
        .stat-card,
        .section-card,
        .table-card {
            position: relative;
            background: color-mix(in srgb, var(--card-bg) 88%, transparent);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            box-shadow: var(--card-shadow);
        }

        .stat-card {
            padding: 1rem;
            color: var(--text);
            overflow: hidden;
            min-height: var(--stat-min-h);
            transition: transform 0.3s cubic-bezier(.4, 0, .2, 1),
                box-shadow 0.3s cubic-bezier(.4, 0, .2, 1);
            will-change: transform, box-shadow;

        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 50px rgba(2, 6, 23, .18);
        }


        .glow-ring {
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(56, 189, 248, .55), rgba(139, 92, 246, .45), rgba(255, 123, 114, .45));
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }

        .stat-eyebrow {
            font-size: .72rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-weight: 800;
            color: var(--axis)
        }

        .stat-value {
            font-size: clamp(1.35rem, 3vw, 1.9rem);
            font-weight: 900;
            letter-spacing: .2px;
            margin: 2px 0 4px
        }

        .stat-trend {
            font-size: .8rem;
            font-weight: 700
        }

        .trend-up {
            color: var(--wasabi)
        }

        .trend-down {
            color: #ef4444
        }

        .stat-icon {
            font-size: 1.35rem;
            color: var(--sky);
            opacity: .95
        }

        .spark {
            height: 56px
        }

        .section-card {
            padding: clamp(1rem, 2.2vw, 1.4rem);
            min-height: var(--section-min-h);
        }

        .section-title {
            font-weight: 900;
            letter-spacing: .2px;
            margin: 0
        }

        .divider {
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--divider), transparent);
            margin: .25rem 0 .75rem
        }

        /* Chart skeleton */
        .is-skeleton {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            min-height: 240px;
            background: linear-gradient(90deg, rgba(148, 163, 184, .10), rgba(148, 163, 184, .18), rgba(148, 163, 184, .10));
        }

        .is-skeleton::after {
            content: "";
            position: absolute;
            inset: 0;
            animation: sheen 1.4s infinite linear;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .32), transparent);
            transform: translateX(-100%)
        }

        @keyframes sheen {
            to {
                transform: translateX(100%)
            }
        }

        /* ===== Equal Height System (cards) ===== */
        .card-equal>[class^="col-"],
        .card-equal>[class*=" col-"] {
            display: flex;
        }

        .card-equal>[class^="col-"]>*,
        .card-equal>[class*=" col-"]>* {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .card-equal>[class^="col-"]>.stat-card,
        .card-equal>[class^="col-"]>.section-card,
        .card-equal>[class^="col-"]>.table-card {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        /* ให้บล็อกกราฟยืดเต็มการ์ด */
        .section-card>.section-title+div[id],
        .section-card>div[id^="spark"],
        .section-card>div[id$="Chart"],
        .section-card>div[id$="Heatmap"],
        .section-card>div[id$="Gauge"] {
            flex: 1 1 auto;
            min-height: 240px;
        }

        /* เกจ: คุมเริ่มต้นบนเดสก์ท็อป */
        .section-card [id$="Gauge"] {
            min-height: 260px !important;
        }

        /* Reduce motion */
        @media (prefers-reduced-motion: reduce) {
            .is-skeleton::after {
                animation: none
            }

            .stat-card:hover {
                transform: none
            }
        }

        /* ===== Responsive Tweaks (updated) ===== */
        @media (max-width: 768px) {
            .spark {
                height: 44px;
            }

            .section-title {
                font-size: 1rem;
            }

            .badge {
                font-size: .72rem;
            }
        }

        @media (max-width: 576px) {
            .page-head {
                flex-direction: column;
                align-items: flex-start;
                gap: .5rem;
            }

            /* กลุ่มปุ่มช่วงเวลาให้ตัดบรรทัดได้และกดถนัด */
            #rangeSwitcher {
                display: flex;
                flex-wrap: wrap;
            }

            .btn-group.btn-group-sm .btn {
                padding: .35rem .6rem;
            }

            .stat-card {
                padding: .75rem;
            }

            .stat-value {
                font-size: 1.15rem;
            }

            .spark {
                height: 40px;
            }

            .section-card {
                padding: .8rem;
            }

            .section-title {
                font-size: .95rem;
            }

            .is-skeleton {
                min-height: 160px;
            }

            :root {
                --stat-min-h: 92px;
                --section-min-h: 200px;
            }

            /* บล็อกกราฟใน d-grid (เช่นเกจสองใบ) ไม่ยืดเกิน */
            .d-grid.gap-3>.section-card {
                display: block;
                width: 100%;
                min-height: 200px;
            }

            /* บังคับความสูงแคนวาสของกราฟบนจอเล็ก (กันล้น) */
            #reservationStatusChart .apexcharts-canvas {
                height: 210px !important;
            }

            #visitsChart .apexcharts-canvas,
            #partySizeChart .apexcharts-canvas,
            #userChart .apexcharts-canvas {
                height: 220px !important;
            }

            #peakHeatmap .apexcharts-canvas {
                height: 230px !important;
            }

            /* เกจ: ไม่ให้ล้น */
            #noShowGauge,
            #utilGauge {
                min-height: 180px !important;
            }

            #noShowGauge .apexcharts-canvas,
            #utilGauge .apexcharts-canvas {
                height: 180px !important;
            }

            /* ลด legend donut */
            #reservationStatusChart .apexcharts-legend {
                font-size: 10px !important;
                row-gap: 2px;
            }
        }

        /* กันกรณี ApexCharts กว้างกว่าการ์ด (edge cases) */
        .section-card .apexcharts-canvas,
        .section-card .apexcharts-svg {
            max-width: 100% !important;
            overflow: hidden;
        }
    </style>
@endsection

@section('header')
    <div class="app-surface p-3 mb-3">
        <div class="page-head">
            <div>
                <h4 class="page-title m-0">📊 Sushico Dashboard</h4>
                <div class="page-sub">Daily insights • Reservations • Visitors • Growth</div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="btn-group btn-group-sm" id="rangeSwitcher" role="group" aria-label="Range">
                    <button class="btn btn-outline-secondary btn-toggle active" data-range="12m">12m</button>
                    <button class="btn btn-outline-secondary btn-toggle" data-range="6m">6m</button>
                    <button class="btn btn-outline-secondary btn-toggle" data-range="3m">3m</button>
                </div>
                <button class="btn btn-sm btn-outline-secondary btn-toggle" id="btnRefresh">
                    <i class="bi bi-arrow-repeat me-1"></i>Refresh
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
                        <h5 class="section-title">📈 Website Visits</h5>
                        <span class="badge rounded-pill" style="background:rgba(251,146,60,.15); color:#fb923c">last
                            months</span>
                    </div>
                    <div id="visitsChart" class="is-skeleton mt-2"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card h-100">
                    <h5 class="section-title">🍣 Reservations by Status</h5>
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
                    <h5 class="section-title">👥 Avg Party Size / Month</h5>
                    <div id="partySizeChart" class="is-skeleton"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-card h-100">
                    <h5 class="section-title">📈 User Growth</h5>
                    <div id="userChart" class="is-skeleton"></div>
                </div>
            </div>
        </div>

        {{-- ===== CHARTS: Heatmap + Gauges ===== --}}
        <div class="row g-3 g-lg-4 mt-1 card-equal row-cols-1 row-cols-lg-2">
            <div class="col-lg-8">
                <div class="section-card h-100">
                    <h5 class="section-title">⏰ Peak Hours Heatmap (Last 8 Weeks)</h5>
                    <div id="peakHeatmap" class="is-skeleton"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-grid gap-3">
                    <div class="section-card">
                        <h5 class="section-title">🚫 No-Show Rate (30d)</h5>
                        <div id="noShowGauge" class="is-skeleton"></div>
                    </div>
                    <div class="section-card">
                        <h5 class="section-title">🪑 Table Utilization (7d)</h5>
                        <div id="utilGauge" class="is-skeleton"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        /* ===== Data (safe fallbacks) ===== */
        const visitsArr = {!! json_encode(array_values(($data ?? collect())->toArray() ?? (array) ($data ?? []))) !!} || [];
        const visitsLabel = {!! json_encode($label ?? []) !!} || [];
        const userArr = {!! json_encode(array_values(($userGrowthData ?? collect())->toArray() ?? (array) ($userGrowthData ?? []))) !!} || [];
        const userLabel = {!! json_encode($userGrowthLabel ?? []) !!} || [];
        const partyArr = {!! json_encode(array_values(($avgPartySizeData ?? collect())->toArray() ?? (array) ($avgPartySizeData ?? []))) !!} || [];
        const partyLabel = {!! json_encode($avgPartySizeLabel ?? []) !!} || [];
        const statusSeries = {!! json_encode($reservationStatusData ?? []) !!} || [];
        const heatmapSeries = {!! json_encode($heatmapSeries ?? []) !!} || [];
        const noShowRate = {!! json_encode($noShowRate ?? 0) !!};
        const utilRate = {!! json_encode($utilRate ?? 0) !!};
        const reservArr = {!! json_encode(
            array_values(($reservationTrendData ?? collect())->toArray() ?? (array) ($reservationTrendData ?? [])),
        ) !!} || [];
        const reservLabel = {!! json_encode($reservationTrendLabel ?? []) !!} || [];

        /* ===== Theme detection for Apex ===== */
        const root = document.documentElement;
        const axisColor = getComputedStyle(root).getPropertyValue('--axis').trim() || '#64748b';
        const isDark = (root.getAttribute('data-bs-theme') || 'light') === 'dark';

        /* ===== Helpers ===== */
        const sliceRange = (arr, range) => {
            if (!Array.isArray(arr)) return [];
            const map = {
                '12m': 12,
                '6m': 6,
                '3m': 3
            };
            const n = map[range] ?? arr.length;
            return arr.slice(-n);
        };
        const computeTrend = (arr) => {
            if (!arr || arr.length < 2) return {
                txt: '—',
                cls: ''
            };
            const a = +arr[arr.length - 2] || 0,
                b = +arr[arr.length - 1] || 0;
            const delta = b - a;
            const pct = a === 0 ? 100 : (delta / a) * 100;
            const txt = (delta >= 0 ? '↑ ' : '↓ ') + Math.abs(pct).toFixed(1) + '%';
            return {
                txt,
                cls: delta >= 0 ? 'trend-up' : 'trend-down'
            };
        };
        const setTrend = (id, arr) => {
            const el = document.getElementById(id);
            if (!el) return;
            const t = computeTrend(arr);
            el.innerHTML =
                `<span class="badge rounded-pill ${t.cls ? '' : 'text-bg-light'} ${t.cls}" style="background:${t.cls? 'rgba(34,197,94,.15)' : ''};">${t.txt}</span>`;
        };
        const unSkeleton = (id) => {
            const el = document.getElementById(id);
            el && el.classList.remove('is-skeleton');
        };

        // Dynamic height helper by screen size
        const h = (lg, md, sm) => window.innerWidth <= 576 ? (sm ?? md ?? lg) :
            window.innerWidth <= 992 ? (md ?? lg) :
            lg;

        /* ===== Sparkline base ===== */
        const sparkBase = {
            chart: {
                type: 'area',
                height: 56,
                sparkline: {
                    enabled: true
                },
                animations: {
                    enabled: true
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: .38,
                    opacityTo: .05
                }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light'
            }
        };

        let charts = {
            sparkReservations: null,
            sparkMenus: null,
            sparkUsers: null,
            sparkViews: null,
            visits: null,
            donut: null,
            party: null,
            users: null,
            heatmap: null,
            noShow: null,
            util: null
        };

        function buildAll(range = '12m') {
            const vData = sliceRange(visitsArr, range),
                vLabel = sliceRange(visitsLabel, range);
            const uData = sliceRange(userArr, range),
                uLabel = sliceRange(userLabel, range);
            const pData = sliceRange(partyArr, range),
                pLabel = sliceRange(partyLabel, range);
            const rData = sliceRange(reservArr, range),
                rLabel = sliceRange(reservLabel, range);

            setTrend('trendReservations', rData);
            setTrend('trendUsers', uData);
            setTrend('trendViews', vData);

            Object.values(charts).forEach(c => c?.destroy());

            charts.sparkReservations = new ApexCharts(document.querySelector('#sparkReservations'), {
                ...sparkBase,
                colors: ['#38bdf8'],
                series: [{
                    name: 'Reservations',
                    data: rData
                }]
            });
            charts.sparkReservations.render();

            charts.sparkMenus = new ApexCharts(document.querySelector('#sparkMenus'), {
                ...sparkBase,
                colors: ['#8b5cf6'],
                series: [{
                    name: 'Menus',
                    data: Array(Math.max(vData.length, 1)).fill({{ (int) ($countMenu ?? 0) }})
                }]
            });
            charts.sparkMenus.render();

            charts.sparkUsers = new ApexCharts(document.querySelector('#sparkUsers'), {
                ...sparkBase,
                colors: ['#22c55e'],
                series: [{
                    name: 'Users',
                    data: uData
                }]
            });
            charts.sparkUsers.render();

            charts.sparkViews = new ApexCharts(document.querySelector('#sparkViews'), {
                ...sparkBase,
                colors: ['#f59e0b'],
                series: [{
                    name: 'Views',
                    data: vData
                }]
            });
            charts.sparkViews.render();

            // Visits (area)
            charts.visits = new ApexCharts(document.querySelector('#visitsChart'), {
                chart: {
                    type: 'area',
                    height: h(330, 280, 230),
                    toolbar: {
                        show: false
                    },
                    foreColor: axisColor,
                    background: 'transparent',
                    animations: {
                        enabled: true
                    },
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    events: {
                        mounted: () => unSkeleton('visitsChart')
                    }
                },
                colors: ['#8b5cf6'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                series: [{
                    name: 'Visits',
                    data: vData
                }],
                xaxis: {
                    categories: vLabel,
                    labels: {
                        rotate: -15
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        formatter: v => Math.round(v)
                    }
                },
                grid: {
                    borderColor: 'rgba(148,163,184,.22)'
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: .3,
                        opacityFrom: .45,
                        opacityTo: .08,
                        stops: [0, 90, 100]
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                },
                responsive: [{
                        breakpoint: 1400,
                        options: {
                            chart: {
                                height: 300
                            }
                        }
                    },
                    {
                        breakpoint: 992,
                        options: {
                            chart: {
                                height: 280
                            },
                            xaxis: {
                                labels: {
                                    rotate: -10
                                }
                            }
                        }
                    },
                    {
                        breakpoint: 768,
                        options: {
                            chart: {
                                height: 250
                            },
                            xaxis: {
                                tickAmount: 6
                            }
                        }
                    },
                    {
                        breakpoint: 576,
                        options: {
                            chart: {
                                height: 230
                            },
                            xaxis: {
                                tickAmount: 4,
                                labels: {
                                    rotate: -5
                                }
                            }
                        }
                    }
                ]
            });
            charts.visits.render();

            // Donut: Reservation status
            charts.donut = new ApexCharts(document.querySelector('#reservationStatusChart'), {
                chart: {
                    type: 'donut',
                    height: h(300, 260, 210),
                    foreColor: axisColor,
                    background: 'transparent',
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    events: {
                        mounted: () => unSkeleton('reservationStatusChart')
                    }
                },
                labels: ['Confirmed', 'Seated', 'Completed', 'Cancelled', 'No Show'],
                series: statusSeries,
                colors: ['#36d1dc', '#22c55e', '#9ca3af', '#ef4444', '#6b7280'],
                legend: {
                    position: 'bottom',
                    fontSize: '12px'
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '74%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: () => {{ (int) ($countReservation ?? 0) }}
                                }
                            }
                        }
                    }
                },
                responsive: [{
                        breakpoint: 1200,
                        options: {
                            chart: {
                                height: 280
                            }
                        }
                    },
                    {
                        breakpoint: 992,
                        options: {
                            chart: {
                                height: 260
                            },
                            legend: {
                                fontSize: '11px'
                            }
                        }
                    },
                    {
                        breakpoint: 576,
                        options: {
                            chart: {
                                height: 210
                            },
                            legend: {
                                fontSize: '10px'
                            }
                        }
                    }
                ]
            });
            charts.donut.render();

            // Party size (bar)
            charts.party = new ApexCharts(document.querySelector('#partySizeChart'), {
                chart: {
                    type: 'bar',
                    height: h(320, 290, 240),
                    foreColor: axisColor,
                    background: 'transparent',
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    events: {
                        mounted: () => unSkeleton('partySizeChart')
                    }
                },
                series: [{
                    name: 'Avg Party Size',
                    data: pData
                }],
                xaxis: {
                    categories: pLabel,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                colors: ['#f59e0b'],
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: '45%',
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: (v) => v?.toFixed ? v.toFixed(1) : v
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                },
                grid: {
                    strokeDashArray: 3
                },
                responsive: [{
                        breakpoint: 992,
                        options: {
                            chart: {
                                height: 290
                            }
                        }
                    },
                    {
                        breakpoint: 768,
                        options: {
                            chart: {
                                height: 270
                            }
                        }
                    },
                    {
                        breakpoint: 576,
                        options: {
                            chart: {
                                height: 240
                            },
                            dataLabels: {
                                enabled: false
                            }
                        }
                    }
                ]
            });
            charts.party.render();

            // User growth (bar)
            charts.users = new ApexCharts(document.querySelector('#userChart'), {
                chart: {
                    type: 'bar',
                    height: h(320, 290, 240),
                    foreColor: axisColor,
                    background: 'transparent',
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    events: {
                        mounted: () => unSkeleton('userChart')
                    }
                },
                series: [{
                    name: 'Users',
                    data: uData
                }],
                xaxis: {
                    categories: uLabel,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                colors: ['#22c55e'],
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: '45%'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                },
                responsive: [{
                        breakpoint: 992,
                        options: {
                            chart: {
                                height: 290
                            }
                        }
                    },
                    {
                        breakpoint: 768,
                        options: {
                            chart: {
                                height: 270
                            }
                        }
                    },
                    {
                        breakpoint: 576,
                        options: {
                            chart: {
                                height: 240
                            }
                        }
                    }
                ]
            });
            charts.users.render();

            // Heatmap: Peak Hours (last 8 weeks)
            charts.heatmap = new ApexCharts(document.querySelector('#peakHeatmap'), {
                chart: {
                    type: 'heatmap',
                    height: h(350, 310, 260),
                    foreColor: axisColor,
                    background: 'transparent',
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    events: {
                        mounted: () => unSkeleton('peakHeatmap')
                    }
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    heatmap: {
                        shadeIntensity: .45,
                        radius: 8,
                        colorScale: {
                            ranges: [{
                                    from: 0,
                                    to: 0,
                                    color: isDark ? '#1f2937' : '#e5e7eb',
                                    name: '0'
                                },
                                {
                                    from: 1,
                                    to: 2,
                                    color: '#c7d2fe'
                                },
                                {
                                    from: 3,
                                    to: 5,
                                    color: '#93c5fd'
                                },
                                {
                                    from: 6,
                                    to: 9,
                                    color: '#60a5fa'
                                },
                                {
                                    from: 10,
                                    to: 14,
                                    color: '#3b82f6'
                                },
                                {
                                    from: 15,
                                    to: 9999,
                                    color: '#1d4ed8'
                                }
                            ]
                        }
                    }
                },
                series: heatmapSeries,
                xaxis: {
                    type: 'category',
                    labels: {
                        rotate: -45
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                },
                responsive: [{
                        breakpoint: 992,
                        options: {
                            chart: {
                                height: 310
                            },
                            xaxis: {
                                labels: {
                                    rotate: -30
                                }
                            }
                        }
                    },
                    {
                        breakpoint: 576,
                        options: {
                            chart: {
                                height: 260
                            },
                            xaxis: {
                                labels: {
                                    rotate: -15
                                }
                            }
                        }
                    }
                ]
            });
            charts.heatmap.render();

            // Gauge: No-Show Rate (30d)
            charts.noShow = new ApexCharts(document.querySelector('#noShowGauge'), {
                chart: {
                    type: 'radialBar',
                    height: h(260, 220, 180),
                    foreColor: axisColor,
                    background: 'transparent',
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    events: {
                        mounted: () => unSkeleton('noShowGauge')
                    }
                },
                series: [Math.max(0, Math.min(100, noShowRate))],
                colors: ['#ff7b72'],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '58%'
                        },
                        track: {
                            background: isDark ? '#0f172a' : '#f1f5f9'
                        },
                        dataLabels: {
                            name: {
                                show: true,
                                offsetY: 12,
                                formatter: () => 'No-Show'
                            },
                            value: {
                                show: true,
                                fontSize: '20px',
                                formatter: (v) => (typeof v === 'number' ? v.toFixed(1) : v) + '%'
                            }
                        }
                    }
                },
                stroke: {
                    lineCap: 'round'
                },
                responsive: [{
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 180
                        }
                    }
                }]
            });
            charts.noShow.render();

            // Gauge: Table Utilization (7d)
            charts.util = new ApexCharts(document.querySelector('#utilGauge'), {
                chart: {
                    type: 'radialBar',
                    height: h(260, 220, 180),
                    foreColor: axisColor,
                    background: 'transparent',
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    events: {
                        mounted: () => unSkeleton('utilGauge')
                    }
                },
                series: [Math.max(0, Math.min(100, utilRate))],
                colors: ['#22c55e'],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '58%'
                        },
                        track: {
                            background: isDark ? '#0f172a' : '#f1f5f9'
                        },
                        dataLabels: {
                            name: {
                                show: true,
                                offsetY: 12,
                                formatter: () => 'Utilization'
                            },
                            value: {
                                show: true,
                                fontSize: '20px',
                                formatter: (v) => (typeof v === 'number' ? v.toFixed(1) : v) + '%'
                            }
                        }
                    }
                },
                stroke: {
                    lineCap: 'round'
                },
                responsive: [{
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 180
                        }
                    }
                }]
            });
            charts.util.render();
        }

        // Range switcher
        const group = document.getElementById('rangeSwitcher');
        group?.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-range]');
            if (!btn) return;
            [...group.querySelectorAll('.btn-toggle')].forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            buildAll(btn.dataset.range);
        });
        document.getElementById('btnRefresh')?.addEventListener('click', () => {
            buildAll(group.querySelector('.btn-toggle.active')?.dataset.range || '12m');
        });

        // Initial
        buildAll('12m');

        // Rebuild charts on resize (debounced)
        let __rz;
        window.addEventListener('resize', () => {
            clearTimeout(__rz);
            __rz = setTimeout(() => {
                const active = (document.getElementById('rangeSwitcher')?.querySelector(
                    '.btn-toggle.active')?.dataset.range) || '12m';
                buildAll(active);
            }, 180);
        });
    </script>
@endsection
