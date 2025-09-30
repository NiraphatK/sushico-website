@extends('home')
@section('css_before')
@endsection
@section('header')
@endsection
@section('sidebarMenu')
@endsection
@section('content')



    <h3> :: Form Add Menu :: </h3>

    <form action="/menu/" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Menu Name </label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="name" required placeholder="Menu Name" minlength="3"
                    value="{{ old('name') }}">
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
                <textarea name="description" class="form-control" rows="4"
                    placeholder="Menu description">{{ old('description') }}</textarea>
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
                <textarea name="detail" id="detail" rows="8" class="form-control"
                    placeholder="รายละเอียดเพิ่มเติม (รองรับตัวหนา/รูปแบบตัวอักษร)">
                {{ old('detail') }}</textarea>
                @if (isset($errors))
                    @if ($errors->has('detail'))
                        <div class="text-danger"> {{ $errors->first('detail') }}</div>
                    @endif
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Price </label>
            <div class="col-sm-6">
                <input type="number" class="form-control" name="price" required placeholder="Price" min="0" step="0.01"
                    value="{{ old('price') }}">
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
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                <small class="text-muted">ติ๊กเพื่อแสดงในหน้าเมนูลูกค้า</small>
                @if (isset($errors))
                    @if ($errors->has('is_active'))
                        <div class="text-danger"> {{ $errors->first('is_active') }}</div>
                    @endif
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> </label>
            <div class="col-sm-5">

                <button type="submit" class="btn btn-primary"> Insert menu </button>
                <a href="/menu" class="btn btn-danger">Cancel</a>
            </div>
        </div>

    </form>

    </div>


@endsection

@section('footer')
@endsection

@section('js_before')
    {{-- CKEditor 4 (Standard) --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#detail'))
            .catch(error => {
                console.error(error);
            });
    </script>

@endsection