@extends('home')
@section('js_before')
@include('sweetalert::alert')
@section('header')
@section('sidebarMenu')
@section('content')

    <h3> :: Form Update Menu :: </h3>

    <form action="/menu/{{ $menu_id }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')


        <div class="form-group row mb-2">
            <label class="col-sm-2"> Menu Name </label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="name" required placeholder="Menu Name" minlength="3"
                    value="{{ old('name', $name) }}">
                @if (isset($errors))
                    @if ($errors->has('name'))
                        <div class="text-danger"> {{ $errors->first('name') }}</div>
                    @endif
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Description </label>
            <div class="col-sm-7">
                <textarea name="description" class="form-control" rows="7"
                    placeholder="Menu description">{{ old('description', $description) }}</textarea>
                @if (isset($errors))
                    @if ($errors->has('description'))
                        <div class="text-danger"> {{ $errors->first('description') }}</div>
                    @endif
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Detail </label>
            <div class="col-sm-9">
                <textarea name="detail" id="detail" rows="8"
                    class="form-control">{{ old('detail', $detail ?? '') }}</textarea>
                @if ($errors->has('detail'))
                    <div class="text-danger"> {{ $errors->first('detail') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Price </label>
            <div class="col-sm-6">
                <input type="number" class="form-control" name="price" required placeholder="Price" min="0" step="0.01"
                    value="{{ old('price', $price) }}">
                @if (isset($errors))
                    @if ($errors->has('price'))
                        <div class="text-danger"> {{ $errors->first('price') }}</div>
                    @endif
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Image </label>
            <div class="col-sm-6">
                <div class="mb-2">old image</div>
                @if ($image_path)
                    <img src="{{ asset('storage/' . $image_path) }}" width="200px" class="mb-2">
                @else
                    <div class="text-muted mb-2">No image</div>
                @endif
                <div class="mb-1">choose new image</div>
                <input type="file" name="image_path" accept="image/*">
                @if (isset($errors))
                    @if ($errors->has('image_path'))
                        <div class="text-danger"> {{ $errors->first('image_path') }}</div>
                    @endif
                @endif
                <small class="text-muted d-block">รองรับ JPG/PNG สูงสุด 2MB</small>
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Active </label>
            <div class="col-sm-6">
                <input type="hidden" name="is_active" value="0">

                <input type="checkbox" name="is_active" value="1" {{ old('is_active', (int) $is_active) == 1 ? 'checked' : '' }}>
                <small class="text-muted">ติ๊กเพื่อแสดงเมนูนี้ในฝั่งลูกค้า</small>

                @if ($errors->has('is_active'))
                    <div class="text-danger">{{ $errors->first('is_active') }}</div>
                @endif
            </div>
        </div>


        <div class="form-group row mb-2">
            <label class="col-sm-2"> </label>
            <div class="col-sm-5">
                {{-- เก็บ path รูปเดิมไว้ ถ้าผู้ใช้ไม่อัปโหลดใหม่ --}}
                <input type="hidden" name="oldImg" value="{{ $image_path }}">
                <button type="submit" class="btn btn-primary"> Update </button>
                <a href="/menu" class="btn btn-danger">cancel</a>
            </div>
        </div>

    </form>
    </div>


@endsection


@section('footer')
@endsection

@section('js_before')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ClassicEditor
                .create(document.querySelector('#detail'))
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
@endsection