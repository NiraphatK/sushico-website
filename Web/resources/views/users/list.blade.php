@extends('home')

@section('css_before')
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endsection


@section('content')
    <div class="container py-3">
        {{-- Header + Filters (ทั้งหมดทำงานฝั่งหน้าเว็บ) --}}
        <div class="card-wrap mb-3">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                <h1 class="page-title m-0">User Management</h1>
                <a href="/users/adding" class="btn-pill btn-primary-soft d-none d-md-inline-flex btn">
                    <i class="bi bi-person-plus"></i> Add User
                </a>
            </div>

            {{-- Floating Add button (มือถือ) --}}
            <div class="container py-3 has-fab">
                <a href="/users/adding" class="fab-add d-md-none" aria-label="Add User" data-bs-toggle="tooltip"
                    title="Add User">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>

            <div class="filters" id="filters">
                <div class="position-relative">
                    <input type="text" class="inp" id="q" placeholder="Search name, email, phone…">
                </div>

                <div class="dropdown-wrap position-relative">
                    <select class="inp w-100 pe-5" id="role">
                        <option value="">All roles</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                        <option value="customer">Customer</option>
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
                    {{-- แสดงผลรวม/ช่วงจาก Laravel pagination --}}
                    @php
                        $count = $UserList->total();
                        $from = $UserList->firstItem();
                        $to = $UserList->lastItem();
                    @endphp
                    <span
                        id="serverRange">{{ $count ? "Showing {$from}-{$to} of {$count} users" : 'No users found' }}</span>
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
                <table class="table table-hover table-custom align-middle" id="userTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="6%">No.</th>
                            <th width="22%">Full Name</th>
                            <th width="20%">Email</th>
                            <th width="14%">Phone</th>
                            <th width="12%">Role</th>
                            <th width="12%">Status</th>
                            <th class="text-center" width="14%">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTbody">
                        @forelse ($UserList as $row)
                            @php
                                $role = strtolower($row->role);
                                $roleClasses = [
                                    'admin' => 'badge-role-admin',
                                    'staff' => 'badge-role-staff',
                                    'customer' => 'badge-role-customer',
                                ];
                                $roleClass = $roleClasses[$role] ?? 'bg-secondary';
                                $statusText = $row->is_active ? 'active' : 'inactive';
                                $searchBlob = strtolower(
                                    trim(
                                        ($row->full_name ?? '') . ' ' . ($row->email ?? '') . ' ' . ($row->phone ?? ''),
                                    ),
                                );
                            @endphp
                            <tr class="js-user-row" data-role="{{ $role }}" data-status="{{ $statusText }}"
                                data-search="{{ e($searchBlob) }}">
                                <td class="text-center fw-bold">{{ $UserList->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $row->full_name }}</td>
                                <td>{{ $row->email ?? '-' }}</td>
                                <td>{{ $row->phone }}</td>
                                <td><span class="badge {{ $roleClass }}">{{ ucfirst($row->role) }}</span></td>
                                <td>
                                    @if ($row->is_active)
                                        <span class="badge badge-active">Active</span>
                                    @else
                                        <span class="badge badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="/users/{{ $row->user_id }}" class="btn-action btn-edit"
                                        data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/users/reset/{{ $row->user_id }}" class="btn-action btn-reset"
                                        data-bs-toggle="tooltip" title="Reset password">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete" data-bs-toggle="tooltip"
                                        title="Delete" onclick="deleteConfirm({{ $row->user_id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $row->user_id }}" action="/users/remove/{{ $row->user_id }}"
                                        method="POST" style="display:none;">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $UserList->links() }}
            <div id="noResultTable" class="text-center text-muted py-4 d-none">No results matched your filters</div>
        </div>

        {{-- Mobile: Card list --}}
        <div class="d-md-none" id="userCards">
            @forelse ($UserList as $row)
                @php
                    $role = strtolower($row->role);
                    $roleClasses = [
                        'admin' => 'badge-role-admin',
                        'staff' => 'badge-role-staff',
                        'customer' => 'badge-role-customer',
                    ];
                    $roleClass = $roleClasses[$role] ?? 'bg-secondary';
                    $statusText = $row->is_active ? 'active' : 'inactive';
                    $searchBlob = strtolower(
                        trim(($row->full_name ?? '') . ' ' . ($row->email ?? '') . ' ' . ($row->phone ?? '')),
                    );
                @endphp
                <div class="u-card js-user-card" data-role="{{ $role }}" data-status="{{ $statusText }}"
                    data-search="{{ e($searchBlob) }}">
                    <div class="u-head">
                        <div>
                            <div class="u-name">{{ $row->full_name }}</div>
                            <div class="u-sub">{{ $row->email ?? '-' }}</div>
                        </div>
                        <span class="badge {{ $roleClass }}">{{ ucfirst($row->role) }}</span>
                    </div>
                    <div class="u-meta">
                        <span class="chip"><i class="bi bi-telephone me-1"></i>{{ $row->phone }}</span>
                        @if ($row->is_active)
                            <span class="chip" style="color:#fff;background:#10b981;">Active</span>
                        @else
                            <span class="chip" style="color:#fff;background:#ef4444;">Inactive</span>
                        @endif
                    </div>
                    <div class="u-actions">
                        <a href="/users/{{ $row->user_id }}" class="btn-action btn-edit" title="Edit"><i
                                class="bi bi-pencil"></i></a>
                        <a href="/users/reset/{{ $row->user_id }}" class="btn-action btn-reset"
                            title="Reset password"><i class="bi bi-arrow-repeat"></i></a>
                        <button type="button" class="btn-action btn-delete" title="Delete"
                            onclick="deleteConfirm({{ $row->user_id }})">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="delete-form-{{ $row->user_id }}" action="/users/remove/{{ $row->user_id }}"
                            method="POST" style="display:none;">
                            @csrf
                            @method('delete')
                        </form>
                    </div>
                </div>
            @empty
                <div class="u-card text-center text-muted">No users found</div>
            @endforelse

            <div class="mt-2">
                {{ $UserList->links() }}
            </div>

            <div id="noResultCards" class="text-center text-muted py-3 d-none">No results matched your filters</div>
        </div>
    </div>
@endsection

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/users.js') }}" defer></script>
@endsection
