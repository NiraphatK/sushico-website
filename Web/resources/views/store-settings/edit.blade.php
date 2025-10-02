@extends('home')

@section('css_before')
    {{-- Bootstrap Icons + Google Fonts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/store-settings.css') }}">
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
    <script src="{{ asset('js/store-settings.js') }}?v=1"></script>
@endsection
