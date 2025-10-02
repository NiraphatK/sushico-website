@extends('home')

@section('css_before')
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endsection



@section('content')
    <div class="container page-wrap">
        <!-- Hero -->
        <div class="hero">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="hero-title">Add User</h1>
                    <div class="hero-sub">เพิ่มผู้ใช้งานใหม่ — รองรับอีเมล เบอร์โทร ตั้งรหัสผ่าน และกำหนดบทบาท</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="/users" class="btn-ghost btn"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="glass-card mt-3">
            <form action="/users/" method="post" novalidate>
                @csrf

                <h6 class="sec-title">Account Information</h6>
                <hr class="divider">

                <div class="form-grid">
                    {{-- Full Name --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-person"></i>
                        <input type="text" class="form-control with-icon @error('full_name') is-invalid @enderror"
                            id="full_name" name="full_name" placeholder=" " required value="{{ old('full_name') }}">
                        <label for="full_name">Full Name <span class="text-danger">*</span></label>
                        @if (isset($errors) && $errors->has('full_name'))
                            <div class="invalid-feedback">{{ $errors->first('full_name') }}</div>
                        @endif
                    </div>

                    {{-- Email --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-envelope"></i>
                        <input type="email" class="form-control with-icon @error('email') is-invalid @enderror"
                            id="email" name="email" placeholder=" " value="{{ old('email') }}">
                        <label for="email">Email (optional)</label>
                        @if (isset($errors) && $errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    {{-- Phone --}}
                    <div class="form-floating floating-field">
                        <i class="bi bi-telephone"></i>
                        <input type="text" inputmode="tel"
                            class="form-control with-icon @error('phone') is-invalid @enderror" id="phone"
                            name="phone" placeholder=" " required value="{{ old('phone') }}">
                        <label for="phone">Phone <span class="text-danger">*</span></label>
                        @if (isset($errors) && $errors->has('phone'))
                            <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                        @endif
                        <div class="help">ระบบจะจัดรูปแบบ 08x-xxx-xxxx ให้อัตโนมัติ</div>
                    </div>

                    {{-- Role (segmented) --}}
                    <div>
                        <div class="sec-title" style="margin-top:-2px">Role *</div>
                        <div class="role-group" id="roleGroup">
                            @php $oldRole = old('role','CUSTOMER'); @endphp
                            <label class="role-chip {{ $oldRole === 'CUSTOMER' ? 'active' : '' }}">
                                <input type="radio" name="role" value="CUSTOMER"
                                    {{ $oldRole === 'CUSTOMER' ? 'checked' : '' }}>
                                <i class="bi bi-person-badge"></i> Customer
                            </label>
                            <label class="role-chip {{ $oldRole === 'STAFF' ? 'active' : '' }}">
                                <input type="radio" name="role" value="STAFF"
                                    {{ $oldRole === 'STAFF' ? 'checked' : '' }}>
                                <i class="bi bi-briefcase"></i> Staff
                            </label>
                            <label class="role-chip {{ $oldRole === 'ADMIN' ? 'active' : '' }}">
                                <input type="radio" name="role" value="ADMIN"
                                    {{ $oldRole === 'ADMIN' ? 'checked' : '' }}>
                                <i class="bi bi-shield-lock"></i> Admin <span class="tag">Full access</span>
                            </label>
                        </div>
                        @if (isset($errors) && $errors->has('role'))
                            <div class="invalid-feedback d-block mt-1">{{ $errors->first('role') }}</div>
                        @endif
                    </div>
                </div>

                <h6 class="sec-title mt-3">Security</h6>
                <hr class="divider">

                <div class="form-grid">
                    {{-- Password --}}
                    <div class="form-floating floating-field position-relative">
                        <i class="bi bi-key"></i>
                        <input type="password" class="form-control with-icon @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder=" " required>
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <button type="button" class="eye" aria-label="Toggle password" data-eye="pw">
                            <i class="bi bi-eye"></i>
                        </button>
                        @if (isset($errors) && $errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                        @endif

                        <div class="meter mt-2" id="pwMeter" aria-hidden="true">
                            <span></span><span></span><span></span><span></span><span></span>
                        </div>
                        <div class="help" id="pwHint">
                            แนะนำ: อย่างน้อย 8 ตัวอักษร ผสม ตัวพิมพ์เล็ก/ใหญ่ ตัวเลข และตัวอักษรพิเศษ
                        </div>
                    </div>

                    {{-- Spacer --}}
                    <div class="d-none d-md-block"></div>
                </div>

                <div class="actions">
                    <a href="/users" class="btn-pill btn-cancel btn"><i class="bi bi-x-circle"></i> Cancel</a>
                    <button type="submit" class="btn-pill btn-primary-soft">
                        <i class="bi bi-check2-circle"></i> Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_before')
    <script src="{{ asset('js/users.js') }}"></script>
@endsection
