/* ==================================
   Contact Us: form handler (SweetAlert2)
   ================================== */
(() => {
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("contactForm");
        if (!form) return;

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            if (!form.reportValidity()) return;

            const btn = form.querySelector('button[type="submit"]');
            const prevHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = "<span>Sending…</span>";

            try {
                await new Promise((r) => setTimeout(r, 400));
                if (window.Swal) {
                    Swal.fire({
                        title: "ส่งข้อความเรียบร้อย",
                        text: "ทางเราได้รับข้อความของท่านเรียบร้อยแล้ว",
                        icon: "success",
                        confirmButtonText: "OK",
                        draggable: true,
                        customClass: { confirmButton: "btn-default" },
                    });
                }
                form.reset();
            } catch (err) {
                if (window.Swal) {
                    Swal.fire({
                        title: "Oops…",
                        text:
                            err?.message ||
                            "Something went wrong. Please try again.",
                        icon: "error",
                        confirmButtonText: "Close",
                    });
                }
            } finally {
                btn.disabled = false;
                btn.innerHTML = prevHTML;
            }
        });
    });
})();

/* ==============================
   Reserve Steps
   ============================== */
(() => {
    "use strict";

    const qs = (s, sc = document) => sc.querySelector(s);
    const qsa = (s, sc = document) => [...sc.querySelectorAll(s)];
    const clamp = (v, min, max) => Math.max(min, Math.min(max, v));
    const addMin = (hhmm, m) => {
        if (!hhmm) return "";
        const [h, mi] = hhmm.split(":").map(Number);
        const mins = h * 60 + mi + m;
        const H = String(Math.floor(mins / 60) % 24).padStart(2, "0");
        const M = String(mins % 60).padStart(2, "0");
        return `${H}:${M}`;
    };

    document.addEventListener("DOMContentLoaded", () => {
        const form = qs("#reserveForm");
        if (!form) return;

        const allowAfter = form.dataset.allowAfter || "00:00";
        const duration = parseInt(form.dataset.duration || "60", 10);
        const CAP = { TABLE: 10, BAR: 1 };

        // elements
        const qty = qs("#party_size");
        const minusBtn = qs('.qty-btn[data-qty="-1"]');
        const plusBtn = qs('.qty-btn[data-qty="+1"]');

        const capHint = qs("#capHint");
        const preview = qs("#preview");

        const sumP = qs("#sumP");
        const sumS = qs("#sumS");
        const sumT = qs("#sumT");
        const sumP_side = qs("#sumP_side");
        const sumS_side = qs("#sumS_side");
        const sumT_side = qs("#sumT_side");

        const mParty = qs("#mParty");
        const mSeat = qs("#mSeat");
        const mTime = qs("#mTime");

        const seatRadios = qsa('input[name="seat_type"]');
        const timeRadios = qsa('input[name="start_time"]');

        const hoursBar = qs("#hours");
        const noSlotsHour = qs("#noSlotsHour");
        const btnPrevHr = qs("#hPrev");
        const btnNextHr = qs("#hNext");
        const btnPickEarliest = qs("#btnPickEarliest");

        const btnPrevStep = qs("#btnPrevStep");
        const btnNextStep = qs("#btnNextStep");
        const btnSubmit = qs("#btnSubmit");

        const mobileCta = qs("#mobileCta");
        const btnMobilePrimary = qs("#btnMobilePrimary");
        const btnMobileBack = qs("#btnMobileBack");

        const panes = qsa(".step-pane");
        const steps = qsa(".stepper .step");

        // ---- Start at Step 1 always (no auto-skip) ----
        let stepIndex = 0;

        // helpers
        const getSeat = () =>
            qs('input[name="seat_type"]:checked')?.value || "TABLE";

        function toggleStepperButtons() {
            const min = parseInt(qty.min || "1", 10);
            const max = parseInt(qty.max || "10", 10);
            const v = parseInt(qty.value || String(min), 10);
            if (minusBtn) minusBtn.disabled = v <= min;
            if (plusBtn) plusBtn.disabled = v >= max;
        }

        function syncCap() {
            const seat = getSeat();
            const cap = CAP[seat] || 10;

            if (qty) {
                qty.max = String(cap);
                if (parseInt(qty.value || "1", 10) > cap) qty.value = cap;
            }
            capHint && (capHint.textContent = `${seat} รองรับสูงสุด ${cap} คน`);

            sumS && (sumS.textContent = seat);
            sumS_side && (sumS_side.textContent = seat);
            mSeat && (mSeat.textContent = seat);

            syncQtyUI();
        }

        function syncQtyUI() {
            sumP && (sumP.textContent = `${qty.value} คน`);
            sumP_side && (sumP_side.textContent = `${qty.value} คน`);
            mParty && (mParty.textContent = qty.value);
            toggleStepperButtons();
        }

        function setPreviewEmpty() {
            if (preview)
                preview.innerHTML =
                    '<span class="text-muted" style="font-weight:600">เลือกเวลา</span>';
            if (sumT) sumT.textContent = "—";
            if (sumT_side) sumT_side.textContent = "—";
            if (mTime) mTime.textContent = "—";
        }

        function updateTime(start) {
            if (!start) {
                setPreviewEmpty();
                return;
            }
            const end = addMin(start, duration);
            sumT && (sumT.textContent = start);
            sumT_side && (sumT_side.textContent = start);
            mTime && (mTime.textContent = start);
            preview && (preview.textContent = `${start} – ${end}`);
        }

        // qty stepper & chips
        minusBtn?.addEventListener("click", () => {
            qty.value = clamp(
                parseInt(qty.value || "1", 10) - 1,
                parseInt(qty.min || "1", 10),
                parseInt(qty.max || "10", 10)
            );
            syncQtyUI();
        });
        plusBtn?.addEventListener("click", () => {
            qty.value = clamp(
                parseInt(qty.value || "1", 10) + 1,
                parseInt(qty.min || "1", 10),
                parseInt(qty.max || "10", 10)
            );
            syncQtyUI();
        });
        qty?.addEventListener("change", syncQtyUI);
        qsa("[data-qty-chip]").forEach((btn) => {
            btn.addEventListener("click", () => {
                const v = parseInt(btn.getAttribute("data-qty-chip"), 10);
                const cap = CAP[getSeat()] || 10;
                qty.value = String(clamp(v, 1, cap));
                syncQtyUI();
            });
        });

        // ย้อนกลับทีละสเต็ป
        btnMobileBack?.addEventListener("click", () => {
            if (typeof prevStep === "function") prevStep();
        });

        // seat
        seatRadios.forEach((r) => r.addEventListener("change", syncCap));
        syncCap();

        // hours tabs
        function setHour(h) {
            const radios = qsa('input[name="start_time"]');
            const labels = qsa("label.slot");

            labels.forEach((l) =>
                l.classList.toggle("d-none", l.dataset.hour !== h)
            );
            radios.forEach((r) =>
                r.classList.toggle("d-none", r.dataset.hour !== h)
            );

            const visible = radios.filter(
                (r) => !r.classList.contains("d-none")
            );
            const usable = visible
                .filter((r) => !r.disabled && r.value > allowAfter)
                .sort((a, b) => a.value.localeCompare(b.value));
            const checked = visible.find(
                (r) =>
                    r.checked &&
                    !r.disabled &&
                    r.value > allowAfter &&
                    r.dataset.autoselect !== "1"
            );

            if (!usable.length) {
                noSlotsHour?.classList.remove("d-none");
                setPreviewEmpty();
                return;
            }
            noSlotsHour?.classList.add("d-none");

            // ไม่ auto-pick ถ้าไม่มีที่ผู้ใช้เลือกเอง
            if (checked) {
                updateTime(checked.value);
                const label = qs(`label[for="${checked.id}"]`);
                label?.scrollIntoView({
                    behavior: "smooth",
                    block: "nearest",
                    inline: "center",
                });
            } else {
                setPreviewEmpty();
            }
        }

        function updateHourArrows() {
            const bar = hoursBar;
            if (!bar) return;
            const scrollLeft = bar.scrollLeft;
            const maxScroll = bar.scrollWidth - bar.clientWidth - 1;
            btnPrevHr && (btnPrevHr.disabled = scrollLeft <= 0);
            btnNextHr && (btnNextHr.disabled = scrollLeft >= maxScroll);
        }

        btnPrevHr?.addEventListener("click", () =>
            hoursBar?.scrollBy({ left: -240, behavior: "smooth" })
        );
        btnNextHr?.addEventListener("click", () =>
            hoursBar?.scrollBy({ left: 240, behavior: "smooth" })
        );
        hoursBar?.addEventListener("scroll", updateHourArrows);

        // init hour pane
        const checkedTime = qs('input[name="start_time"]:checked');
        const initialHour = checkedTime
            ? checkedTime.dataset.hour
            : qs("[data-hour-tab]")?.dataset.hourTab;
        if (checkedTime?.dataset.autoselect === "1") {
            // ถ้าเป็น auto-select ให้ “เลิกติ๊ก” เพื่อไม่ให้ข้ามสเต็ป
            checkedTime.checked = false;
        }
        if (initialHour) {
            qs(`#h-${initialHour}`)?.click();
            setHour(initialHour);
        }
        hoursBar?.querySelectorAll("[data-hour-tab]").forEach((tab) => {
            tab.addEventListener("click", () => setHour(tab.dataset.hourTab));
        });

        // slot change (user-picked)
        timeRadios.forEach((r) => {
            r.addEventListener("change", () => {
                if (!r.checked) return;
                r.removeAttribute("data-autoselect"); // นับเป็นการเลือกของผู้ใช้แล้ว
                updateTime(r.value);
            });
        });
        requestAnimationFrame(updateHourArrows);

        // stepper
        function renderSteps() {
            panes.forEach((p, i) =>
                p.classList.toggle("is-active", i === stepIndex)
            );
            steps.forEach((s, i) => {
                s.classList.toggle("is-active", i === stepIndex);
                s.classList.toggle("is-complete", i < stepIndex);
                if (i === stepIndex) s.setAttribute("aria-current", "step");
                else s.removeAttribute("aria-current");
            });

            if (stepIndex < 3) {
                btnNextStep?.classList.remove("d-none");
                btnSubmit?.classList.add("d-none");
            } else {
                btnNextStep?.classList.add("d-none");
                btnSubmit?.classList.remove("d-none");
            }

            if (mobileCta) {
                mobileCta.hidden = false;
                const span = btnMobilePrimary?.querySelector(".btn-text");
                if (span) span.textContent = stepIndex < 3 ? "ถัดไป" : "ยืนยัน";
            }

            btnPrevStep?.toggleAttribute("disabled", stepIndex === 0);
        }

        // ปรับ padding-bottom ของหน้าให้เท่าความสูง CTA + safe area
        function adjustBottomPadding() {
            const cta = qs("#mobileCta");
            const page = qs(".reserve-page");
            if (!cta || !page) return;
            const h = cta.getBoundingClientRect().height || 72;
            page.style.paddingBottom = `calc(${h}px + env(safe-area-inset-bottom, 0px))`;
        }
        adjustBottomPadding();
        window.addEventListener("resize", adjustBottomPadding);

        function validateCurrentStep() {
            if (stepIndex === 0) {
                const seat = getSeat();
                return seat === "TABLE" || seat === "BAR";
            }
            if (stepIndex === 1) {
                if (!qty?.reportValidity()) return false;
                const cap = CAP[getSeat()] || 10;
                const v = parseInt(qty.value || "1", 10);
                return v >= 1 && v <= cap;
            }
            if (stepIndex === 2) {
                const chosen = qs('input[name="start_time"]:checked');
                return !!chosen && chosen.dataset.autoselect !== "1";
            }
            return true;
        }

        function nextStep() {
            if (!validateCurrentStep()) {
                if (window.Swal) {
                    Swal.fire({
                        title: "กรอกข้อมูลให้ครบถ้วน",
                        text: "โปรดตรวจสอบข้อมูลในขั้นตอนนี้ก่อนดำเนินการต่อ",
                        icon: "warning",
                        confirmButtonText: "ตกลง",
                        customClass: { confirmButton: "btn-default" },
                    });
                }
                return;
            }
            if (stepIndex < 3) stepIndex += 1;
            renderSteps();
        }

        function prevStep() {
            if (stepIndex > 0) stepIndex -= 1;
            renderSteps();
        }

        // actions
        btnNextStep?.addEventListener("click", nextStep);
        btnPrevStep?.addEventListener("click", prevStep);

        btnMobilePrimary?.addEventListener("click", () => {
            if (stepIndex < 3) nextStep();
            else submitWithLoading();
        });
        btnMobileBack?.addEventListener("click", prevStep);

        // link-edit (review)
        qsa("[data-goto-step]").forEach((btn) => {
            btn.addEventListener("click", () => {
                const n = parseInt(btn.getAttribute("data-goto-step"), 10);
                stepIndex = clamp(n - 1, 0, 3);
                renderSteps();
            });
        });

        // earliest button
        btnPickEarliest?.addEventListener("click", () => {
            const earliest = qsa('input[name="start_time"]')
                .filter((r) => !r.disabled && r.value > allowAfter)
                .sort((a, b) => a.value.localeCompare(b.value))[0];
            if (earliest) {
                earliest.checked = true;
                earliest.removeAttribute("data-autoselect");
                updateTime(earliest.value);
            } else {
                setPreviewEmpty();
            }
        });

        // submit affordance
        function lock(btn) {
            btn?.setAttribute("disabled", "true");
            btn?.querySelector(".btn-text")?.classList.add("d-none");
            btn?.querySelector(".spinner")?.classList.remove("d-none");
        }
        function unlock(btn) {
            btn?.removeAttribute("disabled");
            btn?.querySelector(".btn-text")?.classList.remove("d-none");
            btn?.querySelector(".spinner")?.classList.add("d-none");
        }
        function submitWithLoading() {
            lock(btnSubmit);
            lock(btnMobilePrimary);
            form.submit();
        }
        form.addEventListener("submit", () => {
            lock(btnSubmit);
            lock(btnMobilePrimary);
        });
        form.addEventListener(
            "invalid",
            (e) => {
                e.preventDefault();
                unlock(btnSubmit);
                unlock(btnMobilePrimary);
            },
            true
        );

        // init summaries & step 1
        const sel = qs('input[name="start_time"]:checked');
        if (sel?.dataset.autoselect === "1") setPreviewEmpty();
        else if (sel) updateTime(sel.value);
        renderSteps();
    });
})();
