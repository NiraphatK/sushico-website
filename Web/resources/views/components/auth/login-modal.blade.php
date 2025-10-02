@props([
    'id' => 'loginModal',
    'openOnError' => false, // true = เปิดอัตโนมัติเมื่อมี error ใน bag 'login'
    'action' => url('/login'),
    'title' => 'เข้าสู่ระบบ',
])

@php
    // รองรับ error bag 'login' อย่างปลอดภัย
    $loginBag = $errors->hasBag('login') ? $errors->getBag('login') : null;
@endphp

@once
    {{-- Vendor + Assets (โหลดครั้งเดียว) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/auth-modals.css') }}">
    <script src="{{ asset('js/auth-modals.js') }}" defer></script>
@endonce

<div class="modal fade login-modal" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label"
    aria-hidden="true" data-login-modal>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content lm-card">
            <div class="lm-accent" aria-hidden="true"></div>

            <div class="modal-header lm-header">
                <h5 class="modal-title m-0 fw-bolder" id="{{ $id }}Label">
                    <span class="lm-title-grad">{{ $title }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
            </div>

            <form method="POST" action="{{ $action }}" novalidate data-login-form>
                @csrf
                <div class="modal-body pt-3">
                    @if (session('login_message'))
                        <div class="alert alert-warning py-2 mb-3" role="alert">{{ session('login_message') }}</div>
                    @endif

                    {{-- PHONE --}}
                    <div class="form-floating lm-floating mb-3">
                        <input id="{{ $id }}-phone" type="text" name="phone" inputmode="tel"
                            autocomplete="username" maxlength="20" placeholder="เบอร์โทร"
                            class="form-control lm-control {{ $loginBag && $loginBag->has('phone') ? 'is-invalid' : '' }}"
                            value="{{ old('phone') }}" required
                            aria-invalid="{{ $loginBag && $loginBag->has('phone') ? 'true' : 'false' }}"
                            @if ($loginBag && $loginBag->has('phone')) aria-describedby="{{ $id }}-phone-error" @endif
                            data-autofmt-phone>
                        <label for="{{ $id }}-phone">เบอร์โทร</label>
                        <span class="lm-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.11.37 2.31.57 3.58.57a1 1 0 0 1 1 1V21a1 1 0 0 1-1 1C11.3 22 2 12.7 2 2a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.27.2 2.47.57 3.58a1 1 0 0 1-.24 1.01l-2.2 2.2Z" />
                            </svg>
                        </span>
                        @if ($loginBag && $loginBag->has('phone'))
                            <div id="{{ $id }}-phone-error" class="invalid-feedback d-block mt-1">
                                {{ $loginBag->first('phone') }}
                            </div>
                        @endif
                    </div>

                    {{-- PASSWORD --}}
                    <div class="form-floating lm-floating mb-2">
                        <input id="{{ $id }}-password" type="password" name="password"
                            autocomplete="current-password" placeholder="รหัสผ่าน"
                            class="form-control lm-control {{ $loginBag && $loginBag->has('password') ? 'is-invalid' : '' }}"
                            required aria-invalid="{{ $loginBag && $loginBag->has('password') ? 'true' : 'false' }}"
                            @if ($loginBag && $loginBag->has('password')) aria-describedby="{{ $id }}-password-error" @endif
                            data-caps-hint="#{{ $id }}-caps">
                        <label for="{{ $id }}-password">รหัสผ่าน</label>
                        <span class="lm-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 17a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm6-6h-1V8a5 5 0 1 0-10 0v3H6a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2Zm-8-3a3 3 0 0 1 6 0v3H10V8Z" />
                            </svg>
                        </span>
                        <button type="button" class="lm-eye" data-toggle-password="#{{ $id }}-password"
                            aria-label="สลับการแสดงรหัสผ่าน">
                            <i class="bi bi-eye eye-on"></i>
                            <i class="bi bi-eye-slash eye-off d-none"></i>
                        </button>

                        @if ($loginBag && $loginBag->has('password'))
                            <div id="{{ $id }}-password-error" class="invalid-feedback d-block mt-1">
                                {{ $loginBag->first('password') }}
                            </div>
                        @endif

                        <div id="{{ $id }}-caps" class="form-text small text-danger d-none mt-1">Caps Lock
                            เปิดอยู่</div>
                    </div>

                    <div class="lm-cta-register mb-3 text-center">
                        <span class="text-body-secondary">ยังไม่มีสมาชิกใช่หรือไม่?</span>
                        {{-- สลับโมดัลแบบเนียน: ปิด login ก่อน แล้วค่อยเปิด register --}}
                        <a class="lm-register-link ms-2" data-switch-modal="#registerModal" aria-label="สมัครสมาชิก">
                            สมัครสมาชิก <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn lm-primary fw-semibold" data-submit-btn>เข้าสู่ระบบ</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($openOnError)
    @once
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const shouldOpen = @json(session('showLogin') || ($errors->hasBag('login') ? $errors->getBag('login')->any() : false));
                if (shouldOpen) {
                    const el = document.getElementById(@json($id));
                    if (el) bootstrap.Modal.getOrCreateInstance(el).show();
                }
            });
        </script>
    @endonce
@endif
