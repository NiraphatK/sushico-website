/* Menu detail modal (SweetAlert2) */
(() => {
    "use strict";

    const moneyTH = (num) =>
        new Intl.NumberFormat("th-TH", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(Number(num || 0));

    // decode ค่าใน data-attr ที่ผ่าน e() มา (เช่น &amp; -> &)
    const decodeHTML = (s) => {
        if (!s) return "";
        const t = document.createElement("textarea");
        t.innerHTML = s;
        return t.value;
    };

    const buildHtml = ({ id, name, desc, price, img, detailRaw }) => {
        const safeName = decodeHTML(name);
        const safeDesc = decodeHTML(desc);

        return `
      <div class="menu-hero">
        <img src="${img}" alt="${safeName}" onerror="this.style.display='none'">
        <div class="price-chip">฿${moneyTH(price)}</div>
      </div>

      <div class="menu-body-wrap">
        <div class="menu-title-xxl">${safeName}</div>
        ${safeDesc ? `<div class="menu-desc">${safeDesc}</div>` : ``}

        ${
            detailRaw
                ? `<div>
               <div class="menu-section-title">รายละเอียด</div>
               <div class="menu-detail-html">${detailRaw}</div>
             </div>`
                : ``
        }

        <div class="menu-actions">
          <button type="button" class="btn btn-outline-dark round" id="btnClose-${id}">
            ปิด
          </button>
        </div>
      </div>
    `;
    };

    // ใช้ event delegation รองรับ element ใหม่ๆ (กรณีมีการรีเรนเดอร์)
    const on = (event, selector, handler) => {
        document.addEventListener(event, (e) => {
            const el = e.target.closest(selector);
            if (el) handler(e, el);
        });
    };

    const openDetail = (card) => {
        const id = card.dataset.menuId;
        const name = card.dataset.name || "";
        const desc = card.dataset.desc || "";
        const price = Number(card.dataset.price || 0);
        const img = card.dataset.img || "";

        const detailEl = document.getElementById(`detail-${id}`);
        const detailRaw = detailEl ? detailEl.innerHTML : "";

        const html = buildHtml({ id, name, desc, price, img, detailRaw });

        if (!window.Swal) {
            // fallback ง่ายๆ ถ้าไม่มี SweetAlert
            alert(decodeHTML(name));
            return;
        }

        Swal.fire({
            title: "",
            html,
            width: "auto",
            padding: 0,
            showConfirmButton: false,
            showCloseButton: true,
            focusConfirm: false,
            allowOutsideClick: true,
            backdrop: true,
            customClass: {
                popup: "menu-detail",
                htmlContainer: "p-0",
            },
            didOpen: (popup) => {
                const btnClose = popup.querySelector(`#btnClose-${id}`);
                if (btnClose)
                    btnClose.addEventListener("click", () => Swal.close());
            },
        });
    };

    document.addEventListener("DOMContentLoaded", () => {
        // คลิกที่การ์ดเมนู
        on("click", ".menu-card[data-menu-id]", (_e, card) => openDetail(card));
    });
})();

/* ==================================
   Contact Us: form handler (SweetAlert2)
   ================================== */
(function () {
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("contactForm");
        if (!form) return;

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            if (!form.reportValidity()) return;

            const btn = form.querySelector('button[type="submit"]');
            const prevHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = "<span>Sending…</span>";

            try {
                // ถ้ามี endpoint จริง:
                // const resp = await fetch(form.action, {
                //   method: 'POST',
                //   headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '' },
                //   body: new FormData(form),
                // });
                // if (!resp.ok) throw new Error('Network error');

                // demo หน่วงเล็กน้อย
                await new Promise((r) => setTimeout(r, 400));

                if (window.Swal) {
                    Swal.fire({
                        title: "ส่งข้อความเรียบร้อย",
                        text: "ทางเราได้รับข้อความของท่านเรียบร้อยแล้ว",
                        icon: "success",
                        confirmButtonText: "OK",
                        draggable: true,
                        customClass: { confirmButton: "btn-default" },
                    });
                }
                form.reset();
            } catch (err) {
                if (window.Swal) {
                    Swal.fire({
                        title: "Oops…",
                        text:
                            err?.message ||
                            "Something went wrong. Please try again.",
                        icon: "error",
                        confirmButtonText: "Close",
                    });
                }
            } finally {
                btn.disabled = false;
                btn.innerHTML = prevHTML;
            }
        });
    });
})();
