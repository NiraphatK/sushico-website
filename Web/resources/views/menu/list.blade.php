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
            background: #fff;
            padding: 2rem;
        }

        .card-header {
            border-bottom: 1px solid #e5e7eb;
        }

        .card-title {
            font-size: 1.7rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ปุ่ม Add */
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

        /* Badge */
        .badge {
            font-size: 0.8rem;
            padding: 0.45em 0.9em;
            border-radius: 30px;
            font-weight: 500;
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

        /* Image preview hover */
        .menu-thumb {
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .menu-thumb:hover {
            transform: scale(1.05);
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card card-custom">
            <div class="d-flex justify-content-between align-items-center bg-white">
                <div class="d-flex mx-auto">
                    <h1 class="card-title">Menu Management</h1>
                </div>
                <a href="/menu/adding" class="btn-add btn">
                    <i class="bi bi-plus-circle"></i> Add Menu
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-custom align-middle">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">No.</th>
                                <th width="15%">Menu Image</th>
                                <th width="40%">Menu Name & Description</th>
                                <th width="15%">Price</th>
                                <th width="10%">Status</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($menu as $row)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        @if ($row->image_path)
                                            <img src="{{ asset('storage/' . $row->image_path) }}" width="100"
                                                class="rounded shadow-sm menu-thumb"
                                                onclick="previewImage('{{ asset('storage/' . $row->image_path) }}', '{{ $row->name }}')">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <b>{{ $row->name }}</b><br>
                                        <small class="text-muted">
                                            {{ Str::limit($row->description, 120, '...') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-price fs-6">
                                            ฿{{ number_format($row->price, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($row->is_active)
                                            <span class="badge badge-active">Active</span>
                                        @else
                                            <span class="badge badge-inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="/menu/{{ $row->menu_id }}" class="btn-action btn-edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn-action btn-delete" title="Delete"
                                            onclick="deleteConfirm({{ $row->menu_id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $row->menu_id }}"
                                            action="/menu/remove/{{ $row->menu_id }}" method="POST" style="display:none;">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $menu->links() }}
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

        function previewImage(url, title) {
            Swal.fire({
                title: title,
                imageUrl: url,
                imageWidth: 400,
                imageAlt: title,
                showCloseButton: true,
                showConfirmButton: false,
                background: '#fff',
                customClass: {
                    popup: 'rounded-4 shadow-lg'
                }
            })
        }
    </script>
@endsection
