@extends('home')

@section('css_before')
    {{-- Bootstrap Icons + Google Fonts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ==== Design Tokens ==== */
        :root{
            --bg:#eef2ff;
            --bg-grad: radial-gradient(1200px 600px at 10% -10%, #a5b4fc22, transparent 60%),
                       radial-gradient(900px 500px at 110% 0%, #c7d2fe22, transparent 60%);
            --surface:#fff; --glass:rgba(255,255,255,.75); --line:#e6e9f0;
            --text:#0f172a; --muted:#64748b;
            --brand-1:#7c3aed; --brand-2:#2563eb;
            --grad-hero:linear-gradient(135deg,#60a5fa,#7c3aed 55%,#4f46e5);
            --grad-cta:linear-gradient(135deg,var(--brand-2),var(--brand-1));
            --ring-anim:linear-gradient(135deg,#60a5fa,#7c3aed,#22d3ee);
            --fld-h:60px; --icon-pad:2.8rem;
            --r-xl:26px; --shadow-sm:0 6px 18px rgba(2,6,23,.06);
            --shadow-md:0 12px 34px rgba(2,6,23,.10);
            --shadow-lg:0 24px 64px rgba(2,6,23,.16);
            --focus:0 0 0 6px rgba(59,130,246,.16);
        }
        body{background:var(--bg);background-image:var(--bg-grad);background-attachment:fixed;
             font-family:"Inter","Poppins","Noto Sans Thai",system-ui,sans-serif;color:var(--text);}
        .page-wrap{max-width:1024px;margin-inline:auto;margin-top:22px;padding-inline:4px;}

        /* Hero */
        .hero{position:relative;border-radius:var(--r-xl);padding:clamp(18px,2.6vw,26px);color:#fff;
              background:var(--grad-hero);box-shadow:var(--shadow-md);overflow:hidden;isolation:isolate;}
        .hero::before{content:"";position:absolute;inset:-2px;border-radius:inherit;padding:2px;background:var(--ring-anim);
            -webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);
            -webkit-mask-composite:xor;mask-composite:exclude;animation:hue 12s linear infinite;opacity:.35;}
        @keyframes hue{to{filter:hue-rotate(360deg)}}
        .hero-row{display:flex;align-items:center;justify-content:space-between;gap:.8rem;flex-wrap:wrap;}
        .hero-title{margin:0;font-weight:900;letter-spacing:.2px;font-size:clamp(1.25rem,.9rem + 1.2vw,2rem);}
        .hero-sub{opacity:.94;max-width:56ch}
        .btn-ghost{border-radius:999px;height:46px;display:inline-flex;align-items:center;gap:.6rem;padding:.6rem 1rem;
            font-weight:700;color:#fff;background:#ffffff1a;border:1px solid #ffffff33;backdrop-filter:blur(6px);transition:.2s;}
        .btn-ghost:hover{background:#ffffff2a;transform:translateY(-1px);}

        /* Card */
        .glass-card{position:relative;background:var(--glass);border:1px solid var(--line);border-radius:var(--r-xl);
            box-shadow:var(--shadow-md);padding:clamp(16px,2.2vw,24px);overflow:hidden;}
        .glass-card::after{content:"";position:absolute;inset:0;
            background-image:radial-gradient(600px 120px at 10% 0%,#60a5fa17,transparent 55%),
                              radial-gradient(500px 140px at 90% 10%,#7c3aed14,transparent 55%);pointer-events:none;}
        .sec-title{font-weight:800;margin:6px 0 10px;font-size:1rem}
        .divider{height:1px;margin:10px 0 14px;border:0;
            background:linear-gradient(90deg,transparent,#e8ebf555 20%,#e8ebf5aa 50%,#e8ebf555 80%,transparent);}

        /* Grid */
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
        @media(max-width:767.98px){.form-grid{grid-template-columns:1fr;}}

        /* Floating + Icon */
        .floating-field{position:relative;}
        .floating-field>i{position:absolute;left:.9rem;top:0;height:var(--fld-h);display:flex;align-items:center;
            color:var(--muted);pointer-events:none;font-size:1rem;transition:.2s;}
        .floating-field .with-icon{padding-left:var(--icon-pad);}
        .floating-field>label{left:var(--icon-pad) !important;width:calc(100% - var(--icon-pad));color:#6b7280;}
        .form-floating>.form-control,.form-floating>.form-select{
            height:var(--fld-h);border-radius:14px;border:1px solid var(--line);background:var(--surface);}
        .form-floating>.form-control:focus,.form-floating>.form-select:focus{
            border-color:transparent;outline:none;box-shadow:var(--focus);}
        .floating-field:focus-within>i{color:var(--brand-2);transform:scale(1.04);opacity:1;}
        .help{font-size:.86rem;color:var(--muted);margin-top:.35rem;}
        .is-invalid{border-color:#ef4444 !important;box-shadow:0 0 0 3px #ef44441f !important;}
        .invalid-feedback{display:block;}

        /* Actions */
        .actions{display:flex;gap:.6rem;flex-wrap:wrap;justify-content:flex-end;margin-top:1rem;}
        .btn-pill{border-radius:999px;height:50px;display:inline-flex;align-items:center;gap:.6rem;padding:.7rem 1.15rem;
            font-weight:900;border:1px solid var(--line);transition:.2s;}
        .btn-cancel{background:var(--surface);color:var(--text);}
        .btn-cancel:hover{transform:translateY(-1px);box-shadow:var(--shadow-sm);}
        .btn-primary-soft{background:var(--grad-cta);color:#fff;border-color:transparent;box-shadow:var(--shadow-md);}
        .btn-primary-soft:hover{transform:translateY(-1px);box-shadow:var(--shadow-lg);}
        @media(max-width:575.98px){
            .actions{position:sticky;bottom:0;background:linear-gradient(180deg,transparent,var(--surface) 35%,var(--surface) 100%);
                padding-top:.35rem;padding-bottom:.15rem;z-index:10;}
        }

        /* Image uploader */
        .uploader{display:flex;align-items:center;gap:12px;}
        .thumb{width:96px;height:96px;border-radius:14px;background:#f3f4f6;border:1px dashed #e5e7eb;
               display:flex;align-items:center;justify-content:center;overflow:hidden;}
        .thumb img{width:100%;height:100%;object-fit:cover;}

        /* CKEditor look */
        .ck-editor__editable[role="textbox"]{min-height:220px;border-radius:14px !important;}
        .ck.ck-toolbar{border-top-left-radius:14px !important;border-top-right-radius:14px !important;}

        /* Switch */
        .switch{position:relative;display:inline-block;width:54px;height:30px;vertical-align:middle;}
        .switch input{opacity:0;width:0;height:0;}
        .slider{position:absolute;inset:0;cursor:pointer;background:#e5e7eb;border-radius:999px;
            transition:background .2s,box-shadow .2s;}
        .slider:before{content:"";position:absolute;height:22px;width:22px;left:4px;top:4px;background:#fff;border-radius:50%;
            transition:.2s;box-shadow:var(--shadow-sm);}
        .switch input:checked + .slider{background:#22c55e;box-shadow:var(--shadow-md);}
        .switch input:checked + .slider:before{transform:translateX(24px);}
        .switch:has(input:focus) .slider{box-shadow:var(--focus);}
    </style>
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
        <form action="/menu/{{ $menu_id }}" method="post" enctype="multipart/form-data" novalidate id="menuUpdateForm">
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
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Price --}}
                <div class="form-floating floating-field">
                    <i class="bi bi-cash-coin"></i>
                    <input type="number" class="form-control with-icon @error('price') is-invalid @enderror"
                           id="price" name="price" placeholder=" " required min="0" step="0.01"
                           value="{{ old('price', $price) }}">
                    <label for="price">Price (THB) <span class="text-danger">*</span></label>
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="help">ระบบจะปรับเป็นทศนิยม 2 ตำแหน่งอัตโนมัติก่อนบันทึก</div>
                </div>

                {{-- Short Description --}}
                <div class="form-floating floating-field" style="grid-column:1 / -1;">
                    <i class="bi bi-card-text"></i>
                    <textarea class="form-control with-icon @error('description') is-invalid @enderror"
                              id="description" name="description" placeholder=" "
                              rows="3" style="height:var(--fld-h);">{{ old('description', $description) }}</textarea>
                    <label for="description">Short Description</label>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <h6 class="sec-title mt-3">Detail & Media</h6>
            <hr class="divider">

            <div class="form-grid">
                {{-- Detail (CKEditor) --}}
                <div style="grid-column:1 / -1;">
                    <label class="sec-title" for="detail" style="margin-top:0">Detail (rich-text)</label>
                    <textarea name="detail" id="detail" rows="8"
                              class="form-control @error('detail') is-invalid @enderror"
                              placeholder="รายละเอียดเพิ่มเติม (รองรับตัวหนา/รูปแบบตัวอักษร)">{{ old('detail', $detail ?? '') }}</textarea>
                    @error('detail') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
                            @error('image_path') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
                    @error('is_active') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
    {{-- SweetAlert (ถ้าใช้) --}}
    @include('sweetalert::alert')

    {{-- CKEditor 5 --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // CKEditor
        ClassicEditor.create(document.querySelector('#detail')).catch(console.error);

        // Image preview for new upload
        const inputImg = document.getElementById('image_path');
        const box = document.getElementById('previewBox');
        let imgEl = null;
        const showPreview = (file) => {
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                if (!imgEl) {
                    imgEl = document.createElement('img');
                    box.innerHTML = '';
                    box.appendChild(imgEl);
                }
                imgEl.src = e.target.result;
            };
            reader.readAsDataURL(file);
        };
        if (inputImg) {
            inputImg.addEventListener('change', () => {
                const f = inputImg.files?.[0];
                if (f) showPreview(f);
            });
        }

        // Price normalize (2 decimals on blur)
        const price = document.getElementById('price');
        if (price) {
            price.addEventListener('blur', () => {
                if (price.value !== '') {
                    const v = Number(price.value);
                    if (!Number.isNaN(v)) price.value = v.toFixed(2);
                }
            });
        }

        // Live text for Active/Inactive
        const activeChk = document.querySelector('input[name="is_active"][type="checkbox"]');
        const statusText = document.getElementById('statusText');
        if (activeChk && statusText) {
            const render = () => { statusText.textContent = activeChk.checked ? 'Active' : 'Inactive'; };
            render();
            activeChk.addEventListener('change', render);
        }

        // Prevent double submit
        const form = document.getElementById('menuUpdateForm');
        if (form) {
            form.addEventListener('submit', () => {
                if (price && price.value !== '') {
                    const v = Number(price.value);
                    if (!Number.isNaN(v)) price.value = v.toFixed(2);
                }
                const btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';
                }
            });
        }
    });
    </script>
@endsection
