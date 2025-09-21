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
                        <h5 class="mb-0">เพิ่มการจอง (สำหรับวันนี้: {{ $today }})</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ url('reservations') }}" method="POST">
                            @csrf

                            {{-- เลือกผู้ใช้งาน --}}
                            <div class="mb-3">
                                <label class="form-label">เลือกผู้ใช้งาน</label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">-- กรุณาเลือกผู้ใช้งาน --</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->user_id }}"
                                            {{ old('user_id') == $u->user_id ? 'selected' : '' }}>
                                            {{ $u->full_name }} ({{ $u->phone }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <small class="text-danger">** {{ $message }} </small>
                                @enderror
                            </div>

                            {{-- จำนวนลูกค้า --}}
                            <div class="mb-3">
                                <label class="form-label">จำนวนลูกค้าที่จะมา</label>
                                <input type="number" name="party_size" class="form-control" min="1" max="10"
                                    value="{{ old('party_size') }}" required>
                                @error('party_size')
                                    <small class="text-danger">** {{ $message }} </small>
                                @enderror
                            </div>

                            {{-- ประเภทที่นั่ง --}}
                            <div class="mb-3">
                                <label class="form-label">เลือกประเภทที่นั่ง</label>
                                <select name="seat_type" class="form-select" required>
                                    <option value="">-- กรุณาเลือก --</option>
                                    <option value="TABLE" {{ old('seat_type') == 'TABLE' ? 'selected' : '' }}>โต๊ะอาหาร
                                    </option>
                                    <option value="BAR" {{ old('seat_type') == 'BAR' ? 'selected' : '' }}>เคาน์เตอร์บาร์
                                    </option>
                                </select>
                                @error('seat_type')
                                    <small class="text-danger">** {{ $message }} </small>
                                @enderror
                            </div>

                            {{-- เวลาเริ่มต้น --}}
                            <div class="mb-3">
                                <label class="form-label">เวลาเริ่มต้น (วันนี้)</label>
                                <input type="time" name="start_time" class="form-control"
                                    min="{{ $settings->open_time }}" max="{{ $settings->close_time }}"
                                    step="{{ $settings->slot_granularity_minutes * 60 }}" value="{{ old('start_time') }}"
                                    required>

                                <div class="form-text text-muted">
                                    เวลาเปิดร้าน: {{ $settings->open_time }} – ปิดร้าน: {{ $settings->close_time }}
                                    (เลือกได้ทีละ {{ $settings->slot_granularity_minutes }} นาที)
                                </div>

                                @error('start_time')
                                    <small class="text-danger">** {{ $message }} </small>
                                @enderror
                            </div>


                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">บันทึกการจอง</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
