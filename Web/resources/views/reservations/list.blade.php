@extends('home')

@section('css_before')
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root{
            --surface:#ffffff;
            --bg:#f7f9fc;
            --line:#e5e7eb;
            --text:#0f172a;
            --muted:#64748b;
            --primary:#2563eb;
            --primary-2:#0d6efd;
            --primary-soft:#eef6ff;
            --radius-lg:18px;
            --radius-md:14px;
            --radius-sm:10px;
            --shadow-md:0 6px 20px rgba(2,6,23,.06);
            --shadow-lg:0 12px 36px rgba(2,6,23,.10);
        }
        body{background:var(--bg);font-family:"Inter","Poppins","Noto Sans Thai",system-ui,sans-serif;color:var(--text)}
        .card-wrap{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-md);padding:1.2rem 1.2rem 1rem}
        .page-title{font-weight:800;font-size:clamp(1.25rem,2vw,1.8rem);color:#1e293b;letter-spacing:.2px}

        /* Filters */
        .filters{display:grid;grid-template-columns:3fr 1fr 1fr 1fr auto;gap:.6rem;align-items:stretch}
        .filters-actions{display:flex;gap:.5rem;align-items:stretch}
        .filters-actions .btn-pill{height:100%;display:flex;align-items:center;justify-content:center}
        @media (max-width: 767.98px){
            .filters{grid-template-columns:1fr}
            .filters-actions{justify-content:stretch}
            .filters-actions .btn-pill{flex:1}
        }

        /* FAB (มือถือ) */
        .has-fab{padding-bottom:calc(80px + env(safe-area-inset-bottom))}
        @media (min-width:768px){.has-fab{padding-bottom:0}}
        .fab-add{position:fixed;right:20px;bottom:calc(20px + env(safe-area-inset-bottom));z-index:999;width:56px;height:56px;display:inline-flex;align-items:center;justify-content:center;border-radius:999px;background:linear-gradient(135deg,var(--primary-2),var(--primary));color:#fff;box-shadow:0 12px 28px rgba(37,99,235,.35),0 2px 6px rgba(0,0,0,.12);border:none;transition:transform .15s ease,box-shadow .2s ease,filter .2s ease}
        .fab-add:hover{transform:translateY(-2px);filter:brightness(1.05)}
        .fab-add:focus{outline:none;box-shadow:0 0 0 4px rgba(13,110,253,.18)}
        .fab-add i{font-size:1.35rem;line-height:1}

        /* Inputs & Buttons */
        .inp{appearance:none;border-radius:999px;border:1px solid var(--line);padding-left:1rem;height:42px;background:#fff;width:100%;transition:.2s}
        .inp:focus{border-color:rgba(13,110,253,.65);box-shadow:0 0 0 4px rgba(13,110,253,.12);outline:none}
        .btn-pill{border-radius:999px;border:1px solid var(--line);height:42px;display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1rem;font-weight:600;line-height:1;white-space:nowrap;transition:all .25s ease}
        .btn-primary-soft{background:linear-gradient(135deg,var(--primary-2),var(--primary));color:#fff;border-color:transparent;box-shadow:var(--shadow-md)}
        .btn-primary-soft:hover{transform:translateY(-1px);color:#fff;box-shadow:var(--shadow-lg)}
        .btn-ghost{background:#fff;color:var(--muted)}
        .btn-ghost:hover{background:#f1f5f9;color:#0f172a}

        /* Summary */
        .summary{display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap;padding:.5rem 0 0}
        .chip{background:var(--primary-soft);color:var(--primary);border-radius:999px;padding:.35rem .7rem;font-weight:700;font-size:.85rem}

        /* Content card + table */
        .card-custom{border:none;border-radius:var(--radius-lg);box-shadow:var(--shadow-md);padding:1rem;background:#fff;margin-top:.9rem}
        .table-custom{border-radius:var(--radius-md);overflow:hidden;border:1px solid var(--line)}
        .table-custom thead{background:#f1f5f9;color:#334155}
        .table-custom thead th{font-weight:700;font-size:.86rem;text-transform:uppercase;letter-spacing:.35px}
        .table-custom tbody tr:nth-child(even){background:#fafbfc}
        .table-custom tbody tr:hover{background:var(--primary-soft)!important}

        /* Badges */
        .badge{font-size:.78rem;padding:.45em .9em;border-radius:999px;font-weight:600}
        .badge-status-confirmed{background:linear-gradient(135deg,#60a5fa,#2563eb);color:#fff}
        .badge-status-seated{background:linear-gradient(135deg,#34d399,#059669);color:#fff}
        .badge-status-completed{background:linear-gradient(135deg,#9ca3af,#4b5563);color:#fff}
        .badge-status-cancelled{background:linear-gradient(135deg,#f87171,#dc2626);color:#fff}
        .badge-status-no-show{background:linear-gradient(135deg,#6b7280,#111827);color:#fff}

        .badge-seat-bar{background:linear-gradient(135deg,#7c3aed,#4c1d95);color:#fff}
        .badge-seat-table{background:linear-gradient(135deg,#60a5fa,#2563eb);color:#fff}

        /* Actions */
        .btn-action{border:none;border-radius:50%;width:36px;height:36px;display:inline-flex;align-items:center;justify-content:center;font-size:1rem;margin:0 2px;transition:.2s}
        .btn-action:hover{transform:translateY(-2px);filter:brightness(1.05)}
        .btn-edit{background:#facc15;color:#fff}
        .btn-delete{background:#ef4444;color:#fff}
        .btn-checkin{background:#22c55e;color:#fff}

        /* Pagination */
        .pagination{justify-content:center;margin-top:1rem}
        .pagination .page-link{border-radius:10px;margin:0 3px;color:var(--primary-2)}
        .pagination .active .page-link{background:var(--primary-2);border-color:var(--primary-2);color:#fff}

        /* Mobile Card list */
        .u-card{border:1px solid var(--line);border-radius:var(--radius-md);padding:.9rem;background:#fff;box-shadow:0 2px 10px rgba(2,6,23,.03)}
        .u-card+.u-card{margin-top:.75rem}
        .u-head{display:flex;align-items:center;justify-content:space-between;gap:.5rem}
        .u-name{font-weight:700;font-size:1rem;color:#0f172a}
        .u-sub{color:var(--muted);font-size:.9rem}
        .u-meta{display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:.4rem;margin-top:.5rem}
        .u-actions{display:flex;align-items:center;gap:.35rem;justify-content:flex-end;margin-top:.6rem}

        @media (max-width: 575.98px){
            .card-wrap,.card-custom{padding:.9rem}
        }
    </style>
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
                <button class="btn-pill btn-primary-soft" id="btnSearch" type="button">
                    <i class="bi bi-search"></i> Search
                </button>
                <button class="btn-pill btn-ghost" id="btnClear" type="button">
                    <i class="bi bi-x-circle"></i> Clear
                </button>
            </div>
        </div>

        <div class="summary">
            <div class="small text-secondary">
                @php
                    $count = $ReservationList->total();
                    $from = $ReservationList->firstItem();
                    $to   = $ReservationList->lastItem();
                @endphp
                <span id="serverRange">{{ $count ? "Showing {$from}-{$to} of {$count} reservations" : 'No reservations yet' }}</span>
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
                                'seated'    => 'badge-status-seated',
                                'completed' => 'badge-status-completed',
                                'cancelled' => 'badge-status-cancelled',
                                'no_show'   => 'badge-status-no-show',
                            ];
                            $statusClass = $statusBadgeMap[$statusKey] ?? 'bg-secondary';

                            $seatClass = $seat === 'bar' ? 'badge-seat-bar' : 'badge-seat-table';

                            $searchBlob = strtolower(
                                trim(
                                    ($r->user->full_name ?? '') . ' ' .
                                    ($r->user->email ?? '') . ' ' .
                                    ($r->user->phone ?? '') . ' ' .
                                    ($r->reservation_id ?? '')
                                )
                            );

                            $start = \Carbon\Carbon::parse($r->start_at);
                            $end   = \Carbon\Carbon::parse($r->end_at);
                            $now   = \Carbon\Carbon::now();
                            $timeTag = $start->isSameDay($now) ? 'today' : ($start->greaterThan($now) ? 'future' : 'past');
                        @endphp
                        <tr class="js-res-row"
                            data-status="{{ $statusKey }}"
                            data-seat="{{ $seat }}"
                            data-time="{{ $timeTag }}"
                            data-search="{{ e($searchBlob) }}">
                            <td class="text-center fw-bold">{{ $ReservationList->firstItem() + $loop->index }}</td>
                            <td class="fw-semibold text-start">
                                {{ $r->user->full_name ?? '-' }}<br>
                                <span class="text-muted small">{{ $r->user->phone ?? '-' }}</span>
                            </td>
                            <td><span class="badge bg-primary">{{ $r->party_size }}</span></td>
                            <td><span class="badge {{ $seatClass }}">{{ strtoupper($r->seat_type) }}</span></td>
                            <td>{{ $start->format('d/m/Y H:i') }}</td>
                            <td>{{ $end->format('d/m/Y H:i') }}</td>
                            <td><span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_',' ', $statusKey)) }}</span></td>
                            <td>
                                @if($r->table && $r->table->table_number)
                                    <span class="chip" style="font-weight:600">{{ $r->table->table_number }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ url('reservations/'.$r->reservation_id) }}" class="btn-action btn-edit" data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete" data-bs-toggle="tooltip" title="Delete"
                                            onclick="deleteConfirm({{ $r->reservation_id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $r->reservation_id }}" action="{{ url('reservations/remove/'.$r->reservation_id) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>

                                    @if (strtoupper($r->status) === 'CONFIRMED')
                                        <form action="{{ url('reservations/checkin/'.$r->reservation_id) }}" method="POST"
                                              onsubmit="event.preventDefault(); checkinConfirm(this, '{{ $r->user->full_name ?? '-' }}', '{{ $start->format('d/m/Y H:i') }}')">
                                            @csrf
                                            <button type="submit" class="btn-action btn-checkin" data-bs-toggle="tooltip" title="Check-in">
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
                    'seated'    => 'badge-status-seated',
                    'completed' => 'badge-status-completed',
                    'cancelled' => 'badge-status-cancelled',
                    'no_show'   => 'badge-status-no-show',
                ];
                $statusClass = $statusBadgeMap[$statusKey] ?? 'bg-secondary';
                $seatClass = $seat === 'bar' ? 'badge-seat-bar' : 'badge-seat-table';

                $searchBlob = strtolower(
                    trim(
                        ($r->user->full_name ?? '') . ' ' .
                        ($r->user->email ?? '') . ' ' .
                        ($r->user->phone ?? '') . ' ' .
                        ($r->reservation_id ?? '')
                    )
                );

                $start = \Carbon\Carbon::parse($r->start_at);
                $end   = \Carbon\Carbon::parse($r->end_at);
                $now   = \Carbon\Carbon::now();
                $timeTag = $start->isSameDay($now) ? 'today' : ($start->greaterThan($now) ? 'future' : 'past');
            @endphp
            <div class="u-card js-res-card"
                 data-status="{{ $statusKey }}"
                 data-seat="{{ $seat }}"
                 data-time="{{ $timeTag }}"
                 data-search="{{ e($searchBlob) }}">
                <div class="u-head">
                    <div>
                        <div class="u-name">{{ $r->user->full_name ?? '-' }}</div>
                        <div class="u-sub">
                            <i class="bi bi-telephone me-1"></i>{{ $r->user->phone ?? '-' }}
                            <span class="ms-2"><i class="bi bi-people me-1"></i>{{ $r->party_size }}</span>
                        </div>
                    </div>
                    <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_',' ', $statusKey)) }}</span>
                </div>

                <div class="u-meta">
                    <span class="chip"><i class="bi bi-clock me-1"></i>{{ $start->format('d/m/Y H:i') }}</span>
                    <span class="chip"><i class="bi bi-clock-history me-1"></i>{{ $end->format('d/m/Y H:i') }}</span>
                    <span class="chip {{ $seatClass }}">{{ strtoupper($r->seat_type) }}</span>
                    @if($r->table && $r->table->table_number)
                        <span class="chip"><i class="bi bi-grid-3x3-gap me-1"></i>{{ $r->table->table_number }}</span>
                    @endif
                </div>

                <div class="u-actions">
                    <a href="{{ url('reservations/'.$r->reservation_id) }}" class="btn-action btn-edit" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" class="btn-action btn-delete" title="Delete"
                            onclick="deleteConfirm({{ $r->reservation_id }})">
                        <i class="bi bi-trash"></i>
                    </button>
                    <form id="delete-form-{{ $r->reservation_id }}" action="{{ url('reservations/remove/'.$r->reservation_id) }}" method="POST" style="display:none;">
                        @csrf @method('DELETE')
                    </form>

                    @if (strtoupper($r->status) === 'CONFIRMED')
                        <form action="{{ url('reservations/checkin/'.$r->reservation_id) }}" method="POST"
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert ลบ
    function deleteConfirm(id){
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

    function checkinConfirm(form, user, time){
        Swal.fire({
            title: 'เช็กอินลูกค้า?',
            html: `ต้องการจะเช็กอิน <b>${user}</b><br>เวลา <b>${time}</b> หรือไม่`,
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

    // Tooltip
    document.addEventListener('DOMContentLoaded', () => {
        const tList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tList.map(el => new bootstrap.Tooltip(el));
    });

    // ------- Client-side Search & Filters -------
    (function(){
        const q       = document.getElementById('q');
        const seat    = document.getElementById('seat');
        const status  = document.getElementById('status');
        const timeSel = document.getElementById('time');
        const btnSearch = document.getElementById('btnSearch');
        const btnClear  = document.getElementById('btnClear');

        const rows  = Array.from(document.querySelectorAll('.js-res-row'));
        const cards = Array.from(document.querySelectorAll('.js-res-card'));

        const noResultTable = document.getElementById('noResultTable');
        const noResultCards = document.getElementById('noResultCards');
        const clientInfo    = document.getElementById('clientInfo');

        function match(item){
            const kw     = (q.value || '').trim().toLowerCase();
            const fSeat  = seat.value.toLowerCase();
            const fStat  = status.value.toLowerCase();
            const fTime  = timeSel.value.toLowerCase();

            if (kw && !item.dataset.search.includes(kw)) return false;
            if (fSeat  && fSeat  !== item.dataset.seat)   return false;
            if (fStat  && fStat  !== item.dataset.status) return false;
            if (fTime  && fTime  !== item.dataset.time)   return false;
            return true;
        }

        function apply(){
            const kw     = (q.value || '').trim().toLowerCase();
            const fSeat  = seat.value.toLowerCase();
            const fStat  = status.value.toLowerCase();
            const fTime  = timeSel.value.toLowerCase();

            let shownTable = 0, shownCards = 0;

            rows.forEach(tr => {
                const ok = match(tr);
                tr.style.display = ok ? '' : 'none';
                if (ok) shownTable++;
            });

            cards.forEach(card => {
                const ok = match(card);
                card.style.display = ok ? '' : 'none';
                if (ok) shownCards++;
            });

            if (rows.length)  noResultTable.classList.toggle('d-none', shownTable !== 0);
            if (cards.length) noResultCards.classList.toggle('d-none', shownCards !== 0);

            const hasClientFilter = !!(kw || fSeat || fStat || fTime);
            clientInfo.classList.toggle('d-none', !hasClientFilter);
            if (hasClientFilter){
                const parts = [];
                if (kw)    parts.push(`q: "${kw}"`);
                if (fSeat) parts.push(`seat: ${fSeat}`);
                if (fStat) parts.push(`status: ${fStat}`);
                if (fTime) parts.push(`time: ${fTime}`);
                clientInfo.textContent = `${shownTable || shownCards} matched (${parts.join(', ')})`;
            }
        }

        // events
        let timer = null;
        q.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(apply, 350); });
        [seat, status, timeSel].forEach(el => el.addEventListener('change', apply));
        btnSearch.addEventListener('click', apply);
        q.addEventListener('keydown', e => { if (e.key === 'Enter'){ e.preventDefault(); apply(); } });

        btnClear.addEventListener('click', () => {
            q.value = ''; seat.value = ''; status.value = ''; timeSel.value = '';
            apply();
        });
    })();
</script>
@endsection
