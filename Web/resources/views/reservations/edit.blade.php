@extends('home')

@section('css_before')
    {{-- Bootstrap Icons + Google Fonts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reservations.css') }}">
@endsection

@section('content')
    @php
        $todayTh = \Carbon\Carbon::today()->locale('th')->translatedFormat('l j F Y');
    @endphp

    <div class="container page-wrap">
        <!-- Hero -->
        <div class="hero">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="hero-title">Edit Reservation</h1>
                    <div class="hero-sub">แก้ไขการจองสำหรับวันนี้ — {{ $todayTh }}</div>
                </div>
                <a href="{{ url('reservations') }}" class="btn-ghost btn">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Card -->
        <div class="glass-card">
            <form action="{{ url('reservations/' . $reservation->reservation_id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <h6 class="sec-title">Reservation Details</h6>
                <hr class="divider">

                {{-- เลือกผู้ใช้งาน --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-person-badge"></i>
                    <select name="user_id" id="user_id"
                        class="form-select with-icon @error('user_id') is-invalid @enderror" required>
                        @foreach ($users as $u)
                            <option value="{{ $u->user_id }}"
                                {{ $reservation->user_id == $u->user_id ? 'selected' : '' }}>
                                {{ $u->full_name }} ({{ $u->phone }})
                            </option>
                        @endforeach
                    </select>
                    <label for="user_id">เลือกผู้ใช้งาน <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('user_id'))
                        <div class="invalid-feedback">{{ $errors->first('user_id') }}</div>
                    @endif
                </div>

                {{-- จำนวนลูกค้า --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-people"></i>
                    <input type="number" min="1" max="10"
                        class="form-control with-icon @error('party_size') is-invalid @enderror" id="party_size"
                        name="party_size" placeholder=" " value="{{ old('party_size', $reservation->party_size) }}"
                        required>
                    <label for="party_size">จำนวนลูกค้าที่จะมา <span class="text-danger">*</span></label>
                    <div class="help">กำหนดช่วง 1–10 ที่นั่ง</div>
                    @if (isset($errors) && $errors->has('party_size'))
                        <div class="invalid-feedback">{{ $errors->first('party_size') }}</div>
                    @endif
                </div>

                {{-- ประเภทที่นั่ง --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <select name="seat_type" id="seat_type"
                        class="form-select with-icon @error('seat_type') is-invalid @enderror" required>
                        <option value="TABLE" {{ $reservation->seat_type == 'TABLE' ? 'selected' : '' }}>โต๊ะอาหาร
                        </option>
                        <option value="BAR" {{ $reservation->seat_type == 'BAR' ? 'selected' : '' }}>เคาน์เตอร์บาร์
                        </option>
                    </select>
                    <label for="seat_type">เลือกประเภทที่นั่ง <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('seat_type'))
                        <div class="invalid-feedback">{{ $errors->first('seat_type') }}</div>
                    @endif
                </div>

                {{-- เวลาเริ่มต้น (วันนี้) --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-clock"></i>
                    <input type="time" id="start_time" name="start_time"
                        class="form-control with-icon @error('start_time') is-invalid @enderror"
                        min="{{ $settings->open_time }}" max="{{ $settings->close_time }}"
                        step="{{ $settings->slot_granularity_minutes * 60 }}"
                        value="{{ old('start_time', \Carbon\Carbon::parse($reservation->start_at)->format('H:i')) }}"
                        required>
                    <label for="start_time">เวลาเริ่มต้น (วันนี้) <span class="text-danger">*</span></label>
                    <div class="help">
                        เวลาเปิด {{ $settings->open_time }} – ปิด {{ $settings->close_time }}
                        • ช่วงเวลา {{ $settings->slot_granularity_minutes }} นาที
                    </div>
                    @if (isset($errors) && $errors->has('start_time'))
                        <div class="invalid-feedback">{{ $errors->first('start_time') }}</div>
                    @endif
                </div>

                {{-- สถานะการจอง --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-bookmark-check"></i>
                    <select name="status" id="status"
                        class="form-select with-icon @error('status') is-invalid @enderror" required>
                        <option value="CONFIRMED" {{ $reservation->status == 'CONFIRMED' ? 'selected' : '' }}>ยืนยันแล้ว
                        </option>
                        <option value="SEATED" {{ $reservation->status == 'SEATED' ? 'selected' : '' }}>นั่งแล้ว</option>
                        <option value="COMPLETED" {{ $reservation->status == 'COMPLETED' ? 'selected' : '' }}>เสร็จสิ้น
                        </option>
                        <option value="CANCELLED" {{ $reservation->status == 'CANCELLED' ? 'selected' : '' }}>ยกเลิก
                        </option>
                        <option value="NO_SHOW" {{ $reservation->status == 'NO_SHOW' ? 'selected' : '' }}>ไม่มา</option>
                    </select>
                    <label for="status">สถานะการจอง <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('status'))
                        <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                    @endif
                </div>

                {{-- ปุ่ม --}}
                <div class="actions">
                    <a href="{{ url('reservations') }}" class="btn-pill btn-cancel btn">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn-pill btn-primary-soft">
                        <i class="bi bi-check2-circle"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_before')
    <script src="{{ asset('js/reservations.js') }}"></script>
@endsection
