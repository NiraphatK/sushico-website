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

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/table.js') }}"></script>
@endsection
