@extends('home')

@section('css_before')
    <style>
        body {
            background: #f8fafc;
            font-family: "Inter", "Poppins", sans-serif;
        }

        .card-custom {
            border: none;
            border-radius: 18px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            background: #fff;
            padding: 2rem;
        }

        .card-custom h1 {
            font-size: 1.7rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .btn-add {
            border: none;
            border-radius: 30px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #0d6efd, #2563eb);
            color: #fff;
            transition: all 0.25s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            color: #fff;
        }

        .table-custom {
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .table-custom thead {
            background: #f1f5f9;
            color: #334155;
        }

        .table-custom thead th {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .table-custom tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .table-custom tbody tr:hover {
            background: #eef6ff !important;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.9em;
            border-radius: 30px;
            font-weight: 500;
        }

        .badge-status-confirmed {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            color: #fff;
        }

        .badge-status-seated {
            background: linear-gradient(135deg, #34d399, #059669);
            color: #fff;
        }

        .badge-status-completed {
            background: linear-gradient(135deg, #9ca3af, #4b5563);
            color: #fff;
        }

        .badge-status-cancelled {
            background: linear-gradient(135deg, #f87171, #dc2626);
            color: #fff;
        }

        .badge-status-no-show {
            background: linear-gradient(135deg, #6b7280, #111827);
            color: #fff;
        }

        .badge-seat-bar {
            background: linear-gradient(135deg, #7c3aed, #4c1d95);
            color: #fff;
        }

        .badge-seat-table {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            color: #fff;
        }

        .btn-action {
            border: none;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            margin: 0 2px;
            transition: 0.2s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-edit {
            background: #facc15;
            color: #fff;
        }

        .btn-delete {
            background: #ef4444;
            color: #fff;
        }

        .btn-checkin {
            background: #22c55e;
            color: #fff;
        }

        .pagination {
            justify-content: center;
            margin-top: 1.5rem;
        }

        .pagination .page-link {
            border-radius: 10px;
            margin: 0 3px;
            color: #0d6efd;
        }

        .pagination .active .page-link {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">

                <div class="card card-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="mx-auto">Reservation Management</h1>
                        <a href="{{ url('reservations/adding') }}" class="btn-add btn">
                            <i class="bi bi-calendar-plus"></i> Add Reservation
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-custom align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Party Size</th>
                                        <th>Seat Type</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Status</th>
                                        <th>Table</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ReservationList as $r)
                                        <tr>
                                            <td>{{ $r->reservation_id }}</td>
                                            <td>{{ $r->user->full_name ?? '-' }}</td>
                                            <td><span class="badge bg-primary">{{ $r->party_size }}</span></td>
                                            <td>
                                                @if ($r->seat_type == 'BAR')
                                                    <span class="badge badge-seat-bar">Bar Counter</span>
                                                @else
                                                    <span class="badge badge-seat-table">Dining Table</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($r->start_at)->format('d/m/Y H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($r->end_at)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @switch($r->status)
                                                    @case('CONFIRMED')
                                                        <span class="badge badge-status-confirmed">Confirmed</span>
                                                    @break

                                                    @case('SEATED')
                                                        <span class="badge badge-status-seated">Seated</span>
                                                    @break

                                                    @case('COMPLETED')
                                                        <span class="badge badge-status-completed">Completed</span>
                                                    @break

                                                    @case('CANCELLED')
                                                        <span class="badge badge-status-cancelled">Cancelled</span>
                                                    @break

                                                    @case('NO_SHOW')
                                                        <span class="badge badge-status-no-show">No Show</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>{{ $r->table->table_number ?? '-' }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">

                                                    {{-- Edit --}}
                                                    <a href="{{ url('reservations/' . $r->reservation_id) }}"
                                                        class="btn-action btn-edit" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    {{-- Delete --}}
                                                    <button type="button" class="btn-action btn-delete"
                                                        onclick="deleteConfirm({{ $r->reservation_id }})" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $r->reservation_id }}"
                                                        action="{{ url('reservations/remove/' . $r->reservation_id) }}"
                                                        method="POST" style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                    {{-- Check-in --}}
                                                    @if ($r->status == 'CONFIRMED')
                                                        <form
                                                            action="{{ url('reservations/checkin/' . $r->reservation_id) }}"
                                                            method="POST"
                                                            onsubmit="event.preventDefault(); checkinConfirm(this, '{{ $r->user->full_name ?? '-' }}', '{{ \Carbon\Carbon::parse($r->start_at)->format('d/m/Y H:i') }}')">
                                                            @csrf
                                                            <button type="submit" class="btn-action btn-checkin"
                                                                title="Check-in">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-muted">No reservations yet</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $ReservationList->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endsection

    @section('js_before')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function deleteConfirm(id) {
                Swal.fire({
                    title: 'แน่ใจหรือไม่?',
                    text: "คุณต้องการลบการจองนี้จริง ๆ หรือไม่",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            }

            function checkinConfirm(form, user, time) {
                Swal.fire({
                    title: 'แน่ใจหรือไม่?',
                    html: `ต้องการจะเช็กอินคุณ <b>${user}</b><br>เวลา <b>${time}</b> หรือไม่`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#22c55e',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'ใช่, เช็กอิน!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        </script>
    @endsection
