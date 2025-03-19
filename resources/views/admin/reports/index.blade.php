@extends('layouts.app')

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

    <!-- Top 5 Brands by Revenue -->
    <div class="card brand-revenue-card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ __('messages.top_brands_by_revenue') }}</h5>
        </div>
        <div class="card-body">
            @foreach($brandRevenue->take(5) as $revenue)
            <div class="brand-revenue-item">
                <div class="brand-name">{{ $revenue->brand_name }}</div>
                <div class="revenue">${{ number_format($revenue->total_revenue, 2) }}</div>
            </div>
            @endforeach
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
        
        // Revenue by Brand Chart
        new Chart(document.getElementById('categoryRevenueChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($brandRevenue->pluck('brand_name')) !!},
                datasets: [{
                    label: '{{ __("messages.revenue_by_brand") }}',
                    data: {!! json_encode($brandRevenue->pluck('total_revenue')) !!},
                    backgroundColor: 'rgba(37, 99, 235, 0.5)',
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false,
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
                }
            }
        });

        // Daily Revenue Chart
        new Chart(document.getElementById('revenueByDateChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueByDate->pluck('date')) !!},
                datasets: [{
                    label: '{{ __("messages.daily_revenue") }}',
                    data: {!! json_encode($revenueByDate->pluck('total_revenue')) !!},
                    borderColor: 'rgb(37, 99, 235)',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgb(37, 99, 235)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false,
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
                }
            }
        });

        // Monthly Revenue Chart
        new Chart(document.getElementById('revenueByMonthChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueByMonth->map(function($item) { return $item->year . '-' . $item->month; })) !!},
                datasets: [{
                    label: '{{ __("messages.monthly_revenue") }}',
                    data: {!! json_encode($revenueByMonth->pluck('total_revenue')) !!},
                    backgroundColor: 'rgba(37, 99, 235, 0.5)',
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false,
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
                }
            }
        });

        // Yearly Revenue Chart
        new Chart(document.getElementById('revenueByYearChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueByYear->pluck('year')) !!},
                datasets: [{
                    label: '{{ __("messages.yearly_revenue") }}',
                    data: {!! json_encode($revenueByYear->pluck('total_revenue')) !!},
                    backgroundColor: 'rgba(37, 99, 235, 0.5)',
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false,
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
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
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(96, 165, 250, 0.7)',
                        'rgba(147, 197, 253, 0.7)'
                    ],
                    borderColor: [
                        'rgb(37, 99, 235)',
                        'rgb(59, 130, 246)',
                        'rgb(96, 165, 250)',
                        'rgb(147, 197, 253)'
                    ],
                    borderWidth: 1,
                    hoverOffset: 4
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
                            boxWidth: 12
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection