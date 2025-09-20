@extends('home')

@section('css_before')
@endsection

@section('header')
@endsection

@section('sidebarMenu')
@endsection

@section('content')
    <h3> :: Table Management ::
        <a href="/table/adding" class="btn btn-primary btn-sm"> Add Table </a>
    </h3>

    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr class="table-info text-center">
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
                    <td class="text-center">{{ $loop->iteration }}</td>

                    <td class="text-center">
                        <b>{{ $row->table_number }}</b>
                    </td>

                    <td class="text-center">
                        <span class="badge bg-primary fs-6">{{ $row->capacity }}</span>
                    </td>

                    <td class="text-center">
                        @if ($row->seat_type === 'BAR')
                            <span class="badge bg-dark">BAR</span>
                        @else
                            <span class="badge bg-info text-dark">TABLE</span>
                        @endif
                    </td>

                    <td class="text-center">
                        @if ($row->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>

                    <td class="text-center">
                        {{-- แก้ให้ชี้ไปยัง TableController@edit และ @remove --}}
                        <a href="/table/{{ $row->table_id }}" class="btn btn-warning btn-sm">Edit</a>

                        <button type="button" class="btn btn-danger btn-sm"
                                onclick="deleteConfirm({{ $row->table_id }})">Delete</button>

                        <form id="delete-form-{{ $row->table_id }}"
                              action="/table/remove/{{ $row->table_id }}"
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
        {{ $tables->links() }}
    </div>
@endsection

@section('footer')
@endsection

@section('js_before')
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteConfirm(id) {
        Swal.fire({
            title: 'แน่ใจหรือไม่?',
            text: "คุณต้องการลบโต๊ะนี้จริง ๆ หรือไม่",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
