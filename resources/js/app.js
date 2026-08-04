import Swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import Chart from "chart.js/auto";

window.Swal = Swal;

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector("[data-sidebar]");
    const mainShell = document.querySelector("[data-main-shell]");
    const sidebarToggle = document.querySelector("[data-sidebar-toggle]");
    const sidebarOverlay = document.querySelector("[data-sidebar-overlay]");
    const sidebarCloseButtons = document.querySelectorAll(
        "[data-sidebar-close]",
    );
    const sidebarCollapse = document.querySelector("[data-sidebar-collapse]");
    const collapseIcon = document.querySelector("[data-collapse-icon]");
    const sidebarLabels = document.querySelectorAll("[data-sidebar-label]");
    const navLinks = document.querySelectorAll("[data-nav-link]");
    const menuSearch = document.querySelector("[data-menu-search]");
    const navItems = document.querySelectorAll("[data-nav-item]");
    const userMenuToggle = document.querySelector("[data-user-menu-toggle]");
    const userMenu = document.querySelector("[data-user-menu]");
    const notificationToggle = document.querySelector(
        "[data-notification-toggle]",
    );
    const notificationMenu = document.querySelector("[data-notification-menu]");
    const logoutForms = document.querySelectorAll("[data-logout-form]");
    const flash = document.getElementById("app-flash");

    let sidebarCollapsed = false;

    try {
        sidebarCollapsed =
            localStorage.getItem("space-sidebar-collapsed") === "1";
    } catch (error) {
        sidebarCollapsed = false;
    }

    const openSidebar = () => {
        if (!sidebar || !sidebarOverlay) return;
        sidebar.classList.remove("-translate-x-full");
        sidebarOverlay.classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
    };

    const closeSidebar = () => {
        if (!sidebar || !sidebarOverlay) return;
        sidebar.classList.add("-translate-x-full");
        sidebarOverlay.classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
    };

    const renderSidebar = () => {
        if (!sidebar || !mainShell) return;

        const desktop = window.innerWidth >= 1024;
        const collapsed = desktop && sidebarCollapsed;

        sidebar.classList.toggle("w-20", collapsed);
        sidebar.classList.toggle("w-64", !collapsed);
        mainShell.classList.toggle("lg:pl-20", collapsed);
        mainShell.classList.toggle("lg:pl-64", !collapsed);

        sidebarLabels.forEach((label) => {
            label.classList.toggle("hidden", collapsed);
        });

        navLinks.forEach((link) => {
            link.classList.toggle("justify-center", collapsed);
        });

        collapseIcon?.classList.toggle("rotate-180", collapsed);
    };

    const closeMenus = () => {
        userMenu?.classList.add("hidden");
        notificationMenu?.classList.add("hidden");
        userMenuToggle?.setAttribute("aria-expanded", "false");
    };

    sidebarToggle?.addEventListener("click", openSidebar);
    sidebarOverlay?.addEventListener("click", closeSidebar);
    sidebarCloseButtons.forEach((button) =>
        button.addEventListener("click", closeSidebar),
    );

    sidebarCollapse?.addEventListener("click", () => {
        sidebarCollapsed = !sidebarCollapsed;

        try {
            localStorage.setItem(
                "space-sidebar-collapsed",
                sidebarCollapsed ? "1" : "0",
            );
        } catch (error) {}

        renderSidebar();
    });

    userMenuToggle?.addEventListener("click", (event) => {
        event.stopPropagation();
        notificationMenu?.classList.add("hidden");
        userMenu?.classList.toggle("hidden");
        userMenuToggle.setAttribute(
            "aria-expanded",
            String(!userMenu?.classList.contains("hidden")),
        );
    });

    notificationToggle?.addEventListener("click", (event) => {
        event.stopPropagation();
        userMenu?.classList.add("hidden");
        notificationMenu?.classList.toggle("hidden");
    });

    document.addEventListener("click", (event) => {
        if (
            userMenu &&
            !userMenu.contains(event.target) &&
            !userMenuToggle?.contains(event.target)
        ) {
            userMenu.classList.add("hidden");
        }

        if (
            notificationMenu &&
            !notificationMenu.contains(event.target) &&
            !notificationToggle?.contains(event.target)
        ) {
            notificationMenu.classList.add("hidden");
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeSidebar();
            closeMenus();
        }
    });

    window.addEventListener("resize", () => {
        if (window.innerWidth >= 1024) {
            sidebarOverlay?.classList.add("hidden");
            document.body.classList.remove("overflow-hidden");
        }
        renderSidebar();
    });

    menuSearch?.addEventListener("input", () => {
        const keyword = menuSearch.value.trim().toLowerCase();

        navItems.forEach((item) => {
            const label = item.textContent.trim().toLowerCase();
            item.classList.toggle(
                "hidden",
                keyword !== "" && !label.includes(keyword),
            );
        });
    });

    logoutForms.forEach((form) => {
        form.addEventListener("submit", async (event) => {
            if (form.dataset.confirmed === "true") return;

            event.preventDefault();

            const result = await Swal.fire({
                icon: "question",
                title: "Keluar dari Sistem?",
                text: "Sesi login administrator akan diakhiri.",
                showCancelButton: true,
                confirmButtonText: "Ya, Keluar",
                cancelButtonText: "Batal",
                confirmButtonColor: "#dc2626",
                cancelButtonColor: "#64748b",
                reverseButtons: true,
                focusCancel: true,
            });

            if (result.isConfirmed) {
                form.dataset.confirmed = "true";
                form.submit();
            }
        });
    });

    if (flash) {
        const messages = [
            {
                type: "success",
                value: flash.dataset.success,
                title: "Berhasil",
            },
            {
                type: "error",
                value: flash.dataset.error,
                title: "Proses Gagal",
            },
            {
                type: "warning",
                value: flash.dataset.warning,
                title: "Perhatian",
            },
            { type: "info", value: flash.dataset.info, title: "Informasi" },
        ];

        const message = messages.find((item) => item.value);

        if (message) {
            Swal.fire({
                icon: message.type,
                title: message.title,
                text: message.value,
                confirmButtonText: "Mengerti",
                confirmButtonColor: "#2563eb",
                timer: message.type === "success" ? 2200 : undefined,
                timerProgressBar: message.type === "success",
            });
        }
    }

   const overviewCanvas = document.querySelector("[data-overview-chart]");

