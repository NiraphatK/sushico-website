<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — ไม่พบหน้าที่ต้องการ</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --forest: #193533;
            /* เขียวเข้ม */
            --matcha: #3a6a5a;
            /* เขียวมัทฉะ */
            --leaf: #1fb773;
            /* accent */
            --ink: #0f172a;
            /* ตัวอักษร */
            --muted: #475569;
            /* คำอธิบาย */
            --card: #ffffffee;
            /* การ์ดโปร่งใส */
        }

        /* Base */
        html,
        body {
            height: 100%
        }

        body {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: "Sarabun", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(900px 600px at 15% 10%, #e9f6ef 0%, transparent 65%),
                radial-gradient(900px 600px at 85% 90%, #eef9f3 0%, transparent 65%),
                linear-gradient(180deg, #f7fbf8 0%, #f1f7f3 40%, #edf4f0 100%);
            overflow: hidden;
        }

        /* single soft orb */
        .orb {
            position: fixed;
            inset: auto -140px -160px auto;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle at 65% 65%, var(--matcha), transparent 60%);
            filter: blur(120px);
            opacity: .22;
            z-index: 0;
            animation: float 12s ease-in-out infinite alternate;
        }

        @keyframes float {
            from {
                transform: translateY(0)
            }

            to {
                transform: translateY(-44px)
            }
        }

        /* Card */
        .card {
            position: relative;
            z-index: 1;
            width: min(760px, 92vw);
            padding: 56px 40px;
            text-align: center;
            background: var(--card);
            backdrop-filter: blur(14px) saturate(140%);
            border: 1px solid rgba(25, 53, 51, .08);
            border-radius: 24px;
            box-shadow: 0 18px 48px rgba(2, 6, 23, .10);
            animation: rise .6s ease both;
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(24px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* Typography */
        .code {
            margin: 0 0 .2em;
            line-height: 1;
            letter-spacing: .02em;
            font-weight: 700;
            font-size: clamp(64px, 12vw, 120px);
            background: linear-gradient(120deg, var(--forest), var(--matcha) 60%, var(--leaf));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .title {
            margin: .1em 0 .3em;
            font-weight: 700;
            font-size: clamp(22px, 3.2vw, 32px);
            color: var(--forest);
        }

        .subtitle {
            margin: 0 0 28px;
            color: var(--muted);
            font-size: clamp(15px, 2vw, 18px);
            line-height: 1.7;
        }

        /* Button */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            text-decoration: none;
            padding: .9rem 2rem;
            border-radius: 999px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--forest), var(--matcha));
            box-shadow: 0 10px 26px rgba(25, 53, 51, .28);
            transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 34px rgba(25, 53, 51, .36);
            filter: brightness(1.03)
        }

        .btn i {
            font-size: 1.1em
        }

        /* Reduce motion */
        @media (prefers-reduced-motion: reduce) {
            .orb {
                animation: none
            }

            .card {
                animation: none
            }

            .btn {
                transition: none
            }
        }
    </style>
</head>

<body>
    <div class="orb" aria-hidden="true"></div>

    <main class="card" role="main" aria-labelledby="code">
        <h1 class="code" id="code">404</h1>
        <h2 class="title">ไม่พบหน้าที่คุณต้องการ</h2>
        <p class="subtitle">กรุณากลับไปยังหน้าหลักเพื่อเริ่มต้นใหม่</p>
        <a href="/" class="btn"><i class="bi bi-house-door"></i> กลับหน้าหลัก</a>
    </main>
</body>

</html>
