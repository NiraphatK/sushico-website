@extends('frontend')

@section('css_before')
    @section('navbar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <style>
            /* ---------- SweetAlert – scoped styles (ไม่แตะ navbar/site) ---------- */
            .swal2-popup.menu-detail {
                width: min(720px, 92vw) !important;
                padding: 0 !important;
                border-radius: 20px !important;
                overflow: hidden !important;
                box-shadow: 0 12px 40px rgba(0, 0, 0, .18);
            }

            .swal2-close {
                top: 10px !important;
                right: 10px !important;
                background: rgba(255, 255, 255, .85) !important;
                border-radius: 999px !important;
                width: 36px;
                height: 36px;
            }

            .menu-hero {
                position: relative;
                background:
                    radial-gradient(1200px 300px at 50% -40%, rgba(255, 255, 255, .45), transparent),
                    linear-gradient(180deg, rgba(255, 255, 255, .5), rgba(255, 255, 255, 0));
            }

            .menu-hero img {
                width: 100%;
                height: auto;
                display: block;
                object-fit: contain;
                max-height: clamp(220px, 36vw, 360px);
            }

            .price-chip {
                position: absolute;
                bottom: 14px;
                left: 14px;
                backdrop-filter: blur(6px);
                background: rgba(255, 99, 99, .88);
                color: #fff;
                padding: 8px 14px;
                border-radius: 999px;
                font-weight: 700;
                box-shadow: 0 6px 16px rgba(255, 99, 99, .35);
            }

            .menu-body-wrap {
                padding: 18px 20px 20px;
            }

            .menu-title-xxl {
                font-size: clamp(1.2rem, 2.2vw, 1.6rem);
                font-weight: 800;
                line-height: 1.25;
                margin: 2px 0 6px;
                letter-spacing: .2px;
            }

            .menu-desc {
                color: #4b5563;
                margin-bottom: 10px;
            }

            .menu-section-title {
                font-weight: 700;
                margin: 14px 0 8px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .menu-section-title::before {
                content: "";
                width: 6px;
                height: 6px;
                border-radius: 999px;
                background: #fb7185;
                box-shadow: 0 0 0 3px rgba(251, 113, 133, .25);
            }

            .menu-detail-html {
                text-align: left;
            }

            .menu-actions {
                display: grid;
                grid-template-columns: 1fr;
                gap: 10px;
                margin-top: 14px;
            }

            .btn-outline-dark.round {
                border-radius: 12px;
                font-weight: 600;
                border-width: 2px;
                padding: 10px 14px;
            }
        </style>
    @endsection
@endsection

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function () {
            const thMoney = (num) => new Intl.NumberFormat('th-TH', {
                minimumFractionDigits: 2, maximumFractionDigits: 2
            }).format(Number(num || 0));

            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.menu-card[data-menu-id]').forEach(card => {
                    card.addEventListener('click', () => {
                        const id = card.dataset.menuId;
                        const name = card.dataset.name || '';
                        const desc = card.dataset.desc || '';
                        const price = Number(card.dataset.price || 0);
                        const img = card.dataset.img || '';

                        const detailEl = document.getElementById(`detail-${id}`);
                        const detailRaw = detailEl ? detailEl.innerHTML : '';

                        const html = `
                        <div class="menu-hero">
                            <img src="${img}" alt="${name}" onerror="this.style.display='none'">
                            <div class="price-chip">฿${thMoney(price)}</div>
                        </div>

                        <div class="menu-body-wrap">
                            <div class="menu-title-xxl">${name}</div>
                            ${desc ? `<div class="menu-desc">${desc}</div>` : ``}

                            ${detailRaw
                                ? `<div>
                                     <div class="menu-section-title">รายละเอียด</div>
                                     <div class="menu-detail-html">${detailRaw}</div>
                                   </div>`
                                : ``}

                            <div class="menu-actions">
                                <button type="button" class="btn btn-outline-dark round" id="btnClose-${id}">
                                    ปิด
                                </button>
                            </div>
                        </div>
                    `;

                        Swal.fire({
                            title: '',
                            html: html,
                            width: 'auto',
                            padding: 0,
                            showConfirmButton: false,
                            showCloseButton: true,
                            focusConfirm: false,
                            allowOutsideClick: true,
                            backdrop: true,
                            customClass: {
                                popup: 'menu-detail',
                                htmlContainer: 'p-0'
                            },
                            didOpen: (popup) => {
                                const btnClose = popup.querySelector(`#btnClose-${id}`);
                                if (btnClose) btnClose.addEventListener('click', () => Swal.close());
                            }
                        });
                    });
                });
            });
        })();
    </script>
@endsection

@section('body')
    @foreach ($menu as $item)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 mt-4">
            <article class="menu-card h-100" role="button" data-menu-id="{{ $item->menu_id }}" data-name="{{ e($item->name) }}"
                data-desc="{{ e($item->description) }}" data-price="{{ $item->price }}"
                data-img="{{ asset('storage/' . $item->image_path) }}">
                <div class="menu-thumb">
                    <img src="{{ asset('storage/' . $item->image_path) }}" class="card-img-top rounded-top"
                        alt="{{ $item->name }}">
                </div>
                <div class="menu-body">
                    <div class="menu-title">{{ $item->name }}</div>
                    <p class="menu-desc mt-1">{{ Str::limit($item->description, 80, '...') }}</p>
                    <div class="btn btn-salmon w-100 mt-2">฿{{ number_format($item->price, 2) }}</div>
                </div>
            </article>
        </div>

        {{-- เก็บ rich text (HTML) ของรายละเอียดไว้ใน DOM --}}
        <div id="detail-{{ $item->menu_id }}" class="d-none">{!! $item->detail !!}</div>
    @endforeach

    <div class="row mt-2 mb-2">
        <div class="col-sm-5 col-md-5"></div>
        <div class="col-sm-3 col-md-3 text-center">
            {{ $menu->links() }}
        </div>
    </div>
@endsection

@section('footer')
@endsection