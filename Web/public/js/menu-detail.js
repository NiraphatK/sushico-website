/* Menu detail — Clean UX (fixed: no global `card` reference) */
(() => {
    "use strict";

    const moneyTH = (num) =>
        new Intl.NumberFormat("th-TH", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(Number(num || 0));

    const decodeHTML = (s) => {
        if (!s) return "";
        const t = document.createElement("textarea");
        t.innerHTML = s;
        return t.value;
    };

    // sanitize innerHTML ของรายละเอียด
    const stripDanger = (html) => {
        if (!html) return "";
        const tmp = document.createElement("div");
        tmp.innerHTML = html;
        tmp.querySelectorAll(
            "script,style,iframe,object,embed,link,meta"
        ).forEach((n) => n.remove());
        tmp.querySelectorAll("*").forEach((el) => {
            [...el.attributes].forEach((a) => {
                if (/^on/i.test(a.name) || a.name === "srcdoc")
                    el.removeAttribute(a.name);
            });
        });
        return tmp.innerHTML;
    };

    // === HTML builder (รองรับ subtitle แบบออปชัน) ===
    const buildHtml = ({
        id,
        name,
        subtitle,
        desc,
        price,
        img,
        detailRaw,
        tagsHtml,
    }) => {
        const safeName = decodeHTML(name);
        const safeSubtitle = decodeHTML(subtitle || "");
        const safeDesc = decodeHTML(desc);
        const safeDetail = stripDanger(detailRaw || "");
        const tags = tagsHtml || "";
        const headingId = `menu-title-${id}`;

        return `
      <div class="menu-wrap" aria-labelledby="${headingId}">
        <div class="menu-hero">
          <img src="${img || ""}" alt="${safeName}" draggable="false">
          <div class="price-chip">฿${moneyTH(price)}</div>
        </div>

        <div class="menu-body-wrap">
          <div class="menu-scroll-shadow menu-shadow-top"></div>
          <div class="menu-body-scroll">
            <header>
              <h2 class="menu-title-xxl" id="${headingId}">${safeName}</h2>
              ${
                  safeSubtitle
                      ? `<div class="menu-subtitle">${safeSubtitle}</div>`
                      : ``
              }
            </header>

            ${tags ? `<div class="menu-tags">${tags}</div>` : ``}
            ${safeDesc ? `<p class="menu-desc">${safeDesc}</p>` : ``}

            ${
                safeDetail
                    ? `
              <section aria-label="รายละเอียด">
                <div class="menu-section-title">รายละเอียด</div>
                <div class="menu-detail-html">${safeDetail}</div>
              </section>`
                    : ``
            }
          </div>
          <div class="menu-scroll-shadow menu-shadow-bottom"></div>
        </div>
      </div>

      <div class="menu-float-close">
        <button type="button" id="btnClose-${id}" aria-label="ปิด">
          <i class="bi bi-x-lg" aria-hidden="true"></i> ปิด
        </button>
      </div>
    `;
    };

    // helper: event delegation
    const on = (event, selector, handler) => {
        document.addEventListener(event, (e) => {
            const el = e.target.closest(selector);
            if (el) handler(e, el);
        });
    };

    const loadImageWithFade = (imgEl) => {
        if (!imgEl) return;
        const src = imgEl.getAttribute("src");
        if (!src) {
            imgEl.style.display = "none";
            return;
        }
        const ph = new Image();
        ph.onload = () => imgEl.classList.add("loaded");
        ph.onerror = () => {
            imgEl.style.display = "none";
        };
        ph.src = src;
    };

    // เงาบอกสถานะสกอลล์
    const bindScrollShadows = (popup) => {
        const wrap = popup.querySelector(".menu-body-wrap");
        const scroller = popup.querySelector(".menu-body-scroll");
        if (!wrap || !scroller) return;
        const update = () => {
            const atTop = scroller.scrollTop <= 0;
            const atEnd =
                Math.ceil(scroller.scrollTop + scroller.clientHeight) >=
                scroller.scrollHeight - 1;
            wrap.classList.toggle("has-top", !atTop);
            wrap.classList.toggle("has-bottom", !atEnd);
        };
        scroller.addEventListener("scroll", update, { passive: true });
        new ResizeObserver(update).observe(scroller);
        update();
    };

    // === เปิดรายละเอียด (card ถูกส่งเข้ามาในสโคปนี้เท่านั้น) ===
    const openDetail = (card) => {
        const id = card.dataset.menuId;
        const name = card.dataset.name || "";
        const desc = card.dataset.desc || "";
        const price = Number(card.dataset.price || 0);
        const img = card.dataset.img || "";
        const subtitle =
            card.dataset.subtitle || card.dataset.en || card.dataset.eng || "";

        const rawTags = (card.dataset.tags || "")
            .split(",")
            .map((s) => s.trim())
            .filter(Boolean);
        const tagsHtml = rawTags
            .map((t) => `<span class="menu-tag">${decodeHTML(t)}</span>`)
            .join("");

        const detailEl = document.getElementById(`detail-${id}`);
        const detailRaw = detailEl ? detailEl.innerHTML : "";

        const html = buildHtml({
            id,
            name,
            subtitle,
            desc,
            price,
            img,
            detailRaw,
            tagsHtml,
        });

        if (!window.Swal) {
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
            allowOutsideClick: true,
            focusConfirm: false,
            backdrop: true,
            customClass: {
                container: "menu-detail-container", // จัด popup ชิดบนเฉพาะกล่องนี้
                popup: "menu-detail",
                htmlContainer: "p-0",
            },
            didOpen: (popup) => {
                loadImageWithFade(popup.querySelector(".menu-hero img"));
                bindScrollShadows(popup);
                const btnClose = popup.querySelector(`#btnClose-${id}`);
                btnClose?.addEventListener("click", () => Swal.close());
                btnClose?.focus({ preventScroll: true });
            },
        });
    };

    // กันกดรัว
    let lock = false;
    const safeOpen = (card) => {
        if (lock) return;
        lock = true;
        requestAnimationFrame(() => {
            openDetail(card);
            setTimeout(() => (lock = false), 220);
        });
    };

    document.addEventListener("DOMContentLoaded", () => {
        on("click", ".menu-card[data-menu-id]", (_e, card) => safeOpen(card));
        on("keydown", ".menu-card[data-menu-id]", (e, card) => {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                safeOpen(card);
            }
        });
    });
})();
