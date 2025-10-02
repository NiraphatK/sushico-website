{{-- resources/views/contact-us.blade.php --}}
@extends('frontend')

@section('css_before')
    <style>
        /* ================== THEME TOKENS ================== */
        :root {
            --salmon: #1A3636;
            --wasabi: #677D6A;
            --gold: #40534C;
            --ink: #0b1220;
            --muted: #6b7280;
            --card: #ffffff;
            --bg: #f7f8fb;
            --accent-ink: #0c0d2b;
            --ring: rgba(11, 18, 32, .12);
            --shadow: 0 10px 30px rgba(11, 18, 32, .12);
            --shadow-lg: 0 18px 44px rgba(11, 18, 32, .16);
        }

        /* Page padding fix */
        body {
            background: var(--bg);
            color: var(--ink)
        }

        /* ================== CONTACT: LEFT IMAGE ================== */
        .contact-us-image {
            position: relative;
            isolation: isolate;
        }

        .contact-us-img img {
            width: 100%;
            display: block;
            aspect-ratio: 4/3;
            object-fit: cover;
            border-radius: 22px;
            box-shadow: var(--shadow);
            filter: saturate(105%);
            transition: transform .5s ease, box-shadow .5s ease, filter .5s ease;
        }

        .contact-us-image:hover .contact-us-img img {
            transform: translateY(-2px) scale(1.01);
            box-shadow: var(--shadow-lg);
            filter: saturate(112%);
        }

        .img-hover {
            position: relative;
            display: block;
            overflow: hidden;
            border-radius: 18px;
            transition: transform .45s cubic-bezier(.2, .7, .2, 1), box-shadow .45s;
            box-shadow: 0 2px 10px rgba(11, 18, 32, .08);
            isolation: isolate;
        }

        .img-hover img {
            display: block;
            width: 100%;
            height: auto;
            transition: transform .6s ease, filter .45s ease;
            transform-origin: center;
        }

        .img-hover::before {
            content: "";
            position: absolute;
            top: -20%;
            left: -40%;
            width: 30%;
            height: 140%;
            transform: skewX(-20deg) translateX(-120%);
            background: linear-gradient(120deg,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, .45) 50%,
                    rgba(255, 255, 255, 0) 100%);
            pointer-events: none;
            z-index: 2;
        }

        .img-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(11, 18, 32, .35);
        }

        .img-hover:hover::before {
            animation: shine 1.2s ease;
        }

        @keyframes shine {
            0% {
                left: -30%;
                opacity: 0;
            }

            10% {
                opacity: .3;
            }

            50% {
                left: 120%;
                opacity: .7;
            }

            100% {
                left: 140%;
                opacity: 0;
            }
        }

        /* ================== OPENING HOURS CARD ================== */
        .opening-hours-item {
            position: absolute;
            left: clamp(12px, 2vw, 22px);
            bottom: clamp(12px, 2vw, 22px);
            background: rgba(255, 255, 255, .75);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(11, 18, 32, .08);
            border-radius: 16px;
            padding: 16px 18px;
            box-shadow: var(--shadow);
            width: min(320px, 85%);
            transition: transform .35s ease, box-shadow .35s ease, background .35s ease;
        }

        .contact-us-image:hover .opening-hours-item {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            background: rgba(255, 255, 255, .92);
        }

        .opening-hours-item h3 {
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: .2px;
            margin: 0 0 8px;
            color: var(--accent-ink);
        }

        .opening-hours-item ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .opening-hours-item li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: .95rem;
            color: var(--ink);
            padding: 6px 0;
        }

        .opening-hours-item li::before {
            content: "";
            flex: 0 0 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--salmon);
            box-shadow: 0 0 0 4px rgba(87, 110, 45, .12);
        }

        /* Mobile: เอาการ์ดออกจากภาพเพื่อไม่ทับ */
        @media (max-width: 991.98px) {
            .opening-hours-item {
                position: static;
                width: 100%;
                margin-top: 14px;
                margin-bottom: 30px;
            }
        }

        /* ================== RIGHT CONTENT ================== */
        .section-title {
            margin-bottom: 18px
        }

        .section-title h2 {
            font-weight: 800;
            line-height: 1.15;
            margin: 8px 0 16px;
            font-size: clamp(1.8rem, 3.4vw, 2.6rem)
        }

        @media (max-width:1024px) {
            .section-title h2 {
                font-size: 2rem
            }
        }

        .section-title .eyebrow {
            color: var(--salmon);
            font-weight: 700;
            letter-spacing: .12em;
            font-size: .9rem
        }

        .section-title .text-anime-style-3 .split-line {
            white-space: normal
        }

        .section-title p {
            color: var(--muted);
            margin: 0
        }

        /* ================== FORM ================== */
        .contact-us-form {
            margin-top: 14px
        }

        .contact-us-form .form-group {
            position: relative
        }

        .contact-us-form .form-control {
            background: var(--card);
            border: 1px solid rgba(11, 18, 32, .12);
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 1rem;
            color: var(--ink);
            transition: border-color .2s ease, box-shadow .2s ease, transform .06s ease;
        }

        .contact-us-form .form-control:hover {
            border-color: rgba(11, 18, 32, .22)
        }

        .contact-us-form .form-control:focus {
            outline: 0;
            border-color: var(--salmon);
            box-shadow: 0 0 0 .2rem var(--ring)
        }

        .contact-us-form textarea.form-control {
            min-height: 120px;
            resize: vertical
        }

        /* ================== BUTTON ================== */
        .btn-default {
            position: relative;
            isolation: isolate;
            padding: .8rem 1.8rem;
            border: none;
            border-radius: 50px;
            font-weight: 800;
            letter-spacing: .3px;
            color: #fff;
            background: linear-gradient(135deg, var(--salmon), var(--gold) 55%, var(--wasabi));
            box-shadow: 0 10px 22px rgba(0, 0, 0, .35), 0 1px 0 rgba(68, 68, 68, .5) inset;
            transition: transform .15s ease, filter .2s ease, box-shadow .2s ease;
            overflow: hidden;
        }

        .btn-default::before {
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

        .btn-default:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
            box-shadow: 0 16px 36px rgba(0, 0, 0, .4);
            color: #fff
        }

        .btn-default:hover::before {
            animation: shine 1.2s ease
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

        /* ================== LAYOUT TWEAKS ================== */
        @media (min-width:992px) {
            .contact-us-content {
                padding-left: 6px
            }
        }

        .col-12.py-5>.container {
            padding-top: 8px
        }

        /* WOW-like fallback */
        .wow {
            opacity: 1
        }

        .fadeInUp {
            animation: fadeUp .6s ease both
        }

        @keyframes fadeUp {
            from {
                opacity: .0;
                transform: translate3d(0, 8px, 0)
            }

            to {
                opacity: 1;
                transform: none
            }
        }
    </style>
@endsection

{{-- ถ้าต้องการคง navbar เดิม ให้ลบ section นี้ทิ้งได้ --}}
@section('navbar')
@endsection

@section('body')
    @php
        // ป้องกัน InvalidFormatException: รองรับ H:i, H:i:s, g:i A
        $tz = optional($setting)->timezone ?? 'Asia/Bangkok';
        $openRaw = trim((string) (optional($setting)->open_time ?? '09:00'));
        $closeRaw = trim((string) (optional($setting)->close_time ?? '20:00'));

        $fmt = function (string $raw, string $tz) {
            try {
                if (preg_match('/^\d{2}:\d{2}$/', $raw))
                    return \Carbon\Carbon::createFromFormat('H:i', $raw, $tz)->format('h:i A');
                if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $raw))
                    return \Carbon\Carbon::createFromFormat('H:i:s', $raw, $tz)->format('h:i A');
                if (preg_match('/^\d{1,2}:\d{2}\s?(AM|PM)$/i', $raw))
                    return \Carbon\Carbon::createFromFormat('g:i A', strtoupper($raw), $tz)->format('h:i A');
                if (preg_match('/^(\d{1,2}):(\d{2})/', $raw, $m)) {
                    $hh = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                    $mm = $m[2];
                    return \Carbon\Carbon::createFromFormat('H:i', "$hh:$mm", $tz)->format('h:i A');
                }
            } catch (\Throwable $e) {
            }
            return mb_substr($raw, 0, 5); // fallback เผื่อค่าเพี้ยน
        };

        $open = $fmt($openRaw, $tz);
        $close = $fmt($closeRaw, $tz);
    @endphp

    <div class="col-12 py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Contact Us Image Start -->
                    <div class="contact-us-image">
                        <div class="contact-us-img img-hover">
                            <img src="/assets/images/contact-us.jpg" alt="Contact illustration">
                        </div>

                        <!-- Opening Hours Item Start -->
                        <div class="opening-hours-item wow fadeInUp">
                            <h3>Opening Hour</h3>
                            <ul>
                                <li>Mon - Sun : {{ $open }} - {{ $close }}</li>
                            </ul>
                        </div>
                        <!-- Opening Hours Item End -->
                    </div>
                    <!-- Contact Us Image End -->
                </div>

                <div class="col-lg-6">
                    <!-- Contact Us Content Start -->
                    <div class="contact-us-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <div class="eyebrow">CONTACT US</div>
                            <h2 class="text-anime-style-3" data-cursor="-opaque" style="perspective:400px;">
                                <div class="split-line" style="display:block; text-align:start; position:relative;">
                                    Get in quick to touch with us
                                </div>
                            </h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">
                                หากท่านมีข้อสงสัย ข้อเสนอแนะ หรือความต้องการพิเศษ สามารถติดต่อเราได้ทันที
                                ทีมงานพร้อมให้ความช่วยเหลือเพื่อมอบประสบการณ์ที่ดีที่สุดแก่ท่าน
                            </p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Contact Form Start -->
                        <div class="contact-us-form">
                            <form id="contactForm" action="#" method="POST" novalidate class="contact-form wow fadeInUp"
                                data-wow-delay="0.4s">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6 mb-4">
                                        <input type="text" name="fname" class="form-control" id="fname"
                                            placeholder="First name" required>
                                    </div>

                                    <div class="form-group col-md-6 mb-4">
                                        <input type="text" name="lname" class="form-control" id="lname"
                                            placeholder="Last name" required>
                                    </div>

                                    <div class="form-group col-md-6 mb-4">
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Email"
                                            required>
                                    </div>

                                    <div class="form-group col-md-6 mb-4">
                                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone"
                                            required>
                                    </div>

                                    <div class="form-group col-md-12 mb-4">
                                        <textarea name="message" class="form-control" id="message" rows="3"
                                            placeholder="Write Message..."></textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn-default"><span>SUBMIT MESSAGE</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Contact Form End -->
                    </div>
                    <!-- Contact Us Content End -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
