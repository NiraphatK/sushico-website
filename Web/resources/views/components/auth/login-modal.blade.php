@props([
    'id' => 'loginModal',
    'openOnError' => false, // true = เปิดอัตโนมัติเมื่อมี error ใน bag 'login'
    'action' => url('/login'),
    'title' => 'เข้าสู่ระบบ',
])

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

{{-- Palette เฉพาะ modal ผ่านตัวแปร --lm-* (ไม่กระทบส่วนอื่นของเว็บ) --}}
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true"
    data-login-modal
    style="
    --lm-salmon: var(--salmon, #1A3636);
    --lm-gold:   var(--gold,   #40534C);
    --lm-wasabi: var(--wasabi, #677D6A);
    --lm-ink:    var(--txt,    #0b1220);
    --lm-muted:  var(--muted,  #5b647a);
    --lm-surface:    255,255,255;   /* light surface */
    --lm-surface-dk: 17,24,39;      /* dark surface */
    --lm-ring: 0 0 0 3px rgba(133,172,157,.45), 0 10px 30px rgba(64,83,76,.18);
  ">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content lm-card">
            <!-- Accent bar -->
            <div class="lm-accent"></div>

            <!-- Header -->
            <div class="modal-header lm-header">
                <h5 class="modal-title m-0 fw-extrabold" id="{{ $id }}Label">
                    <span class="lm-title-grad">{{ $title }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ $action }}" novalidate data-login-form>
                @csrf
                <div class="modal-body pt-3">

                    @if (session('login_message'))
                        <div class="alert alert-warning py-2 mb-3" role="alert">{{ session('login_message') }}</div>
                    @endif

                    {{-- PHONE (floating label + icon) --}}
                    <div class="form-floating lm-floating mb-3">
                        <input id="{{ $id }}-phone" type="text" name="phone" inputmode="tel"
                            autocomplete="username" maxlength="20" placeholder="เบอร์โทร"
                            class="form-control lm-control @error('phone', 'login') is-invalid @enderror"
                            value="{{ old('phone') }}" required
                            aria-invalid="@error('phone', 'login') true @else false @enderror"
                            @error('phone', 'login') aria-describedby="{{ $id }}-phone-error" @enderror
                            data-autofmt-phone>
                        <label for="{{ $id }}-phone">เบอร์โทร</label>
                        <span class="lm-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.11.37 2.31.57 3.58.57a1 1 0 0 1 1 1V21a1 1 0 0 1-1 1C11.3 22 2 12.7 2 2a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.27.2 2.47.57 3.58a1 1 0 0 1-.24 1.01l-2.2 2.2Z" />
                            </svg>
                        </span>
                        @error('phone', 'login')
                            <div id="{{ $id }}-phone-error" class="invalid-feedback d-block mt-1">
                                {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PASSWORD (floating label + icon + toggle inside) --}}
                    <div class="form-floating lm-floating mb-2">
                        <input id="{{ $id }}-password" type="password" name="password"
                            autocomplete="current-password" placeholder="รหัสผ่าน"
                            class="form-control lm-control @error('password', 'login') is-invalid @enderror" required
                            aria-invalid="@error('password', 'login') true @else false @enderror"
                            @error('password', 'login') aria-describedby="{{ $id }}-password-error" @enderror
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

                        @error('password', 'login')
                            <div id="{{ $id }}-password-error" class="invalid-feedback d-block mt-1">
                                {{ $message }}</div>
                        @enderror
                        <div id="{{ $id }}-caps" class="form-text small text-danger d-none mt-1">Caps
                            Lock เปิดอยู่</div>
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

@once
    <style>
        /* ===== Scope: เฉพาะใน [data-login-modal] ===== */

        /* ความกว้าง dialog ให้บาลานซ์กับเลย์เอาต์หน้า */
        [data-login-modal] .modal-dialog {
            max-width: 420px;
        }

        @media (min-width:768px) {
            [data-login-modal] .modal-dialog {
                max-width: 460px;
            }
        }

        /* Card glass + entrance animation */
        [data-login-modal] .lm-card {
            border: 0;
            border-radius: 24px;
            overflow: hidden;
            background:
                radial-gradient(120% 80% at 110% -10%, rgba(var(--lm-surface), .45), transparent 60%),
                radial-gradient(120% 80% at -30% 110%, rgba(var(--lm-surface), .35), transparent 60%),
                linear-gradient(180deg, rgba(var(--lm-surface), .95), rgba(var(--lm-surface), .88));
            backdrop-filter: blur(14px) saturate(140%);
            box-shadow: 0 28px 60px rgba(2, 6, 23, .18);
            transform: translateY(8px) scale(.985);
            animation: lm-pop .35s cubic-bezier(.2, .7, .2, 1) forwards;
        }

        @keyframes lm-pop {
            to {
                transform: translateY(0) scale(1);
            }
        }

        /* Accent & header */
        [data-login-modal] .lm-accent {
            height: 4px;
            background: linear-gradient(90deg, var(--lm-salmon), var(--lm-gold) 50%, var(--lm-wasabi));
        }

        [data-login-modal] .lm-header {
            border: 0;
            background: linear-gradient(180deg, rgba(var(--lm-surface), .7), rgba(var(--lm-surface), .35));
            backdrop-filter: blur(10px) saturate(140%);
            box-shadow: 0 1px 0 rgba(2, 6, 23, .06) inset;
        }

        [data-login-modal] .lm-title-grad {
            background: linear-gradient(120deg, var(--lm-salmon), var(--lm-gold) 55%, var(--lm-wasabi));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 900;
            letter-spacing: .2px;
        }

        /* Floating fields */
        [data-login-modal] .lm-floating {
            position: relative;
        }

        [data-login-modal] .lm-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--lm-muted);
            opacity: .85;
            pointer-events: none;
        }

        [data-login-modal] .lm-eye {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            padding: .25rem;
            border-radius: 8px;
            cursor: pointer;
            color: var(--lm-muted);
        }

        [data-login-modal] .lm-eye:hover {
            color: var(--lm-ink);
        }

        [data-login-modal] .form-floating>.form-control {
            padding-left: 42px;
        }

        [data-login-modal] .form-floating>label {
            left: 42px;
            color: var(--lm-muted);
            opacity: .95;
            transition: color .2s ease, opacity .2s ease;
        }

        [data-login-modal] .form-floating>.form-control:focus~label {
            transition: .3s;
            color: var(--lm-ink);
            opacity: 1;
        }

        [data-login-modal] .lm-control {
            border-radius: 14px;
            border: 1px solid rgba(2, 6, 23, .12);
            background: rgba(var(--lm-surface), .95);
            color: var(--lm-ink);
            transition: box-shadow .2s ease, border-color .2s ease, background .2s ease, transform .06s ease;
        }

        [data-login-modal] .lm-control:hover {
            transform: translateY(-1px);
        }

        [data-login-modal] .lm-control:focus {
            box-shadow: var(--lm-ring);
            border-color: transparent;
            background: rgba(var(--lm-surface), 1);
        }

        [data-login-modal] .lm-control.is-invalid {
            border-color: rgba(220, 53, 69, .55);
            box-shadow: 0 0 0 2px rgba(220, 53, 69, .15);
        }

        /* Primary button (เฉพาะ modal) */
        [data-login-modal] .lm-primary {
            position: relative;
            isolation: isolate;
            border: 0;
            border-radius: 999px;
            padding: .75rem 1.25rem;
            color: #fff;
            background: linear-gradient(135deg, var(--lm-salmon), var(--lm-gold) 55%, var(--lm-wasabi));
            box-shadow: 0 14px 30px rgba(2, 6, 23, .18);
            transition: transform .12s ease, filter .2s ease, box-shadow .2s ease;
            overflow: hidden;
        }

        [data-login-modal] .lm-primary:hover {
            transform: translateY(-1px);
            filter: brightness(1.03);
            box-shadow: 0 20px 40px rgba(2, 6, 23, .26);
        }

        .lm-primary::before {
            content: "";
            position: absolute;
            top: -120%;
            left: -30%;
            width: 40%;
            height: 300%;
            transform: rotate(25deg);
            background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, .7) 50%, rgba(255, 255, 255, 0) 100%);
            opacity: 0;
            filter: blur(6px);
        }

        .lm-primary:hover::before {
            animation: shine 1.2s ease;
        }

        @keyframes shine {
            0% {
                left: -30%;
                opacity: 0
            }

            10% {
                opacity: .2
            }

            50% {
                left: 120%;
                opacity: .6
            }

            100% {
                left: 140%;
                opacity: 0
            }
        }

        [data-login-modal] .lm-primary.disabled,
        [data-login-modal] .lm-primary[aria-disabled="true"] {
            opacity: .8;
            cursor: not-allowed;
        }

        [data-login-modal] .btn-light {
            position: relative;
            isolation: isolate;
            padding: .8rem 1.1rem;
            border-radius: 50px;
            color: var(--txt);
            background: linear-gradient(180deg, rgba(255, 255, 255, .95), rgba(255, 255, 255, .7));
            border: 1px solid rgba(11, 18, 32, .1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all .2s ease;
            font-weight: 700;
        }

        [data-login-modal] .btn-light:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(11, 18, 32, .12);
        }

        [data-login-modal] .invalid-feedback {
            font-size: .82rem;
        }

        @media (prefers-reduced-motion: reduce) {
            [data-login-modal] .lm-card {
                animation: none;
                transform: none;
            }

            [data-login-modal] .lm-control:hover {
                transform: none;
            }
        }

        /* ===== Backdrop เฉพาะตอนเปิด modal นี้ (กลืนพื้นหลังของหน้า) ===== */
        body[data-login-open="{{ $id }}"] .modal-backdrop.show {
            opacity: 1 !important;
            background:
                radial-gradient(1200px 800px at -10% -20%, rgba(255, 111, 97, .18), transparent 60%),
                radial-gradient(1100px 900px at 110% 120%, rgba(248, 201, 74, .16), transparent 60%),
                radial-gradient(1000px 800px at 50% 50%, rgba(103, 125, 106, .10), transparent 65%),
                rgba(11, 18, 32, .52);
            backdrop-filter: blur(6px) saturate(120%);
            -webkit-backdrop-filter: blur(6px) saturate(120%);
        }
    </style>

    <script>
        (function() {
            // Toggle password + swap eye icons
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('[data-toggle-password]');
                if (!btn) return;
                const input = document.querySelector(btn.getAttribute('data-toggle-password'));
                if (!input) return;
                input.type = (input.type === 'password') ? 'text' : 'password';
                btn.querySelector('.eye-on')?.classList.toggle('d-none', input.type === 'text');
                btn.querySelector('.eye-off')?.classList.toggle('d-none', input.type !== 'text');
                input.focus();
            });

            // Prevent double submit + spinner
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('form[data-login-form]');
                if (!form) return;
                const btn = form.querySelector('[data-submit-btn]');
                if (btn?.dataset.loading === '1') {
                    e.preventDefault();
                    return false;
                }
                if (btn) {
                    btn.dataset.loading = '1';
                    btn.dataset.original = btn.innerHTML;
                    btn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>กำลังเข้าสู่ระบบ...';
                    btn.classList.add('disabled');
                    btn.setAttribute('aria-disabled', 'true');
                }
            });

            // Autofocus: invalid first > phone
            document.addEventListener('shown.bs.modal', function(e) {
                const m = e.target.closest('[data-login-modal]');
                if (!m) return;
                const firstInvalid = m.querySelector('.is-invalid');
                const phone = m.querySelector('input[name="phone"]');
                (firstInvalid || phone)?.focus();

                // ติดธงบน body เพื่อสไตล์ backdrop เฉพาะ modal นี้
                if (m.id) document.body.setAttribute('data-login-open', m.id);
            });

            // ล้างธงเมื่อปิด
            document.addEventListener('hidden.bs.modal', function(e) {
                const m = e.target.closest('[data-login-modal]');
                if (!m) return;
                if (document.body.getAttribute('data-login-open') === m.id) document.body.removeAttribute(
                    'data-login-open');
            });

            // CapsLock hint
            document.addEventListener('keydown', capsToggle);
            document.addEventListener('keyup', capsToggle);

            function capsToggle(e) {
                const inp = e.target.closest('input[type="password"][data-caps-hint]');
                if (!inp) return;
                const hint = document.querySelector(inp.getAttribute('data-caps-hint'));
                if (!hint) return;
                const on = e.getModifierState && e.getModifierState('CapsLock');
                hint.classList.toggle('d-none', !on);
            }

            // Phone auto-format (simple 3-3-4)
            document.addEventListener('input', function(e) {
                const el = e.target.closest('input[data-autofmt-phone]');
                if (!el) return;
                const digits = el.value.replace(/\D/g, '').slice(0, 10);
                let out = digits;
                if (digits.length > 3 && digits.length <= 6) out = digits.slice(0, 3) + '-' + digits.slice(3);
                else if (digits.length > 6) out = digits.slice(0, 3) + '-' + digits.slice(3, 6) + '-' + digits
                    .slice(6);
                el.value = out;
            });

            document.addEventListener('submit', function(e) {
                const form = e.target.closest('form[data-login-form]');
                if (!form) return;
                const phoneInput = form.querySelector('input[data-autofmt-phone]');
                if (phoneInput) {
                    phoneInput.value = phoneInput.value.replace(/\D/g, ''); // เก็บเฉพาะตัวเลข
                }
            });
        })
        ();
    </script>
@endonce
