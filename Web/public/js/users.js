/* global bootstrap, Swal */
(() => {
    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

    // ---------- Utilities ----------
    const thPhoneMask = (v) => {
        v = String(v || "")
            .replace(/\D/g, "")
            .slice(0, 10);
        if (v.length <= 3) return v;
        if (v.length <= 6) return `${v.slice(0, 3)}-${v.slice(3)}`;
        return `${v.slice(0, 3)}-${v.slice(3, 6)}-${v.slice(6)}`;
    };

    const scorePass = (s) => {
        let sc = 0;
        if (!s) return 0;
        if (s.length >= 8) sc++;
        if (/[a-z]/.test(s) && /[A-Z]/.test(s)) sc++;
        if (/\d/.test(s)) sc++;
        if (/[^\w\s]/.test(s)) sc++;
        if (s.length >= 12) sc++;
        return Math.min(sc, 5);
    };

    // ---------- Widgets ----------
    function initTooltips() {
        if (typeof bootstrap === "undefined" || !bootstrap.Tooltip) return;
        $$('[data-bs-toggle="tooltip"]').forEach(
            (el) => new bootstrap.Tooltip(el)
        );
    }

    // Make deleteConfirm usable from inline onclick
    window.deleteConfirm = function (id, opts = {}) {
        if (typeof Swal === "undefined") {
            const f = document.getElementById(`delete-form-${id}`);
            if (f) f.submit();
            return;
        }
        const {
            title = "คุณแน่ใจหรือไม่?",
            text = "หากลบแล้วจะไม่สามารถกู้คืนได้!",
            confirmText = "ใช่, ลบเลย!",
            cancelText = "ยกเลิก",
            confirmColor = "#d33",
            cancelColor = "#3085d6",
        } = opts;

        Swal.fire({
            title,
            text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: cancelColor,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
        }).then((res) => {
            if (res.isConfirmed) {
                const f = document.getElementById(`delete-form-${id}`);
                if (f) f.submit();
            }
        });
    };

    function initRoleSegmented() {
        const grp = $("#roleGroup");
        if (!grp) return;
        grp.querySelectorAll('input[type="radio"]').forEach((r) => {
            r.addEventListener("change", () => {
                grp.querySelectorAll(".role-chip").forEach((c) =>
                    c.classList.remove("active")
                );
                r.closest(".role-chip")?.classList.add("active");
            });
        });
    }

    function initEyes() {
        $$(".eye").forEach((btn) => {
            const target = btn.dataset.target
                ? $(btn.dataset.target)
                : btn.closest(".floating-field")?.querySelector("input");
            if (!target) return;
            btn.addEventListener("click", () => {
                const icon = btn.querySelector("i");
                const toText = target.type === "password";
                target.type = toText ? "text" : "password";
                if (icon)
                    icon.classList.toggle("bi-eye-slash", toText),
                        icon.classList.toggle("bi-eye", !toText);
                target.focus();
            });
        });
    }

    function initPasswordMeter() {
        const pw = $("#password"),
            meter = $("#pwMeter"),
            hint = $("#pwHint");
        if (!pw || !meter) return;
        pw.addEventListener("input", () => {
            const sc = scorePass(pw.value);
            meter.dataset.score = sc;
            if (!hint) return;
            if (sc <= 1)
                hint.textContent =
                    "รหัสผ่านอ่อนมาก — ควรเพิ่มความยาวและผสมตัวอักษร/ตัวเลข/สัญลักษณ์";
            else if (sc === 2)
                hint.textContent = "ยังอ่อน — ลองเพิ่มตัวพิมพ์ใหญ่/เล็กผสม";
            else if (sc === 3)
                hint.textContent =
                    "พอใช้ — เพิ่มสัญลักษณ์หรือความยาวให้ปลอดภัยขึ้น";
            else hint.textContent = "ดีมาก — ความปลอดภัยเหมาะสม";
        });
    }

    function initPhoneMask() {
        const phone = $("#phone");
        if (!phone) return;
        phone.addEventListener("input", () => {
            phone.value = thPhoneMask(phone.value);
        });
        const form = phone.closest("form");
        if (form)
            form.addEventListener("submit", () => {
                phone.value = phone.value.replace(/\D/g, "");
            });
    }

    function initSubmitOnce() {
        $$("form").forEach((form) => {
            const btn = form.querySelector('button[type="submit"]');
            if (!btn) return;
            form.addEventListener("submit", () => {
                if (btn.disabled) return;
                btn.disabled = true;
                const label = btn.textContent.trim() || "Processing...";
                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> ${label}`;
            });
        });
    }

    function initClientFiltersUsers() {
        const q = $("#q"),
            role = $("#role"),
            status = $("#status"),
            btnSearch = $("#btnSearch"),
            btnClear = $("#btnClear");
        if (!q || !role || !status || !btnSearch || !btnClear) return;

        const rows = $$(".js-user-row");
        const cards = $$(".js-user-card");
        const noResultTable = $("#noResultTable");
        const noResultCards = $("#noResultCards");
        const clientInfo = $("#clientInfo");

        const match = (item, kw, r, s) => {
            const seat = item.dataset.role,
                st = item.dataset.status,
                blob = item.dataset.search || "";
            if (kw && !blob.includes(kw)) return false;
            if (r && r !== seat) return false;
            if (s && s !== st) return false;
            return true;
        };

        const apply = () => {
            const kw = (q.value || "").trim().toLowerCase();
            const r = role.value.toLowerCase();
            const s = status.value.toLowerCase();
            let shownTable = 0,
                shownCards = 0;

            rows.forEach((tr) => {
                const ok = match(tr, kw, r, s);
                tr.style.display = ok ? "" : "none";
                if (ok) shownTable++;
            });
            cards.forEach((card) => {
                const ok = match(card, kw, r, s);
                card.style.display = ok ? "" : "none";
                if (ok) shownCards++;
            });

            if (noResultTable)
                noResultTable.classList.toggle("d-none", shownTable !== 0);
            if (noResultCards)
                noResultCards.classList.toggle("d-none", shownCards !== 0);

            const has = !!(kw || r || s);
            if (clientInfo) {
                clientInfo.classList.toggle("d-none", !has);
                if (has) {
                    const parts = [];
                    if (kw) parts.push(`q: "${kw}"`);
                    if (r) parts.push(`role: ${r}`);
                    if (s) parts.push(`status: ${s}`);
                    clientInfo.textContent = `${
                        shownTable || shownCards
                    } matched (${parts.join(", ")})`;
                }
            }
        };

        let timer = null;
        q.addEventListener("input", () => {
            clearTimeout(timer);
            timer = setTimeout(apply, 350);
        });
        [role, status].forEach((el) => el.addEventListener("change", apply));
        btnSearch.addEventListener("click", apply);
        q.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                apply();
            }
        });
        btnClear.addEventListener("click", () => {
            q.value = "";
            role.value = "";
            status.value = "";
            apply();
        });
    }

    // ---------- boot ----------
    document.addEventListener("DOMContentLoaded", () => {
        initTooltips();
        initRoleSegmented();
        initEyes();
        initPasswordMeter();
        initPhoneMask();
        initSubmitOnce();
        initClientFiltersUsers();
    });
})();
