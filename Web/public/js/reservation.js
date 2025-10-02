// /assets/js/reservation.js
(() => {
    "use strict";
    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    const hasSwal = () => typeof window.Swal !== "undefined";

    // --- SweetAlert: delete ---
    window.deleteConfirm = (id) => {
        if (hasSwal()) {
            Swal.fire({
                title: "แน่ใจหรือไม่?",
                text: "คุณต้องการลบรายการนี้จริง ๆ หรือไม่",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "ใช่, ลบเลย!",
                cancelButtonText: "ยกเลิก",
            }).then((res) => {
                if (res.isConfirmed) {
                    const form = document.getElementById("delete-form-" + id);
                    form && form.submit();
                }
            });
        } else if (confirm("ยืนยันการลบรายการนี้?")) {
            const form = document.getElementById("delete-form-" + id);
            form && form.submit();
        }
    };

    // --- SweetAlert: check-in ---
    window.checkinConfirm = (formEl, user, time) => {
        if (hasSwal()) {
            Swal.fire({
                title: "เช็กอินลูกค้า?",
                html: `ต้องการเช็กอิน <b>${user}</b><br>เวลา <b>${time}</b> หรือไม่`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#22c55e",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "ใช่, เช็กอิน!",
                cancelButtonText: "ยกเลิก",
            }).then((res) => {
                if (res.isConfirmed) formEl.submit();
            });
        } else if (confirm(`เช็กอิน ${user} เวลา ${time}?`)) {
            formEl.submit();
        }
    };

    // --- Bootstrap tooltips (FIXED) ---
    const initTooltips = () => {
        if (!window.bootstrap) return;
        document
            .querySelectorAll('[data-bs-toggle="tooltip"]')
            .forEach((el) => {
                try {
                    new bootstrap.Tooltip(el);
                } catch (_) {}
            });
    };

    // --- Clamp party size 1–10 ---
    const initPartySizeClamp = () => {
        const inp = $("#party_size");
        if (!inp) return;
        const clamp = () => {
            let v = parseInt(inp.value, 10);
            if (Number.isNaN(v)) return;
            if (v < 1) v = 1;
            if (v > 10) v = 10;
            inp.value = v;
        };
        inp.addEventListener("input", clamp);
    };

    // --- Snap time to step ---
    const initTimeSnap = () => {
        const time = $("#start_time");
        if (!time) return;
        const step = parseInt(time.getAttribute("step") || "0", 10); // seconds
        if (!step) return;
        const snap = (val) => {
            const [h, m] = (val || "00:00").split(":").map(Number);
            let secs = (h * 60 + m) * 60;
            const snapped = Math.round(secs / step) * step;
            const hh = Math.floor(snapped / 3600)
                .toString()
                .padStart(2, "0");
            const mm = Math.floor((snapped % 3600) / 60)
                .toString()
                .padStart(2, "0");
            return `${hh}:${mm}`;
        };
        time.addEventListener("change", () => {
            time.value = snap(time.value);
        });
    };

    // --- Client-side filters (List page) ---
    const initFilters = () => {
        const q = $("#q");
        const seat = $("#seat");
        const status = $("#status");
        const timeSel = $("#time");
        const btnSearch = $("#btnSearch");
        const btnClear = $("#btnClear");

        const rows = $$(".js-res-row"); // desktop rows
        const cards = $$(".js-res-card"); // mobile cards

        const noResultTable = $("#noResultTable");
        const noResultCards = $("#noResultCards");
        const clientInfo = $("#clientInfo");

        // ไม่มีตัวกรอง/ไม่มีรายการ ก็ไม่ต้องทำอะไร
        if (!q || (!rows.length && !cards.length)) return;

        const match = (el) => {
            const kw = (q.value || "").trim().toLowerCase();
            const fSeat = (seat?.value || "").toLowerCase();
            const fStat = (status?.value || "").toLowerCase();
            const fTime = (timeSel?.value || "").toLowerCase();
            if (kw && !(el.dataset.search || "").includes(kw)) return false;
            if (fSeat && fSeat !== (el.dataset.seat || "")) return false;
            if (fStat && fStat !== (el.dataset.status || "")) return false;
            if (fTime && fTime !== (el.dataset.time || "")) return false;
            return true;
        };

        const apply = () => {
            const kw = (q.value || "").trim().toLowerCase();
            const fSeat = (seat?.value || "").toLowerCase();
            const fStat = (status?.value || "").toLowerCase();
            const fTime = (timeSel?.value || "").toLowerCase();

            let shownTable = 0,
                shownCards = 0;

            rows.forEach((tr) => {
                const ok = match(tr);
                tr.style.display = ok ? "" : "none";
                if (ok) shownTable++;
            });

            cards.forEach((card) => {
                const ok = match(card);
                card.style.display = ok ? "" : "none";
                if (ok) shownCards++;
            });

            if (noResultTable && rows.length)
                noResultTable.classList.toggle("d-none", shownTable !== 0);
            if (noResultCards && cards.length)
                noResultCards.classList.toggle("d-none", shownCards !== 0);

            const hasFilter = !!(kw || fSeat || fStat || fTime);
            if (clientInfo) {
                clientInfo.classList.toggle("d-none", !hasFilter);
                if (hasFilter) {
                    const parts = [];
                    if (kw) parts.push(`q: "${kw}"`);
                    if (fSeat) parts.push(`seat: ${fSeat}`);
                    if (fStat) parts.push(`status: ${fStat}`);
                    if (fTime) parts.push(`time: ${fTime}`);
                    clientInfo.textContent = `${
                        shownTable || shownCards
                    } matched (${parts.join(", ")})`;
                }
            }
        };

        let timer = null;
        q.addEventListener("input", () => {
            clearTimeout(timer);
            timer = setTimeout(apply, 300);
        });
        [seat, status, timeSel].forEach(
            (el) => el && el.addEventListener("change", apply)
        );
        btnSearch && btnSearch.addEventListener("click", apply);
        q.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                apply();
            }
        });

        btnClear &&
            btnClear.addEventListener("click", () => {
                q.value = "";
                if (seat) seat.value = "";
                if (status) status.value = "";
                if (timeSel) timeSel.value = "";
                apply();
            });
    };

    document.addEventListener("DOMContentLoaded", () => {
        try {
            initTooltips();
        } catch (e) {
            console.warn("tooltips error", e);
        }
        try {
            initPartySizeClamp();
        } catch (e) {
            console.warn("party clamp error", e);
        }
        try {
            initTimeSnap();
        } catch (e) {
            console.warn("time snap error", e);
        }
        try {
            initFilters();
        } catch (e) {
            console.warn("filters error", e);
        }
    });
})();
