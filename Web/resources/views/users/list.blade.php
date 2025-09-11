@extends('home')

@section('css_before')
@endsection

@section('header')
@endsection

@section('sidebarMenu')
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1>User data
                    <a href="/users/adding" class="btn btn-primary btn-sm mb-2"> + User </a>
                </h1>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="table-info">
                            <th width="5%" class="text-center">No.</th>
                            <th width="20%">Full Name</th>
                            <th width="20%">Email</th>
                            <th width="15%">Phone</th>
                            <th width="10%">Role</th>
                            <th width="10%">Status</th>
                            <th width="20%" class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($UserList as $row)
                            <tr>
                                <td align="center">{{ $loop->iteration }}</td>
                                <td>{{ $row->full_name }}</td>
                                <td>{{ $row->email ?? '-' }}</td>
                                <td>{{ $row->phone }}</td>
                                <td>{{ $row->role }}</td>
                                <td>
                                    @if ($row->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="/users/{{ $row->user_id }}" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="/users/reset/{{ $row->user_id }}" class="btn btn-info btn-sm">Reset</a>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="deleteConfirm({{ $row->user_id }})">Delete</button>
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

                <div>
                    {{ $UserList->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection

@section('footer')
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
