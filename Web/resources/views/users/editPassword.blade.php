@extends('home')

@section('css_before')
    {{-- Bootstrap Icons + Google Fonts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endsection

@section('content')
    <div class="container page-wrap">
        <!-- Hero -->
        <div class="hero d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="hero-title">Reset Password</h1>
                <div class="hero-sub">ตั้งรหัสผ่านใหม่สำหรับผู้ใช้งาน</div>
            </div>
            <a href="/users" class="btn-ghost btn"><i class="bi bi-arrow-left"></i> Back</a>
        </div>

        <!-- Card -->
        <div class="glass-card">
            <form action="/users/reset/{{ $user_id }}" method="post" novalidate>
                @csrf
                @method('put')

                {{-- Full Name --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-person"></i>
                    <input type="text" class="form-control-plaintext with-icon" value="{{ $full_name }}" readonly>
                    <label>Full Name</label>
                </div>

                {{-- Email --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-envelope"></i>
                    <input type="email" class="form-control-plaintext with-icon" value="{{ $email }}" readonly>
                    <label>Email</label>
                </div>

                {{-- Phone --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-telephone"></i>
                    <input type="text" class="form-control-plaintext with-icon" value="{{ $phone }}" readonly>
                    <label>Phone</label>
                </div>


                {{-- New Password --}}
                <div class="form-floating floating-field position-relative mb-3">
                    <i class="bi bi-key"></i>
                    <input type="password"
                        class="form-control with-icon @if (isset($errors) && $errors->has('password')) is-invalid @endif" id="password"
                        name="password" placeholder=" " required minlength="6">
                    <label for="password">New Password</label>
                    <button type="button" class="eye" data-eye="pw"><i class="bi bi-eye"></i></button>
                    @if (isset($errors) && $errors->has('password'))
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    @endif
                    <div class="meter mt-2" id="pwMeter"><span></span><span></span><span></span><span></span><span></span>
                    </div>
                    <div class="help" id="pwHint">แนะนำ: อย่างน้อย 8 ตัวอักษร ผสมตัวพิมพ์เล็ก/ใหญ่ ตัวเลข และสัญลักษณ์
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="form-floating floating-field position-relative mb-3">
                    <i class="bi bi-shield-lock"></i>
                    <input type="password"
                        class="form-control with-icon @if (isset($errors) && $errors->has('password_confirmation')) is-invalid @endif"
                        id="password_confirmation" name="password_confirmation" placeholder=" " required minlength="6">
                    <label for="password_confirmation">Confirm Password</label>
                    <button type="button" class="eye" data-eye="pw2"><i class="bi bi-eye"></i></button>
                    @if (isset($errors) && $errors->has('password_confirmation'))
                        <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="actions">
                    <a href="/users" class="btn-pill btn-cancel"><i class="bi bi-x-circle"></i> Cancel</a>
                    <button type="submit" class="btn-pill btn-primary-soft"><i class="bi bi-check2-circle"></i>
                        Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_before')
    <script src="{{ asset('js/users.js') }}"></script>
@endsection
