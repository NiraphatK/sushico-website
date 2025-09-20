@extends('home')

@section('content')
    @php
        $today = \Carbon\Carbon::today()->locale('th')->translatedFormat('l j F Y');
    @endphp

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-12 mx-auto">

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">แก้ไขการจอง (สำหรับวันนี้: {{ $today }})</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ url('reservations/' . $reservation->reservation_id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- เลือกผู้ใช้งาน --}}
                            <div class="mb-3">
                                <label class="form-label">เลือกผู้ใช้งาน</label>
                                <select name="user_id" class="form-select" required>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->user_id }}"
                                            {{ $reservation->user_id == $u->user_id ? 'selected' : '' }}>
                                            {{ $u->full_name }} ({{ $u->phone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- จำนวนลูกค้า --}}
                            <div class="mb-3">
                                <label class="form-label">จำนวนลูกค้าที่จะมา</label>
                                <input type="number" name="party_size" class="form-control"
                                    value="{{ $reservation->party_size }}" min="1" max="10" required>
                            </div>

                            {{-- ประเภทที่นั่ง --}}
                            <div class="mb-3">
                                <label class="form-label">เลือกประเภทที่นั่ง</label>
                                <select name="seat_type" class="form-select" required>
                                    <option value="TABLE" {{ $reservation->seat_type == 'TABLE' ? 'selected' : '' }}>
                                        โต๊ะอาหาร</option>
                                    <option value="BAR" {{ $reservation->seat_type == 'BAR' ? 'selected' : '' }}>
                                        เคาน์เตอร์บาร์</option>
                                </select>
                            </div>

                            {{-- เวลาเริ่มต้น --}}
                            <div class="mb-3">
                                <label class="form-label">เวลาเริ่มต้น (วันนี้)</label>
                                <input type="time" name="start_time" class="form-control"
                                    min="{{ $settings->open_time }}" max="{{ $settings->close_time }}"
                                    step="{{ $settings->slot_granularity_minutes * 60 }}"
                                    value="{{ \Carbon\Carbon::parse($reservation->start_at)->format('H:i') }}" required>
                                <div class="form-text text-muted">
                                    เวลาสิ้นสุดจะถูกกำหนดอัตโนมัติ ({{ $settings->default_duration_minutes }}
                                    นาทีหลังจากเริ่ม)
                                </div>
                            </div>

                            {{-- สถานะ --}}
                            <div class="mb-3">
                                <label class="form-label">สถานะการจอง</label>
                                <select name="status" class="form-select" required>
                                    <option value="CONFIRMED" {{ $reservation->status == 'CONFIRMED' ? 'selected' : '' }}>
                                        ยืนยันแล้ว</option>
                                    <option value="SEATED" {{ $reservation->status == 'SEATED' ? 'selected' : '' }}>
                                        นั่งแล้ว</option>
                                    <option value="COMPLETED" {{ $reservation->status == 'COMPLETED' ? 'selected' : '' }}>
                                        เสร็จสิ้น</option>
                                    <option value="CANCELLED" {{ $reservation->status == 'CANCELLED' ? 'selected' : '' }}>
                                        ยกเลิก</option>
                                    <option value="NO_SHOW" {{ $reservation->status == 'NO_SHOW' ? 'selected' : '' }}>ไม่มา
                                    </option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">อัปเดตการจอง</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
