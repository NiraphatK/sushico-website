@extends('home')

@section('css_before')
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
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
    <script src="{{ asset('js/menu.js') }}"></script>
@endsection
