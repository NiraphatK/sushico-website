@extends('home')

@section('css_before')
    {{-- Bootstrap Icons + Google Fonts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/table.js') }}"></script>
@endsection
