@props([
    'id' => 'registerModal',
    'openOnError' => false, // true = เปิดอัตโนมัติเมื่อมี error ใน bag 'register'
    'action' => url('/register'),
    'title' => 'สมัครสมาชิก',
])

@once
    <link rel="stylesheet" href="{{ asset('css/auth-modals.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endonce

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true"
    data-register-modal>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content lm-card">
            <div class="lm-accent"></div>

            <div class="modal-header lm-header">
                <h5 class="modal-title m-0 fw-extrabold" id="{{ $id }}Label">
                    <span class="lm-title-grad">{{ $title }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
            </div>

            <form method="POST" action="{{ $action }}" novalidate data-register-form>
                @csrf
                <div class="modal-body pt-3">

                    @if (session('register_message'))
                        <div class="alert alert-success py-2 mb-3" role="alert">{{ session('register_message') }}
                        </div>
                    @endif

                    {{-- FULL NAME --}}
                    <div class="form-floating lm-floating mb-3">
                        <input id="{{ $id }}-full_name" type="text" name="full_name"
                            placeholder="ชื่อ-สกุล"
                            class="form-control lm-control @error('full_name', 'register') is-invalid @enderror"
                            value="{{ old('full_name') }}" required
                            aria-invalid="@error('full_name', 'register') true @else false @enderror"
                            @error('full_name', 'register') aria-describedby="{{ $id }}-full_name-error" @enderror>
                        <label for="{{ $id }}-full_name">ชื่อ-สกุล</label>
                        <span class="lm-icon" aria-hidden="true"><i class="bi bi-person"></i></span>
                        @error('full_name', 'register')
                            <div id="{{ $id }}-full_name-error" class="invalid-feedback d-block mt-1">
                                {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PHONE --}}
                    <div class="form-floating lm-floating mb-3">
                        <input id="{{ $id }}-phone" type="text" name="phone" inputmode="tel"
                            autocomplete="username" maxlength="20" placeholder="เบอร์โทร"
                            class="form-control lm-control @error('phone', 'register') is-invalid @enderror"
                            value="{{ old('phone') }}" required
                            aria-invalid="@error('phone', 'register') true @else false @enderror"
                            @error('phone', 'register') aria-describedby="{{ $id }}-phone-error" @enderror
                            data-autofmt-phone>
                        <label for="{{ $id }}-phone">เบอร์โทร</label>
                        <span class="lm-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.11.37 2.31.57 3.58.57a1 1 0 0 1 1 1V21a1 1 0 0 1-1 1C11.3 22 2 12.7 2 2a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.27.2 2.47.57 3.58a1 1 0 0 1-.24 1.01l-2.2 2.2Z" />
                            </svg>
                        </span>
                        @error('phone', 'register')
                            <div id="{{ $id }}-phone-error" class="invalid-feedback d-block mt-1">
                                {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- EMAIL (optional) --}}
                    <div class="form-floating lm-floating mb-3">
                        <input id="{{ $id }}-email" type="email" name="email"
                            placeholder="อีเมล (ไม่บังคับ)" autocomplete="email"
                            class="form-control lm-control @error('email', 'register') is-invalid @enderror"
                            value="{{ old('email') }}"
                            aria-invalid="@error('email', 'register') true @else false @enderror"
                            @error('email', 'register') aria-describedby="{{ $id }}-email-error" @enderror>
                        <label for="{{ $id }}-email">อีเมล (ไม่บังคับ)</label>
                        <span class="lm-icon" aria-hidden="true"><i class="bi bi-envelope"></i></span>
                        @error('email', 'register')
                            <div id="{{ $id }}-email-error" class="invalid-feedback d-block mt-1">
                                {{ $message }}</div>
                        @enderror
                    </div>

                    @php
                        $registerBag = $errors->hasBag('register') ? $errors->getBag('register') : null;
                    @endphp

                  {{-- PASSWORD --}}
