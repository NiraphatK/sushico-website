(function () {
    // ===== Read state from Blade-injected JSON =====
    const dataEl = document.getElementById("dashboard-data");
    const STATE = dataEl ? JSON.parse(dataEl.textContent || "{}") : {};

    const {
        visitsArr = [],
        visitsLabel = [],
        userArr = [],
        userLabel = [],
        partyArr = [],
        partyLabel = [],
        statusSeries = [],
        heatmapSeries = [],
        noShowRate = 0,
        utilRate = 0,
        reservArr = [],
        reservLabel = [],
        countReservation = 0,
        countMenu = 0,
    } = STATE;

    // ===== Theme detection for Apex =====
    const root = document.documentElement;
    const axisColor =
        getComputedStyle(root).getPropertyValue("--axis").trim() || "#64748b";
    const isDark =
        (root.getAttribute("data-bs-theme") || "light") === "light"
            ? false
            : true;

    // ===== Helpers =====
    const sliceRange = (arr, range) => {
        if (!Array.isArray(arr)) return [];
        const map = { "12m": 12, "6m": 6, "3m": 3 };
        const n = map[range] ?? arr.length;
        return arr.slice(-n);
    };

    const computeTrend = (arr) => {
        if (!arr || arr.length < 2) return { txt: "—", cls: "" };
        const a = +arr[arr.length - 2] || 0,
            b = +arr[arr.length - 1] || 0;
        const delta = b - a;
        const pct = a === 0 ? 100 : (delta / a) * 100;
        const txt = (delta >= 0 ? "↑ " : "↓ ") + Math.abs(pct).toFixed(1) + "%";
        return { txt, cls: delta >= 0 ? "trend-up" : "trend-down" };
    };

    const setTrend = (id, arr) => {
        const el = document.getElementById(id);
        if (!el) return;
        const t = computeTrend(arr);
        el.innerHTML = `<span class="badge rounded-pill ${
            t.cls ? "" : "text-bg-light"
        } ${t.cls}"
      style="background:${t.cls ? "rgba(34,197,94,.15)" : ""};">${
            t.txt
        }</span>`;
    };

    const unSkeleton = (id) => {
        const el = document.getElementById(id);
        el && el.classList.remove("is-skeleton");
    };

    // Dynamic height helper by screen size
    const h = (lg, md, sm) =>
        window.innerWidth <= 576
            ? sm ?? md ?? lg
            : window.innerWidth <= 992
            ? md ?? lg
            : lg;

    // ===== Sparkline base =====
    const sparkBase = {
        chart: {
            type: "area",
            height: 56,
            sparkline: { enabled: true },
            animations: { enabled: true },
        },
        stroke: { curve: "smooth", width: 2 },
        fill: {
            type: "gradient",
            gradient: { opacityFrom: 0.38, opacityTo: 0.05 },
        },
        tooltip: { theme: isDark ? "dark" : "light" },
    };

    let charts = {
        sparkReservations: null,
        sparkMenus: null,
        sparkUsers: null,
        sparkViews: null,
        visits: null,
        donut: null,
        party: null,
        users: null,
        heatmap: null,
        noShow: null,
        util: null,
    };

    function buildAll(range = "12m") {
        const vData = sliceRange(visitsArr, range),
            vLabel = sliceRange(visitsLabel, range);
        const uData = sliceRange(userArr, range),
            uLabel = sliceRange(userLabel, range);
        const pData = sliceRange(partyArr, range),
            pLabel = sliceRange(partyLabel, range);
        const rData = sliceRange(reservArr, range),
            rLabel = sliceRange(reservLabel, range);

        setTrend("trendReservations", rData);
        setTrend("trendUsers", uData);
        setTrend("trendViews", vData);

        // Destroy existing charts
        Object.values(charts).forEach((c) => c?.destroy());

        // Sparklines
        charts.sparkReservations = new ApexCharts(
            document.querySelector("#sparkReservations"),
            {
                ...sparkBase,
                colors: ["#38bdf8"],
                series: [{ name: "Reservations", data: rData }],
            }
        );
        charts.sparkReservations.render();

        charts.sparkMenus = new ApexCharts(
            document.querySelector("#sparkMenus"),
            {
                ...sparkBase,
                colors: ["#8b5cf6"],
                series: [
                    {
                        name: "Menus",
                        data: Array(Math.max(vData.length, 1)).fill(countMenu),
                    },
                ],
            }
        );
        charts.sparkMenus.render();

        charts.sparkUsers = new ApexCharts(
            document.querySelector("#sparkUsers"),
            {
                ...sparkBase,
                colors: ["#22c55e"],
                series: [{ name: "Users", data: uData }],
            }
        );
        charts.sparkUsers.render();

        charts.sparkViews = new ApexCharts(
            document.querySelector("#sparkViews"),
            {
                ...sparkBase,
                colors: ["#f59e0b"],
                series: [{ name: "Views", data: vData }],
            }
        );
        charts.sparkViews.render();

        // Visits (area)
        charts.visits = new ApexCharts(document.querySelector("#visitsChart"), {
            chart: {
                type: "area",
                height: h(330, 280, 230),
                toolbar: { show: false },
                foreColor: axisColor,
                background: "transparent",
                animations: { enabled: true },
                theme: { mode: isDark ? "dark" : "light" },
                events: { mounted: () => unSkeleton("visitsChart") },
            },
            colors: ["#8b5cf6"],
            stroke: { curve: "smooth", width: 3 },
            series: [{ name: "Visits", data: vData }],
            xaxis: {
                categories: vLabel,
                labels: { rotate: -15 },
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            yaxis: { labels: { formatter: (v) => Math.round(v) } },
            grid: { borderColor: "rgba(148,163,184,.22)" },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 0.3,
                    opacityFrom: 0.45,
                    opacityTo: 0.08,
                    stops: [0, 90, 100],
                },
            },
            tooltip: { theme: isDark ? "dark" : "light" },
            responsive: [
                { breakpoint: 1400, options: { chart: { height: 300 } } },
                {
                    breakpoint: 992,
                    options: {
                        chart: { height: 280 },
                        xaxis: { labels: { rotate: -10 } },
                    },
                },
                {
                    breakpoint: 768,
                    options: {
                        chart: { height: 250 },
                        xaxis: { tickAmount: 6 },
                    },
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: { height: 230 },
                        xaxis: { tickAmount: 4, labels: { rotate: -5 } },
                    },
                },
            ],
        });
        charts.visits.render();

        // Donut: Reservation status
        charts.donut = new ApexCharts(
            document.querySelector("#reservationStatusChart"),
            {
                chart: {
                    type: "donut",
                    height: h(300, 260, 210),
                    foreColor: axisColor,
                    background: "transparent",
                    theme: { mode: isDark ? "dark" : "light" },
                    events: {
                        mounted: () => unSkeleton("reservationStatusChart"),
                    },
                },
                labels: [
                    "Confirmed",
                    "Seated",
                    "Completed",
                    "Cancelled",
                    "No Show",
                ],
                series: statusSeries,
                colors: ["#36d1dc", "#22c55e", "#9ca3af", "#ef4444", "#6b7280"],
                legend: { position: "bottom", fontSize: "12px" },
                dataLabels: { enabled: false },
                tooltip: { theme: isDark ? "dark" : "light" },
                plotOptions: {
                    pie: {
                        donut: {
                            size: "74%",
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: "Total",
                                    formatter: () => countReservation,
                                },
                            },
                        },
                    },
                },
                responsive: [
                    { breakpoint: 1200, options: { chart: { height: 280 } } },
                    {
                        breakpoint: 992,
                        options: {
                            chart: { height: 260 },
                            legend: { fontSize: "11px" },
                        },
                    },
                    {
                        breakpoint: 576,
                        options: {
                            chart: { height: 210 },
                            legend: { fontSize: "10px" },
                        },
                    },
                ],
            }
        );
        charts.donut.render();

        // Party size (bar)
        charts.party = new ApexCharts(
            document.querySelector("#partySizeChart"),
            {
                chart: {
                    type: "bar",
                    height: h(320, 290, 240),
                    foreColor: axisColor,
                    background: "transparent",
                    theme: { mode: isDark ? "dark" : "light" },
                    events: { mounted: () => unSkeleton("partySizeChart") },
                },
                series: [{ name: "Avg Party Size", data: pData }],
                xaxis: {
                    categories: pLabel,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                colors: ["#f59e0b"],
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: "45%",
                        dataLabels: { position: "top" },
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: (v) => (v?.toFixed ? v.toFixed(1) : v),
                },
                tooltip: { theme: isDark ? "dark" : "light" },
                grid: { strokeDashArray: 3 },
                responsive: [
                    { breakpoint: 992, options: { chart: { height: 290 } } },
                    { breakpoint: 768, options: { chart: { height: 270 } } },
                    {
                        breakpoint: 576,
                        options: {
                            chart: { height: 240 },
                            dataLabels: { enabled: false },
                        },
                    },
                ],
            }
        );
        charts.party.render();

        // User growth (bar)
        charts.users = new ApexCharts(document.querySelector("#userChart"), {
            chart: {
                type: "bar",
                height: h(320, 290, 240),
                foreColor: axisColor,
                background: "transparent",
                theme: { mode: isDark ? "dark" : "light" },
                events: { mounted: () => unSkeleton("userChart") },
            },
            series: [{ name: "Users", data: uData }],
            xaxis: {
                categories: uLabel,
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            colors: ["#22c55e"],
            plotOptions: { bar: { borderRadius: 10, columnWidth: "45%" } },
            dataLabels: { enabled: false },
            tooltip: { theme: isDark ? "dark" : "light" },
            responsive: [
                { breakpoint: 992, options: { chart: { height: 290 } } },
                { breakpoint: 768, options: { chart: { height: 270 } } },
                { breakpoint: 576, options: { chart: { height: 240 } } },
            ],
        });
        charts.users.render();

        // Heatmap
        charts.heatmap = new ApexCharts(
            document.querySelector("#peakHeatmap"),
            {
                chart: {
                    type: "heatmap",
                    height: h(350, 310, 260),
                    foreColor: axisColor,
                    background: "transparent",
                    theme: { mode: isDark ? "dark" : "light" },
                    events: { mounted: () => unSkeleton("peakHeatmap") },
                },
                dataLabels: { enabled: false },
                plotOptions: {
                    heatmap: {
                        shadeIntensity: 0.45,
                        radius: 8,
                        colorScale: {
                            ranges: [
                                {
                                    from: 0,
                                    to: 0,
                                    color: isDark ? "#1f2937" : "#e5e7eb",
                                    name: "0",
                                },
                                { from: 1, to: 2, color: "#c7d2fe" },
                                { from: 3, to: 5, color: "#93c5fd" },
                                { from: 6, to: 9, color: "#60a5fa" },
                                { from: 10, to: 14, color: "#3b82f6" },
                                { from: 15, to: 9999, color: "#1d4ed8" },
                            ],
                        },
                    },
                },
                series: heatmapSeries,
                xaxis: { type: "category", labels: { rotate: -45 } },
                tooltip: { theme: isDark ? "dark" : "light" },
                responsive: [
                    {
                        breakpoint: 992,
                        options: {
                            chart: { height: 310 },
                            xaxis: { labels: { rotate: -30 } },
                        },
                    },
                    {
                        breakpoint: 576,
                        options: {
                            chart: { height: 260 },
                            xaxis: { labels: { rotate: -15 } },
                        },
                    },
                ],
            }
        );
        charts.heatmap.render();

        // Gauge: No-Show
        charts.noShow = new ApexCharts(document.querySelector("#noShowGauge"), {
            chart: {
                type: "radialBar",
                height: h(260, 220, 180),
                foreColor: axisColor,
                background: "transparent",
                theme: { mode: isDark ? "dark" : "light" },
                events: { mounted: () => unSkeleton("noShowGauge") },
            },
            series: [Math.max(0, Math.min(100, noShowRate))],
            colors: ["#ff7b72"],
            plotOptions: {
                radialBar: {
                    hollow: { size: "58%" },
                    track: { background: isDark ? "#0f172a" : "#f1f5f9" },
                    dataLabels: {
                        name: {
                            show: true,
                            offsetY: 12,
                            formatter: () => "No-Show",
                        },
                        value: {
                            show: true,
                            fontSize: "20px",
                            formatter: (v) =>
                                (typeof v === "number" ? v.toFixed(1) : v) +
                                "%",
                        },
                    },
                },
            },
            stroke: { lineCap: "round" },
            responsive: [
                { breakpoint: 576, options: { chart: { height: 180 } } },
            ],
        });
        charts.noShow.render();

        // Gauge: Utilization
        charts.util = new ApexCharts(document.querySelector("#utilGauge"), {
            chart: {
                type: "radialBar",
                height: h(260, 220, 180),
                foreColor: axisColor,
                background: "transparent",
                theme: { mode: isDark ? "dark" : "light" },
                events: { mounted: () => unSkeleton("utilGauge") },
            },
            series: [Math.max(0, Math.min(100, utilRate))],
            colors: ["#22c55e"],
            plotOptions: {
                radialBar: {
                    hollow: { size: "58%" },
                    track: { background: isDark ? "#0f172a" : "#f1f5f9" },
                    dataLabels: {
                        name: {
                            show: true,
                            offsetY: 12,
                            formatter: () => "Utilization",
                        },
                        value: {
                            show: true,
                            fontSize: "20px",
                            formatter: (v) =>
                                (typeof v === "number" ? v.toFixed(1) : v) +
                                "%",
                        },
                    },
                },
            },
            stroke: { lineCap: "round" },
            responsive: [
                { breakpoint: 576, options: { chart: { height: 180 } } },
            ],
        });
        charts.util.render();
    }

    // ===== Range switcher =====
    document.addEventListener("DOMContentLoaded", function () {
        const rangeOrder = ["12m", "6m", "3m"];
        const $group = document.getElementById("rangeSwitcher");
        if (!$group) {
            buildAll("12m");
            return;
        }

        function setActiveRange(range) {
            const btns = [...$group.querySelectorAll(".seg-btn")];
            btns.forEach((btn) => {
                const on = btn.dataset.range === range;
                btn.classList.toggle("active", on);
                btn.setAttribute("aria-pressed", String(on));
            });
            const idx = Math.max(0, rangeOrder.indexOf(range));
            $group.dataset.index = idx;
            buildAll(range);
        }

        (function initRange() {
            const current =
                $group.querySelector(".seg-btn.active")?.dataset.range || "12m";
            $group.dataset.index = Math.max(0, rangeOrder.indexOf(current));
            [...$group.querySelectorAll(".seg-btn")].forEach((btn) => {
                btn.setAttribute(
                    "aria-pressed",
                    String(btn.dataset.range === current)
                );
            });
            buildAll(current);
        })();

        $group.addEventListener("click", (e) => {
            const btn = e.target.closest(".seg-btn[data-range]");
            if (!btn) return;
            setActiveRange(btn.dataset.range);
        });

        document.getElementById("btnRefresh")?.addEventListener("click", () => {
            const current =
                $group.querySelector(".seg-btn.active")?.dataset.range || "12m";
            buildAll(current);
        });

        let __rz;
        window.addEventListener("resize", () => {
            clearTimeout(__rz);
            __rz = setTimeout(() => {
                const active =
                    $group.querySelector(".seg-btn.active")?.dataset.range ||
                    "12m";
                buildAll(active);
            }, 180);
        });
    });
})();
