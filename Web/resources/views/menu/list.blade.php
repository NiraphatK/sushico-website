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
            grid-template-columns: 3fr 1fr 1fr 1fr auto;
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

        @media (max-width: 991.98px) {
            .filters {
                grid-template-columns: 1fr 1fr 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .filters {
                grid-template-columns: 1fr;
            }

            .filters-actions .btn-pill {
                flex: 1;
            }
        }

        /* FAB (mobile) */
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
            transition: .2s;
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

        /* Card (content) */
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

        .badge-price {
            background: linear-gradient(135deg, #34d399, #059669);
            color: #fff;
        }

        .badge-active {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
        }

        .badge-inactive {
            background: linear-gradient(135deg, #9ca3af, #4b5563);
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

        /* Thumbs */
        .menu-thumb {
            cursor: pointer;
            transition: transform .2s ease;
            border-radius: 10px;
        }

        .menu-thumb:hover {
            transform: scale(1.05);
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

        /* Responsive padding */
        @media (max-width:575.98px) {

            .card-wrap,
            .card-custom {
                padding: .9rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container py-3">
        {{-- Header + Filters --}}
        <div class="card-wrap mb-3">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <h1 class="page-title m-0">Menu Management</h1>
                <a href="/menu/adding" class="btn-pill btn-primary-soft d-none d-md-inline-flex btn">
                    <i class="bi bi-plus-circle"></i> Add Menu
                </a>
            </div>

            {{-- Floating Add button (มือถือ) --}}
            <div class="container py-2 has-fab">
                <a href="/menu/adding" class="fab-add d-md-none" aria-label="Add Menu" data-bs-toggle="tooltip"
                    title="Add Menu">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>

            <div class="filters" id="filters">
                <div class="position-relative">
                    <input type="text" class="inp" id="q" placeholder="Search name or description…">
                </div>
                <div class="dropdown-wrap position-relative">
                    <select class="inp w-100 pe-5" id="status">
                        <option value="">All status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <i class="bi bi-caret-down-fill position-absolute end-0 top-50 translate-middle-y me-3 text-muted"></i>
                </div>
                <div><input type="number" class="inp" id="minPrice" placeholder="Min price" min="0"
                        step="0.01"></div>
                <div><input type="number" class="inp" id="maxPrice" placeholder="Max price" min="0"
                        step="0.01"></div>

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
                        $count = $menu->total();
                        $from = $menu->firstItem();
                        $to = $menu->lastItem();
                    @endphp
                    <span
                        id="serverRange">{{ $count ? "Showing {$from}-{$to} of {$count} menus" : 'No menus found' }}</span>
                    <span id="clientInfo" class="ms-2 d-none chip"></span>
                </div>
            </div>
        </div>

        {{-- Alert (ถ้ามี) --}}
        @if (session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        {{-- Table --}}
        <div class="card-custom">
            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle" id="menuTable">
                    <thead>
                        <tr class="text-center">
                            <th width="6%">No.</th>
                            <th width="16%">Image</th>
                            <th width="40%">Menu Name & Description</th>
                            <th width="14%">Price</th>
                            <th width="12%">Status</th>
                            <th width="12%">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="menuTbody">
                        @forelse ($menu as $row)
                            @php
                                $statusText = $row->is_active ? 'active' : 'inactive';
                                $blob = strtolower(trim(($row->name ?? '') . ' ' . ($row->description ?? '')));
                            @endphp
                            <tr class="js-menu-row" data-status="{{ $statusText }}"
                                data-price="{{ number_format($row->price, 2, '.', '') }}" data-search="{{ e($blob) }}">
                                <td class="text-center fw-bold">{{ $menu->firstItem() + $loop->index }}</td>
                                <td class="text-center">
                                    @if ($row->image_path)
                                        <img src="{{ asset('storage/' . $row->image_path) }}" width="96"
                                            class="shadow-sm menu-thumb"
                                            onclick="previewImage('{{ asset('storage/' . $row->image_path) }}','{{ $row->name }}')"
                                            alt="{{ $row->name }}">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $row->name }}</div>
                                    <small class="text-muted">{{ Str::limit($row->description, 120, '...') }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-price fs-6">฿{{ number_format($row->price, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    @if ($row->is_active)
                                        <span class="badge badge-active">Active</span>
                                    @else
                                        <span class="badge badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="/menu/{{ $row->menu_id }}" class="btn-action btn-edit"
                                        data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete" data-bs-toggle="tooltip"
                                        title="Delete" onclick="deleteConfirm({{ $row->menu_id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $row->menu_id }}" action="/menu/remove/{{ $row->menu_id }}"
                                        method="POST" style="display:none;">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No menus found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Laravel pagination --}}
            <div class="mt-2">{{ $menu->links() }}</div>
            <div id="noResultTable" class="text-center text-muted py-4 d-none">No results matched your filters</div>
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
                text: "คุณต้องการลบเมนูนี้จริง ๆ หรือไม่",
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
            })
        }

        // Preview รูป
        function previewImage(url, title) {
            Swal.fire({
                title: title,
                imageUrl: url,
                imageWidth: 480,
                imageAlt: title,
                showCloseButton: true,
                showConfirmButton: false,
                background: '#fff',
                customClass: {
                    popup: 'rounded-4 shadow-lg'
                }
            })
        }

        // Tooltip
        document.addEventListener('DOMContentLoaded', () => {
            const tList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tList.map(el => new bootstrap.Tooltip(el));
        });

        // ------- Client-side Filters -------
        (function() {
            const q = document.getElementById('q');
            const status = document.getElementById('status');
            const minPrice = document.getElementById('minPrice');
            const maxPrice = document.getElementById('maxPrice');
            const btnSearch = document.getElementById('btnSearch');
            const btnClear = document.getElementById('btnClear');

            const rows = Array.from(document.querySelectorAll('.js-menu-row'));
            const noResultTable = document.getElementById('noResultTable');
            const clientInfo = document.getElementById('clientInfo');

            function inRange(price, minV, maxV) {
                if (minV !== '' && price < Number(minV)) return false;
                if (maxV !== '' && price > Number(maxV)) return false;
                return true;
            }

            function match(itemSearch, itemStatus, itemPrice, kw, fStatus, fMin, fMax) {
                if (kw && !itemSearch.includes(kw)) return false;
                if (fStatus && fStatus !== itemStatus) return false;
                if (!inRange(Number(itemPrice), fMin, fMax)) return false;
                return true;
            }

            function apply() {
                const kw = (q.value || '').trim().toLowerCase();
                const fStatus = (status.value || '').toLowerCase();
                const fMin = minPrice.value;
                const fMax = maxPrice.value;

                let shown = 0;
                rows.forEach(tr => {
                    const ok = match(tr.dataset.search, tr.dataset.status, tr.dataset.price, kw, fStatus, fMin,
                        fMax);
                    tr.style.display = ok ? '' : 'none';
                    if (ok) shown++;
                });

                // toggle empty state
                if (rows.length) noResultTable.classList.toggle('d-none', shown !== 0);

                // info chip
                const hasClientFilter = !!(kw || fStatus || fMin || fMax);
                clientInfo.classList.toggle('d-none', !hasClientFilter);
                if (hasClientFilter) {
                    const parts = [];
                    if (kw) parts.push(`q: "${kw}"`);
                    if (fStatus) parts.push(`status: ${fStatus}`);
                    if (fMin !== '') parts.push(`min: ${Number(fMin).toFixed(2)}`);
                    if (fMax !== '') parts.push(`max: ${Number(fMax).toFixed(2)}`);
                    clientInfo.textContent = `${shown} matched (${parts.join(', ')})`;
                }
            }

            // events
            let timer = null;
            q.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(apply, 300);
            });
            [status, minPrice, maxPrice].forEach(el => el.addEventListener('change', apply));
            btnSearch.addEventListener('click', apply);
            q.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    apply();
                }
            });

            btnClear.addEventListener('click', () => {
                q.value = '';
                status.value = '';
                minPrice.value = '';
                maxPrice.value = '';
                apply();
            });
        })();
    </script>
@endsection
