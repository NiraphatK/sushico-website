@extends('home')

@section('css_before')
    {{-- Bootstrap Icons + Google Fonts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #eef2ff;
            --bg-grad: radial-gradient(1200px 600px at 10% -10%, #a5b4fc22, transparent 60%),
                radial-gradient(900px 500px at 110% 0%, #c7d2fe22, transparent 60%);
            --surface: #ffffff;
            --glass: rgba(255, 255, 255, .78);
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
            padding-inline: 4px
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
            margin: 0
        }

        .hero-sub {
            opacity: .94
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
            transform: translateY(-1px)
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

        .btn-pill:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-cancel {
            background: var(--surface);
            color: var(--text) transition: .25s;
        }

        .btn-primary-soft {
            background: var(--grad-cta);
            color: #fff;
            border: none;
            box-shadow: var(--shadow-md) transition: .25s;
        }

        .btn-primary-soft:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg)
        }

        .btn-cancel:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
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
            margin: 6px 0 10px
        }

        .divider {
            height: 1px;
            margin: 10px 0 14px;
            border: 0;
            background: linear-gradient(90deg, transparent, #e8ebf555 20%, #e8ebf5aa 50%, #e8ebf555 80%, transparent)
        }

        /* Floating fields */
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
            position: relative
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
            z-index: 2;
        }

        .floating-field .with-icon {
            padding-left: var(--icon-pad)
        }

        .floating-field:focus-within>i {
            color: var(--brand-2);
            transform: scale(1.04)
        }

        .floating-field>label {
            left: var(--icon-pad) !important;
            width: calc(100% - var(--icon-pad))
        }

        .help {
            font-size: .86rem;
            color: var(--muted);
            margin-top: .35rem
        }

        .invalid-feedback {
            display: block
        }

        .is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px #ef44441f !important
        }

        .actions {
            display: flex;
            gap: .6rem;
            justify-content: flex-end;
            margin-top: 1rem
        }

        .row-gap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px
        }

        @media (max-width:767.98px) {
            .row-gap {
                grid-template-columns: 1fr
            }
        }

        /* Responsive actions bar */
        .actions {
            display: flex;
            gap: .6rem;
            flex-wrap: wrap;
            justify-content: flex-end;
            align-items: center;
        }

        /* ≥768px: ปุ่มชิดขวาตามปกติ */
        .actions .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
        }

        /* ≤991.98px (แท็บเล็ต): ให้ปุ่มยืดเท่ากัน */
        @media (max-width: 991.98px) {
            .actions {
                justify-content: stretch;
            }

            .actions .btn {
                flex: 1 1 auto;
                justify-content: center;
            }
        }

        /* ≤575.98px (มือถือ): ซ้อนแนวตั้ง เต็มความกว้าง + sticky ล่าง */
        @media (max-width: 575.98px) {
            .actions {
                position: sticky;
                bottom: 0;
                z-index: 10;
                background: linear-gradient(180deg, transparent, var(--surface, #fff) 35%, var(--surface, #fff) 100%);
                padding-top: .4rem;
                padding-bottom: .2rem;
            }

            .actions .btn {
                flex: 1 1 100%;
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container page-wrap">
        <!-- Hero -->
        <div class="hero">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="hero-title">Store Settings</h1>
                    <div class="hero-sub">ตั้งค่าเวลาร้าน การจอง และพารามิเตอร์ระบบ</div>
                </div>
                <a href="{{ url('dashboard') }}" class="btn-ghost btn">
                    <i class="bi bi-speedometer2"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Card -->
        <div class="glass-card">
            <form id="settings-form" action="/store-settings/update" method="post" novalidate>
                @csrf
                @method('put')

                <h6 class="sec-title">Time & Slots</h6>
                <hr class="divider">

                <div class="row-gap">
                    {{-- Timezone --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-globe2" data-bs-toggle="tooltip"
                            title="กำหนดโซนเวลา เช่น Asia/Bangkok เพื่อใช้คำนวณเวลา"></i>
                        <select name="timezone" id="timezone"
                            class="form-select with-icon @error('timezone') is-invalid @enderror" required>
                            @php
                                $timezones = ['Asia/Bangkok']; // เพิ่มได้ตามต้องการ
                            @endphp
                            @foreach ($timezones as $tz)
                                <option value="{{ $tz }}"
                                    {{ old('timezone', $setting->timezone) == $tz ? 'selected' : '' }}>
                                    {{ $tz }}
                                </option>
                            @endforeach
                        </select>
                        <label for="timezone">Time Zone <span class="text-danger">*</span></label>
                        @if (isset($errors) && $errors->has('timezone'))
                            <div class="invalid-feedback">{{ $errors->first('timezone') }}</div>
                        @endif
                    </div>

                    {{-- Slot granularity --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-ui-checks-grid" data-bs-toggle="tooltip"
                            title="ช่วงเวลาต่อ 1 สล็อต เช่น 15 นาที"></i>
                        <input type="number" min="1"
                            class="form-control with-icon @error('slot_granularity_minutes') is-invalid @enderror"
                            id="slot_granularity_minutes" name="slot_granularity_minutes" placeholder=" "
                            value="{{ old('slot_granularity_minutes', $setting->slot_granularity_minutes) }}" required>
                        <label for="slot_granularity_minutes">Slot Granularity (minutes) <span
                                class="text-danger">*</span></label>
                        @if (isset($errors) && $errors->has('slot_granularity_minutes'))
                            <div class="invalid-feedback">{{ $errors->first('slot_granularity_minutes') }}</div>
                        @endif
                    </div>

                    {{-- Open time --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-clock" data-bs-toggle="tooltip" title="เวลาร้านเปิด เช่น 09:00"></i>
                        <input type="time" step="60"
                            class="form-control with-icon @error('open_time') is-invalid @enderror" id="open_time"
                            name="open_time" placeholder=" " value="{{ old('open_time', $setting->open_time) }}" required>
                        <label for="open_time">Open Time <span class="text-danger">*</span></label>
                        @if (isset($errors) && $errors->has('open_time'))
                            <div class="invalid-feedback">{{ $errors->first('open_time') }}</div>
                        @endif
                    </div>

                    {{-- Close time --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-clock-history" data-bs-toggle="tooltip" title="เวลาร้านปิด เช่น 20:00"></i>
                        <input type="time" step="60"
                            class="form-control with-icon @error('close_time') is-invalid @enderror" id="close_time"
                            name="close_time" placeholder=" " value="{{ old('close_time', $setting->close_time) }}"
                            required>
                        <label for="close_time">Close Time <span class="text-danger">*</span></label>
                        @if (isset($errors) && $errors->has('close_time'))
                            <div class="invalid-feedback">{{ $errors->first('close_time') }}</div>
                        @endif
                    </div>
                </div>

                <div class="mt-3"></div>

                <h6 class="sec-title">Policies</h6>
                <hr class="divider">

                <div class="row-gap">
                    {{-- Cut-off --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-hourglass-split" data-bs-toggle="tooltip"
                            title="ปิดรับการจองล่วงหน้าก่อนถึงเวลาจริง เช่น 30 นาที"></i>
                        <input type="number" min="0"
                            class="form-control with-icon @error('cut_off_minutes') is-invalid @enderror"
                            id="cut_off_minutes" name="cut_off_minutes" placeholder=" "
                            value="{{ old('cut_off_minutes', $setting->cut_off_minutes) }}">
                        <label for="cut_off_minutes">Cut-off (minutes)</label>
                        @if (isset($errors) && $errors->has('cut_off_minutes'))
                            <div class="invalid-feedback">{{ $errors->first('cut_off_minutes') }}</div>
                        @endif
                    </div>

                    {{-- Grace --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-alarm" data-bs-toggle="tooltip"
                            title="เวลาผ่อนผันเมื่อลูกค้ามาสาย ก่อนเป็น No Show เช่น 15 นาที "></i>
                        <input type="number" min="0"
                            class="form-control with-icon @error('grace_minutes') is-invalid @enderror" id="grace_minutes"
                            name="grace_minutes" placeholder=" "
                            value="{{ old('grace_minutes', $setting->grace_minutes) }}">
                        <label for="grace_minutes">Grace (minutes)</label>
                        @if (isset($errors) && $errors->has('grace_minutes'))
                            <div class="invalid-feedback">{{ $errors->first('grace_minutes') }}</div>
                        @endif
                    </div>

                    {{-- Buffer --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-arrow-clockwise" data-bs-toggle="tooltip"
                            title="เวลาสำหรับเตรียมโต๊ะก่อนรอบถัดไป เช่น 10 นาที"></i>
                        <input type="number" min="0"
                            class="form-control with-icon @error('buffer_minutes') is-invalid @enderror"
                            id="buffer_minutes" name="buffer_minutes" placeholder=" "
                            value="{{ old('buffer_minutes', $setting->buffer_minutes) }}">
                        <label for="buffer_minutes">Buffer (minutes)</label>
                        @if (isset($errors) && $errors->has('buffer_minutes'))
                            <div class="invalid-feedback">{{ $errors->first('buffer_minutes') }}</div>
                        @endif
                    </div>

                    {{-- Default duration --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-stopwatch" data-bs-toggle="tooltip"
                            title="เวลามาตรฐานที่ลูกค้าใช้โต๊ะ เช่น 60 นาที"></i>
                        <input type="number" min="1"
                            class="form-control with-icon @error('default_duration_minutes') is-invalid @enderror"
                            id="default_duration_minutes" name="default_duration_minutes" placeholder=" "
                            value="{{ old('default_duration_minutes', $setting->default_duration_minutes) }}">
                        <label for="default_duration_minutes">Default Duration (minutes)</label>
                        @if (isset($errors) && $errors->has('default_duration_minutes'))
                            <div class="invalid-feedback">{{ $errors->first('default_duration_minutes') }}</div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="actions">
                    <a href="{{ url('dashboard') }}" class="btn-pill btn-cancel btn">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="button" class="btn-pill btn-danger btn" onclick="resetConfirm()">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset to Default
                    </button>
                    <button type="submit" class="btn-pill btn-primary-soft btn">
                        <i class="bi bi-check2-circle"></i> Update Settings
                    </button>
                </div>
            </form>

            {{-- ฟอร์ม Reset แยก --}}
            <form id="reset-form" action="/store-settings/reset" method="post" style="display:none;">
                @csrf
            </form>
        </div>
    </div>
@endsection

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Bootstrap Tooltips
        document.addEventListener("DOMContentLoaded", function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(el) {
                return new bootstrap.Tooltip(el);
            });
        });

        // Reset confirm
        function resetConfirm() {
            Swal.fire({
                title: 'แน่ใจหรือไม่?',
                text: "คุณต้องการรีเซ็ตค่ากลับเป็นค่าเริ่มต้นทั้งหมด",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, รีเซ็ตเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reset-form').submit();
                }
            });
        }
    </script>
@endsection
