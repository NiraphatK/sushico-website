@extends('home')

@section('css_before')
@endsection

@section('header')
@endsection

@section('sidebarMenu')
@endsection

@section('content')
    <h3> :: Menu Management ::
        <a href="/menu/adding" class="btn btn-primary btn-sm"> Add Menu </a>
    </h3>

    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr class="table-info text-center">
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
                    <td class="text-center"> {{ $loop->iteration }} </td>

                    <td class="text-center">
                        @if ($row->image_path)
                            <img src="{{ asset('storage/' . $row->image_path) }}" width="100" class="rounded shadow-sm">
                        @else
                            <span class="text-muted">No image</span>
                        @endif
                    </td>

                    <td>
                        <b>{{ $row->name }}</b> <br>
                        <small class="text-muted">
                            {{ Str::limit($row->description, 120, '...') }}
                        </small>
                    </td>

                    <td class="text-center">
                        <span class="badge bg-success fs-6">฿{{ number_format($row->price, 2) }}</span>
                    </td>

                    <td class="text-center">
                        @if ($row->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <a href="/menu/{{ $row->menu_id }}" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="deleteConfirm({{ $row->menu_id }})">Delete</button>
                        <form id="delete-form-{{ $row->menu_id }}" action="/menu/remove/{{ $row->menu_id }}" method="POST"
                            style="display:none;">
                            @csrf
                            @method('delete')
                        </form>
                    </td>

                    <form id="delete-form-{{ $row->menu_id }}" action="/menu/remove/{{ $row->menu_id }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('delete')
                    </form>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    <div>
        {{ $menu->links() }}
    </div>
@endsection

@section('footer')
@endsection

@section('js_before')
@endsection

@section('js_before')
@endsection


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteConfirm(id) {
        Swal.fire({
            title: 'แน่ใจหรือไม่?',
            text: "คุณต้องการลบเมนูนี้จริง ๆ หรือไม่",
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
