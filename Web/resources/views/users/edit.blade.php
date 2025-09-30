@extends('home')

@section('css_before')
    {{-- Bootstrap Icons + Google Fonts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #eef2ff;
            --bg-grad: radial-gradient(1200px 600px at 10% -10%, #a5b4fc22, transparent 60%),
                radial-gradient(900px 500px at 110% 0%, #c7d2fe22, transparent 60%);
            --surface: #ffffff;
            --glass: rgba(255, 255, 255, .75);
            --line: #e6e9f0;
            --text: #0f172a;
            --muted: #64748b;
            --brand-1: #7c3aed;
            --brand-2: #2563eb;
            --grad-cta: linear-gradient(135deg, var(--brand-2), var(--brand-1));
            --fld-h: 60px;
            --icon-pad: 2.8rem;
            --shadow-sm: 0 6px 18px rgba(2, 6, 23, .06);
            --shadow-md: 0 12px 34px rgba(2, 6, 23, .10);
            --shadow-lg: 0 24px 64px rgba(2, 6, 23, .16);
        }

        body {
            background: var(--bg);
            background-image: var(--bg-grad);
            font-family: "Inter", "Poppins", "Noto Sans Thai", system-ui, sans-serif;
            color: var(--text);
        }

        .page-wrap {
            max-width: 1024px;
            margin-inline: auto;
            margin-top: 22px;
            padding-inline: 4px;
        }

        /* ==== Hero ==== */
        .hero {
            border-radius: 26px;
            padding: 22px;
            color: #fff;
            background: linear-gradient(135deg, #60a5fa, #7c3aed 55%, #4f46e5);
            box-shadow: var(--shadow-md);
            margin-bottom: 18px;
        }

        .hero-title {
            font-weight: 900;
            font-size: clamp(1.25rem, 1rem + 1vw, 2rem);
            margin: 0;
        }

        .hero-sub {
            opacity: .94;
        }

        .btn-ghost {
            border-radius: 999px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .6rem 1rem;
            font-weight: 700;
            color: #fff;
            background: #ffffff1a;
            border: 1px solid #ffffff33;
            backdrop-filter: blur(6px);
            transition: .2s;
        }

        .btn-ghost:hover {
            background: #ffffff2a;
            transform: translateY(-1px);
        }

        .btn-cancel:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary-soft:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }

        /* ==== Card ==== */
        .glass-card {
            background: var(--glass);
            border: 1px solid var(--line);
            border-radius: 26px;
            box-shadow: var(--shadow-md);
            padding: 24px;
        }

        .sec-title {
            font-weight: 800;
            font-size: 1rem;
            margin: 6px 0 10px;
        }

        .divider {
            height: 1px;
            margin: 10px 0 14px;
            border: 0;
            background: linear-gradient(90deg, transparent, #e8ebf555 20%, #e8ebf5aa 50%, #e8ebf555 80%, transparent);
        }

        /* ==== Form ==== */
        .form-floating>.form-control,
        .form-floating>.form-select {
            height: var(--fld-h);
            border-radius: 14px;
            border: 1px solid var(--line);
            background: var(--surface);
        }

        .form-floating>.form-control:focus,
        .form-floating>.form-select:focus {
            border-color: transparent;
            outline: none;
            box-shadow: 0 0 0 6px rgba(59, 130, 246, .16);
        }

        .floating-field {
            position: relative;
        }

        .floating-field>i {
            position: absolute;
            left: 0.9rem;
            top: 0;
            height: var(--fld-h);
            display: flex;
            align-items: center;
            color: var(--muted);
            pointer-events: none;
        }

        .floating-field .with-icon {
            padding-left: var(--icon-pad);
        }

        /* focus แล้ว icon เด่นขึ้น */
        .floating-field:focus-within>i {
            color: var(--brand-2);
            transform: scale(1.04);
            opacity: 1;
        }

        .floating-field>label {
            left: var(--icon-pad) !important;
            width: calc(100% - var(--icon-pad));
        }

        .is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px #ef44441f !important;
        }

        .invalid-feedback {
            display: block;
        }

        .help {
            font-size: .86rem;
            color: var(--muted);
            margin-top: .35rem;
        }

        /* ==== Actions ==== */
        .actions {
            display: flex;
            gap: .6rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .btn-pill {
            border-radius: 999px;
            height: 50px;
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .7rem 1.15rem;
            font-weight: 900;
            border: 1px solid var(--line);
        }

        .btn-cancel {
            background: var(--surface);
            color: var(--text);
        }

        .btn-primary-soft {
            background: var(--grad-cta);
            color: #fff;
            border: none;
            box-shadow: var(--shadow-md);
        }

        /* ===== Switch (Active) ===== */
        .switch {
            position: relative;
            display: inline-block;
            width: 54px;
            height: 30px;
            vertical-align: middle;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            inset: 0;
            cursor: pointer;
            background: #e5e7eb;
            border-radius: 999px;
            transition: background .2s, box-shadow .2s;
        }

        .slider:before {
            content: "";
            position: absolute;
            height: 22px;
            width: 22px;
            left: 4px;
            top: 4px;
            background: #fff;
            border-radius: 50%;
            transition: .2s;
        }

        .switch input:checked+.slider {
            background: #22c55e;
        }

        .switch input:checked+.slider:before {
            transform: translateX(24px);
        }

        .switch:hover .slider {
            box-shadow: 0 0 0 4px rgba(34, 197, 94, .12);
        }
    </style>
@endsection

@section('content')
    <div class="container page-wrap">
        <!-- Hero -->
        <div class="hero">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="hero-title">Edit User</h1>
                    <div class="hero-sub">แก้ไขข้อมูลผู้ใช้งาน — ปรับปรุงชื่อ อีเมล เบอร์โทร บทบาท และสถานะ</div>
                </div>
                <a href="/users" class="btn-ghost btn"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
        </div>

        <!-- Card -->
        <div class="glass-card">
            <form action="/users/{{ $user_id }}" method="post" novalidate>
                @csrf
                @method('put')

                <h6 class="sec-title">Account Information</h6>
                <hr class="divider">

                {{-- Full Name --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-person"></i>
                    <input type="text" class="form-control with-icon @if (isset($errors) && $errors->has('full_name')) is-invalid @endif"
                        id="full_name" name="full_name" placeholder=" " required value="{{ old('full_name', $full_name) }}">
                    <label for="full_name">Full Name <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('full_name'))
                        <div class="invalid-feedback">{{ $errors->first('full_name') }}</div>
                    @endif
                </div>

                {{-- Phone --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-telephone"></i>
                    <input type="text" class="form-control with-icon @if (isset($errors) && $errors->has('phone')) is-invalid @endif"
                        id="phone" name="phone" placeholder=" " required value="{{ old('phone', $phone) }}">
                    <label for="phone">Phone <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('phone'))
                        <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                    @endif
                    <div class="help">ระบบจะจัดรูปแบบ 08x-xxx-xxxx ให้อัตโนมัติ</div>
                </div>

                {{-- Email --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-envelope"></i>
                    <input type="email" class="form-control with-icon @if (isset($errors) && $errors->has('email')) is-invalid @endif"
                        id="email" name="email" placeholder=" " value="{{ old('email', $email) }}">
                    <label for="email">Email</label>
                    @if (isset($errors) && $errors->has('email'))
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <div class="sec-title">Role</div>
                    <select name="role" class="form-select @if (isset($errors) && $errors->has('role')) is-invalid @endif">
                        <option value="CUSTOMER" @if (old('role', $role) == 'CUSTOMER') selected @endif>Customer</option>
                        <option value="STAFF" @if (old('role', $role) == 'STAFF') selected @endif>Staff</option>
                        <option value="ADMIN" @if (old('role', $role) == 'ADMIN') selected @endif>Admin</option>
                    </select>
                    @if (isset($errors) && $errors->has('role'))
                        <div class="invalid-feedback">{{ $errors->first('role') }}</div>
                    @endif
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <div class="sec-title">Status</div>
                    <input type="hidden" name="is_active" value="0">

                    <div class="d-flex align-items-center gap-3">
                        <label class="switch m-0">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', (int) $is_active) == 1 ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <span class="text-muted">
                            {{ old('is_active', (int) $is_active) == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>


                <!-- Actions -->
                <div class="actions">
                    <a href="/users" class="btn-pill btn-cancel btn"><i class="bi bi-x-circle"></i> Cancel</a>
                    <button type="submit" class="btn-pill btn-primary-soft"><i class="bi bi-check2-circle"></i>
                        Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_before')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const phone = document.getElementById('phone');
            if (phone) {
                const fmt = (v) => {
                    v = (v || '').replace(/\D/g, '').slice(0, 10);
                    if (v.length <= 3) return v;
                    if (v.length <= 6) return v.slice(0, 3) + '-' + v.slice(3);
                    return v.slice(0, 3) + '-' + v.slice(3, 6) + '-' + v.slice(6);
                };
                phone.addEventListener('input', () => {
                    phone.value = fmt(phone.value);
                });
                const form = phone.closest('form');
                if (form) {
                    form.addEventListener('submit', () => {
                        phone.value = phone.value.replace(/\D/g, '');
                    });
                }
            }
        });
    </script>
@endsection
