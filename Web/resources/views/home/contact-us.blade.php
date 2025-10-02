@extends('frontend')

@section('css_before')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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
                if (preg_match('/^\d{2}:\d{2}$/', $raw)) {
                    return \Carbon\Carbon::createFromFormat('H:i', $raw, $tz)->format('h:i A');
                }
                if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $raw)) {
                    return \Carbon\Carbon::createFromFormat('H:i:s', $raw, $tz)->format('h:i A');
                }
                if (preg_match('/^\d{1,2}:\d{2}\s?(AM|PM)$/i', $raw)) {
                    return \Carbon\Carbon::createFromFormat('g:i A', strtoupper($raw), $tz)->format('h:i A');
                }
                if (preg_match('/^(\d{1,2}):(\d{2})/', $raw, $m)) {
                    $hh = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                    $mm = $m[2];
                    return \Carbon\Carbon::createFromFormat('H:i', "$hh:$mm", $tz)->format('h:i A');
                }
            } catch (\Throwable $e) {
            }
            return mb_substr($raw, 0, 5);
        };

        $open = $fmt($openRaw, $tz);
        $close = $fmt($closeRaw, $tz);
    @endphp

    {{-- หุ้มทั้งหน้าไว้ด้วย .page-contact เพื่อสcope CSS --}}
    <div class="page-contact">
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
                                <form id="contactForm" action="#" method="POST" novalidate
                                    class="contact-form wow fadeInUp" data-wow-delay="0.4s">
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
                                            <input type="email" name="email" class="form-control" id="email"
                                                placeholder="Email" required>
                                        </div>
                                        <div class="form-group col-md-6 mb-4">
                                            <input type="text" name="phone" class="form-control" id="phone"
                                                placeholder="Phone" required>
                                        </div>
                                        <div class="form-group col-md-12 mb-4">
                                            <textarea name="message" class="form-control" id="message" rows="3" placeholder="Write Message..." required></textarea>
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
    </div>
@endsection

@section('footer')
@endsection

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/home.js') }}" defer></script>
@endsection
