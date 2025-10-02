@extends('frontend')
@section('css_before')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --salmon: #1A3636;
            --wasabi: #677D6A;
            --gold: #40534C;
            --ink: #0b1220;
            --muted: #6b7280;
            --card: #fff;
            --ring: rgba(255, 255, 255, .7);
            --overlay: rgba(0, 0, 0, .22);
            --radius: 22px;
            --easing: cubic-bezier(.2, .8, .2, 1);
        }

        /* ====== Layout ====== */
        .about-wrap {
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            gap: clamp(24px, 4vw, 56px);
            align-items: center;
        }

        .about-media {
            position: relative;
            isolation: isolate;
        }

        /* ====== Images: base ====== */
        .about-media .img-xl {
            width: 100%;
            max-width: min(720px, 100%);
            aspect-ratio: 16/11;
            /* ใกล้เคียง 3:2 */
            object-fit: cover;
            border-radius: 24px;
            box-shadow: 0 16px 50px rgba(11, 18, 32, .18);
        }

        .about-media .img-sm-wrap {
            position: absolute;
            bottom: clamp(-100px, -3vw, -36px);
            left: clamp(16px, 4.5vw, 100px);
            width: min(30vw, 380px);
            /* ย่อ-ขยายตามความกว้างจอ */
        }

        .about-media .img-sm {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: 0 14px 40px rgba(11, 18, 32, .2);
            border: 6px solid #fff;
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

        /* ====== Badges ====== */
        .repeat-vertical {
            position: absolute;
            top: 40px;
            right: -22px;
            background: linear-gradient(135deg, var(--salmon), var(--gold) 55%, var(--wasabi));
            color: #fff;
            border-radius: 16px;
            padding: 18px 10px;
            width: 70px;
            text-align: center;
        }

        .repeat-vertical small {
            display: block;
            font-size: .7rem;
            letter-spacing: .5px;
            opacity: .85;
            writing-mode: vertical-rl;
            transform: rotate(180deg);
        }

        .repeat-vertical .big {
            font-weight: 800;
            font-size: 1.8rem;
            margin-top: 8px;
            writing-mode: vertical-rl;
            transform: rotate(180deg);
        }

        /* ====== Text / bullets / stats ====== */
        .about-copy .eyebrow {
            color: var(--salmon);
            font-weight: 700;
            letter-spacing: .12em;
            font-size: .9rem
        }

        .about-copy h2 {
            font-weight: 800;
            line-height: 1.15;
            margin: 8px 0 16px;
            font-size: clamp(1.8rem, 3.4vw, 2.6rem)
        }

        .about-copy p.lead {
            color: var(--muted)
        }

        .bullets {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px 22px;
            margin: 18px 0 26px
        }

        .bullets li {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .bullets .bi {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            font-size: 16px;
            color: var(--salmon);
            background: color-mix(in srgb, var(--gold) 20%, transparent);
            border-radius: 50%;
        }

        .stats-card {
            background: var(--card);
            border: 1px solid rgba(11, 18, 32, .08);
            border-radius: 18px;
            padding: 18px 22px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            align-items: center;
            margin-bottom: 22px;
            box-shadow: 0 8px 24px rgba(11, 18, 32, .06)
        }

        .stat h3 {
            font-weight: 800;
            margin: 0
        }

        .stat small {
            color: var(--muted)
        }

        .btn-accent {
            display: inline-block;
            position: relative;
            padding: 0.9rem 3rem 0.9rem 2rem;
            color: #ffffff;
            border: none;
            font-weight: 800;
            text-decoration: none;
            border-radius: 50px;
            background: linear-gradient(135deg, var(--salmon), var(--gold) 55%, var(--wasabi));
            overflow: hidden;
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.35), 0 1px 0 rgba(255, 255, 255, 0) inset;
            transition: transform .15s ease, filter .2s ease, box-shadow .2s ease, background .3s ease;
        }

        /* วงกลม + icon */
        .btn-accent::after {
            content: "";
            position: absolute;
            right: 0.4rem;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: #fff;
            /* วงกลมขาว */
            background-image: url("/assets/icons/arrow-accent.svg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 14px 14px;
            /* ปรับขนาดไอคอน */
            transition: transform .3s ease, background-color .3s ease;
        }

        /* shine effect */
        .btn-accent::before {
            content: "";
            position: absolute;
            top: -120%;
            left: -30%;
            width: 40%;
            height: 300%;
            transform: rotate(25deg);
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, .7) 50%,
                    rgba(255, 255, 255, 0) 100%);
            opacity: 0;
        }

        /* Hover: ปุ่มเป็นกรมเข้ม, ลูกศรหมุนเล็กน้อย */
        .btn-accent:hover {
            background: #0c0d2b;
            box-shadow: 0 16px 36px rgba(11, 18, 32, .35);
            color: #fff !important;
        }

        .btn-accent:hover::after {
            transform: translateY(-50%) rotate(45deg);
        }

        .btn-accent:hover::before {
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

        /* ====== Breakpoints ====== */
        @media (max-width: 500px) {
            .about-wrap {
                grid-template-columns: 1fr
            }

            .about-media {
                display: grid;
                place-items: center
            }

            .about-media .img-xl {
                max-width: min(680px, 100%);
                aspect-ratio: 16/12;
            }

            .about-media .img-sm-wrap {
                position: relative;
                inset: auto;
                width: min(65vw, 440px);
                margin-top: clamp(-36px, -6vw, -20px);
                margin-left: clamp(10%, 12vw, 18%);
            }

            .repeat-vertical {
                right: 12px
            }
        }
    </style>

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