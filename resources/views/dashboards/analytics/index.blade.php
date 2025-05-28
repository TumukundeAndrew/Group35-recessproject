@extends('layouts.dashboard')

@section('title', 'Analytics Dashboard')

@section('header', 'Analytics Dashboard')

@section('sidebar')
<div class="px-4">
    <div class="space-y-2">
        <!-- Common Analytics -->
        <div class="mb-4">
            <h3 class="text-xs uppercase text-gray-400 mb-2">Overview</h3>
            <a href="{{ route('analytics.overview') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                <i class="fas fa-chart-pie mr-3"></i>
                <span>Dashboard Overview</span>
            </a>
        </div>

        @if(auth()->user()->role === 'admin')
            <!-- Admin Analytics -->
            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">System Analytics</h3>
                <a href="{{ route('analytics.demand-prediction') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Demand Prediction</span>
                </a>
                <a href="{{ route('analytics.customer-segmentation') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-users mr-3"></i>
                    <span>Customer Segmentation</span>
                </a>
                <a href="{{ route('analytics.inventory-projections') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-boxes mr-3"></i>
                    <span>Inventory Projections</span>
                </a>
                <a href="{{ route('analytics.workforce') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-user-clock mr-3"></i>
                    <span>Workforce Analytics</span>
                </a>
            </div>
        @endif

        @if(in_array(auth()->user()->role, ['wholesaler', 'retailer']))
            <!-- Market Analytics -->
            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Market Insights</h3>
                <a href="{{ route('analytics.sales-trends') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Sales Trends</span>
                </a>
                <a href="{{ route('analytics.product-performance') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-box-open mr-3"></i>
                    <span>Product Performance</span>
                </a>
                <a href="{{ route('analytics.restock-predictions') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-dolly mr-3"></i>
                    <span>Restock Predictions</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Performance Metrics -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Performance Overview</h3>
        <canvas id="performanceChart" class="w-full"></canvas>
    </div>

    <!-- Trend Analysis -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Trend Analysis</h3>
        <canvas id="trendChart" class="w-full"></canvas>
    </div>

    <!-- Key Metrics -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Key Metrics</h3>
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-600">Revenue Growth</p>
                <div class="flex items-center">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <span class="ml-4 text-sm font-medium text-gray-600">70%</span>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600">Customer Satisfaction</p>
                <div class="flex items-center">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                    <span class="ml-4 text-sm font-medium text-gray-600">85%</span>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600">Inventory Turnover</p>
                <div class="flex items-center">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 60%"></div>
                    </div>
                    <span class="ml-4 text-sm font-medium text-gray-600">60%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Analytics -->
<div class="mt-8 grid grid-cols-1 gap-6">
    <!-- Time Series Analysis -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Time Series Analysis</h3>
        <canvas id="timeSeriesChart" class="w-full h-96"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Performance Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    new Chart(performanceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'In Progress', 'Pending'],
            datasets: [{
                data: [65, 20, 15],
                backgroundColor: ['#10B981', '#3B82F6', '#9061F9']
            }]
        }
    });

    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Sales Trend',
                data: [30, 45, 35, 50, 40, 60],
                borderColor: '#3B82F6',
                tension: 0.4
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

    // Time Series Chart
    const timeSeriesCtx = document.getElementById('timeSeriesChart').getContext('2d');
    new Chart(timeSeriesCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
            datasets: [{
                label: 'Revenue',
                data: [1000, 1500, 1300, 1700, 1600, 2000],
                borderColor: '#10B981',
                tension: 0.4
            }, {
                label: 'Orders',
                data: [50, 65, 55, 70, 65, 80],
                borderColor: '#3B82F6',
                tension: 0.4
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
</script>
@endpush