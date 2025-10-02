(() => {
    "use strict";

    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    const hasSwal = () => typeof window.Swal !== "undefined";

    // -------- Add/Edit page helpers --------
    const initSeatSegment = () => {
        const group = $("#seatGroup");
        if (!group) return;
        $$('#seatGroup input[type="radio"]').forEach((r) => {
            r.addEventListener("change", () => {
                $$("#seatGroup .seg-chip").forEach((c) =>
                    c.classList.remove("active")
                );
                const chip = r.closest(".seg-chip");
                if (chip) chip.classList.add("active");
            });
        });
    };

    const initTableNumberNormalizer = () => {
        const tno = $("#table_number");
        if (!tno) return;
        const norm = (v) =>
            (v || "").toUpperCase().replace(/\s+/g, "").slice(0, 10);
        tno.addEventListener("input", () => {
            tno.value = norm(tno.value);
        });
        const form = tno.closest("form");
        if (form)
            form.addEventListener("submit", () => {
                tno.value = norm(tno.value);
            });
    };

    const initCapacityClamp = () => {
        const cap = $("#capacity");
        if (!cap) return;
        const clamp = (v) => {
            const n = parseInt(v, 10);
            if (Number.isNaN(n)) return "";
            return Math.max(1, Math.min(10, n));
        };
        cap.addEventListener("input", () => {
            cap.value = clamp(cap.value);
        });
    };

    const initFilledFloating = () => {
        $$(".form-floating .form-control, .form-floating .form-select").forEach(
            (el) => {
                const sync = () =>
                    el.classList.toggle(
                        "is-filled",
                        String(el.value ?? "").trim().length > 0
                    );
                sync();
                el.addEventListener("input", sync);
                el.addEventListener("change", sync);
            }
        );
    };

    const initStatusLiveText = () => {
        const chk = document.querySelector(
            'input[name="is_active"][type="checkbox"]'
        );
        const txt = $("#statusText");
        if (!chk || !txt) return;
        const render = () => {
            txt.textContent = chk.checked ? "Active" : "Inactive";
        };
        render();
        chk.addEventListener("change", render);
    };

    const initPreventDoubleSubmit = () => {
        $$("form").forEach((f) => {
            f.addEventListener("submit", () => {
                const btn = f.querySelector('button[type="submit"]');
                if (btn && !btn.disabled) {
                    btn.disabled = true;
                    btn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
                }
            });
        });
    };

    // -------- List page: SweetAlert delete + tooltips --------
    const initDeleteConfirm = () => {
        window.deleteConfirm = (id) => {
            const submit = () => {
                const fm = document.getElementById(`delete-form-${id}`);
                if (fm) fm.submit();
            };
            if (hasSwal()) {
                Swal.fire({
                    title: "แน่ใจหรือไม่?",
                    text: "คุณต้องการลบโต๊ะนี้จริง ๆ หรือไม่",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "ใช่, ลบเลย!",
                    cancelButtonText: "ยกเลิก",
                }).then((res) => {
                    if (res.isConfirmed) submit();
                });
            } else {
                if (confirm("ยืนยันลบโต๊ะนี้หรือไม่?")) submit();
            }
        };
    };

    const initTooltips = () => {
        if (!window.bootstrap) return;
        $$('[data-bs-toggle="tooltip"]').forEach((el) => {
            try {
                new bootstrap.Tooltip(el);
            } catch (_) {}
        });
    };

    // -------- List page: client-side filters (table & cards) --------
    const initFilters = () => {
        const q = $("#q"),
            seat = $("#seat"),
            status = $("#status");
        const btnSearch = $("#btnSearch"),
            btnClear = $("#btnClear");
        if (!q || !seat || !status || !btnSearch || !btnClear) return;

        const rows = $$(".js-table-row");
        const cards = $$(".js-table-card");
        const noResultTable = $("#noResultTable");
        const noResultCards = $("#noResultCards");
        const clientInfo = $("#clientInfo");

        const match = (
            itemSearch,
            itemSeat,
            itemStatus,
            kw,
            fSeat,
            fStatus
        ) => {
            if (kw && !itemSearch.includes(kw)) return false;
            if (fSeat && fSeat !== itemSeat) return false;
            if (fStatus && fStatus !== itemStatus) return false;
            return true;
        };

        const apply = () => {
            const kw = (q.value || "").trim().toLowerCase();
            const fSeat = (seat.value || "").toLowerCase();
            const fStatus = (status.value || "").toLowerCase();

            let shownTable = 0,
                shownCards = 0;

            rows.forEach((tr) => {
                const ok = match(
                    tr.dataset.search,
                    tr.dataset.seat,
                    tr.dataset.status,
                    kw,
                    fSeat,
                    fStatus
                );
                tr.style.display = ok ? "" : "none";
                if (ok) shownTable++;
            });

            cards.forEach((card) => {
                const ok = match(
                    card.dataset.search,
                    card.dataset.seat,
                    card.dataset.status,
                    kw,
                    fSeat,
                    fStatus
                );
                card.style.display = ok ? "" : "none";
                if (ok) shownCards++;
            });

            if (noResultTable)
                noResultTable.classList.toggle("d-none", shownTable !== 0);
            if (noResultCards)
                noResultCards.classList.toggle("d-none", shownCards !== 0);

            const hasClientFilter = !!(kw || fSeat || fStatus);
            if (clientInfo) {
                clientInfo.classList.toggle("d-none", !hasClientFilter);
                if (hasClientFilter) {
                    const parts = [];
                    if (kw) parts.push(`q: "${kw}"`);
                    if (fSeat) parts.push(`seat: ${fSeat}`);
                    if (fStatus) parts.push(`status: ${fStatus}`);
                    clientInfo.textContent = `${
                        shownTable || shownCards
                    } matched (${parts.join(", ")})`;
                }
            }
        };

        let t = null;
        q.addEventListener("input", () => {
            clearTimeout(t);
            t = setTimeout(apply, 350);
        });
        [seat, status].forEach((el) => el.addEventListener("change", apply));
        btnSearch.addEventListener("click", apply);
        q.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                apply();
            }
        });
        btnClear.addEventListener("click", () => {
            q.value = "";
            seat.value = "";
            status.value = "";
            apply();
        });
    };

    // -------- Boot --------
    document.addEventListener("DOMContentLoaded", () => {
        initSeatSegment();
        initTableNumberNormalizer();
        initCapacityClamp();
        initFilledFloating();
        initStatusLiveText();
        initPreventDoubleSubmit();

        initDeleteConfirm();
        initTooltips();
        initFilters();
    });
})();
