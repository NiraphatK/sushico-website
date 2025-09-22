@extends('home')

@section('css_before')
    <style>
        body {
            background: #f8fafc;
            font-family: "Inter", "Poppins", sans-serif;
        }

        /* Card */
        .card-custom {
            border: none;
            border-radius: 18px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            background: #fff;
        }

        .card-custom h1 {
            font-size: 1.7rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* ปุ่ม Add */
        .btn-add {
            border: none;
            border-radius: 30px;
            padding: 0.6rem 1.4rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #0d6efd, #2563eb);
            /* gradient ฟ้า */
            color: #fff;

            transition: all 0.25s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            color: #fff;
        }


        /* Table */
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
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .table-custom tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .table-custom tbody tr:hover {
            background: #eef6ff !important;
        }

        /* Badge base */
        .badge {
            font-size: 0.78rem;
            padding: 0.45em 0.9em;
            border-radius: 30px;
            font-weight: 500;
        }

        /* Role Badge */
        .badge-role-admin {
            background: linear-gradient(135deg, #f87171, #dc2626);
            color: #fff;
        }

        .badge-role-staff {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            color: #fff;
        }

        .badge-role-customer {
            background: linear-gradient(135deg, #c084fc, #9333ea);
            color: #fff;
        }

        /* Active / Inactive */
        .badge-active {
            background: linear-gradient(135deg, #34d399, #059669);
            color: #fff;
        }

        .badge-inactive {
            background: linear-gradient(135deg, #f87171, #dc2626);
            color: #fff;
        }

        /* Action buttons */
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

        .btn-reset {
            background: #0ea5e9;
            color: #fff;
        }

        .btn-delete {
            background: #ef4444;
            color: #fff;
        }

        /* Pagination */
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
    <div class="container mt-4">
        <div class="card card-custom">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex mx-auto">
                    <h1>User Management</h1>
                </div>
                <a href="/users/adding" class="btn btn-primary btn-add">
                    <i class="bi bi-person-plus"></i> Add User
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No.</th>
                            <th width="20%">Full Name</th>
                            <th width="20%">Email</th>
                            <th width="15%">Phone</th>
                            <th width="10%">Role</th>
                            <th width="10%">Status</th>
                            <th class="text-center" width="20%">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($UserList as $row)
                            @php
                                $role = strtolower($row->role);
                                $roleClasses = [
                                    'admin' => 'badge-role-admin',
                                    'staff' => 'badge-role-staff',
                                    'customer' => 'badge-role-customer',
                                ];
                                $roleClass = $roleClasses[$role] ?? 'bg-secondary';
                            @endphp

                            <tr>
                                <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                <td>{{ $row->full_name }}</td>
                                <td>{{ $row->email ?? '-' }}</td>
                                <td>{{ $row->phone }}</td>
                                <td>
                                    <span class="badge {{ $roleClass }}">{{ ucfirst($row->role) }}</span>
                                </td>
                                <td>
                                    @if ($row->is_active)
                                        <span class="badge badge-active">Active</span>
                                    @else
                                        <span class="badge badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="/users/{{ $row->user_id }}" class="btn-action btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/users/reset/{{ $row->user_id }}" class="btn-action btn-reset" title="Reset">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete" title="Delete"
                                        onclick="deleteConfirm({{ $row->user_id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $row->user_id }}" action="/users/remove/{{ $row->user_id }}"
                                        method="POST" style="display:none;">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $UserList->links() }}
        </div>
    </div>
@endsection

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteConfirm(id) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "หากลบแล้วจะไม่สามารถกู้คืนได้!",
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
    </script>
@endsection
