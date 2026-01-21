
// Statistics Page JavaScript

let currentPeriod = 'month';
let charts = {};

// Sample data for different periods
const data = {
    month: {
        labels: ['Yan', 'Fev', 'Mar', 'Apr', 'May', 'Iyun', 'Iyul', 'Avg', 'Sen', 'Okt', 'Noy', 'Dek'],
        revenue: [8.5, 9.2, 10.1, 9.8, 11.2, 10.5, 11.8, 12.1, 10.9, 11.5, 10.8, 9.1],
        expenses: [6.2, 6.8, 7.5, 7.2, 8.1, 7.8, 8.5, 8.9, 8.2, 8.6, 8.1, 7.3],
        profit: [2.3, 2.4, 2.6, 2.6, 3.1, 2.7, 3.3, 3.2, 2.7, 2.9, 2.7, 1.8]
    },
    quarter: {
        labels: ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025'],
        revenue: [27.8, 31.5, 34.8, 31.4],
        expenses: [20.5, 23.1, 25.6, 23.0],
        profit: [7.3, 8.4, 9.2, 8.4]
    },
    year: {
        labels: ['2022', '2023', '2024', '2025'],
        revenue: [85.2, 98.5, 112.3, 125.5],
        expenses: [65.8, 72.4, 81.2, 89.2],
        profit: [19.4, 26.1, 31.1, 36.3]
    }
};

// Initialize charts
function initCharts() {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#e2e8f0' : '#1e293b';
    const gridColor = isDark ? '#334155' : '#e2e8f0';

    // Revenue vs Expenses Chart
    const ctx1 = document.getElementById('revenueExpensesChart').getContext('2d');
    charts.revenueExpenses = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: data[currentPeriod].labels,
            datasets: [
                {
                    label: 'Kirim',
                    data: data[currentPeriod].revenue,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                },
                {
                    label: 'Chiqim',
                    data: data[currentPeriod].expenses,
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: textColor,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + 'M so\'m';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: textColor,
                        callback: function(value) {
                            return value + 'M';
                        }
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    ticks: {
                        color: textColor
                    },
                    grid: {
                        color: gridColor
                    }
                }
            }
        }
    });

    // Profit Trend Chart
    const ctx2 = document.getElementById('profitTrendChart').getContext('2d');
    charts.profitTrend = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: data[currentPeriod].labels,
            datasets: [{
                label: 'Foyda',
                data: data[currentPeriod].profit,
                backgroundColor: 'rgba(34, 197, 94, 0.2)',
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgba(34, 197, 94, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: textColor,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Foyda: ' + context.parsed.y + 'M so\'m';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: textColor,
                        callback: function(value) {
                            return value + 'M';
                        }
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    ticks: {
                        color: textColor
                    },
                    grid: {
                        color: gridColor
                    }
                }
            }
        }
    });

    // Expenses Pie Chart
    const ctx3 = document.getElementById('expensesPieChart').getContext('2d');
    charts.expensesPie = new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: ['Ish haqi', 'Operatsion', 'Marketing', 'Boshqa'],
            datasets: [{
                data: [39.5, 30.0, 17.5, 13.0],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(234, 179, 8, 0.8)',
                    'rgba(34, 197, 94, 0.8)'
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(239, 68, 68, 1)',
                    'rgba(234, 179, 8, 1)',
                    'rgba(34, 197, 94, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: textColor,
                        font: {
                            size: 12,
                            weight: 'bold'
                        },
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            }
        }
    });
}

// Update period
function updatePeriod(period) {
    currentPeriod = period;
    
    // Update button styles
    ['month', 'quarter', 'year'].forEach(p => {
        const btn = document.getElementById('btn-' + p);
        if (p === period) {
            btn.classList.remove('bg-slate-200', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300');
            btn.classList.add('bg-blue-500', 'text-white');
        } else {
            btn.classList.remove('bg-blue-500', 'text-white');
            btn.classList.add('bg-slate-200', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300');
        }
    });
    
    // Update charts
    updateCharts();
}

// Update charts with new data
function updateCharts() {
    // Update Revenue vs Expenses Chart
    charts.revenueExpenses.data.labels = data[currentPeriod].labels;
    charts.revenueExpenses.data.datasets[0].data = data[currentPeriod].revenue;
    charts.revenueExpenses.data.datasets[1].data = data[currentPeriod].expenses;
    charts.revenueExpenses.update();
    
    // Update Profit Trend Chart
    charts.profitTrend.data.labels = data[currentPeriod].labels;
    charts.profitTrend.data.datasets[0].data = data[currentPeriod].profit;
    charts.profitTrend.update();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initCharts();
    
    // Watch for theme changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                // Destroy and recreate charts with new theme
                Object.values(charts).forEach(chart => chart.destroy());
                initCharts();
            }
        });
    });
    
    observer.observe(document.documentElement, {
        attributes: true
    });
});