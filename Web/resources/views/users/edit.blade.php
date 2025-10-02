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
        <div class="hero">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="hero-title">Edit User</h1>
                    <div class="hero-sub">แก้ไขข้อมูลผู้ใช้งาน — ปรับปรุงชื่อ อีเมล เบอร์โทร บทบาท และสถานะ</div>
                </div>
                <a href="/users" class="btn-ghost btn"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
        </div>

        <!-- Card -->
        <div class="glass-card">
            <form action="/users/{{ $user_id }}" method="post" novalidate>
                @csrf
                @method('put')

                <h6 class="sec-title">Account Information</h6>
                <hr class="divider">

                {{-- Full Name --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-person"></i>
                    <input type="text" class="form-control with-icon @if (isset($errors) && $errors->has('full_name')) is-invalid @endif"
                        id="full_name" name="full_name" placeholder=" " required value="{{ old('full_name', $full_name) }}">
                    <label for="full_name">Full Name <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('full_name'))
                        <div class="invalid-feedback">{{ $errors->first('full_name') }}</div>
                    @endif
                </div>

                {{-- Phone --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-telephone"></i>
                    <input type="text" class="form-control with-icon @if (isset($errors) && $errors->has('phone')) is-invalid @endif"
                        id="phone" name="phone" placeholder=" " required value="{{ old('phone', $phone) }}">
                    <label for="phone">Phone <span class="text-danger">*</span></label>
                    @if (isset($errors) && $errors->has('phone'))
                        <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                    @endif
                    <div class="help">ระบบจะจัดรูปแบบ 08x-xxx-xxxx ให้อัตโนมัติ</div>
                </div>

                {{-- Email --}}
                <div class="form-floating floating-field mb-3">
                    <i class="bi bi-envelope"></i>
                    <input type="email" class="form-control with-icon @if (isset($errors) && $errors->has('email')) is-invalid @endif"
                        id="email" name="email" placeholder=" " value="{{ old('email', $email) }}">
                    <label for="email">Email</label>
                    @if (isset($errors) && $errors->has('email'))
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <div class="sec-title">Role</div>
                    <select name="role" class="form-select @if (isset($errors) && $errors->has('role')) is-invalid @endif">
                        <option value="CUSTOMER" @if (old('role', $role) == 'CUSTOMER') selected @endif>Customer</option>
                        <option value="STAFF" @if (old('role', $role) == 'STAFF') selected @endif>Staff</option>
                        <option value="ADMIN" @if (old('role', $role) == 'ADMIN') selected @endif>Admin</option>
                    </select>
                    @if (isset($errors) && $errors->has('role'))
                        <div class="invalid-feedback">{{ $errors->first('role') }}</div>
                    @endif
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <div class="sec-title">Status</div>
                    <input type="hidden" name="is_active" value="0">

                    <div class="d-flex align-items-center gap-3">
                        <label class="switch m-0">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', (int) $is_active) == 1 ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <span class="text-muted">
                            {{ old('is_active', (int) $is_active) == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>


                <!-- Actions -->
                <div class="actions">
                    <a href="/users" class="btn-pill btn-cancel btn"><i class="bi bi-x-circle"></i> Cancel</a>
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
