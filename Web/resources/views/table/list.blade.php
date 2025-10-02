@extends('home')

@section('css_before')
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
@endsection

@section('content')
    <div class="container py-3">
        {{-- Header + Filters --}}
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
    <script src="{{ asset('js/table.js') }}"></script>
@endsection
