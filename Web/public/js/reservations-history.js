/*!
 * reservations-history.js (empty-safe)
 * Realtime filters for reservations history
 * - Works even when there are NO date groups (server returned empty list)
 * - Intercepts segmented pills & Clear button to avoid full-page reload
 * - Presets behave as realtime (Cmd/Ctrl = open server-side)
 * - Apply = server-side (keeps params for pagination)
 */

(() => {
    "use strict";

    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    const ymdToNum = (s) =>
        s && /^\d{4}-\d{2}-\d{2}$/.test(s)
            ? Number(s.replaceAll("-", ""))
            : null;
    const inRange = (ymd, fromY, toY) => {
        const n = ymdToNum(ymd);
        if (!n) return true;
        if (fromY && n < fromY) return false;
        if (toY && n > toY) return false;
        return true;
    };
    const debounce = (fn, ms = 100) => {
        let t;
        return (...a) => {
            clearTimeout(t);
            t = setTimeout(() => fn(...a), ms);
        };
    };
    const todayYMD = () => {
        const d = new Date();
        const p = (n) => String(n).padStart(2, "0");
        return `${d.getFullYear()}-${p(d.getMonth() + 1)}-${p(d.getDate())}`;
    };

    let wired = false;

    function wire() {
        if (wired) return;
        wired = true;

        // controls
        const statusSel = $("#filter-status");
        const seatSel = $("#filter-seat");
        const fromInput = $("#filter-from");
        const toInput = $("#filter-to");
        const onlyCancel = $("#toggleOnlyCancellable");
        const hideCancel = $("#toggleHideCancelled");
        const applyBtn = $("#applyBtn");
        const clearBtn = $("#clearFilters");
        const jumpToday = $("#jumpToday");
        const emptyClient = $(".empty-client");

        // data
        const dateGroups = $$(".date-group");
        const hasGroups = dateGroups.length > 0;

        // segmented pills
        const seg = $("#segStatus");
        const segLinks = seg ? $$("a.seg-pill", seg) : [];

        // sync pills
        const syncSegActive = () => {
            if (!segLinks.length) return;
            const v = (statusSel?.value || "").trim();
            segLinks.forEach((a) => {
                const val = (a.getAttribute("data-status") || "").trim();
                a.classList.toggle("active", val === v || (!v && val === ""));
            });
        };

        // core filter (safe when no groups)
        const doFilter = () => {
            const sVal = statusSel?.value?.trim() || "";
            const seat = seatSel?.value?.trim() || "";
            const fVal = fromInput?.value?.trim() || "";
            const tVal = toInput?.value?.trim() || "";
            const only = !!(onlyCancel && onlyCancel.checked);
            const hide = !!(hideCancel && hideCancel.checked);

            const fromN = ymdToNum(fVal);
            const toN = ymdToNum(tVal);

            let totalVisible = 0;

            if (hasGroups) {
                for (const group of dateGroups) {
                    const ymd = group.getAttribute("data-date") || "";
                    const dateOk = inRange(ymd, fromN, toN);

                    let visibleInGroup = 0;
                    const cards = $$(".res-card", group);

                    for (const card of cards) {
                        let v = true;

                        if (!dateOk) v = false;

                        const cStatus = card.getAttribute("data-status") || "";
                        const cSeat = card.getAttribute("data-seat") || "";
                        const canFlag =
                            card.getAttribute("data-cancellable") === "1";

                        if (v && sVal && cStatus !== sVal) v = false;
                        if (v && seat && cSeat !== seat) v = false;
                        if (v && only && !canFlag) v = false;
                        if (
                            v &&
                            hide &&
                            (cStatus === "CANCELLED" || cStatus === "NO_SHOW")
                        )
                            v = false;

                        card.hidden = !v;
                        if (v) visibleInGroup++;
                    }

                    const countEl = $(".date-count", group);
                    if (countEl)
                        countEl.textContent = `${visibleInGroup} รายการ`;

                    group.hidden = visibleInGroup === 0;
                    if (visibleInGroup > 0) totalVisible += visibleInGroup;
                }
            }

            // emptyClient มีเฉพาะกรณี server ส่งกลุ่มมาแล้ว เรากรองจนไม่เหลือ
            if (emptyClient)
                emptyClient.hidden = !hasGroups || totalVisible > 0;

            syncSegActive();
        };

        const doFilterDebounced = debounce(doFilter, 80);

        // inputs & toggles
        [statusSel, seatSel, fromInput, toInput].forEach((el) => {
            if (!el) return;
            el.addEventListener("input", doFilterDebounced);
            el.addEventListener("change", doFilterDebounced);
        });
        if (onlyCancel)
            onlyCancel.addEventListener("change", doFilterDebounced);
        if (hideCancel)
            hideCancel.addEventListener("change", doFilterDebounced);
        if (statusSel) statusSel.addEventListener("change", syncSegActive);

        // segmented pills -> realtime (even if no groups)
        segLinks.forEach((a) => {
            a.addEventListener("click", (ev) => {
                if (ev.metaKey || ev.ctrlKey) return;
                ev.preventDefault();

                const val = (a.getAttribute("data-status") || "").trim();
                if (statusSel) statusSel.value = val;

                segLinks.forEach((x) => x.classList.remove("active"));
                a.classList.add("active");

                doFilter();

                try {
                    const url = new URL(window.location.href);
                    if (val) url.searchParams.set("status", val);
                    else url.searchParams.delete("status");
                    url.searchParams.delete("page");
                    history.replaceState({}, "", url);
                } catch (_) {}
            });
        });

        // presets -> realtime (even if no groups)
        $$(".preset-row a").forEach((a) => {
            a.addEventListener("click", (ev) => {
                if (ev.metaKey || ev.ctrlKey) return;
                ev.preventDefault();
                try {
                    const u = new URL(a.href);
                    const qs = u.searchParams;
                    if (statusSel) statusSel.value = qs.get("status") ?? "";
                    if (seatSel) seatSel.value = qs.get("seat_type") ?? "";
                    if (fromInput) fromInput.value = qs.get("from") ?? "";
                    if (toInput) toInput.value = qs.get("to") ?? "";
                    doFilter();
                    syncSegActive();

                    const url = new URL(window.location.href);
                    ["status", "seat_type", "from", "to"].forEach((k) => {
                        const v = qs.get(k);
                        if (v) url.searchParams.set(k, v);
                        else url.searchParams.delete(k);
                    });
                    url.searchParams.delete("page");
                    history.replaceState({}, "", url);
                } catch {
                    window.location.assign(a.href);
                }
            });
        });

        // clear -> realtime (even if no groups)
        if (clearBtn) {
            clearBtn.addEventListener("click", (ev) => {
                if (ev.metaKey || ev.ctrlKey) return;
                ev.preventDefault();

                if (statusSel) statusSel.value = "";
                if (seatSel) seatSel.value = "";
                if (fromInput) fromInput.value = "";
                if (toInput) toInput.value = "";
                if (onlyCancel) onlyCancel.checked = false;
                if (hideCancel) hideCancel.checked = false;

                if (segLinks.length) {
                    segLinks.forEach((x) => x.classList.remove("active"));
                    const allPill = segLinks.find(
                        (x) => (x.getAttribute("data-status") || "") === ""
                    );
                    if (allPill) allPill.classList.add("active");
                }

                doFilter();

                try {
                    const url = new URL(window.location.href);
                    ["status", "seat_type", "from", "to", "page"].forEach((k) =>
                        url.searchParams.delete(k)
                    );
                    history.replaceState({}, "", url);
                } catch (_) {}
            });
        }

        // Apply -> server-side (ตั้งใจให้รีหน้าเพื่อคง params สำหรับ pagination)
        if (applyBtn) {
            applyBtn.addEventListener("click", () => {
                const params = new URLSearchParams();
                if (statusSel?.value) params.set("status", statusSel.value);
                if (seatSel?.value) params.set("seat_type", seatSel.value);
                if (fromInput?.value) params.set("from", fromInput.value);
                if (toInput?.value) params.set("to", toInput.value);

                const url = new URL(window.location.href);
                url.search = params.toString();
                window.location.assign(url.toString());
            });
        }

        // Jump to Today (จะไม่มีผลเมื่อไม่มี groups — ไม่เป็นไร)
        const ymd = todayYMD();
        const todaySection = document.querySelector(
            `.date-group[data-date="${ymd}"]`
        );
        if (todaySection && jumpToday) {
            jumpToday.hidden = false;
            jumpToday.addEventListener("click", () => {
                todaySection.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            });
        }

        // Tooltips (optional)
        if (window.bootstrap && hasGroups) {
            $$(".res-card [data-bs-toggle='tooltip']").forEach((el) => {
                try {
                    new bootstrap.Tooltip(el);
                } catch (_) {}
            });
        }

        // initial
        doFilter();

        window.__reservationsHistoryFilter = { refresh: doFilter };
    }

    const init = () => {
        try {
            wire();
        } catch (e) {
            console.error("reservations-history init error:", e);
        }
    };

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init, { once: true });
    } else {
        init();
    }
    window.addEventListener("pageshow", init);
    window.addEventListener("turbo:load", init);
    document.addEventListener("livewire:navigated", init);

    // SweetAlert confirm (unchanged)
    window.confirmCancel = (id, labelTime, btn) => {
        if (!window.Swal) return;
        Swal.fire({
            title: "ยืนยันยกเลิกการจอง?",
            html: `คุณต้องการยกเลิกการจองวันที่ <b>${labelTime}</b> ใช่หรือไม่`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#1A3636",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "ใช่ ยกเลิกเลย",
            cancelButtonText: "ไม่เอา",
            reverseButtons: true,
        }).then((res) => {
            if (res.isConfirmed) {
                const form = btn?.closest("form");
                if (form) form.submit();
            }
        });
    };
})();
