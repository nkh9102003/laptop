@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Customers</h5>
                    <p class="card-text">{{ $totalCustomers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mt-4">Revenue by Brand</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Brand</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brandRevenue as $revenue)
            <tr>
                <td>{{ $revenue->brand_name }}</td>
                <td>${{ number_format($revenue->total_revenue, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="mt-4">Charts</h2>
    <div class="row">
        <div class="col-md-4">
            <canvas id="categoryRevenueChart"></canvas>
        </div>
        <div class="col-md-4">
            <canvas id="revenueByDateChart"></canvas>
        </div>
        <div class="col-md-4">
            <canvas id="revenueByMonthChart"></canvas>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            <canvas id="revenueByYearChart"></canvas>
        </div>
        <div class="col-md-4">
            <canvas id="revenueByPaymentMethodChart"></canvas>
        </div>
        <div class="col-md-4">
            <!-- Optional: An empty column for consistent spacing -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Revenue by Brand Chart
        new Chart(document.getElementById('categoryRevenueChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($brandRevenue->pluck('brand_name')) !!},
                datasets: [{
                    label: 'Revenue by Brand',
                    data: {!! json_encode($brandRevenue->pluck('total_revenue')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
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
                    label: 'Daily Revenue',
                    data: {!! json_encode($revenueByDate->pluck('total_revenue')) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });

        // Monthly Revenue Chart
        new Chart(document.getElementById('revenueByMonthChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueByMonth->map(function($item) { return $item->year . '-' . $item->month; })) !!},
                datasets: [{
                    label: 'Monthly Revenue',
                    data: {!! json_encode($revenueByMonth->pluck('total_revenue')) !!},
                    backgroundColor: 'rgba(255, 159, 64, 0.5)',
                    borderColor: 'rgb(255, 159, 64)',
                    borderWidth: 1
                }]
            }
        });

        // Yearly Revenue Chart
        new Chart(document.getElementById('revenueByYearChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueByYear->pluck('year')) !!},
                datasets: [{
                    label: 'Yearly Revenue',
                    data: {!! json_encode($revenueByYear->pluck('total_revenue')) !!},
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgb(153, 102, 255)',
                    borderWidth: 1
                }]
            }
        });

        // Revenue by Payment Method Chart
        new Chart(document.getElementById('revenueByPaymentMethodChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($revenueByPaymentMethod->pluck('payment_method')) !!},
                datasets: [{
                    label: 'Revenue by Payment Method',
                    data: {!! json_encode($revenueByPaymentMethod->pluck('total_revenue')) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)'
                    ],
                    borderWidth: 1
                }]
            }
        });
    });
</script>
@endsection