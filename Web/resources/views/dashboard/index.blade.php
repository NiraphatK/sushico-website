@extends('home')

@section('css_before')
    <style>
        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 1.5rem;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.9;
        }

        .stat-title {
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 600;
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
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            background: #fff;
        }
    </style>
@endsection

@section('header')
    <h4 class="fw-semibold mb-4">📊 Dashboard Overview</h4>
@endsection

@section('sidebarMenu')
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container-fluid">
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

        {{-- Chart --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="chart-card">
                    <h5 class="fw-semibold mb-3">📈 จำนวนการเข้าชมเว็บไซต์แยกตามเดือน</h5>
                    <canvas id="visitsChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('visitsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($label) !!},
                datasets: [{
                    label: 'จำนวนเข้าชมเว็บไซต์ (12 เดือนล่าสุด)',
                    data: {!! json_encode($data) !!},
                    borderColor: 'rgba(102,126,234,1)',
                    backgroundColor: 'rgba(102,126,234,0.2)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 5,
                    pointBackgroundColor: '#667eea',
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection

@section('footer')
@endsection
