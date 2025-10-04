@props([
    'id' => 'accountModal',
    'title' => 'ข้อมูลบัญชีของฉัน',
    'openOnError' => false,
])

@php
    // มี error ในแท็บรหัสผ่านไหม (ทั้ง named bag และ default เผื่อมี validate อื่น)
    $isPwdError =
        $errors->getBag('password')->any() ||
        $errors->hasAny(['current_password', 'password', 'password_confirmation']);

    // มี error ใด ๆ ไหม (ใช้ตัดสินใจเปิดโมดัลอัตโนมัติ)
    $hasAnyError = $errors->any() || $errors->getBag('account')->any() || $errors->getBag('password')->any();
@endphp

@once
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/account-modal.css') }}">
    {{-- โหลดไฟล์ JS นี้ "หลัง" bootstrap.bundle.js ใน layout หลัก --}}
    <script src="{{ asset('js/account-modal.js') }}" defer></script>
@endonce

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true"
    data-account-modal data-open-on-error="{{ $openOnError && $hasAnyError ? '1' : '0' }}"
    data-password-error="{{ $isPwdError ? '1' : '0' }}">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content lm-card">

            <div class="lm-accent"></div>

            <div class="modal-header lm-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="{{ $id }}Label">
                    <i class="bi bi-person-vcard"></i>
                    <span class="lm-title-grad">{{ $title }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
            </div>

            <div class="modal-body">
                {{-- Tabs --}}
                <ul class="nav nav-tabs mb-3" role="tablist" style="border-bottom: 1px solid rgba(2,6,23,.08)">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold {{ $isPwdError ? '' : 'active' }}" id="acc-prof-tab"
                            data-bs-toggle="tab" data-bs-target="#acc-prof" type="button" role="tab"
                            aria-controls="acc-prof" aria-selected="{{ $isPwdError ? 'false' : 'true' }}">
                            โปรไฟล์
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold {{ $isPwdError ? 'active' : '' }}" id="acc-pass-tab"
                            data-bs-toggle="tab" data-bs-target="#acc-pass" type="button" role="tab"
                            aria-controls="acc-pass" aria-selected="{{ $isPwdError ? 'true' : 'false' }}">
                            เปลี่ยนรหัสผ่าน
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- โปรไฟล์ --}}
                    <div class="tab-pane fade {{ $isPwdError ? '' : 'show active' }}" id="acc-prof" role="tabpanel"
                        aria-labelledby="acc-prof-tab" tabindex="0">

                        <form method="POST" action="{{ route('account.update') }}" novalidate
                            class="needs-validation">
                            @csrf @method('PUT')

                            <div class="lm-floating mb-3">
                                <i class="bi bi-person lm-icon" aria-hidden="true"></i>
                                <div class="form-floating">
                                    <input type="text" name="full_name" id="acc-fullname"
                                        class="form-control lm-control @error('full_name', 'account') is-invalid @enderror"
                                        value="{{ old('full_name', Auth::guard('user')->user()->full_name ?? '') }}"
                                        placeholder="ชื่อ-สกุล" required autocomplete="name">
                                    <label for="acc-fullname">ชื่อ-สกุล</label>
                                    @error('full_name', 'account')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="lm-floating">
                                    <i class="bi bi-envelope lm-icon lm-icon-half" aria-hidden="true"></i>
                                    <div class="form-floating">
                                        <input type="email" name="email" id="acc-email"
                                            class="form-control lm-control @error('email', 'account') is-invalid @enderror"
                                            value="{{ old('email', Auth::guard('user')->user()->email ?? '') }}"
                                            placeholder="อีเมล" autocomplete="email">
                                        <label for="acc-email">อีเมล (ไม่บังคับ)</label>
                                        @error('email', 'account')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="lm-floating">
                                    <i class="bi bi-phone lm-icon lm-icon-half" aria-hidden="true"></i>
                                    <div class="form-floating">
                                        <input type="tel" name="phone" id="acc-phone" data-autofmt-phone
                                            class="form-control lm-control @error('phone', 'account') is-invalid @enderror"
                                            value="{{ old('phone', Auth::guard('user')->user()->phone ?? '') }}"
                                            placeholder="เบอร์โทรศัพท์" required autocomplete="tel">
                                        <label for="acc-phone">เบอร์โทรศัพท์</label>
                                        @error('phone', 'account')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-3">
                                <button class="lm-primary" type="submit">
                                    <i class="bi bi-check2-circle me-1"></i> บันทึกโปรไฟล์
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- เปลี่ยนรหัสผ่าน --}}
                    <div class="tab-pane fade {{ $isPwdError ? 'show active' : '' }}" id="acc-pass" role="tabpanel"
                        aria-labelledby="acc-pass-tab" tabindex="0">

                        <form method="POST" action="{{ route('account.password') }}" novalidate
                            class="needs-validation">
                            @csrf @method('PUT')

                            <div class="lm-floating mb-3">
                                <i class="bi bi-lock lm-icon" aria-hidden="true"></i>
                                <div class="form-floating">
                                    <input type="password" name="current_password" id="acc-current"
                                        class="form-control lm-control @error('current_password', 'password') is-invalid @enderror"
                                        placeholder="รหัสผ่านปัจจุบัน" required autocomplete="current-password"
                                        data-caps-hint="#acc-current-caps">
                                    <small id="acc-current-caps" class="caps-hint d-none text-danger"
                                        aria-live="polite"></small>

                                    <label for="acc-current">รหัสผ่านปัจจุบัน</label>
                                    @error('current_password', 'password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="lm-floating">
                                    <i class="bi bi-shield-lock lm-icon lm-icon-half" aria-hidden="true"></i>
                                    <div class="form-floating">
                                        <input type="password" name="password" id="acc-new"
                                            class="form-control lm-control @error('password', 'password') is-invalid @enderror"
                                            placeholder="รหัสผ่านใหม่" required autocomplete="new-password"
                                            minlength="6" data-needs="#acc-need-list"
                                            data-caps-hint="#acc-new-caps">
                                        <small id="acc-new-caps" class="caps-hint d-none text-danger"
                                            aria-live="polite"></small>

                                        <label for="acc-new">รหัสผ่านใหม่</label>
                                        @error('password', 'password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <button type="button" class="lm-eye" aria-label="แสดง/ซ่อนรหัส"
                                            data-toggle-eye data-target="#acc-new">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="lm-floating">
                                    <i class="bi bi-shield-check lm-icon lm-icon-half" aria-hidden="true"></i>
                                    <div class="form-floating">
                                        <input type="password" name="password_confirmation" id="acc-new2"
                                            class="form-control lm-control @error('password_confirmation', 'password') is-invalid @enderror"
                                            placeholder="ยืนยันรหัสผ่านใหม่" required autocomplete="new-password"
                                            data-caps-hint="#acc-new2-caps">
                                        <small id="acc-new2-caps" class="caps-hint d-none text-danger"
                                            aria-live="polite"></small>

                                        <label for="acc-new2">ยืนยันรหัสผ่านใหม่</label>
                                        @error('password_confirmation', 'password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <button type="button" class="lm-eye" aria-label="แสดง/ซ่อนรหัส"
                                            data-toggle-eye data-target="#acc-new2">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <ul id="acc-need-list" class="lm-need-list" data-label="คำแนะนำรหัสผ่าน">
                                <li><span class="dot"></span> อย่างน้อย 8 ตัวอักษร</li>
                                <li><span class="dot"></span> ผสมตัวพิมพ์เล็ก/ใหญ่</li>
                                <li><span class="dot"></span> ตัวเลขหรือสัญลักษณ์</li>
                            </ul>

                            <div class="d-grid mt-3">
                                <button class="lm-primary" type="submit">
                                    <i class="bi bi-key-fill me-1"></i> เปลี่ยนรหัสผ่าน
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
