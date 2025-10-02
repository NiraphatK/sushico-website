(() => {
    "use strict";
    // กันโหลดซ้ำ (เช่น มีหลายเพจ partial รวมกัน)
    if (window.__authModalsInit) return;
    window.__authModalsInit = true;

    // ===== helpers =====
    const fmtPhone = (v) => {
        const d = String(v || "")
            .replace(/\D/g, "")
            .slice(0, 10);
        if (d.length <= 3) return d;
        if (d.length <= 6) return d.slice(0, 3) + "-" + d.slice(3);
        return d.slice(0, 3) + "-" + d.slice(3, 6) + "-" + d.slice(6);
    };

    const score = (p) => {
        let s = 0;
        if (!p) return s;
        const rules = [/[a-z]/, /[A-Z]/, /[0-9]/, /[^A-Za-z0-9]/];
        s += Math.min(10, p.length) * 6;
        rules.forEach((r) => r.test(p) && (s += 12));
        if (p.length >= 10) s += 10;
        return Math.min(100, s);
    };

    // ===== DOM ready =====
    document
        .querySelectorAll('input[type="password"][data-strength]')
        .forEach((inp) => {
            const evt = new Event("input", { bubbles: true });
            inp.dispatchEvent(evt);
        });

    // ===== toggle password (ทั้ง login + register) =====
    document.addEventListener("click", (e) => {
        const btn = e.target.closest("[data-toggle-password]");
        if (btn) {
            const sel = btn.getAttribute("data-toggle-password");
            const input = sel ? document.querySelector(sel) : null;
            if (!input) return;
            input.type = input.type === "password" ? "text" : "password";
            btn.querySelector(".eye-on")?.classList.toggle(
                "d-none",
                input.type === "text"
            );
            btn.querySelector(".eye-off")?.classList.toggle(
                "d-none",
                input.type !== "text"
            );
            input.focus();
            return;
        }

        // สลับโมดัล: ปิดตัวที่อยู่ แล้วเปิดเป้าหมาย
        const switcher = e.target.closest("[data-switch-modal]");
        if (switcher) {
            const targetSel = switcher.getAttribute("data-switch-modal");
            const current = switcher.closest(".modal");
            if (!current) return;
            const currentInstance =
                bootstrap.Modal.getInstance(current) ||
                bootstrap.Modal.getOrCreateInstance(current);
            current.addEventListener(
                "hidden.bs.modal",
                () => {
                    const target = document.querySelector(targetSel);
                    if (target)
                        bootstrap.Modal.getOrCreateInstance(target).show();
                },
                { once: true }
            );
            currentInstance.hide();
            return;
        }
    });

    // --- input handlers: phone auto-format + strength bar + needs/suggest ---
    const analyzePass = (s) => {
        const len = (s || "").length;
        const hasLower = /[a-z]/.test(s);
        const hasUpper = /[A-Z]/.test(s);
        const hasNumber = /\d/.test(s);
        const hasSpecial = /[^A-Za-z0-9]/.test(s);

        const min6 = len >= 6;
        const long12 = len >= 12;

        // คะแนน 0–5
        let sc = 0;
        if (min6) sc++;
        if (hasLower && hasUpper) sc++;
        if (hasNumber) sc++;
        if (hasSpecial) sc++;
        if (long12) sc++;
        sc = Math.min(sc, 5);

        // สิ่งที่ยังขาด
        const missing = [];
        if (!min6) {
            const remain = Math.max(0, 6 - len);
            missing.push(`ความยาวอย่างน้อย 6 ตัว (ขาดอีก ${remain})`);
        }
        if (!hasLower) missing.push("ตัวพิมพ์เล็ก (a–z)");
        if (!hasUpper) missing.push("ตัวพิมพ์ใหญ่ (A–Z)");
        if (!hasNumber) missing.push("ตัวเลข (0–9)");
        if (!hasSpecial) missing.push("อักขระพิเศษ (!@#$%...)");

        const tips = [];
        if (min6 && !long12)
            tips.push("แนะนำ: เพิ่มความยาวถึง 12 ตัวขึ้นไปเพื่อความปลอดภัย");

        return { score: sc, missing, tips, empty: len === 0 };
    };

    const renderNeeds = (ul, list) => {
        if (!ul) return;
        ul.innerHTML = "";
        if (!list.length) return;
        const frag = document.createDocumentFragment();
        list.forEach((txt) => {
            const li = document.createElement("li");
            const dot = document.createElement("span");
            dot.className = "dot";
            li.appendChild(dot);
            li.appendChild(document.createTextNode(" " + txt));
            frag.appendChild(li);
        });
        ul.appendChild(frag);
    };

    document.addEventListener("input", (e) => {
        const el = e.target;

        // phone: 3-3-4
        if (el.matches("input[data-autofmt-phone]")) {
            el.value = fmtPhone(el.value);
            return;
        }

        // register: password strength + needs + suggest
        if (el.matches('input[type="password"][data-strength]')) {
            const barSel = el.getAttribute("data-strength");
            const bar = barSel ? document.querySelector(barSel) : null;

            const needsSel = el.getAttribute("data-needs");
            const needsEl = needsSel ? document.querySelector(needsSel) : null;

            const suggestSel = el.getAttribute("data-suggest");
            const suggestEl = suggestSel
                ? document.querySelector(suggestSel)
                : null;

            const { score, missing, tips, empty } = analyzePass(el.value);

            // แถบความแข็งแรง (0–100%)
            if (bar) {
                const pct = Math.round((score / 5) * 100);
                bar.style.width = pct + "%";
                bar.className = "progress-bar";
                if (pct < 34) bar.classList.add("bg-danger");
                else if (pct < 67) bar.classList.add("bg-warning");
                else bar.classList.add("bg-success");
            }

            // รายการสิ่งที่ยังขาด
            renderNeeds(needsEl, missing);

            // ข้อเสนอแนะเสริม
            if (suggestEl)
                suggestEl.textContent = empty ? "" : tips.join(" • ");

            return;
        }
    });

    // ===== CapsLock hint (แก้ให้ตัดสินจากสถานะทุกครั้ง) =====
    const capsToggle = (e) => {
        const inp = e.target.closest('input[type="password"][data-caps-hint]');
        if (!inp) return;
        const hint = document.querySelector(inp.getAttribute("data-caps-hint"));
        if (!hint) return;
        const on = e.getModifierState && e.getModifierState("CapsLock");
        // ถ้าอยากใช้แบบข้อความถาวร:
        hint.textContent = on ? "Caps Lock เปิดอยู่" : "";
        // แสดง/ซ่อนด้วย d-none
        hint.classList.toggle("d-none", !on);
    };
    document.addEventListener("keydown", capsToggle);
    document.addEventListener("keyup", capsToggle);

    // ===== prevent double submit + strip phone + spinner =====
    document.addEventListener("submit", (e) => {
        const form = e.target.closest(
            "form[data-login-form], form[data-register-form]"
        );
        if (!form) return;

        // กันกดซ้ำ
        const btn = form.querySelector("[data-submit-btn]");
        if (btn && (btn.dataset.loading === "1" || btn.disabled)) {
            e.preventDefault();
            return false;
        }

        // ล้าง non-digits ของเบอร์ก่อนส่ง
        const phone = form.querySelector("input[data-autofmt-phone]");
        if (phone) phone.value = phone.value.replace(/\D/g, "");

        if (btn) {
            const isLogin = form.matches("form[data-login-form]");
            btn.dataset.loading = "1";
            btn.dataset.original = btn.innerHTML;
            btn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                (isLogin ? "กำลังเข้าสู่ระบบ..." : "กำลังสมัครสมาชิก...");
            btn.classList.add("disabled");
            btn.setAttribute("aria-disabled", "true");
            btn.disabled = true;
        }
    });

    // ===== focus field + backdrop flag เฉพาะ modal ที่เปิดอยู่ =====
    document.addEventListener("shown.bs.modal", (e) => {
        const m = e.target.closest("[data-login-modal], [data-register-modal]");
        if (!m) return;
        const isLogin = m.matches("[data-login-modal]");
        const firstInvalid = m.querySelector(".is-invalid");
        const fallback = m.querySelector(
            isLogin ? 'input[name="phone"]' : 'input[name="full_name"]'
        );
        (firstInvalid || fallback)?.focus();
        document.body.setAttribute(
            isLogin ? "data-login-open" : "data-register-open",
            m.id || "1"
        );
    });
    document.addEventListener("hidden.bs.modal", (e) => {
        const m = e.target.closest("[data-login-modal], [data-register-modal]");
        if (!m) return;
        const attr = m.matches("[data-login-modal]")
            ? "data-login-open"
            : "data-register-open";
        if (document.body.hasAttribute(attr))
            document.body.removeAttribute(attr);
    });
})();
