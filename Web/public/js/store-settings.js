(() => {
    "use strict";

    const hasSwal = () => typeof window.Swal !== "undefined";

    // Reset confirm -> submit hidden form
    window.resetConfirm = () => {
        const submitReset = () => {
            const f = document.getElementById("reset-form");
            if (f) f.submit();
        };
        if (hasSwal()) {
            Swal.fire({
                title: "แน่ใจหรือไม่?",
                text: "คุณต้องการรีเซ็ตค่ากลับเป็นค่าเริ่มต้นทั้งหมด",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "ใช่, รีเซ็ตเลย!",
                cancelButtonText: "ยกเลิก",
            }).then((res) => {
                if (res.isConfirmed) submitReset();
            });
        } else {
            if (confirm("ยืนยันรีเซ็ตการตั้งค่าทั้งหมดหรือไม่?")) submitReset();
        }
    };

    // Tooltips: hover เท่านั้น
    function initTooltips() {
        if (!window.bootstrap) return;

        const tooltipEls = document.querySelectorAll(
            '[data-bs-toggle="tooltip"]'
        );
        tooltipEls.forEach((el) => {
            bootstrap.Tooltip.getOrCreateInstance(el, {
                container: "body",
                trigger: "hover", // ← เปลี่ยนเป็น hover อย่างเดียว
                placement: el.getAttribute("data-bs-placement") || "right",
                fallbackPlacements: ["right", "left", "top", "bottom"],
                html: !!el.getAttribute("data-bs-html"),
                sanitize: true,
            });
        });

        // ปิด/ล้าง tooltip ตอน submit กันค้าง
        const form = document.getElementById("settings-form");
        if (form) {
            form.addEventListener("submit", () => {
                tooltipEls.forEach((el) => {
                    const t = bootstrap.Tooltip.getInstance(el);
                    if (t) {
                        try {
                            t.hide();
                            t.dispose();
                        } catch (_) {}
                    }
                });
            });
        }
    }

    document.addEventListener("DOMContentLoaded", initTooltips);
})();
