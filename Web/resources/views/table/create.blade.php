@extends('home')

@section('css_before')
    {{-- Bootstrap Icons + Google Fonts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ===== Design Tokens ===== */
        :root {
            --bg: #eef2ff;
            --bg-grad:
                radial-gradient(1200px 600px at 10% -10%, #a5b4fc22, transparent 60%),
                radial-gradient(900px 500px at 110% 0%, #c7d2fe22, transparent 60%);
            --surface: #fff;
            --glass: rgba(255, 255, 255, .75);
            --line: #e6e9f0;
            --text: #0f172a;
            --muted: #64748b;

            --brand-1: #7c3aed;
            /* violet-600 */
            --brand-2: #2563eb;
            /* blue-600 */

            --r-xl: 26px;
            --shadow-sm: 0 6px 18px rgba(2, 6, 23, .06);
            --shadow-md: 0 12px 34px rgba(2, 6, 23, .10);
            --shadow-lg: 0 24px 64px rgba(2, 6, 23, .16);

            --focus: 0 0 0 6px rgba(59, 130, 246, .16);
            --grad-hero: linear-gradient(135deg, #60a5fa, #7c3aed 55%, #4f46e5);
            --grad-cta: linear-gradient(135deg, var(--brand-2), var(--brand-1));

            --fld-h: 60px;
            --icon-pad: 2.8rem;
        }

        /* ===== Page ===== */
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

        /* ===== Hero ===== */
        .hero {
            position: relative;
            border-radius: var(--r-xl);
            padding: 22px;
            color: #fff;
            background: var(--grad-hero);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            isolation: isolate;
        }

        .hero-title {
            font-weight: 900;
            font-size: clamp(1.25rem, 1rem + 1vw, 2rem);
            margin: 0;
        }

        .hero-sub {
            opacity: .94;
            font-family: "Noto Sans Thai", sans-serif;
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

        /* ===== Card ===== */
        .glass-card {
            background: var(--glass);
            border: 1px solid var(--line);
            border-radius: var(--r-xl);
            box-shadow: var(--shadow-md);
            padding: 24px;
            position: relative;
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
            font-size: 1rem;
            margin: 6px 0 10px;
        }

        .divider {
            height: 1px;
            margin: 10px 0 14px;
            border: 0;
            background: linear-gradient(90deg, transparent, #e8ebf555 20%, #e8ebf5aa 50%, #e8ebf555 80%, transparent);
        }

        /* ===== Grid ===== */
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

        /* ===== Floating fields + Icons ===== */
        .floating-field {
            position: relative;
        }

        .floating-field>i {
            position: absolute;
            left: .9rem;
            top: 0;
            height: var(--fld-h);
            display: flex;
            align-items: center;
            color: var(--muted);
            transition: .2s;
            font-size: 1rem;
        }

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

        .floating-field .with-icon {
            padding-left: var(--icon-pad);
        }


        .floating-field>label {
            left: var(--icon-pad) !important;
            width: calc(100% - var(--icon-pad));
            color: #6b7280;
        }

        .floating-field:focus-within>i {
            color: var(--brand-2);
            transform: scale(1.04);
            opacity: 1;
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

        .form-floating>label {
            pointer-events: none;
        }

        /* ===== Segmented (Seat Type) ===== */
        .seg-group {
            display: flex;
            gap: .55rem;
            flex-wrap: wrap;
        }

        .seg-chip {
            position: relative;
            border: 1px solid var(--line);
            background: var(--surface);
            color: var(--text);
            border-radius: 999px;
            padding: .6rem 1rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            cursor: pointer;
            transition: .18s ease;
        }

        .seg-chip:hover {
            transform: translateY(-1px);
            box-shadow: 0 1px 0 rgba(2, 6, 23, .06);
        }

        .seg-chip input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .seg-chip.active {
            background: var(--grad-cta);
            color: #fff;
            border-color: transparent;
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

        /* ===== Actions ===== */
        .actions {
            display: flex;
            gap: .6rem;
            flex-wrap: wrap;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .btn-pill,
        .btn-ghost,
        .btn-primary-soft,
        .btn-cancel {
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            font-weight: 900;
            transition: transform .18s ease, box-shadow .2s ease, filter .2s ease;
        }

        .btn-pill {
            height: 50px;
            padding: .7rem 1.15rem;
            border: 1px solid var(--line);
            transition: .25s;
        }

        .btn-cancel {
            background: var(--surface);
            color: var(--text);
            transition: .25s;
        }

        .btn-cancel:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary-soft {
            background: var(--grad-cta);
            color: #fff;
            border: none;
            box-shadow: var(--shadow-md);
            transition: .25s;
        }

        .btn-primary-soft:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
            filter: brightness(1.02);
        }

        .btn-ghost:hover {
            background: #ffffff2a;
            transform: translateY(-1px);
        }

        .btn-primary-soft:active,
        .btn-cancel:active {
            transform: translateY(0);
        }

        /* ===== Floating label fix for type=number & prefilled ===== */
        .form-floating .form-control.is-filled~label,
        .form-floating .form-select.is-filled~label {
            transform: scale(.85) translateY(-0.6rem) translateX(.15rem);
            opacity: .85;
        }

        .floating-field .form-control.is-filled~label {
            left: var(--icon-pad) !important;
            width: calc(100% - var(--icon-pad));
        }

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
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1 class="hero-title">Add Table</h1>
                    <div class="hero-sub">เพิ่มโต๊ะใหม่ — ระบุหมายเลขโต๊ะ จำนวนที่นั่ง ประเภทที่นั่ง และสถานะการใช้งาน</div>
                </div>
                <a href="/table" class="btn-ghost btn"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
        </div>

        <!-- Card -->
        <div class="glass-card mt-3">
            <form action="/table" method="post" novalidate>
                @csrf

                <h6 class="sec-title">Table Information</h6>
                <hr class="divider">

                <div class="form-grid">
                    {{-- Table Number --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-hash"></i>
                        <input type="text"
                            class="form-control with-icon @if (isset($errors) && $errors->has('table_number')) is-invalid @endif"
                            id="table_number" name="table_number" placeholder=" " required minlength="1" maxlength="10"
                            value="{{ old('table_number') }}">
                        <label for="table_number">Table Number <span class="text-danger">*</span></label>
                        @if (isset($errors) && $errors->has('table_number'))
                            <div class="invalid-feedback">{{ $errors->first('table_number') }}</div>
                        @endif
                        <div class="help">เช่น A01, B12 (ระบบจะปรับอักษรเป็นตัวพิมพ์ใหญ่อัตโนมัติ)</div>
                    </div>

                    {{-- Capacity --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-people"></i>
                        <input type="number"
                            class="form-control with-icon @if (isset($errors) && $errors->has('capacity')) is-invalid @endif"
                            id="capacity" name="capacity" placeholder=" " required min="1" max="10"
                            step="1" value="{{ old('capacity') }}">
                        <label for="capacity">Capacity <span class="text-danger">*</span></label>
                        @if (isset($errors) && $errors->has('capacity'))
                            <div class="invalid-feedback">{{ $errors->first('capacity') }}</div>
                        @endif
                        <div class="help">จำนวนที่นั่งของโต๊ะ (1–10)</div>
                    </div>

                    {{-- Seat Type (segmented) --}}
                    <div>
                        <div class="sec-title" style="margin-top:-2px">Seat Type *</div>
                        <div class="seg-group" id="seatGroup">
                            @php $oldSeat = old('seat_type'); @endphp
                            <label class="seg-chip {{ $oldSeat === 'TABLE' ? 'active' : '' }}">
                                <input type="radio" name="seat_type" value="TABLE"
                                    {{ $oldSeat === 'TABLE' ? 'checked' : '' }}>
                                <i class="bi bi-grid-3x3-gap"></i> TABLE
                            </label>
                            <label class="seg-chip {{ $oldSeat === 'BAR' ? 'active' : '' }}">
                                <input type="radio" name="seat_type" value="BAR"
                                    {{ $oldSeat === 'BAR' ? 'checked' : '' }}>
                                <i class="bi bi-cup-straw"></i> BAR
                            </label>
                        </div>
                        @if (isset($errors) && $errors->has('seat_type'))
                            <div class="invalid-feedback d-block mt-1">{{ $errors->first('seat_type') }}</div>
                        @endif
                    </div>

                    {{-- Active (switch) --}}
                    <div class="d-flex align-items-center gap-3">
                        <div class="sec-title m-0">Active</div>

                        <label class="switch m-0">
                            <!-- ถ้าไม่ติ๊ก จะได้ค่า 0 -->
                            <input type="hidden" name="is_active" value="0">

                            <!-- ถ้าติ๊ก จะส่งค่า 1 -->
                            <input id="is_active" type="checkbox" name="is_active" value="1"
                                @checked(old('is_active', isset($table) ? (int) $table->is_active : 1) == 1)>
                            <span class="slider"></span>
                        </label>

                        <span class="text-muted">เปิดใช้งานโต๊ะนี้</span>
                    </div>

                </div>

                <div class="actions">
                    <a href="/table" class="btn-pill btn-cancel btn"><i class="bi bi-x-circle"></i> Cancel</a>
                    <button type="submit" class="btn-pill btn-primary-soft">
                        <i class="bi bi-check2-circle"></i> Insert Table
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_before')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Seat type segmented state
            const seatGroup = document.getElementById('seatGroup');
            if (seatGroup) {
                seatGroup.querySelectorAll('input[type="radio"]').forEach(r => {
                    r.addEventListener('change', () => {
                        seatGroup.querySelectorAll('.seg-chip').forEach(c => c.classList.remove(
                            'active'));
                        r.closest('.seg-chip').classList.add('active');
                    });
                });
            }

            // Table Number: uppercase, no spaces, max 10
            const tno = document.getElementById('table_number');
            if (tno) {
                const norm = v => (v || '').toUpperCase().replace(/\s+/g, '').slice(0, 10);
                tno.addEventListener('input', () => {
                    tno.value = norm(tno.value);
                });
                const form = tno.closest('form');
                if (form) form.addEventListener('submit', () => {
                    tno.value = norm(tno.value);
                });
            }

            // Capacity clamp 1–10
            const cap = document.getElementById('capacity');
            if (cap) {
                const clamp = v => {
                    v = parseInt(v || '');
                    if (isNaN(v)) return '';
                    return Math.max(1, Math.min(10, v));
                };
                cap.addEventListener('input', () => {
                    cap.value = clamp(cap.value);
                });
            }

            // Floating label: mark filled (for number & old())
            const syncFilled = el => {
                const hasVal = (el.value ?? '').toString().trim().length > 0;
                el.classList.toggle('is-filled', hasVal);
            };
            document.querySelectorAll('.form-floating .form-control, .form-floating .form-select')
                .forEach(el => {
                    syncFilled(el);
                    el.addEventListener('input', () => syncFilled(el));
                    el.addEventListener('change', () => syncFilled(el));
                });

            // Prevent double submit
            const form = document.querySelector('form[action="/table"]');
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
