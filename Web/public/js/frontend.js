// AOS (Animate On Scroll)
(function () {
    if (typeof AOS !== "undefined") {
        AOS.init({ duration: 900, once: true, easing: "ease-out-cubic" });
        window.addEventListener("load", () => AOS.refresh());
    }
})();

// Toggle floating/docked header on scroll
(function () {
    const header = document.getElementById("floatingHeader");
    if (!header) return;
    const onScroll = () => {
        if (window.scrollY > 10) header.classList.add("is-scrolled");
        else header.classList.remove("is-scrolled");
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
})();

// Parallax hover for menu cards (only when pointer is fine)
(function () {
    if (!window.matchMedia || !window.matchMedia("(pointer: fine)").matches)
        return;
    const cards = document.querySelectorAll(
        ".menu-card.parallax .menu-thumb img, .menu-card .menu-thumb img"
    );
    const clamp = (n, min, max) => Math.min(Math.max(n, min), max);
    cards.forEach((img) => {
        const parent = img.closest(".menu-card");
        if (!parent) return;
        parent.addEventListener("mousemove", (e) => {
            const rect = parent.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;
            const tx = clamp((x - 0.5) * 10, -6, 6);
            const ty = clamp((y - 0.5) * 10, -6, 6);
            img.style.transform = `translate3d(${tx}px, ${ty}px, 0) scale(1.08)`;
        });
        parent.addEventListener("mouseleave", () => {
            img.style.transform = "translate3d(0,0,0) scale(1.04)";
        });
    });
})();

// Logout confirm (SweetAlert2)
(function () {
    const init = () => {
        // รองรับหลายฟอร์ม (ถ้ามี) หรือจะใช้ '#logout-form' ตรงๆ ก็ได้
        const forms = document.querySelectorAll(
            '#logout-form, form[action*="logout"]'
        );
        if (!forms.length) return;

        forms.forEach((form) => {
            if (form.__logoutBound) return; // กันผูกซ้ำ
            form.__logoutBound = true;

            form.addEventListener("submit", (e) => {
                // ถ้ายังไม่มี Swal ให้ปล่อย submit ปกติ (ไม่ block ผู้ใช้)
                if (typeof Swal === "undefined") return;

                e.preventDefault();
                Swal.fire({
                    title: "คุณแน่ใจหรือไม่?",
                    text: "คุณกำลังจะออกจากระบบ",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "ใช่ ออกจากระบบ",
                    cancelButtonText: "ยกเลิก",
                    reverseButtons: true,
                    customClass: {
                        popup: "logout-popup",
                        confirmButton: "logout-confirm",
                        cancelButton: "logout-cancel",
                    },
                }).then((res) => {
                    if (res.isConfirmed) form.submit();
                });
            });
        });
    };

    // รันทันทีถ้า DOM โหลดแล้ว ไม่งั้นค่อยรอ
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init, { once: true });
    } else {
        init();
    }
})();

// ===== Fix ARIA focus-in-hidden element =====
document.addEventListener("hide.bs.modal", (e) => {
    const modal = e.target;
    // ถ้า element ที่โฟกัสอยู่เป็นลูกของโมดัล ให้ blur ออกก่อน
    if (document.activeElement && modal.contains(document.activeElement)) {
        document.activeElement.blur();
    }
    // กันโฟกัสหลุดเข้ามาอีกระหว่าง transition
    modal.setAttribute("inert", "");
});

document.addEventListener("hidden.bs.modal", (e) => {
    // เอา inert ออกหลังปิดสนิท
    e.target.removeAttribute("inert");
});
