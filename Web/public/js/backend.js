/* Sushico Back Office – interactions
   - Close offcanvas then navigate (mobile)
   - Logout confirm (SweetAlert2)
*/

(() => {
    // Close offcanvas then navigate (mobile)
    const offcanvasLinks = document.querySelectorAll(
        "#mobileSidebar a.nav-link"
    );
    offcanvasLinks.forEach((a) => {
        a.addEventListener("click", (e) => {
            const href = a.getAttribute("href");
            if (!href || href === "#" || href.startsWith("#")) return;
            e.preventDefault();
            const offcanvasEl = document.getElementById("mobileSidebar");
            if (!offcanvasEl || !window.bootstrap) {
                window.location.href = href;
                return;
            }
            const oc = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
            offcanvasEl.addEventListener(
                "hidden.bs.offcanvas",
                () => {
                    window.location.href = href;
                },
                { once: true }
            );
            oc.hide();
        });
    });

    // Logout confirm
    document.addEventListener("DOMContentLoaded", () => {
        const btn = document.getElementById("btnLogout");
        if (!btn) return;
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            if (typeof Swal === "undefined") {
                // fallback ถ้าไม่มี sweetalert2
                document.getElementById("logout-form")?.submit();
                return;
            }
            Swal.fire({
                title: "ออกจากระบบ?",
                text: "คุณแน่ใจหรือไม่ว่าต้องการออกจากระบบ",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "ใช่, ออกจากระบบ",
                cancelButtonText: "ยกเลิก",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("logout-form")?.submit();
                }
            });
        });
    });
})();
