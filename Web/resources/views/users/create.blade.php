@extends('home')

@section('css_before')
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ==== Design Tokens (Light only) ==== */
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
            /* violet-600 */
            --brand-2: #2563eb;
            /* blue-600 */
            --brand-3: #06b6d4;
            /* cyan-500 */

            --ok: #10b981;
            --warn: #f59e0b;
            --bad: #ef4444;

            --r-xl: 26px;
            --r-lg: 20px;
            --r-md: 14px;

            --shadow-sm: 0 6px 18px rgba(2, 6, 23, .06);
            --shadow-md: 0 12px 34px rgba(2, 6, 23, .10);
            --shadow-lg: 0 24px 64px rgba(2, 6, 23, .16);

            --focus: 0 0 0 6px rgba(59, 130, 246, .16);

            --grad-hero: linear-gradient(135deg, #60a5fa, #7c3aed 55%, #4f46e5);
            --grad-cta: linear-gradient(135deg, var(--brand-2), var(--brand-1));
            --ring-anim: linear-gradient(135deg, #60a5fa, #7c3aed, #22d3ee);

            /* ความสูงฟิลด์ลอย + ใช้จัด icon ให้อยู่กลางแนวตั้ง */
            --fld-h: 60px;
            --icon-pad: 2.8rem;
        }

        /* ==== Page Shell ==== */
        body {
            background: var(--bg);
            background-image: var(--bg-grad);
            background-attachment: fixed;
            font-family: "Inter", "Poppins", "Noto Sans Thai", system-ui, sans-serif;
            color: var(--text);
        }

        .page-wrap {
            max-width: 1024px;
            margin-inline: auto;
            margin-top: 22px;
            padding-inline: 4px;
        }

        /* ==== Hero (animated border) ==== */
        .hero {
            position: relative;
            border-radius: var(--r-xl);
            padding: clamp(18px, 2.6vw, 26px);
            color: #fff;
            background: var(--grad-hero);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            isolation: isolate;
        }

        .hero::before {
            content: "";
            position: absolute;
            inset: -2px;
            border-radius: inherit;
            padding: 2px;
            background: var(--ring-anim);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            animation: hue 12s linear infinite;
            opacity: .35;
            pointer-events: none;
        }

        @keyframes hue {
            to {
                filter: hue-rotate(360deg);
            }
        }

        .hero-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .hero-title {
            margin: 0;
            font-weight: 900;
            letter-spacing: .2px;
            font-size: clamp(1.25rem, .9rem + 1.2vw, 2rem);
        }

        .hero-sub {
            font-family: "Noto Sans Thai", sans-serif;
            opacity: .94;
            max-width: 56ch;
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

        /* ==== Card ==== */
        .glass-card {
            position: relative;
            background: var(--glass);
            border: 1px solid var(--line);
            border-radius: var(--r-xl);
            box-shadow: var(--shadow-md);
            padding: clamp(16px, 2.2vw, 24px);
            overflow: hidden;
        }

        .glass-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(600px 120px at 10% 0%, #60a5fa17, transparent 55%),
                radial-gradient(500px 140px at 90% 10%, #7c3aed14, transparent 55%);
            pointer-events: none;
        }

        .sec-title {
            font-weight: 800;
            margin: 6px 0 10px;
            font-size: 1rem;
            color: var(--text);
        }

        .divider {
            height: 1px;
            margin: 10px 0 14px;
            border: 0;
            background: linear-gradient(90deg, transparent, #e8ebf555 20%, #e8ebf5aa 50%, #e8ebf555 80%, transparent);
        }

        /* ==== Grid ==== */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width:767.98px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ==== Floating + Icon (VERTICAL CENTER FIX) ==== */
        .floating-field {
            position: relative;
        }

        /* icon ซ้าย: สูงเท่าฟิลด์ และจัดกลางแนวตั้ง */
        .floating-field>i {
            position: absolute;
            left: 0.9rem;
            top: 0;
            /* ยึดจากขอบบนของกล่องฟิลด์ */
            height: var(--fld-h);
            /* สูงเท่าความสูง input */
            display: flex;
            align-items: center;
            color: var(--muted);
            pointer-events: none;
            transition: .2s;
            font-size: 1rem;
        }

        /* เว้น padding input ให้พอดีกับ icon */
        .floating-field .with-icon {
            padding-left: var(--icon-pad);
        }

        /* ขยับ label ให้ไม่ชน icon */
        .floating-field>label {
            left: var(--icon-pad) !important;
            width: calc(100% - var(--icon-pad));
            color: #6b7280;
        }

        /* form-floating control height (ต้องคงที่เพื่อให้ icon กลางเป๊ะ) */
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
            box-shadow: var(--focus);
        }

        /* focus แล้ว icon เด่นขึ้น */
        .floating-field:focus-within>i {
            color: var(--brand-2);
            transform: scale(1.04);
            opacity: 1;
        }

        /* ==== Validation ==== */
        .is-invalid {
            border-color: var(--bad) !important;
            box-shadow: 0 0 0 3px #ef44441f !important;
        }

        .invalid-feedback {
            display: block;
        }

        /* ==== Role Segmented ==== */
        .role-group {
            display: flex;
            gap: .55rem;
            flex-wrap: wrap;
        }

        .role-chip {
            position: relative;
            border: 1px solid var(--line);
            background: var(--surface);
            color: var(--text);
            border-radius: 999px;
            padding: .6rem 1rem;
            font-weight: 800;
            cursor: pointer;
            user-select: none;
            transition: .18s ease;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            box-shadow: 0 1px 0 rgba(2, 6, 23, .06);
            cursor: pointer;
        }

        .role-chip:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .role-chip input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .role-chip.active {
            background: var(--grad-cta);
            color: #fff;
            border-color: transparent;
            box-shadow: var(--shadow-md);
        }

        .role-chip .tag {
            font-size: .8rem;
            opacity: .92;
        }

        /* ==== Helper & Meter ==== */
        .help {
            font-size: .86rem;
            color: var(--muted);
            margin-top: .35rem;
        }

        .meter {
            display: flex;
            gap: .35rem;
            margin-top: .55rem;
        }

        .meter span {
            flex: 1;
            height: 6px;
            border-radius: 999px;
            background: #e5e7eb;
            transition: .2s;
        }

        .meter[data-score="1"] span:nth-child(1) {
            background: var(--bad);
        }

        .meter[data-score="2"] span:nth-child(-n+2) {
            background: var(--warn);
        }

        .meter[data-score="3"] span:nth-child(-n+3) {
            background: #f59e0b;
        }

        .meter[data-score="4"] span:nth-child(-n+4) {
            background: #34d399;
        }

        .meter[data-score="5"] span:nth-child(-n+5) {
            background: var(--ok);
        }

        /* ==== Actions ==== */
        .actions {
            display: flex;
            gap: .6rem;
            flex-wrap: wrap;
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
            transition: .2s;
        }

        .btn-cancel {
            background: var(--surface);
            color: var(--text);
        }

        .btn-cancel:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary-soft {
            background: var(--grad-cta);
            color: #fff;
            border-color: transparent;
            box-shadow: var(--shadow-md);
        }

        .btn-primary-soft:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }

        /* ==== Eye Button (ขวา) จัดกลางแนวตั้งเท่าฟิลด์ ==== */
        .eye {
            position: absolute;
            right: .55rem;
            top: 0;
            /* ยึดจากบนเหมือน icon ซ้าย */
            height: var(--fld-h);
            display: flex;
            align-items: center;
            background: transparent;
            border: none;
            color: #6b7280;
            padding: .35rem .5rem;
            border-radius: 10px;
            transition: .15s;
        }

        .eye:hover {
            background: #f3f4f6;
            color: #111827;
        }

        /* ==== Sticky Actions (mobile) ==== */
        @media (max-width:575.98px) {
            .actions {
                position: sticky;
                bottom: 0;
                background: linear-gradient(180deg, transparent, var(--surface) 35%, var(--surface) 100%);
                padding-top: .35rem;
                padding-bottom: .15rem;
                z-index: 10;
            }
        }
    </style>
@endsection



@section('content')
    <div class="container page-wrap">
        <!-- Hero -->
        <div class="hero">
            <div class="hero-row">
                <div>
                    <h1 class="hero-title">Add User</h1>
                    <div class="hero-sub">เพิ่มผู้ใช้งานใหม่ — รองรับอีเมล เบอร์โทร ตั้งรหัสผ่าน และกำหนดบทบาท</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="/users" class="btn-ghost btn"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="glass-card mt-3">
            <form action="/users/" method="post" novalidate>
                @csrf

                <h6 class="sec-title">Account Information</h6>
                <hr class="divider">

                <div class="form-grid">
                    {{-- Full Name --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-person"></i>
                        <input type="text" class="form-control with-icon @error('full_name') is-invalid @enderror"
                            id="full_name" name="full_name" placeholder=" " required value="{{ old('full_name') }}">
                        <label for="full_name">Full Name <span class="text-danger">*</span></label>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-envelope"></i>
                        <input type="email" class="form-control with-icon @error('email') is-invalid @enderror"
                            id="email" name="email" placeholder=" " value="{{ old('email') }}">
                        <label for="email">Email (optional)</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-telephone"></i>
                        <input type="text" inputmode="tel"
                            class="form-control with-icon @error('phone') is-invalid @enderror" id="phone"
                            name="phone" placeholder=" " required value="{{ old('phone') }}">
                        <label for="phone">Phone <span class="text-danger">*</span></label>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="help">ระบบจะจัดรูปแบบ 08x-xxx-xxxx ให้อัตโนมัติ</div>
                    </div>

                    {{-- Role (segmented) --}}
                    <div>
                        <div class="sec-title" style="margin-top:-2px">Role *</div>
                        <div class="role-group" id="roleGroup">
                            @php $oldRole = old('role','CUSTOMER'); @endphp
                            <label class="role-chip {{ $oldRole === 'CUSTOMER' ? 'active' : '' }}">
                                <input type="radio" name="role" value="CUSTOMER"
                                    {{ $oldRole === 'CUSTOMER' ? 'checked' : '' }}>
                                <i class="bi bi-person-badge"></i> Customer
                            </label>
                            <label class="role-chip {{ $oldRole === 'STAFF' ? 'active' : '' }}">
                                <input type="radio" name="role" value="STAFF"
                                    {{ $oldRole === 'STAFF' ? 'checked' : '' }}>
                                <i class="bi bi-briefcase"></i> Staff
                            </label>
                            <label class="role-chip {{ $oldRole === 'ADMIN' ? 'active' : '' }}">
                                <input type="radio" name="role" value="ADMIN"
                                    {{ $oldRole === 'ADMIN' ? 'checked' : '' }}>
                                <i class="bi bi-shield-lock"></i> Admin <span class="tag">Full access</span>
                            </label>
                        </div>
                        @error('role')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <h6 class="sec-title mt-3">Security</h6>
                <hr class="divider">

                <div class="form-grid">
                    {{-- Password --}}
                    <div class="form-floating floating-field position-relative">
                        <i class="bi bi-key"></i>
                        <input type="password" class="form-control with-icon @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder=" " required>
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <button type="button" class="eye" aria-label="Toggle password" data-eye="pw">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="meter mt-2" id="pwMeter" aria-hidden="true">
                            <span></span><span></span><span></span><span></span><span></span>
                        </div>
                        <div class="help" id="pwHint">
                            แนะนำ: อย่างน้อย 8 ตัวอักษร ผสม ตัวพิมพ์เล็ก/ใหญ่ ตัวเลข และตัวอักษรพิเศษ
                        </div>
                    </div>

                    {{-- Spacer --}}
                    <div class="d-none d-md-block"></div>
                </div>

                <div class="actions">
                    <a href="/users" class="btn-pill btn-cancel btn"><i class="bi bi-x-circle"></i> Cancel</a>
                    <button type="submit" class="btn-pill btn-primary-soft">
                        <i class="bi bi-check2-circle"></i> Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_before')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Role segmented active state
            const roleGroup = document.getElementById('roleGroup');
            if (roleGroup) {
                roleGroup.querySelectorAll('input[type="radio"]').forEach(r => {
                    r.addEventListener('change', () => {
                        roleGroup.querySelectorAll('.role-chip').forEach(c => c.classList.remove(
                            'active'));
                        r.closest('.role-chip').classList.add('active');
                    });
                });
            }

            // Toggle password eye
            const eyeBtn = document.querySelector('[data-eye="pw"]');
            const pw = document.getElementById('password');
            if (eyeBtn && pw) {
                eyeBtn.addEventListener('click', () => {
                    const icon = eyeBtn.querySelector('i');
                    if (pw.type === 'password') {
                        pw.type = 'text';
                        icon.classList.replace('bi-eye', 'bi-eye-slash');
                    } else {
                        pw.type = 'password';
                        icon.classList.replace('bi-eye-slash', 'bi-eye');
                    }
                    pw.focus();
                });
            }

            // Password strength meter
            const meter = document.getElementById('pwMeter');
            const hint = document.getElementById('pwHint');

            function scorePass(s) {
                let sc = 0;
                if (!s) return 0;
                if (s.length >= 8) sc++;
                if (/[a-z]/.test(s) && /[A-Z]/.test(s)) sc++;
                if (/\d/.test(s)) sc++;
                if (/[^\w\s]/.test(s)) sc++;
                if (s.length >= 12) sc++;
                return Math.min(sc, 5);
            }
            if (pw && meter) {
                pw.addEventListener('input', () => {
                    const sc = scorePass(pw.value);
                    meter.dataset.score = sc;
                    if (sc <= 1) hint.textContent =
                        'รหัสผ่านอ่อนมาก — เพิ่มความยาวและผสมตัวอักษร/ตัวเลข/สัญลักษณ์';
                    else if (sc === 2) hint.textContent =
                        'ยังอ่อน — ลองใช้ตัวพิมพ์ใหญ่/เล็กผสมและเพิ่มความยาว';
                    else if (sc === 3) hint.textContent = 'พอใช้ — เพิ่มสัญลักษณ์หรือความยาวให้ปลอดภัยขึ้น';
                    else hint.textContent = 'ดีมาก — ความปลอดภัยเหมาะสม';
                });
            }

            // Phone auto-format 08x-xxx-xxxx (save digits only)
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
                const formForPhone = phone.closest('form');
                if (formForPhone) {
                    formForPhone.addEventListener('submit', () => {
                        phone.value = phone.value.replace(/\D/g, '');
                    });
                }
            }

            // Prevent double submit
            const form = document.querySelector('form[action="/users/"]');
            if (form) {
                form.addEventListener('submit', () => {
                    const btn = form.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
                    }
                });
            }
        });
    </script>
@endsection
