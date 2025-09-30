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
            --shadow-md: 0 12px 34px rgba(2, 6, 23, .10);
        }

        body {
            background: var(--bg);
            background-image: var(--bg-grad);
            font-family: "Inter", "Poppins", "Noto Sans Thai", system-ui, sans-serif;
            color: var(--text);
        }

        .page-wrap {
            max-width: 768px;
            margin-inline: auto;
            margin-top: 22px;
            padding-inline: 4px;
        }

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
        }

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

        .form-floating>.form-control {
            height: var(--fld-h);
            border-radius: 14px;
            border: 1px solid var(--line);
            background: var(--surface);
        }

        .form-floating>.form-control:focus {
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

        .floating-field>label {
            left: var(--icon-pad) !important;
            width: calc(100% - var(--icon-pad));
        }

        .eye {
            position: absolute;
            right: .55rem;
            top: 0;
            height: var(--fld-h);
            display: flex;
            align-items: center;
            background: transparent;
            border: none;
            color: #6b7280;
            padding: .35rem .5rem;
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
        }

        .meter[data-score="1"] span:nth-child(1) {
            background: #ef4444;
        }

        .meter[data-score="2"] span:nth-child(-n+2) {
            background: #f59e0b;
        }

        .meter[data-score="3"] span:nth-child(-n+3) {
            background: #fbbf24;
        }

        .meter[data-score="4"] span:nth-child(-n+4) {
            background: #34d399;
        }

        .meter[data-score="5"] span:nth-child(-n+5) {
            background: #10b981;
        }

        .help {
            font-size: .86rem;
            color: var(--muted);
            margin-top: .35rem;
        }

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

        .is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px #ef44441f !important;
        }

        .invalid-feedback {
            display: block;
        }

        input[disabled],
        input[readonly] {
            opacity: 0.7;
            cursor: not-allowed !important;
        }
    </style>
@endsection

@section('content')
    <div class="container page-wrap">
        <!-- Hero -->
        <div class="hero d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="hero-title">Reset Password</h1>
                <div class="hero-sub">ตั้งรหัสผ่านใหม่สำหรับผู้ใช้งาน</div>
            </div>
            <a href="/users" class="btn-ghost btn"><i class="bi bi-arrow-left"></i> Back</a>
        </div>

        <!-- Card -->
        <div class="glass-card">
            <form action="/users/reset/{{ $user_id }}" method="post" novalidate>
                @csrf
                @method('put')

                {{-- Full Name --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-person"></i>
                    <input type="text" class="form-control-plaintext with-icon" value="{{ $full_name }}" readonly>
                    <label>Full Name</label>
                </div>

                {{-- Email --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-envelope"></i>
                    <input type="email" class="form-control-plaintext with-icon" value="{{ $email }}" readonly>
                    <label>Email</label>
                </div>

                {{-- Phone --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-telephone"></i>
                    <input type="text" class="form-control-plaintext with-icon" value="{{ $phone }}" readonly>
                    <label>Phone</label>
                </div>


                {{-- New Password --}}
                <div class="form-floating floating-field position-relative mb-3">
                    <i class="bi bi-key"></i>
                    <input type="password"
                        class="form-control with-icon @if (isset($errors) && $errors->has('password')) is-invalid @endif" id="password"
                        name="password" placeholder=" " required minlength="6">
                    <label for="password">New Password</label>
                    <button type="button" class="eye" data-eye="pw"><i class="bi bi-eye"></i></button>
                    @if (isset($errors) && $errors->has('password'))
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    @endif
                    <div class="meter mt-2" id="pwMeter"><span></span><span></span><span></span><span></span><span></span>
                    </div>
                    <div class="help" id="pwHint">แนะนำ: อย่างน้อย 8 ตัวอักษร ผสมตัวพิมพ์เล็ก/ใหญ่ ตัวเลข และสัญลักษณ์
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="form-floating floating-field position-relative mb-3">
                    <i class="bi bi-shield-lock"></i>
                    <input type="password"
                        class="form-control with-icon @if (isset($errors) && $errors->has('password_confirmation')) is-invalid @endif"
                        id="password_confirmation" name="password_confirmation" placeholder=" " required minlength="6">
                    <label for="password_confirmation">Confirm Password</label>
                    <button type="button" class="eye" data-eye="pw2"><i class="bi bi-eye"></i></button>
                    @if (isset($errors) && $errors->has('password_confirmation'))
                        <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="actions">
                    <a href="/users" class="btn-pill btn-cancel"><i class="bi bi-x-circle"></i> Cancel</a>
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
            // Toggle password eye
            function setupEye(id, eyeAttr) {
                const input = document.getElementById(id);
                const eyeBtn = document.querySelector(`[data-eye="${eyeAttr}"]`);
                if (input && eyeBtn) {
                    eyeBtn.addEventListener('click', () => {
                        const icon = eyeBtn.querySelector('i');
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.replace('bi-eye', 'bi-eye-slash');
                        } else {
                            input.type = 'password';
                            icon.classList.replace('bi-eye-slash', 'bi-eye');
                        }
                        input.focus();
                    });
                }
            }
            setupEye('password', 'pw');
            setupEye('password_confirmation', 'pw2');

            // Password strength + hint
            const pw = document.getElementById('password');
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

            if (pw && meter && hint) {
                pw.addEventListener('input', () => {
                    const sc = scorePass(pw.value);
                    meter.dataset.score = sc;
                    if (sc <= 1) hint.textContent =
                        'รหัสผ่านอ่อนมาก — ควรเพิ่มความยาวและผสมตัวอักษร/ตัวเลข/สัญลักษณ์';
                    else if (sc === 2) hint.textContent = 'ยังอ่อน — ลองเพิ่มตัวพิมพ์ใหญ่/เล็กผสม';
                    else if (sc === 3) hint.textContent = 'พอใช้ — เพิ่มสัญลักษณ์หรือความยาวให้ปลอดภัยขึ้น';
                    else if (sc >= 4) hint.textContent = 'ดีมาก — ความปลอดภัยเหมาะสม';
                });
            }
        });
    </script>
@endsection
