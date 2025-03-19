@extends('layouts.admin')

@section('styles')
<style>
    .dashboard-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }
    
    .dashboard-card .card-body {
        padding: 1.5rem;
    }
    
    .dashboard-card .card-title {
        font-size: 1rem;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .dashboard-card .card-text {
        font-size: 2rem;
        font-weight: 700;
        color: #2563eb;
        margin-bottom: 0;
    }
    
    .dashboard-card .icon {
        font-size: 2.5rem;
        color: rgba(37, 99, 235, 0.1);
    }
    
    .chart-container {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: none;
    }
    
    .chart-container.active {
        display: block;
    }
    
    .chart-nav {
        display: flex;
        overflow-x: auto;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        gap: 0.5rem;
    }
    
    .chart-nav-item {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        background-color: #f8f9fa;
        color: #6c757d;
        font-weight: 500;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    
    .chart-nav-item:hover {
        background-color: #e9ecef;
    }
    
    .chart-nav-item.active {
        background-color: #2563eb;
        color: white;
    }
    
    .brand-revenue-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }
    
    .brand-revenue-card .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem 1.5rem;
    }
    
    .brand-revenue-card .card-body {
        padding: 1.5rem;
    }
    
    .brand-revenue-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .brand-revenue-item:last-child {
        border-bottom: none;
    }
    
    .brand-revenue-item .brand-name {
        font-weight: 500;
    }
    
    .brand-revenue-item .revenue {
        font-weight: 600;
        color: #2563eb;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">{{ __('messages.admin_dashboard') }}</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary">
                <i class="fas fa-download me-2"></i> {{ __('messages.export_report') }}
            </button>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="timeRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-calendar me-2"></i> {{ __('messages.last_30_days') }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="timeRangeDropdown">
                    <li><a class="dropdown-item" href="#">{{ __('messages.today') }}</a></li>
                    <li><a class="dropdown-item" href="#">{{ __('messages.last_7_days') }}</a></li>
                    <li><a class="dropdown-item" href="#">{{ __('messages.last_30_days') }}</a></li>
                    <li><a class="dropdown-item" href="#">{{ __('messages.this_month') }}</a></li>
                    <li><a class="dropdown-item" href="#">{{ __('messages.this_year') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card dashboard-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">{{ __('messages.total_revenue') }}</h5>
                        <p class="card-text">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">{{ __('messages.total_orders') }}</h5>
                        <p class="card-text">{{ $totalOrders }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">{{ __('messages.total_customers') }}</h5>
                        <p class="card-text">{{ $totalCustomers }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title">{{ __('messages.total_products') }}</h5>
                        <p class="card-text">{{ $totalProducts }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Chart Navigation -->
    <div class="chart-nav">
        <div class="chart-nav-item active" data-target="categoryRevenueChart">{{ __('messages.revenue_by_brand') }}</div>
        <div class="chart-nav-item" data-target="revenueByDateChart">{{ __('messages.daily_revenue') }}</div>
        <div class="chart-nav-item" data-target="revenueByMonthChart">{{ __('messages.monthly_revenue') }}</div>
        <div class="chart-nav-item" data-target="revenueByYearChart">{{ __('messages.yearly_revenue') }}</div>
        <div class="chart-nav-item" data-target="revenueByPaymentMethodChart">{{ __('messages.revenue_by_payment') }}</div>
    </div>

    <!-- Chart Containers -->
    <div class="chart-container active" id="categoryRevenueChart-container">
        <canvas id="categoryRevenueChart" height="300"></canvas>
    </div>
    <div class="chart-container" id="revenueByDateChart-container">
        <canvas id="revenueByDateChart" height="300"></canvas>
    </div>
    <div class="chart-container" id="revenueByMonthChart-container">
        <canvas id="revenueByMonthChart" height="300"></canvas>
    </div>
    <div class="chart-container" id="revenueByYearChart-container">
        <canvas id="revenueByYearChart" height="300"></canvas>
    </div>
    <div class="chart-container" id="revenueByPaymentMethodChart-container">
        <canvas id="revenueByPaymentMethodChart" height="300"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Chart Navigation
        const chartNavItems = document.querySelectorAll('.chart-nav-item');
        const chartContainers = document.querySelectorAll('.chart-container');
        
        chartNavItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all nav items
                chartNavItems.forEach(navItem => navItem.classList.remove('active'));
                
                // Add active class to clicked nav item
                this.classList.add('active');
                
                // Hide all chart containers
                chartContainers.forEach(container => container.classList.remove('active'));
                
                // Show the selected chart container
                const targetChart = this.getAttribute('data-target');
                document.getElementById(`${targetChart}-container`).classList.add('active');
            });
        });

        // Define vibrant colors for charts
        const chartColors = {
            primary: '#2563eb',  // blue
            primaryLight: 'rgba(37, 99, 235, 0.7)',
            secondary: '#10b981', // green
            secondaryLight: 'rgba(16, 185, 129, 0.7)',
            accent: '#f59e0b',   // amber
            accentLight: 'rgba(245, 158, 11, 0.7)',
            danger: '#ef4444',   // red
            dangerLight: 'rgba(239, 68, 68, 0.7)',
            purple: '#8b5cf6',   // purple
            purpleLight: 'rgba(139, 92, 246, 0.7)',
            pink: '#ec4899',     // pink
            pinkLight: 'rgba(236, 72, 153, 0.7)',
            indigo: '#6366f1',   // indigo
            indigoLight: 'rgba(99, 102, 241, 0.7)',
            cyan: '#06b6d4',     // cyan
            cyanLight: 'rgba(6, 182, 212, 0.7)'
        };

        // Gradient backgrounds for charts
        function createGradient(ctx, startColor, endColor) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, startColor);
            gradient.addColorStop(1, endColor);
            return gradient;
        }
        
        // Revenue by Brand Chart
        const categoryCtx = document.getElementById('categoryRevenueChart').getContext('2d');
        const barGradients = {!! json_encode($brandRevenue->pluck('brand_name')) !!}.map((_, i) => {
            const colors = [
                createGradient(categoryCtx, 'rgba(37, 99, 235, 0.8)', 'rgba(37, 99, 235, 0.2)'),
                createGradient(categoryCtx, 'rgba(16, 185, 129, 0.8)', 'rgba(16, 185, 129, 0.2)'),
                createGradient(categoryCtx, 'rgba(245, 158, 11, 0.8)', 'rgba(245, 158, 11, 0.2)'),
                createGradient(categoryCtx, 'rgba(239, 68, 68, 0.8)', 'rgba(239, 68, 68, 0.2)'),
                createGradient(categoryCtx, 'rgba(139, 92, 246, 0.8)', 'rgba(139, 92, 246, 0.2)'),
                createGradient(categoryCtx, 'rgba(236, 72, 153, 0.8)', 'rgba(236, 72, 153, 0.2)'),
                createGradient(categoryCtx, 'rgba(99, 102, 241, 0.8)', 'rgba(99, 102, 241, 0.2)'),
                createGradient(categoryCtx, 'rgba(6, 182, 212, 0.8)', 'rgba(6, 182, 212, 0.2)')
            ];
            return colors[i % colors.length];
        });
        
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($brandRevenue->pluck('brand_name')) !!},
                datasets: [{
                    label: '{{ __("messages.revenue_by_brand") }}',
                    data: {!! json_encode($brandRevenue->pluck('total_revenue')) !!},
                    backgroundColor: barGradients,
                    borderColor: 'transparent',
                    borderWidth: 0,
                    borderRadius: 8,
                    hoverBackgroundColor: chartColors.primaryLight
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(200, 200, 200, 0.2)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Daily Revenue Chart
        const dateCtx = document.getElementById('revenueByDateChart').getContext('2d');
        const dateGradient = createGradient(dateCtx, 'rgba(37, 99, 235, 0.4)', 'rgba(37, 99, 235, 0.0)');
        
        new Chart(dateCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueByDate->pluck('date')) !!},
                datasets: [{
                    label: '{{ __("messages.daily_revenue") }}',
                    data: {!! json_encode($revenueByDate->pluck('total_revenue')) !!},
                    borderColor: chartColors.primary,
                    backgroundColor: dateGradient,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'white',
                    pointBorderColor: chartColors.primary,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: 'white',
                    pointHoverBorderColor: chartColors.primary,
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(200, 200, 200, 0.2)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Monthly Revenue Chart
        const monthCtx = document.getElementById('revenueByMonthChart').getContext('2d');
        const monthGradients = {!! json_encode($revenueByMonth->map(function($item) { return $item->year . '-' . $item->month; })) !!}.map((_, i) => {
            const colors = [
                createGradient(monthCtx, 'rgba(99, 102, 241, 0.8)', 'rgba(99, 102, 241, 0.2)'),
                createGradient(monthCtx, 'rgba(37, 99, 235, 0.8)', 'rgba(37, 99, 235, 0.2)'),
                createGradient(monthCtx, 'rgba(6, 182, 212, 0.8)', 'rgba(6, 182, 212, 0.2)')
            ];
            return colors[i % colors.length];
        });
        
        new Chart(monthCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueByMonth->map(function($item) { return $item->year . '-' . $item->month; })) !!},
                datasets: [{
                    label: '{{ __("messages.monthly_revenue") }}',
                    data: {!! json_encode($revenueByMonth->pluck('total_revenue')) !!},
                    backgroundColor: monthGradients,
                    borderColor: 'transparent',
                    borderWidth: 0,
                    borderRadius: 8,
                    hoverBackgroundColor: chartColors.indigoLight
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(200, 200, 200, 0.2)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Yearly Revenue Chart
        const yearCtx = document.getElementById('revenueByYearChart').getContext('2d');
        const yearGradients = {!! json_encode($revenueByYear->pluck('year')) !!}.map((_, i) => {
            const colors = [
                createGradient(yearCtx, 'rgba(6, 182, 212, 0.8)', 'rgba(6, 182, 212, 0.2)'),
                createGradient(yearCtx, 'rgba(245, 158, 11, 0.8)', 'rgba(245, 158, 11, 0.2)'),
                createGradient(yearCtx, 'rgba(16, 185, 129, 0.8)', 'rgba(16, 185, 129, 0.2)'),
                createGradient(yearCtx, 'rgba(139, 92, 246, 0.8)', 'rgba(139, 92, 246, 0.2)')
            ];
            return colors[i % colors.length];
        });
        
        new Chart(yearCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueByYear->pluck('year')) !!},
                datasets: [{
                    label: '{{ __("messages.yearly_revenue") }}',
                    data: {!! json_encode($revenueByYear->pluck('total_revenue')) !!},
                    backgroundColor: yearGradients,
                    borderColor: 'transparent',
                    borderWidth: 0,
                    borderRadius: 8,
                    hoverBackgroundColor: chartColors.cyanLight
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(200, 200, 200, 0.2)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Revenue by Payment Method Chart
        new Chart(document.getElementById('revenueByPaymentMethodChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($revenueByPaymentMethod->pluck('payment_method')) !!},
                datasets: [{
                    label: '{{ __("messages.revenue_by_payment") }}',
                    data: {!! json_encode($revenueByPaymentMethod->pluck('total_revenue')) !!},
                    backgroundColor: [
                        chartColors.primary,
                        chartColors.secondary,
                        chartColors.accent,
                        chartColors.purple,
                        chartColors.pink,
                        chartColors.indigo,
                        chartColors.cyan
                    ],
                    borderColor: 'white',
                    borderWidth: 2,
                    hoverOffset: 10,
                    hoverBorderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            boxWidth: 12,
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `$${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%',
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });
    });
</script>
@endsection