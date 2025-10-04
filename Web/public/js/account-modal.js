(() => {
    "use strict";
    if (window.__accountModalInit) return;
    window.__accountModalInit = true;

    const ready = (fn) => {
        if (document.readyState !== "loading") fn();
        else document.addEventListener("DOMContentLoaded", fn, { once: true });
    };
    const asBool = (v) =>
        v == null ? false : /^(1|true|yes)$/i.test(String(v).trim());
    const ensureModal = (el) =>
        window.bootstrap?.Modal
            ? bootstrap.Modal.getInstance(el) ||
              new bootstrap.Modal(el, { backdrop: true, focus: true })
            : null;

    // format เบอร์: 3-3-4
    const fmtPhone = (v) => {
        const d = String(v || "")
            .replace(/\D/g, "")
            .slice(0, 10);
        if (d.length <= 3) return d;
        if (d.length <= 6) return d.slice(0, 3) + "-" + d.slice(3);
        return d.slice(0, 3) + "-" + d.slice(3, 6) + "-" + d.slice(6);
    };

    // สลับไปแท็บรหัสผ่าน
    const switchToPasswordTab = (modalEl) => {
        const btn = modalEl.querySelector("#acc-pass-tab");
        const pane = modalEl.querySelector("#acc-pass");
        const btns = modalEl.querySelectorAll('[data-bs-toggle="tab"]');
        const panes = modalEl.querySelectorAll(".tab-pane");
        if (btn && window.bootstrap?.Tab) {
            try {
                new bootstrap.Tab(btn).show();
            } catch {}
        }
        if (btn && pane) {
            btns.forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");
            panes.forEach((p) => p.classList.remove("show", "active"));
            pane.classList.add("show", "active");
        }
    };

    ready(() => {
        // เปิด modal เมื่อคลิกตัวเรียก (ถ้าคุณมีปุ่ม data-open-account ที่อื่น)
        document.addEventListener("click", (e) => {
            const opener = e.target.closest("[data-open-account]");
            if (!opener) return;
            const targetSel =
                opener.getAttribute("data-bs-target") ||
                opener.getAttribute("href");
            const modalEl =
                (targetSel && document.querySelector(targetSel)) ||
                document.querySelector("[data-account-modal]");
            if (!modalEl) return;
            const modal = ensureModal(modalEl);
            if (!modal) return;
            e.preventDefault();
            modal.show();
        });

        // ภายใน modal
        document.querySelectorAll("[data-account-modal]").forEach((modalEl) => {
            const modal = ensureModal(modalEl);
            const openOnError = asBool(
                modalEl.getAttribute("data-open-on-error")
            );
            const pwdError =
                asBool(modalEl.getAttribute("data-password-error")) ||
                !!modalEl.querySelector("#acc-pass .is-invalid");

            // auto-open + switch tab เมื่อมี error ในรหัสผ่าน
            if (openOnError && pwdError) {
                modalEl.addEventListener(
                    "shown.bs.modal",
                    () => {
                        switchToPasswordTab(modalEl);
                        setTimeout(() => switchToPasswordTab(modalEl), 0);
                    },
                    { once: true }
                );
            }
            if (openOnError && modal) {
                modal.show();
                if (pwdError)
                    setTimeout(() => switchToPasswordTab(modalEl), 10);
            }

            // toggle eye (show/hide)
            modalEl.addEventListener("click", (e) => {
                const btn = e.target.closest("[data-toggle-eye]");
                if (!btn) return;
                const sel = btn.getAttribute("data-target");
                const input = sel ? modalEl.querySelector(sel) : null;
                if (!input) return;
                const pw = input.type === "password";
                input.type = pw ? "text" : "password";
                const icon = btn.querySelector("i");
                if (icon) {
                    icon.classList.toggle("bi-eye", !pw);
                    icon.classList.toggle("bi-eye-slash", pw);
                }
                input.focus();
            });

            // backdrop flag
            modalEl.addEventListener("show.bs.modal", () => {
                document.body.setAttribute(
                    "data-account-open",
                    modalEl.id || "1"
                );
            });
            modalEl.addEventListener("hidden.bs.modal", () => {
                document.body.removeAttribute("data-account-open");
                if (
                    document.activeElement &&
                    modalEl.contains(document.activeElement)
                ) {
                    document.activeElement.blur();
                }
            });
        });

        // phone auto-format (input) + format ค่าเดิมตอนโหลด
        const fmtAll = () =>
            document
                .querySelectorAll("input[data-autofmt-phone]")
                .forEach((el) => (el.value = fmtPhone(el.value)));
        fmtAll();
        document.addEventListener("input", (e) => {
            if (e.target.matches("input[data-autofmt-phone]")) {
                e.target.value = fmtPhone(e.target.value);
            }
            // password needs
            if (e.target.matches('input[type="password"][data-needs]')) {
                const ul = document.querySelector(
                    e.target.getAttribute("data-needs")
                );
                if (!ul) return;
                const s = e.target.value || "";
                const len = s.length,
                    hasL = /[a-z]/.test(s),
                    hasU = /[A-Z]/.test(s),
                    hasN = /\d/.test(s),
                    hasS = /[^A-Za-z0-9]/.test(s);
                const missing = [];
                if (len < 8) missing.push("อย่างน้อย 8 ตัวอักษร");
                if (!(hasL && hasU)) missing.push("ผสมตัวพิมพ์เล็ก/ใหญ่");
                if (!(hasN || hasS)) missing.push("ตัวเลขหรือสัญลักษณ์");
                ul.innerHTML = "";
                missing.forEach((t) => {
                    const li = document.createElement("li");
                    const dot = document.createElement("span");
                    dot.className = "dot";
                    li.append(dot, document.createTextNode(" " + t));
                    ul.append(li);
                });
            }
        });

        // ก่อน submit: ลบทุกอักขระที่ไม่ใช่ตัวเลขออกจากเบอร์
        document.addEventListener("submit", (e) => {
            const form = e.target.closest("form");
            if (!form) return;
            form.querySelectorAll("input[data-autofmt-phone]").forEach((el) => {
                el.value = el.value.replace(/\D/g, "");
            });
        });

        // โฟกัสช่องแรกที่ invalid เมื่อ modal เปิด
        document.addEventListener("shown.bs.modal", (e) => {
            const m = e.target.closest("[data-account-modal]");
            if (!m) return;
            (
                m.querySelector(".is-invalid") ||
                m.querySelector("input[autofocus]") ||
                m.querySelector(
                    'input[type="text"], input[type="tel"], input[type="email"], input[type="password"]'
                )
            )?.focus();
        });

        // ===== CapsLock hint =====
        const updateCapsHint = (inp, on) => {
            const sel = inp.getAttribute("data-caps-hint");
            const scope = inp.closest("[data-account-modal]") || document;
            const hint = sel ? scope.querySelector(sel) : null;
            if (!hint) return;
            hint.textContent = on ? "Caps Lock เปิดอยู่" : "";
            hint.classList.toggle("d-none", !on);
        };

        // แสดง/ซ่อนตามสถานะที่กดคีย์
        document.addEventListener("keydown", (e) => {
            const inp = e.target.closest(
                'input[type="password"][data-caps-hint]'
            );
            if (!inp || !e.getModifierState) return;
            updateCapsHint(inp, e.getModifierState("CapsLock"));
        });
        document.addEventListener("keyup", (e) => {
            const inp = e.target.closest(
                'input[type="password"][data-caps-hint]'
            );
            if (!inp || !e.getModifierState) return;
            updateCapsHint(inp, e.getModifierState("CapsLock"));
        });

        // โฟกัสเข้า: เช็ก/รีเซ็ตสถานะ, โฟกัสออก: ซ่อน
        document.addEventListener("focusin", (e) => {
            const inp = e.target.closest(
                'input[type="password"][data-caps-hint]'
            );
            if (!inp) return;
            // เริ่มต้นซ่อน (จะอัปเดตทันทีที่มี keydown/keyup)
            updateCapsHint(inp, false);
        });
        document.addEventListener("focusout", (e) => {
            const inp = e.target.closest(
                'input[type="password"][data-caps-hint]'
            );
            if (!inp) return;
            updateCapsHint(inp, false);
        });
    });
})();
