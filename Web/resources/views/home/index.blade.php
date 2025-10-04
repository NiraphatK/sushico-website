@extends('frontend')

@section('css_before')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('body')
    <section class="container py-3 py-md-2" data-aos="fade-up">
        <div class="text-center mb-4 mb-md-5">
            <div class="topic-header">Our Services</div>
            <p class="header-text">
                ทุกเมนูเกิดจากความตั้งใจและความรักในรายละเอียด เพื่อส่งต่อรสชาติที่ดีที่สุดจากครัวของเรา
            </p>
        </div>

        {{-- ===== จุดเด่นร้าน ===== --}}
        <div class="home-highlights mb-12">
            <div class="hi-card">
                <div class="hi-icon"><i class="bi bi-stars"></i></div>
                <div class="hi-text">
                    <h3>วัตถุดิบพรีเมียม คัดวันต่อวัน</h3>
                    <p>แซลมอน • มากูโระ • อูนางิ ส่งตรงแบบควบคุมอุณหภูมิ</p>
                </div>
            </div>
            <div class="hi-card">
                <div class="hi-icon"><i class="bi bi-heart-pulse"></i></div>
                <div class="hi-text">
                    <h3>สะอาด ปลอดภัย มั่นใจได้</h3>
                    <p>ครัวมาตรฐาน • Food Safety • ครบตามสุขอนามัย</p>
                </div>
            </div>
            <div class="hi-card">
                <div class="hi-icon"><i class="bi bi-clock-history"></i></div>
                <div class="hi-text">
                    <h3>บริการรวดเร็ว ทันใจ</h3>
                    <p>สำรองล่วงหน้า • คิวไม่ขาดตอน • เสิร์ฟทันใจทุกจาน</p>
                </div>
            </div>
        </div>

        {{-- ===== A Culinary Journey ===== --}}
        <div class="journey-grid mt-5 mt-md-10">
            {{-- ด้านซ้าย --}}
            <div class="journey-left">
                <figure class="journey-photo-lg">
                    <img src="{{ asset('assets/images/origin-of-sushi-hero.jpeg') }}" alt="Culinary Journey Left">
                </figure>

                <h2 class="journey-title">
                    <span class="gtext">A Culinary</span>
                    <br class="d-none d-md-inline"><span class="gtext">Journey</span>
                </h2>

                <div class="journey-copy-left">
                    <p>
                        ทุกจานของเราเกิดจากความตั้งใจของเชฟผู้มากประสบการณ์
                        ที่เลือกใช้วัตถุดิบสดใหม่จากแหล่งท้องถิ่นและต่างประเทศ
                        ผสมผสานศิลปะแห่งรสชาติญี่ปุ่นเข้ากับความร่วมสมัย เพื่อสร้างประสบการณ์ที่อบอุ่นและน่าจดจำในทุกคำ
                </div>
            </div>

            {{-- ด้านขวา --}}
            <div class="journey-right">
                <p class="journey-hero-text">
                    Discover the harmony of tradition and innovation. We source the finest seasonal produce and craft each
                    dish with care. Taste the story in every bite.</p>

                <figure class="journey-photo-circle">
                    <img src="{{ asset('assets/images/sushi-set.jpg') }}" alt="Culinary Journey Right">
                </figure>
            </div>
        </div>

        {{-- ไว้สำหรับลายน้ำ --}}
        <span class="journey-deco journey-deco--citrus" aria-hidden="true"></span>
        <span class="journey-deco journey-deco--blossom" aria-hidden="true"></span>
    </section>
@endsection


@section('footer')
@endsection

@section('js_before')
@endsection