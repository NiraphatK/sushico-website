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
            --surface: #fff;
            --glass: rgba(255, 255, 255, .75);
            --line: #e6e9f0;
            --text: #0f172a;
            --muted: #64748b;
            --brand-1: #7c3aed;
            --brand-2: #2563eb;
            --grad-cta: linear-gradient(135deg, var(--brand-2), var(--brand-1));
            --fld-h: 60px;
            --icon-pad: 2.8rem;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, .08);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, .12);
            --shadow-lg: 0 12px 34px rgba(2, 6, 23, .20);
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

        /* Hero */
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
            transition: .25s;
        }

        .btn-ghost:hover {
            background: #ffffff3a;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Card */
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

        /* Form */
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
            left: .9rem;
            top: 0;
            height: var(--fld-h);
            display: flex;
            align-items: center;
            color: var(--muted);
            pointer-events: none;
            transition: .2s;
        }

        .floating-field:focus-within>i {
            color: var(--brand-2);
            transform: scale(1.04);
        }

        .with-icon {
            padding-left: var(--icon-pad) !important;
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

        /* Segmented (Seat Type) */
        .seg {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap
        }

        .seg input {
            display: none;
        }

        .seg label {
            cursor: pointer;
            border: 1px solid var(--line);
            background: var(--surface);
            padding: .55rem 1rem;
            border-radius: 999px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            transition: .2s;
        }

        .seg label:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .seg input:checked+label {
            color: #fff;
            background: var(--grad-cta);
            border-color: transparent;
            box-shadow: var(--shadow-md);
        }

        /* Actions */
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
        }

        .btn-cancel:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary-soft {
            background: var(--grad-cta);
            color: #fff;
            border: none;
            box-shadow: var(--shadow-md);
        }

        .btn-primary-soft:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
            filter: brightness(1.05);
        }
    </style>
@endsection

@section('content')
    @php
        $today = \Carbon\Carbon::today()->locale('th')->translatedFormat('l j F Y');
    @endphp

    <div class="container page-wrap">
        <!-- Hero -->
        <div class="hero">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="hero-title">เพิ่มการจอง</h1>
                    <div class="hero-sub">สำหรับวันนี้: {{ $today }}</div>
                </div>
                <a href="/reservations" class="btn-ghost btn"><i class="bi bi-arrow-left"></i> กลับรายการจอง</a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass-card">
            <form action="{{ url('reservations') }}" method="POST" novalidate>
                @csrf

                <h6 class="sec-title">Reservation Details</h6>
                <hr class="divider">

                {{-- เลือกผู้ใช้งาน --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-person-badge"></i>
                    <select name="user_id" id="user_id"
                        class="form-select with-icon @error('user_id') is-invalid @enderror" required>
                        <option value="">-- กรุณาเลือกผู้ใช้งาน --</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->user_id }}" {{ old('user_id') == $u->user_id ? 'selected' : '' }}>
                                {{ $u->full_name }} ({{ $u->phone }})
                            </option>
                        @endforeach
                    </select>
                    <label for="user_id">เลือกผู้ใช้งาน <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('user_id'))
                        <div class="invalid-feedback">{{ $errors->first('user_id') }}</div>
                    @endif
                </div>

                {{-- จำนวนลูกค้า (จำกัด 1–10 + auto validate) --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-people"></i>
                    <input type="number" name="party_size" id="party_size"
                        class="form-control with-icon @error('party_size') is-invalid @enderror" min="1"
                        max="10" placeholder=" " value="{{ old('party_size') }}" required>
                    <label for="party_size">จำนวนลูกค้าที่จะมา <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('party_size'))
                        <div class="invalid-feedback">{{ $errors->first('party_size') }}</div>
                    @endif
                    <div class="help">จำนวนต้องอยู่ระหว่าง 1–10</div>
                </div>

                {{-- ประเภทที่นั่ง --}}
                <div class="mb-3">
                    <div class="sec-title">Select Seat Type</div>
                    <div class="seg">
                        <input type="radio" id="seat_table" name="seat_type" value="TABLE"
                            {{ old('seat_type') === 'TABLE' ? 'checked' : '' }} required>
                        <label for="seat_table"><i class="bi bi-grid-3x3"></i> Dining Table</label>

                        <input type="radio" id="seat_bar" name="seat_type" value="BAR"
                            {{ old('seat_type') === 'BAR' ? 'checked' : '' }} required>
                        <label for="seat_bar"><i class="bi bi-cup-straw"></i> Bar Counter</label>
                    </div>
                    @if (isset($errors) && $errors->has('seat_type'))
                        <div class="invalid-feedback d-block">{{ $errors->first('seat_type') }}</div>
                    @endif

                </div>

                {{-- เวลาเริ่มต้น --}}
                <div class="form-floating floating-field mb-2">
                    <i class="bi bi-clock-history"></i>
                    <input type="time" name="start_time" id="start_time"
                        class="form-control with-icon @error('start_time') is-invalid @enderror"
                        min="{{ $settings->open_time }}" max="{{ $settings->close_time }}"
                        step="{{ $settings->slot_granularity_minutes * 60 }}" value="{{ old('start_time') }}"
                        placeholder=" " required>
                    <label for="start_time">เวลาเริ่มต้น (วันนี้) <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('start_time'))
                        <div class="invalid-feedback">{{ $errors->first('start_time') }}</div>
                    @endif
                </div>
                <div class="help mb-3">
                    เวลาเปิดร้าน: {{ $settings->open_time }} – ปิดร้าน: {{ $settings->close_time }}
                    (เลือกได้ทีละ {{ $settings->slot_granularity_minutes }} นาที)
                </div>

                <!-- Actions -->
                <div class="actions">
                    <a href="/reservations" class="btn-pill btn-cancel btn"><i class="bi bi-x-circle"></i> Cancel</a>
                    <button type="submit" class="btn-pill btn-primary-soft"><i class="bi bi-check2-circle"></i>
                        Save Reservation</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_before')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const partyInput = document.getElementById("party_size");
            if (partyInput) {
                partyInput.addEventListener("input", () => {
                    let val = parseInt(partyInput.value, 10);
                    if (isNaN(val)) return;
                    if (val < 1) partyInput.value = 1;
                    if (val > 10) partyInput.value = 10;
                });
            }
        });
    </script>
@endsection