@endsection

@section('js_before')
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('contactForm');
            if (!form) return;

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                // HTML5 validation
                if (!form.reportValidity()) return;

                const btn = form.querySelector('button[type="submit"]');
                const prevHTML = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span>Sending…</span>';

                try {
                    // ถ้ามี endpoint จริงให้เปลี่ยน action + uncomment ด้านล่างนี้
                    // const resp = await fetch(form.action, {
                    //   method: 'POST',
                    //   headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    //   body: new FormData(form),
                    // });
                    // if (!resp.ok) throw new Error('Network error');

                    // เดโม: ให้มีหน่วงเล็กน้อย
                    await new Promise(r => setTimeout(r, 400));

                    Swal.fire({
                        title: "ส่งข้อความเรียบร้อย",
                        text: "ทางเราได้รับข้อความของท่านเรียบร้อยแล้ว",
                        icon: "success",
                        confirmButtonText: "OK",
                        draggable: true,
                        customClass: {
                            confirmButton: 'btn-default',
                        }
                    });

                    form.reset();
                } catch (err) {
                    Swal.fire({
                        title: "Oops…",
                        text: err?.message || "Something went wrong. Please try again.",
                        icon: "error",
                        confirmButtonText: "Close"
                    });
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = prevHTML;
                }
            });
        });
    </script>
@endsection