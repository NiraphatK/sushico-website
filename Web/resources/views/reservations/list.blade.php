@extends('home')

@section('css_before')
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
@endsection

@section('content')
    <div class="container py-3">
        {{-- Header + Filters --}}
        <div class="card-wrap mb-3">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <h1 class="page-title m-0">Reservation Management</h1>
                <a href="{{ url('reservations/adding') }}" class="btn-pill btn-primary-soft d-none d-md-inline-flex btn">
                    <i class="bi bi-calendar-plus"></i> Add Reservation
                </a>
            </div>

            {{-- Floating Add button (มือถือ) --}}
            <div class="container py-3 has-fab">
                <a href="{{ url('reservations/adding') }}" class="fab-add d-md-none" aria-label="Add Reservation"
                    data-bs-toggle="tooltip" title="Add Reservation">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>

            <div class="filters" id="filters">
                <div class="position-relative">
                    <input type="text" class="inp" id="q" placeholder="Search customer, phone…">
                </div>

                <div class="dropdown-wrap position-relative">
                    <select class="inp w-100 pe-5" id="seat">
                        <option value="">All seat types</option>
                        <option value="bar">Bar</option>
                        <option value="table">Table</option>
                    </select>
                    <i class="bi bi-caret-down-fill position-absolute end-0 top-50 translate-middle-y me-3 text-muted"></i>
                </div>

                <div class="dropdown-wrap position-relative">
                    <select class="inp w-100 pe-5" id="status">
                        <option value="">All status</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="seated">Seated</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="no_show">No Show</option>
                    </select>
                    <i class="bi bi-caret-down-fill position-absolute end-0 top-50 translate-middle-y me-3 text-muted"></i>
                </div>

                <div class="dropdown-wrap position-relative">
                    <select class="inp w-100 pe-5" id="time">
                        <option value="">All time</option>
                        <option value="today">Today</option>
                        <option value="future">Upcoming</option>
                        <option value="past">Past</option>
                    </select>
                    <i class="bi bi-caret-down-fill position-absolute end-0 top-50 translate-middle-y me-3 text-muted"></i>
                </div>

                <div class="filters-actions">
                    <button class="btn-search" id="btnSearch" type="button">
                        <i class="bi bi-search"></i> Search
                    </button>
                    <button class="btn-clear" id="btnClear" type="button">
                        <i class="bi bi-x-circle"></i> Clear
                    </button>
                </div>
            </div>

            <div class="summary">
                <div class="small text-secondary">
                    @php
                        $count = $ReservationList->total();
                        $from = $ReservationList->firstItem();
                        $to = $ReservationList->lastItem();
                    @endphp
                    <span
                        id="serverRange">{{ $count ? "Showing {$from}-{$to} of {$count} reservations" : 'No reservations yet' }}</span>
                    <span id="clientInfo" class="ms-2 d-none chip"></span>
                </div>
            </div>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        {{-- Desktop/Tablet: Table --}}
        <div class="card-custom d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle text-center" id="resTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="6%">No.</th>
                            <th width="18%">Customer</th>
                            <th width="10%">Party</th>
                            <th width="12%">Seat</th>
                            <th width="15%">Start</th>
                            <th width="15%">End</th>
                            <th width="12%">Status</th>
                            <th width="12%">Table</th>
                            <th class="text-center" width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="resTbody">
                        @forelse ($ReservationList as $r)
                            @php
                                $seat = strtolower($r->seat_type ?? '');
                                $status = strtolower($r->status ?? '');
                                $statusKey = str_replace('-', '_', $status);

                                $statusBadgeMap = [
                                    'confirmed' => 'badge-status-confirmed',
                                    'seated' => 'badge-status-seated',
                                    'completed' => 'badge-status-completed',
                                    'cancelled' => 'badge-status-cancelled',
                                    'no_show' => 'badge-status-no-show',
                                ];
                                $statusClass = $statusBadgeMap[$statusKey] ?? 'bg-secondary';

                                $seatClass = $seat === 'bar' ? 'badge-seat-bar' : 'badge-seat-table';

                                $searchBlob = strtolower(
                                    trim(
                                        ($r->user->full_name ?? '') .
                                            ' ' .
                                            ($r->user->email ?? '') .
                                            ' ' .
                                            ($r->user->phone ?? '') .
                                            ' ' .
                                            ($r->reservation_id ?? ''),
                                    ),
                                );

                                $start = \Carbon\Carbon::parse($r->start_at);
                                $end = \Carbon\Carbon::parse($r->end_at);
                                $now = \Carbon\Carbon::now();
                                $timeTag = $start->isSameDay($now)
                                    ? 'today'
                                    : ($start->greaterThan($now)
                                        ? 'future'
                                        : 'past');
                            @endphp
                            <tr class="js-res-row" data-status="{{ $statusKey }}" data-seat="{{ $seat }}"
                                data-time="{{ $timeTag }}" data-search="{{ e($searchBlob) }}">
                                <td class="text-center fw-bold">{{ $ReservationList->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold text-start">
                                    {{ $r->user->full_name ?? '-' }}<br>
                                    <span class="text-muted small">{{ $r->user->phone ?? '-' }}</span>
                                </td>
                                <td><span class="badge bg-primary">{{ $r->party_size }}</span></td>
                                <td><span class="badge {{ $seatClass }}">{{ strtoupper($r->seat_type) }}</span></td>
                                <td>{{ $start->format('d/m/Y H:i') }}</td>
                                <td>{{ $end->format('d/m/Y H:i') }}</td>
                                <td><span
                                        class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $statusKey)) }}</span>
                                </td>
                                <td>
                                    @if ($r->table && $r->table->table_number)
                                        <span class="chip" style="font-weight:600">{{ $r->table->table_number }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ url('reservations/' . $r->reservation_id) }}"
                                            class="btn-action btn-edit" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn-action btn-delete" data-bs-toggle="tooltip"
                                            title="Delete" onclick="deleteConfirm({{ $r->reservation_id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $r->reservation_id }}"
                                            action="{{ url('reservations/remove/' . $r->reservation_id) }}"
                                            method="POST" style="display:none;">
                                            @csrf @method('DELETE')
                                        </form>

                                        @if (strtoupper($r->status) === 'CONFIRMED')
                                            <form action="{{ url('reservations/checkin/' . $r->reservation_id) }}"
                                                method="POST"
                                                onsubmit="event.preventDefault(); checkinConfirm(this, '{{ $r->user->full_name ?? '-' }}', '{{ $start->format('d/m/Y H:i') }}')">
                                                @csrf
                                                <button type="submit" class="btn-action btn-checkin"
                                                    data-bs-toggle="tooltip" title="Check-in">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No reservations yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $ReservationList->links() }}
            <div id="noResultTable" class="text-center text-muted py-4 d-none">No results matched your filters</div>
        </div>

        {{-- Mobile: Card list --}}
        <div class="d-md-none" id="resCards">
            @forelse ($ReservationList as $r)
                @php
                    $seat = strtolower($r->seat_type ?? '');
                    $status = strtolower($r->status ?? '');
                    $statusKey = str_replace('-', '_', $status);

                    $statusBadgeMap = [
                        'confirmed' => 'badge-status-confirmed',
                        'seated' => 'badge-status-seated',
                        'completed' => 'badge-status-completed',
                        'cancelled' => 'badge-status-cancelled',
                        'no_show' => 'badge-status-no-show',
                    ];
                    $statusClass = $statusBadgeMap[$statusKey] ?? 'bg-secondary';
                    $seatClass = $seat === 'bar' ? 'badge-seat-bar' : 'badge-seat-table';

                    $searchBlob = strtolower(
                        trim(
                            ($r->user->full_name ?? '') .
                                ' ' .
                                ($r->user->email ?? '') .
                                ' ' .
                                ($r->user->phone ?? '') .
                                ' ' .
                                ($r->reservation_id ?? ''),
                        ),
                    );

                    $start = \Carbon\Carbon::parse($r->start_at);
                    $end = \Carbon\Carbon::parse($r->end_at);
                    $now = \Carbon\Carbon::now();
                    $timeTag = $start->isSameDay($now) ? 'today' : ($start->greaterThan($now) ? 'future' : 'past');
                @endphp
                <div class="u-card js-res-card" data-status="{{ $statusKey }}" data-seat="{{ $seat }}"
                    data-time="{{ $timeTag }}" data-search="{{ e($searchBlob) }}">
                    <div class="u-head">
                        <div>
                            <div class="u-name">{{ $r->user->full_name ?? '-' }}</div>
                            <div class="u-sub">
                                <i class="bi bi-telephone me-1"></i>{{ $r->user->phone ?? '-' }}
                                <span class="ms-2"><i class="bi bi-people me-1"></i>{{ $r->party_size }}</span>
                            </div>
                        </div>
                        <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $statusKey)) }}</span>
                    </div>

                    <div class="u-meta">
                        <span class="chip"><i class="bi bi-clock me-1"></i>{{ $start->format('d/m/Y H:i') }}</span>
                        <span class="chip"><i
                                class="bi bi-clock-history me-1"></i>{{ $end->format('d/m/Y H:i') }}</span>
                        <span class="chip {{ $seatClass }}">{{ strtoupper($r->seat_type) }}</span>
                        @if ($r->table && $r->table->table_number)
                            <span class="chip"><i
                                    class="bi bi-grid-3x3-gap me-1"></i>{{ $r->table->table_number }}</span>
                        @endif
                    </div>

                    <div class="u-actions">
                        <a href="{{ url('reservations/' . $r->reservation_id) }}" class="btn-action btn-edit"
                            title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" class="btn-action btn-delete" title="Delete"
                            onclick="deleteConfirm({{ $r->reservation_id }})">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="delete-form-{{ $r->reservation_id }}"
                            action="{{ url('reservations/remove/' . $r->reservation_id) }}" method="POST"
                            style="display:none;">
                            @csrf @method('DELETE')
                        </form>

                        @if (strtoupper($r->status) === 'CONFIRMED')
                            <form action="{{ url('reservations/checkin/' . $r->reservation_id) }}" method="POST"
                                onsubmit="event.preventDefault(); checkinConfirm(this, '{{ $r->user->full_name ?? '-' }}', '{{ $start->format('d/m/Y H:i') }}')">
                                @csrf
                                <button type="submit" class="btn-action btn-checkin" title="Check-in">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="u-card text-center text-muted">No reservations yet</div>
            @endforelse

            <div class="mt-2">
                {{ $ReservationList->links() }}
            </div>

            <div id="noResultCards" class="text-center text-muted py-3 d-none">No results matched your filters</div>
        </div>
    </div>
@endsection

@section('js_before')
    <script src="{{ asset('js/reservation.js') }}"></script>
@endsection
