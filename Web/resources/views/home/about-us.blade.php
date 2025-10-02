@extends('frontend')
@section('css_before')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
@section('css_before')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('navbar')
@endsection

@section('body')
    <section class="container py-5">
        <div class="about-wrap">
            <!-- ซ้าย: รูปภาพซ้อน -->
            <div class="about-media">
                <div class="img-hover">
                    <img class="img-xl " src="{{ asset('assets/images/nigiri-maki-sushi.jpg') }}" alt="Sushi">
                </div>
                <div class="repeat-vertical">
                    <small>Customer<br>Repeat Rate</small>
                    <div class="big">97%</div>
                </div>
                <div class="img-sm-wrap img-hover">
                    <img class="img-sm" src="{{ asset('assets/images/chef.jpg') }}" alt="Chef">
                </div>
            </div>

            <!-- ขวา: ข้อความ + bullet + สถิติ + ปุ่ม -->
            <div class="about-copy">
                <div class="eyebrow">ABOUT US</div>
                <h2>From Kitchen to Heart with Authentic Taste</h2>
                <p class="lead">
                    ซูชิโค่เริ่มจากร้านเล็ก ๆ แต่ตั้งใจคัดสรรวัตถุดิบสดใหม่ทุกวัน
                    สิ่งที่ไม่เคยเปลี่ยนคือความซื่อสัตย์ต่อรสชาติและมาตรฐานคุณภาพของเรา
                </p>

                <ul class="bullets p-0">
                    <li><i class="bi bi-check2-circle"></i> ซูชิปั้นสดใหม่ทุกคำ</li>
                    <li><i class="bi bi-check2-circle"></i> คุณภาพเกรดพรีเมียม</li>
                </ul>

                <div class="stats-card">
                    <div class="stat">
                        <h3>30+</h3>
                        <small>Years of Experience</small>
                    </div>
                    <div class="stat">
                        <h3>25K+</h3>
                        <small>Happy Customers</small>
                    </div>
                    <div class="stat">
                        <h3>98%</h3>
                        <small>Satisfaction Rate</small>
                    </div>
                </div>

                <a href="{{ url('/menus') }}" class="btn btn-accent">
                    Explore the Menu
                </a>
            </div>
        </div>
    </section>
@endsection

@endsection

@section('footer')
@endsection

@section('js_before')
@endsection
