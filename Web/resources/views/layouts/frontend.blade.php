<!doctype html>
<html lang="th" data-bs-theme="light" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sushico</title>
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content="Sushico — Taste the Art of Sushi. ซูชิโค่ คัดวัตถุดิบสดใหม่ทุกวัน">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Kaushan+Script&family=Poppins:wght@300;400;600&family=Noto+Sans+Thai:wght@300;400;600&family=Shippori+Mincho:wght@600&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        /* ---------- SUSHI BRAND TOKENS (LIGHT) ---------- */
        :root {
            --bg: #fbfdff;
            --bg-elev: #ffffff;
            --glass: rgba(13, 21, 40, .04);
            --txt: #0b1220;
            --muted: #5b647a;
            --salmon: #ff6f61;
            --wasabi: #88e083;
            --gold: #f8c94a;
            --soy: #8b5e3c;
            --radius: 16px;
            --ring: 0 0 0 3px rgba(248, 201, 74, .3), 0 6px 30px rgba(255, 176, 97, .15);

            /* === NAV (ใหม่) === */
            --nav-surface: rgba(255, 255, 255, .58);
            /* โปร่งใสตอนอยู่บน hero */
            --nav-solid: #ffffff;
            /* ทึบเมื่อเลื่อนลง */
            --nav-shadow: 0 10px 30px rgba(11, 18, 32, .08);
            --nav-shadow-strong: 0 14px 44px rgba(11, 18, 32, .14);
            --nav-radius: 22px;
        }

        :root {
            color-scheme: light;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            font-family: 'Noto Sans Thai', 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            background:
                radial-gradient(1200px 800px at -5% -20%, #fff7ef 0%, transparent 60%),
                radial-gradient(1100px 900px at 110% 120%, #eff7ff 0%, transparent 60%),
                linear-gradient(180deg, #ffffff, #f6f9ff 35%, #fbfdff 100%);
            color: var(--txt);
            scroll-behavior: smooth;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            z-index: 1;
        }

        /* ลายคลื่นญี่ปุ่น (Seigaiha) */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'><defs><pattern id='w' width='24' height='24' patternUnits='userSpaceOnUse'><path d='M0 24 Q12 0 24 24' fill='none' stroke='rgba(11,18,32,0.06)' stroke-width='1.2'/></pattern></defs><rect width='100%' height='100%' fill='url(%23w)'/></svg>");
            mask-image: radial-gradient(85% 65% at 50% 40%, #000 55%, transparent 100%);
            opacity: .7;
        }

        /* ฟิล์มเกรน */
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='2' stitchTiles='stitch'/><feColorMatrix type='saturate' values='0'/><feComponentTransfer><feFuncA type='table' tableValues='0 0 0 .02 .04 .05 .06 .07 .08 .09 .10 .12'/></feComponentTransfer></filter><rect width='100%' height='100%' filter='url(%23n)'/></svg>");
            opacity: .16;
            mix-blend-mode: multiply;
        }

        /* ---------- FLOATING NAVBAR (ใหม่) ---------- */
        /* ห่อ navbar ให้ลอยกลางหน้าจอ */
        .floating-header {
            position: fixed;
            inset-inline: 0;
            top: 18px;
            /* ระยะลอยจากขอบบน */
            z-index: 1040;
            /* ให้สูงกว่าพื้นหลัง */
            pointer-events: none;
            /* คลิกได้เฉพาะใน .nav-shell */
            transition: top .3s ease;
        }

        .floating-header.is-scrolled {
            top: 0;
        }

        /* Dock ชิดบนเมื่อเลื่อน */

        .nav-shell {
            width: min(1240px, calc(100% - 32px));
            margin-inline: auto;
            background: var(--nav-surface);
            backdrop-filter: saturate(160%) blur(16px);
            -webkit-backdrop-filter: saturate(160%) blur(16px);
            border-radius: var(--nav-radius);
            box-shadow: var(--nav-shadow);
            border: 1px solid rgba(11, 18, 32, .08);
            pointer-events: auto;
            transition: background .25s ease, box-shadow .25s ease, border-radius .25s ease, padding .25s ease, transform .25s ease;
            padding: .35rem .6rem;
        }

        .floating-header.is-scrolled .nav-shell {
            background: var(--nav-solid);
            box-shadow: var(--nav-shadow-strong);
            border-radius: 16px;
            padding: .15rem .4rem;
        }

        /* Bootstrap navbar reset ให้เข้ากับกลาส */
        .navbar {
            padding: .35rem .6rem;
        }

        .navbar-brand {
            font-family: 'Kaushan Script', cursive;
            font-size: 2rem;
            letter-spacing: .5px;
            background: linear-gradient(110deg, var(--salmon), var(--gold), var(--wasabi));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            filter: drop-shadow(0 2px 10px rgba(255, 176, 97, .25));
            background-size: 200% 100%;
            animation: brandShimmer 8s linear infinite;
            margin-right: .25rem;
        }

        .navbar-nav .nav-link {
            color: var(--txt) !important;
            font-weight: 600;
            opacity: .88;
            position: relative;
            transition: transform .2s ease, opacity .2s ease;
        }

        .navbar-nav .nav-link:hover {
            opacity: 1;
            transform: translateY(-1px);
        }

        .navbar-nav .nav-link::after {
            content: "";
            position: absolute;
            left: 10px;
            right: 10px;
            bottom: -8px;
            height: 2px;
            background: linear-gradient(90deg, var(--salmon), var(--gold) 40%, var(--wasabi));
            border-radius: 2px;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .25s ease;
            box-shadow: 0 0 10px rgba(255, 176, 97, .6);
        }

        .navbar-nav .nav-link:hover::after {
            transform: scaleX(1);
        }

        /* ใช้ navbar-light เพื่อให้ toggler icon มองเห็นได้ */
        .navbar-light .navbar-toggler {
            border: 0;
            outline: 0;
        }

        .navbar-light .navbar-toggler-icon {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, .15));
        }

        @keyframes brandShimmer {
            0% {
                background-position: 0% 50%
            }

            100% {
                background-position: 200% 50%
            }
        }

        /* ---------- HERO BANNER (LIGHT) ---------- */
        .banner {
            position: relative;
            height: 66vh;
            min-height: 460px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--txt);
            overflow: hidden;
            border-bottom: 1px solid rgba(11, 18, 32, .06);
            isolation: isolate;
            background: radial-gradient(60% 55% at 50% 35%, #ffffff 0%, #fdf7f1 45%, transparent 70%);
            /* เผื่อพื้นที่ให้เฮดเดอร์ลอย ไม่ต้องดัน layout */
            padding-top: 48px;
        }

        .banner::before {
            content: "";
            position: absolute;
            inset: -15%;
            background:
                radial-gradient(40% 60% at 20% 30%, rgba(255, 111, 97, .20), transparent 60%),
                radial-gradient(35% 55% at 80% 20%, rgba(248, 201, 74, .18), transparent 60%),
                radial-gradient(50% 70% at 60% 80%, rgba(136, 224, 131, .18), transparent 60%),
                linear-gradient(180deg, transparent, transparent);
            animation: float-bg 16s ease-in-out infinite alternate;
            filter: saturate(115%);
            z-index: -2;
        }

        @keyframes float-bg {
            0% {
                transform: translate3d(0, 0, 0) scale(1.02)
            }

            100% {
                transform: translate3d(0, -2%, 0) scale(1.05)
            }
        }

        .banner::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(60% 50% at 50% 40%, rgba(255, 255, 255, .65), transparent 65%);
            z-index: -1;
        }

        .banner h1 {
            font-family: 'Shippori Mincho', serif;
            font-size: clamp(2.4rem, 4.2vw + 1rem, 4.2rem);
            line-height: 1.08;
            letter-spacing: .5px;
            text-shadow: 0 1px 0 #fff, 0 14px 30px rgba(255, 176, 97, .25);
        }

        .banner p.lead {
            color: var(--muted);
            font-weight: 500;
        }

        .hero-cta {
            margin-top: 18px;
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* ---------- BUTTONS (Shine) ---------- */
        .btn-salmon {
            position: relative;
            isolation: isolate;
            padding: .8rem 1.2rem;
            border: none;
            border-radius: 50px;
            font-weight: 800;
            letter-spacing: .3px;
            color: #0b0f19;
            background: linear-gradient(135deg, var(--salmon), var(--gold) 55%, var(--wasabi));
            box-shadow: 0 10px 22px rgba(255, 176, 97, .35), 0 1px 0 rgba(255, 255, 255, .8) inset;
            transition: transform .15s ease, filter .2s ease, box-shadow .2s ease;
            overflow: hidden;
        }

        .btn-salmon:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
            box-shadow: 0 16px 36px rgba(255, 176, 97, .40);
        }

        .btn-salmon::before {
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

        .btn-salmon:hover::before {
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

        .btn-ghost {
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

        .btn-ghost:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 30px rgba(11, 18, 32, .08);
        }

        /* -------- Reserve Table Button -------- */
        .reserve-table-btn {
            display: inline-block;
            position: relative;
            padding: 0.9rem 3rem 0.9rem 2rem;
            border: none;
            font-weight: 800;
            text-decoration: none;
            border-radius: 50px;
            background: linear-gradient(135deg, var(--salmon), var(--gold) 55%, var(--wasabi));
            overflow: hidden;
            box-shadow: 0 10px 22px rgba(255, 176, 97, .35), 0 1px 0 rgba(255, 255, 255, .8) inset;
            transition: transform .15s ease, filter .2s ease, box-shadow .2s ease, background .3s ease;
        }

        /* วงกลม + icon */
        .reserve-table-btn::after {
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
        .reserve-table-btn::before {
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
        .reserve-table-btn:hover {
            background: #0c0d2b;
            box-shadow: 0 16px 36px rgba(11, 18, 32, .35);
            color: #fff !important;
        }

        .reserve-table-btn:hover::after {
            transform: translateY(-50%) rotate(45deg);
        }

        .reserve-table-btn:hover::before {
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



        /* ---------- SPOTLIGHT / MENU CARD / FOOTER / UTIL (เดิม) ---------- */
        .spotlight {
            position: relative;
            padding: 72px 0 18px;
            overflow: hidden;
        }

        .spotlight .orb {
            position: absolute;
            width: 780px;
            height: 780px;
            border-radius: 50%;
            top: -220px;
            right: -220px;
            background: radial-gradient(circle at 30% 30%, rgba(248, 201, 74, .45), rgba(255, 111, 97, .2) 35%, rgba(136, 224, 131, .18) 60%, transparent 70%);
            filter: blur(18px) saturate(120%);
            animation: orbspin 28s linear infinite;
            z-index: 0;
        }

        @keyframes orbspin {
            to {
                transform: rotate(360deg);
            }
        }

        .spotlight .card-omakase {
            position: relative;
            z-index: 1;
            background: linear-gradient(180deg, rgba(255, 255, 255, .95), rgba(255, 255, 255, .86));
            border: 1px solid rgba(11, 18, 32, .08);
            border-radius: 20px;
            box-shadow: 0 30px 70px rgba(11, 18, 32, .08), inset 0 1px 0 rgba(255, 255, 255, .7);
            overflow: hidden;
        }

        .spotlight .card-omakase .shine {
            position: absolute;
            inset: 0;
            background: radial-gradient(600px 240px at 0% 0%, rgba(255, 255, 255, .6), transparent 60%);
            opacity: .35;
            pointer-events: none;
        }

        .feature-pill {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .55rem .8rem;
            border-radius: 999px;
            font-weight: 800;
            background: #fff7ef;
            border: 1px solid rgba(11, 18, 32, .08);
        }

        .menu-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, .97), rgba(255, 255, 255, .9));
            border: 1px solid rgba(11, 18, 32, .08);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(11, 18, 32, .08), inset 0 1px 0 rgba(255, 255, 255, .8);
            backdrop-filter: blur(10px) saturate(120%);
            -webkit-backdrop-filter: blur(10px) saturate(120%);
            transition: transform .25s ease, box-shadow .25s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 30px 80px rgba(11, 18, 32, .12);
        }

        .menu-thumb {
            position: relative;
            aspect-ratio: 16/11;
            overflow: hidden;
            background: #f1f5ff;
        }

        .menu-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: translate3d(0, 0, 0) scale(1.04);
            transition: transform .4s ease;
            will-change: transform;
        }

        .menu-card:hover .menu-thumb img {
            transform: scale(1.08);
        }

        .menu-card.parallax:hover .menu-thumb img {
            transition: transform .05s linear;
        }

        .menu-badges {
            position: absolute;
            top: 10px;
            left: 10px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            z-index: 2;
        }

        .badge-fresh,
        .badge-chef,
        .badge-spicy {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: .5rem .7rem;
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .2px;
            border-radius: 999px;
            line-height: 1;
            white-space: nowrap;
            cursor: default;
            color: #fff;
        }

        .badge-fresh {
            background: linear-gradient(135deg, #8ee28a, #5ed955);
            box-shadow: 0 0 6px rgba(136, 224, 131, .6), 0 0 16px rgba(136, 224, 131, .45);
        }

        .badge-chef {
            background: linear-gradient(135deg, #ffd86b, #ffb65a);
            box-shadow: 0 0 6px rgba(248, 201, 74, .6), 0 0 16px rgba(248, 201, 74, .45);
        }

        .badge-spicy {
            background: linear-gradient(135deg, #ff6f61, #e63946);
            box-shadow: 0 0 6px rgba(255, 111, 97, .6), 0 0 16px rgba(255, 111, 97, .45);
        }

        .menu-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .menu-title {
            color: var(--txt);
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .menu-desc {
            color: var(--muted);
            font-size: .96rem;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 4.5em;
        }

        .price {
            font-weight: 900;
            font-size: 1.12rem;
            letter-spacing: .3px;
            color: var(--soy);
            background: linear-gradient(90deg, #b27a4d, #e7b469 50%, #b27a4d);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 0 #0000;
        }

        .divider-chop {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(11, 18, 32, .12) 20%, rgba(11, 18, 32, .12) 80%, transparent);
            margin: .6rem 0 1rem;
        }

        footer {
            background: linear-gradient(180deg, rgba(255, 255, 255, .92), rgba(255, 255, 255, .7));
            color: var(--txt);
            padding: 18px;
            text-align: center;
            border-top: 1px solid rgba(11, 18, 32, .08);
            box-shadow: 0 -12px 30px rgba(11, 18, 32, .06);
        }

        .container-xxl {
            max-width: 1280px;
        }

        .text-salmon {
            color: var(--salmon) !important;
        }

        .text-wasabi {
            color: var(--wasabi) !important;
        }

        .text-gold {
            color: var(--gold) !important;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(11, 18, 32, .25) transparent;
        }

        ::-webkit-scrollbar {
            height: 10px;
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--salmon), var(--gold));
            border-radius: 999px;
        }

        .visually-hidden {
            position: absolute !important;
            height: 1px;
            width: 1px;
            overflow: hidden;
            clip: rect(1px, 1px, 1px, 1px);
            white-space: nowrap;
        }

        /* Reduce motion */
        @media (prefers-reduced-motion: reduce) {

            .banner::before,
            .navbar-brand {
                animation: none !important;
            }

            .menu-card,
            .nav-link {
                transition: none !important;
            }
        }

        /* center menu inside the container on lg+ */
        @media (min-width: 992px) {
            .navbar .container-xxl {
                position: relative;
            }

            /* anchor */
            .nav-center-abs {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                margin: 0;
                gap: 1.25rem;
                /* spacing between items */
            }
        }

        /* fallback for mobile (collapse) */
        @media (max-width: 991px) {
            .nav-center-abs {
                position: static;
                transform: none;
                padding-left: 50px;
            }
        }

        .input-group,
        .input-group *:focus,
        .input-group *:focus-visible,
        .input-group *:focus-within {
            box-shadow: none !important;
            outline: none !important;
        }

        /* Hero Search Box */
        .search-hero {
            display: flex;
            border-radius: var(--radius);
            border: 1px solid rgba(11, 18, 32, .12);
            overflow: hidden;
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(10px) saturate(120%);
            -webkit-backdrop-filter: blur(10px) saturate(120%);
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .search-hero:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(11, 18, 32, .08);
        }

        .search-input {
            flex: 1;
            border: none !important;
            outline: none !important;
            padding: 1rem 1.2rem;
            font-size: 1.05rem;
            background: transparent;
        }

        .search-input:focus {
            box-shadow: none !important;
        }

        .search-input::placeholder {
            color: var(--muted);
            opacity: .85;
        }

        .search-btn {
            border-radius: 0 !important;
            padding: 0 1.4rem;
            font-weight: 800;
            color: #0b0f19;
            background: linear-gradient(135deg, var(--salmon), var(--gold) 55%);
            box-shadow: none !important;
            transition: none !important;
        }

        .search-btn:hover,
        .search-btn:focus,
        .search-btn:active {
            background: linear-gradient(135deg, var(--salmon), var(--gold) 55%) !important;
            color: #0b0f19 !important;
            box-shadow: none !important;
            transform: none !important;
        }
    </style>

    @yield('css_before')
</head>

<body>
    <!-- ===== Floating Navbar (ใหม่) ===== -->
    <header class="floating-header" id="floatingHeader">
        <div class="nav-shell">
            <nav class="navbar navbar-expand-lg navbar-light" aria-label="Floating navbar">
                <div class="container-xxl">

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent" data-aos="fade-left">
                        <a class="navbar-brand" href="/">Sushico</a>
                        <ul class="navbar-nav nav-center-abs mb-2 mb-lg-0 text-center">
                            <li class="nav-item"><a class="nav-link" href="/">หน้าแรก</a></li>
                            <li class="nav-item"><a class="nav-link" href="/about-us">เกี่ยวกับเรา</a></li>
                            <li class="nav-item"><a class="nav-link" href="/menus">เมนู</a></li>
                            <li class="nav-item"><a class="nav-link" href="/contact-us">ติดต่อเรา</a></li>
                            {{-- แสดง เฉพาะ Admin/Staff --}}
                            @if (Auth::guard('user')->check() && in_array(Auth::guard('user')->user()->role, ['ADMIN', 'STAFF']))
                                <li class="nav-item"><a class="nav-link" href="/dashboard">จัดการหลังบ้าน</a></li>
                            @endif
                        </ul>
                        <div class="ms-auto d-flex align-items-center gap-2">
                            {{-- ถ้า login แล้ว → โชว์ Logout / ถ้ายังไม่ login → โชว์ Login --}}
                            @if (Auth::guard('user')->check())
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost">ออกจากระบบ</button>
                                </form>
                            @else
                                <a href="/login" class="btn btn-ghost">เข้าสู่ระบบ</a>
                            @endif
                            <a href="/reservation" class="btn reserve-table-btn">จองโต๊ะ</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- ===== /Floating Navbar ===== -->

    <main>
        <!-- Banner (navbar จะลอยทับส่วนนี้) -->
        <section class="banner">
            <div class="container" data-aos="zoom-in" data-aos-delay="80">
                @php
                    use Illuminate\Support\Facades\Route;

                    $defaultTitle = 'Taste the Art of Sushi 🍣';
                    $defaultLead = 'วัตถุดิบสดใหม่ คัดพิเศษทุกวัน — Nigiri, Sashimi & Signature Rolls';

                    $routeTitles = [
                        'home.index' => 'Taste the Art of Sushi 🍣',
                        'home.about' => 'About Us',
                        'home.contact' => 'Contact Us',
                        'menu.index' => 'Our Menu',
                        'reservation.index' => 'Reservation',
                    ];

                    $routeLeads = [
                        'home.index' => $defaultLead,
                        'home.about' => 'จากตลาดปลา ถึงจานตรงหน้าคุณ — เรื่องเล่าจากครัวของเรา',
                        'home.contact' => 'เปิดทุกวัน • โทร 02-xxx-xxxx • Line @sushico',
                        'menu.index' => 'คัดวัตถุดิบสดใหม่ทุกวัน — Nigiri, Sashimi & Signature Rolls',
                        'reservation.index' => 'สำรองที่นั่งล่วงหน้า เพื่อช่วงเวลาที่ลงตัว',
                    ];

                    $current = Route::currentRouteName();

                    if (!$current) {
                        $path = request()->path();
                        $pathMap = [
                            '' => 'home.index',
                            'about-us' => 'home.about',
                            'contact-us' => 'home.contact',
                            'menus' => 'menu.index',
                            'menus/search' => 'menu.search',
                            'reservation' => 'reservation.index',
                        ];
                        $current = $pathMap[$path] ?? null;
                    }

                    $bannerTitle = $bannerTitle ?? ($current && isset($routeTitles[$current]) ? $routeTitles[$current] : $defaultTitle);
                    $bannerLead = $bannerLead ?? ($current && isset($routeLeads[$current]) ? $routeLeads[$current] : $defaultLead);
                @endphp

                <h1>{{ $bannerTitle }}</h1>
                <p class="lead mt-2" data-aos="fade-up" data-aos-delay="200">{{ $bannerLead }}</p>

                <div class="hero-cta" data-aos="fade-up" data-aos-delay="320">
                    <a href="/reservation" class="btn btn-salmon">จองโต๊ะ</a>
                    <a href="/about-us" class="btn btn-ghost">เรื่องราวของเรา</a>
                </div>

                {{-- Hero Search (โชว์เฉพาะหน้าเมนู) --}}
                @if (request()->is('menus*'))
                    <form action="{{ route('menu.search') }}" method="GET" class="mt-4" role="search"
                        aria-label="ค้นหาเมนู">
                        <div class="container-xxl">
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-8 col-lg-6">
                                    <div class="search-hero shadow-none">
                                        <input type="text" name="keyword" class="form-control search-input"
                                            placeholder="ค้นหาเช่น ซูชิแซลมอน อูนางิ เผ็ด …"
                                            value="{{ request('keyword') }}">
                                        <button class="btn btn-salmon search-btn" type="submit">ค้นหา</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </section>

        @yield('navbar')

        <!-- Content -->
        <div class="container-xxl mt-4 mt-md-5">
            <div class="row g-4" data-aos="fade-up">
                @yield('body')
            </div>
        </div>
    </main>

    <!-- Location / Hours -->
    @yield('footer')

    <!-- Footer -->
    <footer class="mt-5">
        <div>Sushico © 2025 — Crafted Daily • Tokyo-inspired • Bangkok</div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 900, once: true, easing: 'ease-out-cubic' });
        window.addEventListener('load', () => AOS.refresh());

        // ===== Toggle floating/docked state on scroll =====
        (function () {
            const header = document.getElementById('floatingHeader');
            const onScroll = () => {
                if (window.scrollY > 10) header.classList.add('is-scrolled');
                else header.classList.remove('is-scrolled');
            };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        })();

        // Parallax hover for menu cards (only pointer: fine)
        (function () {
            if (!window.matchMedia || !window.matchMedia('(pointer: fine)').matches) return;
            const cards = document.querySelectorAll('.menu-card.parallax .menu-thumb img, .menu-card .menu-thumb img');
            const clamp = (n, min, max) => Math.min(Math.max(n, min), max);
            cards.forEach(img => {
                const parent = img.closest('.menu-card');
                parent && parent.addEventListener('mousemove', (e) => {
                    const rect = parent.getBoundingClientRect();
                    const x = (e.clientX - rect.left) / rect.width;
                    const y = (e.clientY - rect.top) / rect.height;
                    const tx = clamp((x - .5) * 10, -6, 6);
                    const ty = clamp((y - .5) * 10, -6, 6);
                    img.style.transform = `translate3d(${tx}px, ${ty}px, 0) scale(1.08)`;
                });
                parent && parent.addEventListener('mouseleave', () => {
                    img.style.transform = 'translate3d(0,0,0) scale(1.04)';
                });
            });
        })();
    </script>

    @yield('js_before')
</body>

</html>