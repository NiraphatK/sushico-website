@extends('home')

@section('css_before')
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --surface: #ffffff;
            --bg: #f7f9fc;
            --line: #e5e7eb;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #2563eb;
            --primary-2: #0d6efd;
            --primary-soft: #eef6ff;
            --radius-lg: 18px;
            --radius-md: 14px;
            --radius-sm: 10px;
            --shadow-md: 0 6px 20px rgba(2, 6, 23, .06);
            --shadow-lg: 0 12px 36px rgba(2, 6, 23, .10);
        }

        body {
            background: var(--bg);
            font-family: "Inter", "Poppins", "Noto Sans Thai", system-ui, sans-serif;
            color: var(--text);
        }

        .card-wrap {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 1.2rem 1.2rem 1rem;
        }

        .page-title {
            font-weight: 800;
            font-size: clamp(1.25rem, 2vw, 1.8rem);
            color: #1e293b;
            letter-spacing: .2px;
        }

        /* Filters */
        .filters {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr auto;
            gap: .6rem;
            align-items: stretch;
        }

        .filters-actions {
            display: flex;
            gap: .5rem;
            align-items: stretch;
        }

        .filters-actions .btn-pill {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 767.98px) {
            .filters {
                grid-template-columns: 1fr;
            }

            .filters-actions {
                justify-content: stretch;
            }

            .filters-actions .btn-pill {
                flex: 1;
            }
        }

        /* Floating Add (mobile) */
        .has-fab {
            padding-bottom: calc(80px + env(safe-area-inset-bottom));
        }

        @media (min-width: 768px) {
            .has-fab {
                padding-bottom: 0;
            }
        }

        .fab-add {
            position: fixed;
            right: 20px;
            bottom: calc(20px + env(safe-area-inset-bottom));
            z-index: 999;
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--primary-2), var(--primary));
            color: #fff;
            box-shadow: 0 12px 28px rgba(37, 99, 235, .35), 0 2px 6px rgba(0, 0, 0, .12);
            border: none;
            transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
        }

        .fab-add:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
        }

        .fab-add:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, .18);
        }

        .fab-add i {
            font-size: 1.35rem;
            line-height: 1;
        }

        /* Inputs & Buttons */
        .inp {
            appearance: none;
            border-radius: 999px;
            border: 1px solid var(--line);
            padding-left: 1rem;
            height: 42px;
            background: #fff;
            width: 100%;
            transition: .2s;
        }

        .inp:focus {
            border-color: rgba(13, 110, 253, .65);
            box-shadow: 0 0 0 4px rgba(13, 110, 253, .12);
            outline: none;
        }

        .btn-pill {
            border-radius: 999px;
            border: 1px solid var(--line);
            height: 42px;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .6rem 1rem;
            font-weight: 600;
            line-height: 1;
            white-space: nowrap;
            transition: all 0.25s ease;
        }

        .btn-primary-soft {
            background: linear-gradient(135deg, var(--primary-2), var(--primary));
            color: #fff;
            border-color: transparent;
            box-shadow: var(--shadow-md);
        }

        .btn-primary-soft:hover {
            transform: translateY(-1px);
            color: #fff;
            box-shadow: var(--shadow-lg);
        }

        .btn-ghost {
            background: #fff;
            color: var(--muted);
        }

        .btn-ghost:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        /* Summary */
        .summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            flex-wrap: wrap;
            padding: .5rem 0 0;
        }

        .chip {
            background: var(--primary-soft);
            color: var(--primary);
            border-radius: 999px;
            padding: .35rem .7rem;
            font-weight: 700;
            font-size: .85rem;
        }

        /* Content card */
        .card-custom {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 1rem;
            background: #fff;
            margin-top: .9rem;
        }

        /* Table */
        .table-custom {
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid var(--line);
        }

        .table-custom thead {
            background: #f1f5f9;
            color: #334155;
        }

        .table-custom thead th {
            font-weight: 700;
            font-size: .86rem;
            text-transform: uppercase;
            letter-spacing: .35px;
        }

        .table-custom tbody tr:nth-child(even) {
            background: #fafbfc;
        }

        .table-custom tbody tr:hover {
            background: var(--primary-soft) !important;
        }

        /* Badges */
        .badge {
            font-size: .78rem;
            padding: .45em .9em;
            border-radius: 999px;
            font-weight: 600;
        }

        .badge-capacity {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            color: #fff;
        }

        .badge-seat-bar {
            background: linear-gradient(135deg, #c084fc, #9333ea);
            color: #fff;
        }

        .badge-seat-table {
            background: linear-gradient(135deg, #bae6fd, #38bdf8);
            color: #1e293b;
        }

        .badge-active {
            background: linear-gradient(135deg, #34d399, #059669);
            color: #fff;
        }

        .badge-inactive {
            background: linear-gradient(135deg, #f87171, #dc2626);
            color: #fff;
        }

        /* Actions */
        .btn-action {
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin: 0 2px;
            transition: .2s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
        }

        .btn-edit {
            background: #facc15;
            color: #fff;
        }

        .btn-delete {
            background: #ef4444;
            color: #fff;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 1rem;
        }

        .pagination .page-link {
            border-radius: 10px;
            margin: 0 3px;
            color: var(--primary-2);
        }

        .pagination .active .page-link {
            background: var(--primary-2);
            border-color: var(--primary-2);
            color: #fff;
        }

        /* Mobile Cards */
        .u-card {
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: .9rem;
            background: #fff;
            box-shadow: 0 2px 10px rgba(2, 6, 23, .03);
        }

        .u-card+.u-card {
            margin-top: .75rem;
        }

        .u-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
        }

        .u-name {
            font-weight: 700;
            font-size: 1rem;
            color: #0f172a;
        }

        .u-sub {
            color: var(--muted);
            font-size: .9rem;
        }

        .u-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: .4rem;
            margin-top: .5rem;
        }

        .u-actions {
            display: flex;
            align-items: center;
            gap: .35rem;
            justify-content: flex-end;
            margin-top: .6rem;
        }

        @media (max-width: 575.98px) {

            .card-wrap,
            .card-custom {
                padding: .9rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container py-3">
        {{-- Header + Filters--}}
        <div class="card-wrap mb-3">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <h1 class="page-title m-0">Table Management</h1>
                <a href="/table/adding" class="btn-pill btn-primary-soft d-none d-md-inline-flex btn">
                    <i class="bi bi-plus-circle"></i> Add Table
                </a>
            </div>

            {{-- Floating Add (มือถือ) --}}
            <div class="container py-3 has-fab">
                <a href="/table/adding" class="fab-add d-md-none" aria-label="Add Table" data-bs-toggle="tooltip"
                    title="Add Table">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>

            <div class="filters" id="filters">
                <div class="position-relative">
                    <input type="text" class="inp" id="q" placeholder="Search table no., seat type…">
                </div>

                <div class="dropdown-wrap position-relative">
                    <select class="inp w-100 pe-5" id="seat">
                        <option value="">All seat types</option>
                        <option value="bar">BAR</option>
                        <option value="table">TABLE</option>
                    </select>
                    <i class="bi bi-caret-down-fill position-absolute end-0 top-50 translate-middle-y me-3 text-muted"></i>
                </div>

                <div class="dropdown-wrap position-relative">
                    <select class="inp w-100 pe-5" id="status">
                        <option value="">All status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
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
                        $count = $tables->total();
                        $from = $tables->firstItem();
                        $to = $tables->lastItem();
                    @endphp
                    <span
                        id="serverRange">{{ $count ? "Showing {$from}-{$to} of {$count} tables" : 'No tables found' }}</span>
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
                <table class="table table-hover table-custom align-middle" id="tableTable">
                    <thead>
                        <tr class="text-center">
                            <th width="6%">No.</th>
                            <th width="20%">Table Number</th>
                            <th width="14%">Capacity</th>
                            <th width="20%">Seat Type</th>
                            <th width="12%">Status</th>
                            <th width="18%">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableTbody">
                        @forelse ($tables as $row)
                            @php
                                $seat = strtolower($row->seat_type ?? '');
                                $statusText = $row->is_active ? 'active' : 'inactive';
                                $searchBlob = strtolower(
                                    trim(
                                        ($row->table_number ?? '') .
                                            ' ' .
                                            ($row->seat_type ?? '') .
                                            ' ' .
                                            ($row->capacity ?? ''),
                                    ),
                                );
                            @endphp
                            <tr class="js-table-row" data-seat="{{ $seat }}" data-status="{{ $statusText }}"
                                data-search="{{ e($searchBlob) }}">
                                <td class="text-center fw-bold">{{ $tables->firstItem() + $loop->index }}</td>
                                <td class="text-center">{{ $row->table_number }}</td>
                                <td class="text-center">
                                    <span class="badge badge-capacity fs-6">{{ $row->capacity }}</span>
                                </td>
                                <td class="text-center">
                                    @if (($row->seat_type ?? '') === 'BAR')
                                        <span class="badge badge-seat-bar">BAR</span>
                                    @else
                                        <span class="badge badge-seat-table">TABLE</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($row->is_active)
                                        <span class="badge badge-active">Active</span>
                                    @else
                                        <span class="badge badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="/table/{{ $row->table_id }}" class="btn-action btn-edit"
                                        data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete" data-bs-toggle="tooltip"
                                        title="Delete" onclick="deleteConfirm({{ $row->table_id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $row->table_id }}"
                                        action="/table/remove/{{ $row->table_id }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No tables found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $tables->links() }}
            <div id="noResultTable" class="text-center text-muted py-4 d-none">No results matched your filters</div>
        </div>

        {{-- Mobile: Card list --}}
        <div class="d-md-none" id="tableCards">
            @forelse ($tables as $row)
                @php
                    $seat = strtolower($row->seat_type ?? '');
                    $statusText = $row->is_active ? 'active' : 'inactive';
                    $searchBlob = strtolower(
                        trim(($row->table_number ?? '') . ' ' . ($row->seat_type ?? '') . ' ' . ($row->capacity ?? '')),
                    );
                @endphp
                <div class="u-card js-table-card" data-seat="{{ $seat }}" data-status="{{ $statusText }}"
                    data-search="{{ e($searchBlob) }}">
                    <div class="u-head">
                        <div>
                            <div class="u-name">Table #{{ $row->table_number }}</div>
                            <div class="u-sub">Capacity: {{ $row->capacity }}</div>
                        </div>
                        @if (($row->seat_type ?? '') === 'BAR')
                            <span class="badge badge-seat-bar">BAR</span>
                        @else
                            <span class="badge badge-seat-table">TABLE</span>
                        @endif
                    </div>
                    <div class="u-meta">
                        @if ($row->is_active)
                            <span class="chip" style="color:#fff;background:#10b981;">Active</span>
                        @else
                            <span class="chip" style="color:#fff;background:#ef4444;">Inactive</span>
                        @endif
                    </div>
                    <div class="u-actions">
                        <a href="/table/{{ $row->table_id }}" class="btn-action btn-edit" title="Edit"><i
                                class="bi bi-pencil"></i></a>
                        <button type="button" class="btn-action btn-delete" title="Delete"
                            onclick="deleteConfirm({{ $row->table_id }})">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="delete-form-{{ $row->table_id }}" action="/table/remove/{{ $row->table_id }}"
                            method="POST" style="display:none;">
                            @csrf
                            @method('delete')
                        </form>
                    </div>
                </div>
            @empty
                <div class="u-card text-center text-muted">No tables found</div>
            @endforelse

            <div class="mt-2">
                {{ $tables->links() }}
            </div>

            <div id="noResultCards" class="text-center text-muted py-3 d-none">No results matched your filters</div>
        </div>
    </div>
@endsection

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert ลบ
        function deleteConfirm(id) {
            Swal.fire({
                title: 'แน่ใจหรือไม่?',
                text: "คุณต้องการลบโต๊ะนี้จริง ๆ หรือไม่",
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

        // Tooltip
        document.addEventListener('DOMContentLoaded', () => {
            const tList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tList.map(el => new bootstrap.Tooltip(el));
        });

        // ------- Client-side Search & Filters -------
        (function() {
            const q = document.getElementById('q');
            const seat = document.getElementById('seat');
            const status = document.getElementById('status');
            const btnSearch = document.getElementById('btnSearch');
            const btnClear = document.getElementById('btnClear');

            const rows = Array.from(document.querySelectorAll('.js-table-row'));
            const cards = Array.from(document.querySelectorAll('.js-table-card'));

            const noResultTable = document.getElementById('noResultTable');
            const noResultCards = document.getElementById('noResultCards');
            const clientInfo = document.getElementById('clientInfo');

            function match(itemSearch, itemSeat, itemStatus, kw, fSeat, fStatus) {
                if (kw && !itemSearch.includes(kw)) return false;
                if (fSeat && fSeat !== itemSeat) return false;
                if (fStatus && fStatus !== itemStatus) return false;
                return true;
            }

            function apply() {
                const kw = (q.value || '').trim().toLowerCase();
                const fSeat = seat.value.toLowerCase();
                const fStatus = status.value.toLowerCase();

                let shownTable = 0,
                    shownCards = 0;

                rows.forEach(tr => {
                    const ok = match(tr.dataset.search, tr.dataset.seat, tr.dataset.status, kw, fSeat, fStatus);
                    tr.style.display = ok ? '' : 'none';
                    if (ok) shownTable++;
                });

                cards.forEach(card => {
                    const ok = match(card.dataset.search, card.dataset.seat, card.dataset.status, kw, fSeat,
                        fStatus);
                    card.style.display = ok ? '' : 'none';
                    if (ok) shownCards++;
                });

                if (rows.length) noResultTable.classList.toggle('d-none', shownTable !== 0);
                if (cards.length) noResultCards.classList.toggle('d-none', shownCards !== 0);

                const hasClientFilter = !!(kw || fSeat || fStatus);
                clientInfo.classList.toggle('d-none', !hasClientFilter);
                if (hasClientFilter) {
                    const parts = [];
                    if (kw) parts.push(`q: "${kw}"`);
                    if (fSeat) parts.push(`seat: ${fSeat}`);
                    if (fStatus) parts.push(`status: ${fStatus}`);
                    clientInfo.textContent = `${shownTable || shownCards} matched (${parts.join(', ')})`;
                }
            }

            // events
            let timer = null;
            q.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(apply, 350);
            });
            [seat, status].forEach(el => el.addEventListener('change', apply));
            btnSearch.addEventListener('click', apply);
            q.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    apply();
                }
            });

            btnClear.addEventListener('click', () => {
                q.value = '';
                seat.value = '';
                status.value = '';
                apply();
            });
        })();
    </script>
@endsection
