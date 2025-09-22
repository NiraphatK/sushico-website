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

        /* Badge */
        .badge {
            font-size: 0.78rem;
            padding: 0.45em 0.9em;
            border-radius: 30px;
            font-weight: 500;
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
                    <h1>Table Management</h1>
                </div>
                <a href="/table/adding" class="btn-add btn">
                    <i class="bi bi-plus-circle"></i> Add Table
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle">
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

                    <tbody>
                        @foreach ($tables as $row)
                            <tr>
                                <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $row->table_number }}</td>
                                <td class="text-center">
                                    <span class="badge badge-capacity fs-6">{{ $row->capacity }}</span>
                                </td>
                                <td class="text-center">
                                    @if ($row->seat_type === 'BAR')
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
                                    <a href="/table/{{ $row->table_id }}" class="btn-action btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete" title="Delete"
                                        onclick="deleteConfirm({{ $row->table_id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $row->table_id }}" action="/table/remove/{{ $row->table_id }}"
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

            {{ $tables->links() }}
        </div>
    </div>
@endsection

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
            })
        }
    </script>
@endsection
