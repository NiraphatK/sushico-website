@extends('home')

@section('css_before')
    <style>
        .stat-card {
            border: none;
            border-radius: 18px;
            padding: 1.8rem;
            color: #fff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            font-size: 2.8rem;
            opacity: 0.9;
        }

        .stat-title {
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #f00000, #dc281e);
        }

        .chart-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            background: #fff;
        }
    </style>
@endsection

@section('header')
    <h4 class="fw-bold mb-4">📊 Dashboard Overview</h4>
@endsection

@section('content')
    {{-- ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="container-fluid">
        {{-- Stats --}}
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stat-card bg-gradient-info d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title">Reservations</div>
                        <p class="stat-value">{{ number_format($countReservation, 0) }}</p>
                    </div>
                    <i class="bi bi-calendar-check stat-icon"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-gradient-primary d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title">Menus</div>
                        <p class="stat-value">{{ number_format($countMenu, 0) }}</p>
                    </div>
                    <i class="bi bi-journal-text stat-icon"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-gradient-success d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title">Users</div>
                        <p class="stat-value">{{ number_format($countUser, 0) }}</p>
                    </div>
                    <i class="bi bi-people stat-icon"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-gradient-danger d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title">Views</div>
                        <p class="stat-value">{{ number_format($countView, 0) }}</p>
                    </div>
                    <i class="bi bi-eye stat-icon"></i>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="row mt-4 g-4">
            <div class="col-md-8">
                <div class="chart-card">
                    <h5 class="fw-semibold mb-3">📈 Website Visits (Last 12 Months)</h5>
                    <div id="visitsChart"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="chart-card">
                    <h5 class="fw-semibold mb-3">🍣 Reservations by Status</h5>
                    <div id="reservationStatusChart"></div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-md-6">
                <div class="chart-card">
                    <h5 class="fw-semibold mb-3">👥 Average Party Size per Month</h5>
                    <div id="partySizeChart"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card">
                    <h5 class="fw-semibold mb-3">👥 User Growth</h5>
                    <div id="userChart"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Website Visits (Line/Area)
        new ApexCharts(document.querySelector("#visitsChart"), {
            chart: {
                type: 'area',
                height: 320,
                toolbar: {
                    show: false
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#667eea'],
            series: [{
                name: 'Visits',
                data: {!! json_encode($data) !!}
            }],
            xaxis: {
                categories: {!! json_encode($label) !!}
            },
            fill: {
                type: "gradient",
                gradient: {
                    opacityFrom: 0.5,
                    opacityTo: 0.1
                }
            }
        }).render();

        // Reservations by Status (Donut)
        new ApexCharts(document.querySelector("#reservationStatusChart"), {
            chart: {
                type: 'donut',
                height: 300
            },
            labels: ['Confirmed', 'Seated', 'Completed', 'Cancelled', 'No Show'],
            series: {!! json_encode($reservationStatusData) !!},
            colors: ['#36d1dc', '#38ef7d', '#9ca3af', '#f87171', '#6b7280'],
            legend: {
                position: 'bottom'
            }
        }).render();

        // Average Party Size per Month (Bar)
        new ApexCharts(document.querySelector("#partySizeChart"), {
            chart: {
                type: 'bar',
                height: 320
            },
            series: [{
                name: 'Avg Party Size',
                data: {!! json_encode($avgPartySizeData) !!}
            }],
            xaxis: {
                categories: {!! json_encode($avgPartySizeLabel) !!}
            },
            colors: ['#f59e0b'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '45%'
                }
            },
            dataLabels: {
                enabled: true
            }
        }).render();


        // User Growth (Column)
        new ApexCharts(document.querySelector("#userChart"), {
            chart: {
                type: 'bar',
                height: 320
            },
            series: [{
                name: 'Users',
                data: {!! json_encode($userGrowthData) !!}
            }],
            xaxis: {
                categories: {!! json_encode($userGrowthLabel) !!}
            },
            colors: ['#38ef7d'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '45%'
                }
            }
        }).render();
    </script>
@endsection
