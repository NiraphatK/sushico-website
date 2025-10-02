(() => {
    "use strict";

    // ---------- Utilities ----------
    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    // Format number to 2 decimals (safe)
    const to2 = (v) => {
        const n = Number(v);
        return Number.isNaN(n) ? v : n.toFixed(2);
    };

    // ---------- Global (use in index table actions) ----------
    window.deleteConfirm = function (id) {
        // ถ้าไม่มี SweetAlert ให้ submit ตรง ๆ
        if (!window.Swal) {
            const f = document.getElementById("delete-form-" + id);
            if (f) f.submit();
            return;
        }
        Swal.fire({
            title: "แน่ใจหรือไม่?",
            text: "คุณต้องการลบเมนูนี้จริง ๆ หรือไม่",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "ใช่, ลบเลย!",
            cancelButtonText: "ยกเลิก",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("delete-form-" + id)?.submit();
            }
        });
    };

    window.previewImage = function (url, title) {
        if (!window.Swal) {
            window.open(url, "_blank"); // fallback
            return;
        }
        Swal.fire({
            title: title || "",
            imageUrl: url,
            imageWidth: 480,
            imageAlt: title || "",
            showCloseButton: true,
            showConfirmButton: false,
            background: "#fff",
            customClass: { popup: "rounded-4 shadow-lg" },
        });
    };

    // ---------- Page bootstrap ----------
    document.addEventListener("DOMContentLoaded", () => {
        // Tooltips (Bootstrap)
        if (window.bootstrap?.Tooltip) {
            $$('[data-bs-toggle="tooltip"]').forEach(
                (el) => new bootstrap.Tooltip(el)
            );
        }

        // ===== Shared: CKEditor (Add/Edit) =====
        const detail = $("#detail");
        if (detail && window.ClassicEditor) {
            ClassicEditor.create(detail).catch(console.error);
        }

        // ===== Shared: Image preview (Add/Edit) =====
        const inputImg = $("#image_path");
        const previewBox = $("#previewBox");
        if (inputImg && previewBox) {
            let imgEl = null;
            const showPreview = (file) => {
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (!imgEl) {
                        imgEl = document.createElement("img");
                        previewBox.innerHTML = "";
                        previewBox.appendChild(imgEl);
                    }
                    imgEl.src = e.target.result;
                };
                reader.readAsDataURL(file);
            };
            inputImg.addEventListener("change", () => {
                const f = inputImg.files?.[0];
                if (f) showPreview(f);
            });
        }

        // ===== Shared: Price normalize (Add/Edit) =====
        const price = $("#price");
        if (price) {
            price.addEventListener("blur", () => {
                if (price.value !== "") price.value = to2(price.value);
            });
        }

        // ===== Shared: Live Active/Inactive text (Add/Edit) =====
        const activeChk = document.querySelector(
            'input[name="is_active"][type="checkbox"]'
        );
        const statusText = $("#statusText");
        if (activeChk && statusText) {
            const render = () => {
                statusText.textContent = activeChk.checked
                    ? "Active"
                    : "Inactive";
            };
            render();
            activeChk.addEventListener("change", render);
        }

        // ===== Shared: Prevent double submit (Add/Edit) =====
        const addForm = $("#menuForm");
        const editForm = $("#menuUpdateForm");

        const lockSubmit = (form, labelHTML) => {
            if (!form) return;
            form.addEventListener("submit", () => {
                // normalize price
                if (price && price.value !== "") price.value = to2(price.value);
                const btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = labelHTML;
                }
            });
        };
        lockSubmit(
            addForm,
            '<span class="spinner-border spinner-border-sm me-1"></span> Saving...'
        );
        lockSubmit(
            editForm,
            '<span class="spinner-border spinner-border-sm me-1"></span> Updating...'
        );

        // ===== Index: Client-side filters =====
        const q = $("#q");
        const status = $("#status");
        const minPrice = $("#minPrice");
        const maxPrice = $("#maxPrice");
        const btnSearch = $("#btnSearch");
        const btnClear = $("#btnClear");
        const rows = $$(".js-menu-row");
        const noResultTable = $("#noResultTable");
        const clientInfo = $("#clientInfo");

        if (rows.length) {
            const inRange = (price, minV, maxV) => {
                if (minV !== "" && price < Number(minV)) return false;
                if (maxV !== "" && price > Number(maxV)) return false;
                return true;
            };
            const match = (
                itemSearch,
                itemStatus,
                itemPrice,
                kw,
                fStatus,
                fMin,
                fMax
            ) => {
                if (kw && !itemSearch.includes(kw)) return false;
                if (fStatus && fStatus !== itemStatus) return false;
                if (!inRange(Number(itemPrice), fMin, fMax)) return false;
                return true;
            };
            const apply = () => {
                const kw = (q?.value || "").trim().toLowerCase();
                const fStatus = (status?.value || "").toLowerCase();
                const fMin = minPrice?.value ?? "";
                const fMax = maxPrice?.value ?? "";

                let shown = 0;
                rows.forEach((tr) => {
                    const ok = match(
                        tr.dataset.search,
                        tr.dataset.status,
                        tr.dataset.price,
                        kw,
                        fStatus,
                        fMin,
                        fMax
                    );
                    tr.style.display = ok ? "" : "none";
                    if (ok) shown++;
                });

                // empty state
                if (noResultTable)
                    noResultTable.classList.toggle("d-none", shown !== 0);

                // info chip
                const hasClientFilter = !!(kw || fStatus || fMin || fMax);
                if (clientInfo) {
                    clientInfo.classList.toggle("d-none", !hasClientFilter);
                    if (hasClientFilter) {
                        const parts = [];
                        if (kw) parts.push(`q: "${kw}"`);
                        if (fStatus) parts.push(`status: ${fStatus}`);
                        if (fMin !== "") parts.push(`min: ${to2(fMin)}`);
                        if (fMax !== "") parts.push(`max: ${to2(fMax)}`);
                        clientInfo.textContent = `${shown} matched (${parts.join(
                            ", "
                        )})`;
                    }
                }
            };

            // events
            let timer = null;
            if (q) {
                q.addEventListener("input", () => {
                    clearTimeout(timer);
                    timer = setTimeout(apply, 300);
                });
                q.addEventListener("keydown", (e) => {
                    if (e.key === "Enter") {
                        e.preventDefault();
                        apply();
                    }
                });
            }
            [status, minPrice, maxPrice].forEach(
                (el) => el && el.addEventListener("change", apply)
            );
            btnSearch && btnSearch.addEventListener("click", apply);
            btnClear &&
                btnClear.addEventListener("click", () => {
                    if (q) q.value = "";
                    if (status) status.value = "";
                    if (minPrice) minPrice.value = "";
                    if (maxPrice) maxPrice.value = "";
                    apply();
                });
        }
    });
})();
