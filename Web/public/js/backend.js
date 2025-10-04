/* Sushico Back Office – interactions
   - Close offcanvas then navigate (mobile)
   - Logout confirm (SweetAlert2, brand-styled)
*/
(() => {
    "use strict";

    /* ---------- util ---------- */
    const safeNavigate = (href, { replace = false } = {}) => {
        if (!href) return;
        replace ? location.replace(href) : (location.href = href);
    };

    /* ---------- Close offcanvas then navigate (mobile) ---------- */
    const ocEl = document.getElementById("mobileSidebar");
    if (ocEl) {
        // ใช้ event delegation ให้คลิกลูกหลาน <a.nav-link> ก็ทำงาน
        ocEl.addEventListener("click", (e) => {
            const a = e.target.closest("a.nav-link");
            if (!a) return;

            const href = a.getAttribute("href") || "";
            if (!href || href === "#" || href.startsWith("#")) return;

            e.preventDefault();

            if (!window.bootstrap) {
                safeNavigate(href);
                return;
            }

            const oc = bootstrap.Offcanvas.getOrCreateInstance(ocEl);

            const go = () => {
                ocEl.removeEventListener("hidden.bs.offcanvas", go);
                safeNavigate(href);
            };

            ocEl.addEventListener("hidden.bs.offcanvas", go, { once: true });
            oc.hide();

            // failsafe: เผื่อ hidden ไม่ยิง (เช่น offcanvas ถูกซ่อนอยู่แล้ว)
            setTimeout(() => {
                const isShown = ocEl.classList.contains("show");
                if (!isShown) safeNavigate(href);
            }, 450);
        });
    }

    /* ---------- Logout confirm (SweetAlert2) ---------- */
    const initLogoutConfirm = () => {
        const btn = document.getElementById("btnLogout");
        if (!btn) return;

        const form = document.getElementById("logout-form");
        const submitLogout = () =>
            form?.submit() || document.forms["logout-form"]?.submit();

        const nativeConfirm = () => {
            if (window.confirm("คุณแน่ใจหรือไม่ว่าต้องการออกจากระบบ?"))
                submitLogout();
        };

        const swalConfirm = () => {
            Swal.fire({
                icon: "warning",
                title: "คุณแน่ใจหรือไม่?",
                html: '<div style="margin-top:.25rem;color:var(--muted)">คุณกำลังจะออกจากระบบ</div>',
                showCancelButton: true,
                confirmButtonText: "ใช่ ออกจากระบบ",
                cancelButtonText: "ยกเลิก",
                focusConfirm: true,
                reverseButtons: false,
                allowOutsideClick: true,
                showCloseButton: true,
                heightAuto: false, // กัน jump บนมือถือบางเครื่อง
                showClass: { popup: "swal2-show" },
                hideClass: { popup: "swal2-hide" },
                // ไม่ตั้ง confirmButtonColor/cancelButtonColor เพื่อให้ใช้ธีม CSS ของคุณ
            }).then((res) => {
                if (res.isConfirmed) submitLogout();
            });
        };

        btn.addEventListener("click", (e) => {
            e.preventDefault();
            if (typeof Swal === "undefined") nativeConfirm();
            else swalConfirm();
        });

        // รองรับ Enter/Space หากเป็นลิงก์หรือปุ่มแบบ custom
        btn.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                btn.click();
            }
        });
    };

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initLogoutConfirm);
    } else {
        initLogoutConfirm();
    }
})();
