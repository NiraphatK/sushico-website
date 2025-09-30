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

        /* focus แล้ว icon เด่นขึ้น */
        .floating-field:focus-within>i {
            color: var(--brand-2);
            transform: scale(1.04);
            opacity: 1;
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
            transition: .25s;
        }

        .btn-cancel {
            background: var(--surface);
            color: var(--text);
            transition: .25s;
        }

        .btn-primary-soft {
            background: var(--grad-cta);
            color: #fff;
            border: none;
            box-shadow: var(--shadow-md);
            transition: .25s;
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
                    <h1 class="hero-title">Edit Table</h1>
                    <div class="hero-sub">แก้ไขข้อมูลโต๊ะ — เลขโต๊ะ ความจุ ประเภทที่นั่ง และสถานะการใช้งาน</div>
                </div>
                <a href="/table" class="btn-ghost btn"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
        </div>

        <!-- Card -->
        <div class="glass-card">
            <form action="/table/{{ $table_id }}" method="post" novalidate>
                @csrf
                @method('put')

                <h6 class="sec-title">Table Information</h6>
                <hr class="divider">

                {{-- Table Number --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <input type="text" class="form-control with-icon @if ($errors->has('table_number')) is-invalid @endif"
                        id="table_number" name="table_number" placeholder=" "
                        value="{{ old('table_number', $table_number) }}" required minlength="1" maxlength="10">
                    <label for="table_number">Table Number <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('table_number'))
                        <div class="invalid-feedback">{{ $errors->first('table_number') }}</div>
                    @endif
                </div>

                {{-- Capacity --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-people"></i>
                    <input type="number" class="form-control with-icon @if ($errors->has('capacity')) is-invalid @endif"
                        id="capacity" name="capacity" placeholder=" " value="{{ old('capacity', $capacity) }}" required
                        min="1" max="10">
                    <label for="capacity">Capacity <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('capacity'))
                        <div class="invalid-feedback">{{ $errors->first('capacity') }}</div>
                    @endif
                    <div class="help">จำนวนที่นั่งของโต๊ะ (1 - 10)</div>
                </div>

                {{-- Seat Type --}}
                <div class="mb-3">
                    <div class="sec-title">Seat Type</div>
                    <select name="seat_type" class="form-select @if ($errors->has('seat_type')) is-invalid @endif"
                        required>
                        <option value="">-- Select Seat Type --</option>
                        <option value="TABLE" {{ old('seat_type', $seat_type) === 'TABLE' ? 'selected' : '' }}>Table
                        </option>
                        <option value="BAR" {{ old('seat_type', $seat_type) === 'BAR' ? 'selected' : '' }}>Bar</option>
                    </select>
                    @if (isset($errors) && $errors->has('seat_type'))
                        <div class="invalid-feedback">{{ $errors->first('seat_type') }}</div>
                    @endif
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <div class="sec-title">Status</div>

                    {{-- ส่งค่า 0 เสมอ ถ้าไม่ติ๊ก checkbox --}}
                    <input type="hidden" name="is_active" value="0">

                    <div class="d-flex align-items-center gap-3">
                        <label class="switch m-0" aria-label="Toggle menu active">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', isset($is_active) ? (int) $is_active : 1) == 1 ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <span class="text-muted" id="statusText">
                            {{ old('is_active', isset($is_active) ? (int) $is_active : 1) == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>




                <!-- Actions -->
                <div class="actions">
                    <a href="/table" class="btn-pill btn-cancel btn"><i class="bi bi-x-circle"></i> Cancel</a>
                    <button type="submit" class="btn-pill btn-primary-soft"><i class="bi bi-check2-circle"></i>
                        Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
