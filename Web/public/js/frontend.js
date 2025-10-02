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
    document.addEventListener("DOMContentLoaded", function () {
        const logoutForm = document.getElementById("logout-form");
        if (!logoutForm) return;

        logoutForm.addEventListener("submit", function (e) {
            if (typeof Swal === "undefined") return; // fallback: submit normally
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
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
    });
})();
