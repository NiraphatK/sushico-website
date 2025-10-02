@extends('home')

@section('css_before')
    {{-- Bootstrap Icons + Google Fonts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
@endsection

@section('content')
    <div class="container page-wrap">
        <!-- Hero -->
        <div class="hero">
            <div class="hero-row">
                <div>
                    <h1 class="hero-title">Edit Menu</h1>
                    <div class="hero-sub">แก้ไขเมนูอาหาร — ปรับชื่อ รายละเอียด ราคา รูปภาพ และสถานะการแสดงผล</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="/menu" class="btn-ghost btn"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="glass-card">
            <form action="/menu/{{ $menu_id }}" method="post" enctype="multipart/form-data" novalidate
                id="menuUpdateForm">
                @csrf
                @method('put')

                <h6 class="sec-title">Basic Information</h6>
                <hr class="divider">

                <div class="form-grid">
                    {{-- Menu Name --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-journal-text"></i>
                        <input type="text" class="form-control with-icon @error('name') is-invalid @enderror"
                            id="name" name="name" placeholder=" " required minlength="3"
                            value="{{ old('name', $name) }}">
                        <label for="name">Menu Name <span class="text-danger">*</span></label>
                        @if (isset($errors) && $errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-cash-coin"></i>
                        <input type="number" class="form-control with-icon @error('price') is-invalid @enderror"
                            id="price" name="price" placeholder=" " required min="0" step="0.01"
                            value="{{ old('price', $price) }}">
                        <label for="price">Price (THB) <span class="text-danger">*</span></label>
                        @if (isset($errors) && $errors->has('price'))
                            <div class="invalid-feedback">{{ $errors->first('price') }}</div>
                        @endif
                        <div class="help">ระบบจะปรับเป็นทศนิยม 2 ตำแหน่งอัตโนมัติก่อนบันทึก</div>
                    </div>

                    {{-- Short Description --}}
                    <div class="form-floating floating-field" style="grid-column:1 / -1;">
                        <i class="bi bi-card-text"></i>
                        <textarea class="form-control with-icon @error('description') is-invalid @enderror" id="description" name="description"
                            placeholder=" " rows="3" style="height:var(--fld-h);">{{ old('description', $description) }}</textarea>
                        <label for="description">Short Description</label>
                        @if (isset($errors) && $errors->has('description'))
                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                        @endif
                    </div>
                </div>

                <hr class="divider">

                <div class="form-grid">
                    {{-- Detail (CKEditor) --}}
                    <div style="grid-column:1 / -1;">
                        <label class="sec-title" for="detail" style="margin-top:0">Detail</label>
                        <textarea name="detail" id="detail" rows="8" class="form-control @error('detail') is-invalid @enderror"
                            placeholder="รายละเอียดเพิ่มเติม (รองรับตัวหนา/รูปแบบตัวอักษร)">{{ old('detail', $detail ?? '') }}</textarea>
                        @if (isset($errors) && $errors->has('detail'))
                            <div class="invalid-feedback d-block">{{ $errors->first('detail') }}</div>
                        @endif
                    </div>

                    {{-- Image: current + choose new + preview --}}
                    <div>
                        <label class="sec-title" style="margin-top:0">Image</label>

                        <div class="mb-2 text-muted">Current image</div>
                        <div class="uploader mb-2">
                            <div class="thumb">
                                @if ($image_path)
                                    <img src="{{ asset('storage/' . $image_path) }}" alt="Current image">
                                @else
                                    <i class="bi bi-image"></i>
                                @endif
                            </div>
                            <div class="help">ไฟล์ปัจจุบัน (ถ้าไม่อัปโหลดใหม่ ระบบจะใช้รูปเดิม)</div>
                        </div>

                        <div class="mb-1">Choose new image</div>
                        <div class="uploader">
                            <div class="thumb" id="previewBox"><i class="bi bi-image" id="previewIcon"></i></div>
                            <div>
                                <input type="file" id="image_path" name="image_path" accept="image/*"
                                    class="@error('image_path') is-invalid @enderror">
                                <div class="help">รองรับ JPG/PNG สูงสุด 2MB</div>
                                @if (isset($errors) && $errors->has('image_path'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('image_path') }}</div>
                                @endif
                            </div>
                        </div>
                        {{-- keep old path if not upload --}}
                        <input type="hidden" name="oldImg" value="{{ $image_path }}">
                    </div>

                    {{-- Status (custom switch) --}}
                    <div class="align-self-end">
                        <div class="sec-title">Status</div>
                        {{-- ส่งค่า 0 ถ้าไม่ติ๊ก --}}
                        <input type="hidden" name="is_active" value="0">
                        <div class="d-flex align-items-center gap-3">
                            <label class="switch m-0" aria-label="Toggle menu active">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', (int) $is_active) == 1 ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                            <span class="text-muted" id="statusText">
                                {{ old('is_active', (int) $is_active) == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        @if (isset($errors) && $errors->has('is_active'))
                            <div class="invalid-feedback d-block">{{ $errors->first('is_active') }}</div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="actions">
                    <a href="/menu" class="btn-pill btn-cancel btn"><i class="bi bi-x-circle"></i> Cancel</a>
                    <button type="submit" class="btn-pill btn-primary-soft">
                        <i class="bi bi-check2-circle"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_before')
    @include('sweetalert::alert')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
@endsection