<div class="form-floating lm-floating mb-2">
  <input id="{{ $id }}-password" type="password" name="password"
    autocomplete="new-password" placeholder="รหัสผ่าน"
    class="form-control lm-control {{ $registerBag && $registerBag->has('password') ? 'is-invalid' : '' }}"
    required
    aria-invalid="{{ $registerBag && $registerBag->has('password') ? 'true' : 'false' }}"
    @if ($registerBag && $registerBag->has('password')) aria-describedby="{{ $id }}-password-error" @endif
    data-caps-hint="#{{ $id }}-caps1" data-strength="#{{ $id }}-strength"
    data-needs="#{{ $id }}-pw-needs" data-suggest="#{{ $id }}-pw-suggest">
  <label for="{{ $id }}-password">รหัสผ่าน</label>
  <span class="lm-icon" aria-hidden="true"><i class="bi bi-shield-lock"></i></span>
  <button type="button" class="lm-eye" data-toggle-password="#{{ $id }}-password"
    aria-label="สลับการแสดงรหัสผ่าน">
    <i class="bi bi-eye eye-on"></i>
    <i class="bi bi-eye-slash eye-off d-none"></i>
  </button>

  @if ($registerBag && $registerBag->has('password'))
    <div id="{{ $id }}-password-error" class="invalid-feedback d-block mt-1">
      {{ $registerBag->first('password') }}
    </div>
  @endif

  <small class="text-danger caps-hint d-none" id="{{ $id }}-caps1"></small>
</div>

<div class="d-flex align-items-center gap-2 mt-2 mb-3">
  <div class="progress flex-grow-1" style="height:6px;">
    <div id="{{ $id }}-strength" class="progress-bar" role="progressbar"
      style="width:0%;" aria-valuemin="0" aria-valuemax="100"></div>
  </div>
</div>

{{-- สิ่งที่ยังขาด + ข้อเสนอแนะ --}}
<ul class="lm-need-list" id="{{ $id }}-pw-needs" data-label="คำแนะนำการตั้งรหัสผ่าน"></ul>

{{-- PASSWORD CONFIRM --}}
<div class="form-floating lm-floating mb-3 mt-3">
  <input id="{{ $id }}-password_confirmation" type="password" name="password_confirmation"
    autocomplete="new-password" placeholder="ยืนยันรหัสผ่าน"
    class="form-control lm-control {{ $registerBag && $registerBag->has('password_confirmation') ? 'is-invalid' : '' }}"
    required
    data-caps-hint="#{{ $id }}-caps2">
  <label for="{{ $id }}-password_confirmation">ยืนยันรหัสผ่าน</label>
  <span class="lm-icon" aria-hidden="true"><i class="bi bi-shield-check"></i></span>
  <button type="button" class="lm-eye" data-toggle-password="#{{ $id }}-password_confirmation"
    aria-label="สลับการแสดงรหัสผ่าน">
    <i class="bi bi-eye eye-on"></i>
    <i class="bi bi-eye-slash eye-off d-none"></i>
  </button>

  @if ($registerBag && $registerBag->has('password_confirmation'))
    <div class="invalid-feedback d-block mt-1">
      {{ $registerBag->first('password_confirmation') }}
    </div>
  @endif

  <small class="text-danger caps-hint d-none" id="{{ $id }}-caps2"></small>
</div>


                    {{-- TERMS --}}
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" value="1"
                            id="{{ $id }}-terms" name="terms" {{ old('terms') ? 'checked' : '' }}
                            required>
                        <label class="form-check-label" for="{{ $id }}-terms">
                            ฉันยอมรับข้อตกลงการใช้งานและนโยบายความเป็นส่วนตัว
                        </label>
                    </div>
                    @if ($registerBag && $registerBag->has('terms'))
                        <div class="text-danger small mt-1">{{ $registerBag->first('terms') }}</div>
                    @endif


                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn lm-primary fw-semibold" data-submit-btn>สมัครสมาชิก</button>
                    </div>
            </form>
        </div>
    </div>
</div>

@if ($openOnError)
    @once
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const shouldOpen = @json(session('showRegister') || ($errors->hasBag('register') ? $errors->getBag('register')->any() : false));
                if (shouldOpen) {
                    const el = document.getElementById(@json($id));
                    if (el) bootstrap.Modal.getOrCreateInstance(el).show();
                }
            });
        </script>
    @endonce
@endif

@once
    <script src="{{ asset('js/auth-modals.js') }}" defer></script>
@endonce