if (overviewCanvas) {
    const overviewPanel = overviewCanvas.closest("[data-overview-panel]");
    const loadingElement = overviewPanel?.querySelector(
        "[data-overview-loading]",
    );
    const filterButtons = overviewPanel?.querySelectorAll(
        "[data-overview-filter]",
    );
    const replayButton = overviewPanel?.querySelector(
        "[data-overview-replay]",
    );
    const detailContent = overviewPanel?.querySelector(
        "[data-overview-detail-content]",
    );
    const detailLabel = overviewPanel?.querySelector(
        "[data-overview-detail-label]",
    );
    const detailAvailable = overviewPanel?.querySelector(
        "[data-overview-detail-available]",
    );
    const detailTarget = overviewPanel?.querySelector(
        "[data-overview-detail-target]",
    );
    const detailPercentage = overviewPanel?.querySelector(
        "[data-overview-detail-percentage]",
    );
    const detailProgress = overviewPanel?.querySelector(
        "[data-overview-detail-progress]",
    );
    const detailStatus = overviewPanel?.querySelector(
        "[data-overview-detail-status]",
    );

    const labels = JSON.parse(overviewCanvas.dataset.labels || "[]");
    const values = JSON.parse(overviewCanvas.dataset.values || "[]").map(
        Number,
    );
    const targets = JSON.parse(overviewCanvas.dataset.targets || "[]").map(
        Number,
    );

    let overviewChart = null;
    let selectedIndex = 0;
    let activeFilter = "all";
    let initialized = false;

    const formatNumber = (value) =>
        new Intl.NumberFormat("id-ID").format(Number(value || 0));

    const calculatePercentage = (available, target) => {
        if (target <= 0) {
            return available > 0 ? 100 : 0;
        }

        return Math.min(
            100,
            Math.round((available / target) * 100),
        );
    };

    const updateDetail = (index, animate = true) => {
        if (!labels.length) {
            return;
        }

        const safeIndex = Math.max(
            0,
            Math.min(index, labels.length - 1),
        );

        selectedIndex = safeIndex;

        const label = labels[safeIndex] || "-";
        const available = Number(values[safeIndex] || 0);
        const target = Number(targets[safeIndex] || 0);
        const percentage = calculatePercentage(
            available,
            target,
        );
        const difference = target - available;

        const renderDetail = () => {
            if (detailLabel) {
                detailLabel.textContent = label;
            }

            if (detailAvailable) {
                detailAvailable.textContent =
                    formatNumber(available);
            }

            if (detailTarget) {
                detailTarget.textContent =
                    formatNumber(target);
            }

            if (detailPercentage) {
                detailPercentage.textContent =
                    `${percentage}%`;
            }

            if (detailStatus) {
                detailStatus.textContent =
                    difference <= 0
                        ? "Target untuk data ini telah tercapai."
                        : `${formatNumber(difference)} data lagi diperlukan untuk mencapai target.`;
            }

            if (detailProgress) {
                detailProgress.style.width = "0%";

                requestAnimationFrame(() => {
                    detailProgress.style.width =
                        `${percentage}%`;
                });
            }
        };

        if (!animate || !detailContent) {
            renderDetail();
            return;
        }

        detailContent.classList.add("is-changing");

        window.setTimeout(() => {
            renderDetail();
            detailContent.classList.remove("is-changing");
        }, 170);
    };

    const createOverviewChart = () => {
        const existingChart = Chart.getChart(overviewCanvas);

        if (existingChart) {
            existingChart.destroy();
        }

        const context = overviewCanvas.getContext("2d");
        const availableGradient = context.createLinearGradient(
            0,
            0,
            0,
            350,
        );
        const targetGradient = context.createLinearGradient(
            0,
            0,
            0,
            350,
        );

        availableGradient.addColorStop(
            0,
            "rgba(37, 99, 235, 1)",
        );
        availableGradient.addColorStop(
            1,
            "rgba(96, 165, 250, 0.72)",
        );

        targetGradient.addColorStop(
            0,
            "rgba(245, 158, 11, 1)",
        );
        targetGradient.addColorStop(
            1,
            "rgba(253, 224, 71, 0.72)",
        );

        overviewChart = new Chart(overviewCanvas, {
            type: "bar",
            data: {
                labels,
                datasets: [
                    {
                        label: "Tersedia",
                        data: values,
                        backgroundColor: availableGradient,
                        hoverBackgroundColor: "#1d4ed8",
                        borderColor: "#2563eb",
                        hoverBorderColor: "#1e40af",
                        borderWidth: 0,
                        hoverBorderWidth: 2,
                        borderRadius: 7,
                        borderSkipped: false,
                        maxBarThickness: 28,
                        categoryPercentage: 0.7,
                        barPercentage: 0.78,
                    },
                    {
                        label: "Target",
                        data: targets,
                        backgroundColor: targetGradient,
                        hoverBackgroundColor: "#f59e0b",
                        borderColor: "#fbbf24",
                        hoverBorderColor: "#d97706",
                        borderWidth: 0,
                        hoverBorderWidth: 2,
                        borderRadius: 7,
                        borderSkipped: false,
                        maxBarThickness: 28,
                        categoryPercentage: 0.7,
                        barPercentage: 0.78,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                resizeDelay: 150,
                interaction: {
                    mode: "index",
                    intersect: false,
                },
                animation: {
                    duration: 1400,
                    easing: "easeOutQuart",
                    delay: (context) => {
                        if (context.type !== "data") {
                            return 0;
                        }

                        return (
                            context.dataIndex * 150 +
                            context.datasetIndex * 90
                        );
                    },
                },
                transitions: {
                    active: {
                        animation: {
                            duration: 250,
                        },
                    },
                    show: {
                        animations: {
                            x: {
                                from: 0,
                            },
                            y: {
                                from: 0,
                            },
                        },
                    },
                    hide: {
                        animations: {
                            x: {
                                to: 0,
                            },
                            y: {
                                to: 0,
                            },
                        },
                    },
                },
                onHover: (event, elements) => {
                    const target = event.native?.target;

                    if (target) {
                        target.style.cursor =
                            elements.length > 0
                                ? "pointer"
                                : "default";
                    }

                    if (elements.length > 0) {
                        updateDetail(
                            elements[0].index,
                            false,
                        );
                    }
                },
                onClick: (event, elements) => {
                    if (elements.length === 0) {
                        return;
                    }

                    updateDetail(
                        elements[0].index,
                        true,
                    );
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: "rgba(15, 23, 42, 0.96)",
                        titleColor: "#ffffff",
                        bodyColor: "#e2e8f0",
                        borderColor: "rgba(148, 163, 184, 0.25)",
                        borderWidth: 1,
                        titleFont: {
                            size: 13,
                            weight: "600",
                        },
                        bodyFont: {
                            size: 12,
                        },
                        padding: 12,
                        cornerRadius: 9,
                        displayColors: true,
                        boxPadding: 5,
                        usePointStyle: true,
                        callbacks: {
                            title: (items) =>
                                items[0]?.label || "-",
                            label: (context) => {
                                const value =
                                    context.parsed.y || 0;

                                return `${context.dataset.label}: ${formatNumber(value)}`;
                            },
                            afterBody: (items) => {
                                const index =
                                    items[0]?.dataIndex ?? 0;
                                const available = Number(
                                    values[index] || 0,
                                );
                                const target = Number(
                                    targets[index] || 0,
                                );
                                const percentage =
                                    calculatePercentage(
                                        available,
                                        target,
                                    );

                                return `Pencapaian: ${percentage}%`;
                            },
                            footer: () =>
                                "Klik untuk menampilkan detail",
                        },
                    },
                },
                scales: {
                    x: {
                        stacked: false,
                        grid: {
                            display: false,
                        },
                        border: {
                            display: false,
                        },
                        ticks: {
                            color: "#64748b",
                            padding: 10,
                            font: {
                                size: 11,
                                weight: "600",
                            },
                        },
                    },
                    y: {
                        beginAtZero: true,
                        grace: "12%",
                        border: {
                            display: false,
                        },
                        grid: {
                            color: "rgba(226, 232, 240, 0.8)",
                            drawTicks: false,
                        },
                        ticks: {
                            precision: 0,
                            color: "#94a3b8",
                            padding: 10,
                            font: {
                                size: 11,
                            },
                            callback: (value) =>
                                formatNumber(value),
                        },
                    },
                },
            },
        });

        if (activeFilter === "available") {
            overviewChart.setDatasetVisibility(
                0,
                true,
            );
            overviewChart.setDatasetVisibility(
                1,
                false,
            );
            overviewChart.update();
        }

        if (activeFilter === "target") {
            overviewChart.setDatasetVisibility(
                0,
                false,
            );
            overviewChart.setDatasetVisibility(
                1,
                true,
            );
            overviewChart.update();
        }

        updateDetail(selectedIndex, false);

        window.setTimeout(() => {
            loadingElement?.classList.add("is-hidden");
        }, 300);
    };

    const initializeOverviewChart = () => {
        if (initialized) {
            return;
        }

        initialized = true;
        createOverviewChart();
    };

    if ("IntersectionObserver" in window && overviewPanel) {
        const overviewObserver = new IntersectionObserver(
            (entries, observer) => {
                const entry = entries[0];

                if (!entry?.isIntersecting) {
                    return;
                }

                initializeOverviewChart();
                observer.disconnect();
            },
            {
                threshold: 0.22,
            },
        );

        overviewObserver.observe(overviewPanel);
    } else {
        initializeOverviewChart();
    }

    filterButtons?.forEach((button) => {
        button.addEventListener("click", () => {
            const filter =
                button.dataset.overviewFilter || "all";

            activeFilter = filter;

            filterButtons.forEach((filterButton) => {
                const active =
                    filterButton === button;

                filterButton.classList.toggle(
                    "is-active",
                    active,
                );

                filterButton.setAttribute(
                    "aria-pressed",
                    active ? "true" : "false",
                );
            });

            if (!overviewChart) {
                return;
            }

            overviewChart.setDatasetVisibility(
                0,
                filter === "all" ||
                    filter === "available",
            );

            overviewChart.setDatasetVisibility(
                1,
                filter === "all" ||
                    filter === "target",
            );

            overviewChart.update();
        });
    });

    replayButton?.addEventListener("click", () => {
        if (!overviewChart) {
            initializeOverviewChart();
            return;
        }

        replayButton.classList.add("is-replaying");
        loadingElement?.classList.remove("is-hidden");

        overviewChart.reset();
        overviewChart.update();

        window.setTimeout(() => {
            replayButton.classList.remove(
                "is-replaying",
            );

            loadingElement?.classList.add("is-hidden");
        }, 700);
    });
}

    if (overviewCanvas) {
        const labels = JSON.parse(overviewCanvas.dataset.labels || "[]");
        const values = JSON.parse(overviewCanvas.dataset.values || "[]");
        const targets = JSON.parse(overviewCanvas.dataset.targets || "[]");

        new Chart(overviewCanvas, {
            type: "bar",
            data: {
                labels,
                datasets: [
                    {
                        label: "Tersedia",
                        data: values,
                        backgroundColor: "#2563eb",
                        borderRadius: 3,
                        borderSkipped: false,
                        maxBarThickness: 18,
                    },
                    {
                        label: "Target",
                        data: targets,
                        backgroundColor: "#fbbf24",
                        borderRadius: 3,
                        borderSkipped: false,
                        maxBarThickness: 18,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: "index",
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: "#0f172a",
                        titleFont: { size: 11 },
                        bodyFont: { size: 11 },
                        padding: 10,
                        cornerRadius: 6,
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        border: {
                            display: false,
                        },
                        ticks: {
                            color: "#94a3b8",
                            font: { size: 10 },
                        },
                    },
                    y: {
                        beginAtZero: true,
                        border: {
                            display: false,
                        },
                        grid: {
                            color: "#eef2f7",
                        },
                        ticks: {
                            precision: 0,
                            color: "#94a3b8",
                            font: { size: 10 },
                        },
                    },
                },
            },
        });
    }

    const readinessCanvas = document.querySelector("[data-readiness-chart]");

    if (readinessCanvas) {
        const labels = JSON.parse(readinessCanvas.dataset.labels || "[]");
        const values = JSON.parse(readinessCanvas.dataset.values || "[]");

        new Chart(readinessCanvas, {
            type: "line",
            data: {
                labels,
                datasets: [
                    {
                        data: values,
                        borderColor: "#2563eb",
                        backgroundColor: "rgba(37,99,235,0.08)",
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 2.5,
                        pointHoverRadius: 4,
                        pointBackgroundColor: "#ffffff",
                        pointBorderColor: "#2563eb",
                        pointBorderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: "#0f172a",
                        callbacks: {
                            label: (context) => `${context.parsed.y}%`,
                        },
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        border: {
                            display: false,
                        },
                        ticks: {
                            color: "#94a3b8",
                            font: { size: 9 },
                        },
                    },
                    y: {
                        beginAtZero: true,
                        max: 100,
                        border: {
                            display: false,
                        },
                        grid: {
                            color: "#eef2f7",
                        },
                        ticks: {
                            stepSize: 25,
                            color: "#94a3b8",
                            font: { size: 9 },
                            callback: (value) => `${value}%`,
                        },
                    },
                },
            },
        });
    }

    renderSidebar();
});
