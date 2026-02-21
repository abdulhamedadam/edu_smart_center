/**
 * ProgMaker Admin Dashboard - Charts Module
 * Handles Chart.js charts and visualizations
 */

$(document).ready(function() {
    'use strict';

    // Chart colors
    const colors = {
        primary: '#6366f1',
        primaryLight: 'rgba(99, 102, 241, 0.1)',
        success: '#10b981',
        successLight: 'rgba(16, 185, 129, 0.1)',
        warning: '#f59e0b',
        warningLight: 'rgba(245, 158, 11, 0.1)',
        danger: '#ef4444',
        dangerLight: 'rgba(239, 68, 68, 0.1)',
        info: '#0ea5e9',
        infoLight: 'rgba(14, 165, 233, 0.1)',
        gray: '#94a3b8'
    };

    // Check if dark mode
    function isDarkMode() {
        return document.body.getAttribute('data-theme') === 'dark';
    }

    // Get chart text color based on theme
    function getChartTextColor() {
        return isDarkMode() ? '#94a3b8' : '#64748b';
    }

    function getGridColor() {
        return isDarkMode() ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
    }

    /**
     * Initialize Sales Chart
     */
    function initSalesChart() {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;

        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
                datasets: [{
                    label: 'المبيعات 2024',
                    data: [65000, 59000, 80000, 81000, 56000, 55000, 40000, 75000, 85000, 90000, 95000, 110000],
                    borderColor: colors.primary,
                    backgroundColor: (context) => {
                        const ctx = context.chart.ctx;
                        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
                        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
                        return gradient;
                    },
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }, {
                    label: 'المبيعات 2023',
                    data: [45000, 49000, 60000, 71000, 46000, 45000, 30000, 55000, 65000, 70000, 75000, 80000],
                    borderColor: colors.gray,
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: colors.gray,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: {
                                size: 12,
                                family: 'Segoe UI'
                            },
                            color: getChartTextColor()
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkMode() ? '#1e293b' : '#fff',
                        titleColor: isDarkMode() ? '#fff' : '#1e293b',
                        bodyColor: isDarkMode() ? '#cbd5e1' : '#475569',
                        borderColor: isDarkMode() ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: 'USD'
                                    }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: getChartTextColor(),
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: getGridColor(),
                            drawBorder: false
                        },
                        ticks: {
                            color: getChartTextColor(),
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return '$' + value / 1000 + 'k';
                            }
                        }
                    }
                }
            }
        });

        // Store chart instance for theme updates
        window.salesChart = salesChart;
    }

    /**
     * Initialize Users Chart (Doughnut)
     */
    function initUsersChart() {
        const ctx = document.getElementById('usersChart');
        if (!ctx) return;

        const usersChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['مديرون', 'محررون', 'مستخدمون', 'ضيوف'],
                datasets: [{
                    data: [15, 45, 300, 150],
                    backgroundColor: [
                        colors.danger,
                        colors.info,
                        colors.primary,
                        colors.success
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: {
                                size: 12,
                                family: 'Segoe UI'
                            },
                            color: getChartTextColor()
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkMode() ? '#1e293b' : '#fff',
                        titleColor: isDarkMode() ? '#fff' : '#1e293b',
                        bodyColor: isDarkMode() ? '#cbd5e1' : '#475569',
                        borderColor: isDarkMode() ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Store chart instance
        window.usersChart = usersChart;
    }

    /**
     * Initialize mini charts (sparklines)
     */
    function initSparklines() {
        // Can be implemented with Chart.js or a dedicated sparkline library
        // For now, we'll use simple CSS animations
    }

    /**
     * Update charts on theme change
     */
    function updateChartsTheme() {
        if (window.salesChart) {
            window.salesChart.options.plugins.legend.labels.color = getChartTextColor();
            window.salesChart.options.plugins.tooltip.backgroundColor = isDarkMode() ? '#1e293b' : '#fff';
            window.salesChart.options.plugins.tooltip.titleColor = isDarkMode() ? '#fff' : '#1e293b';
            window.salesChart.options.plugins.tooltip.bodyColor = isDarkMode() ? '#cbd5e1' : '#475569';
            window.salesChart.options.plugins.tooltip.borderColor = isDarkMode() ? '#334155' : '#e2e8f0';
            window.salesChart.options.scales.x.ticks.color = getChartTextColor();
            window.salesChart.options.scales.y.ticks.color = getChartTextColor();
            window.salesChart.options.scales.y.grid.color = getGridColor();
            window.salesChart.update();
        }

        if (window.usersChart) {
            window.usersChart.options.plugins.legend.labels.color = getChartTextColor();
            window.usersChart.options.plugins.tooltip.backgroundColor = isDarkMode() ? '#1e293b' : '#fff';
            window.usersChart.options.plugins.tooltip.titleColor = isDarkMode() ? '#fff' : '#1e293b';
            window.usersChart.options.plugins.tooltip.bodyColor = isDarkMode() ? '#cbd5e1' : '#475569';
            window.usersChart.options.plugins.tooltip.borderColor = isDarkMode() ? '#334155' : '#e2e8f0';
            window.usersChart.update();
        }
    }

    // Initialize charts
    initSalesChart();
    initUsersChart();
    initSparklines();

    // Expose update function
    window.updateChartsTheme = updateChartsTheme;

    // Listen for theme changes
    window.addEventListener('themeChange', updateChartsTheme);
});
