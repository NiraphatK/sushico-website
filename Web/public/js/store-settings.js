(() => {
    "use strict";

    const hasSwal = () => typeof window.Swal !== "undefined";

    // Bootstrap tooltips
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

    document.addEventListener("DOMContentLoaded", () => {
        try {
            initTooltips();
        } catch (_) {}
    });
})();
