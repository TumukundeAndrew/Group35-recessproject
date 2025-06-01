@extends('layouts.dashboard')

@section('title', 'Analytics Dashboard')

@section('header', 'Analytics Dashboard')

@section('sidebar')
@endsection

@section('content')
<div class="px-4 py-6 space-y-10 bg-gray-50 min-h-screen">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-yellow-600">
            Sunflower Oil Sales Analytics
        </h1>
        <div class="text-sm text-gray-600">
            Viewing as: <span class="font-semibold text-yellow-600">{{ ucfirst(Auth::user()->role) }}</span>
        </div>
    </div>

    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ $error }}</span>
        </div>
    @endif

    @if(count($sales) > 0)
        <!-- 1. Sales Data -->
        <section>
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b border-yellow-400 pb-2">
                1. Sales Data
                @if(Auth::user()->role !== 'admin')
                    <span class="text-sm font-normal text-gray-600">(Your sales only)</span>
                @endif
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg shadow divide-y divide-gray-200">
                    <thead class="bg-yellow-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-700 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-700 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-700 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-700 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-700 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($sales as $sale)
                        <tr class="hover:bg-yellow-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $sale['orderId'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $sale['customer'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $sale['quantity'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600 font-semibold">${{ $sale['price'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $sale['date'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- 2. Predicted Demand -->
        <section>
            <h2 class="text-xl font-semibold text-gray-700 mb-2 border-b border-yellow-400 pb-2">
                2. Predicted Demand
                @if(Auth::user()->role !== 'admin')
                    <span class="text-sm font-normal text-gray-600">(Based on your sales)</span>
                @endif
            </h2>
            <p class="text-gray-700 text-lg">
                Total sunflower seeds sold: 
                <strong class="text-yellow-600">{{ $totalQuantity }}</strong> units
            </p>
        </section>

        <!-- 3. Customer Segmentation (Spending) -->
        <section>
            <h2 class="text-xl font-semibold text-gray-700 mb-2 border-b border-yellow-400 pb-2">
                3. Customer Segmentation (Spending)
                @if(Auth::user()->role !== 'admin')
                    <span class="text-sm font-normal text-gray-600">(Your customers only)</span>
                @endif
            </h2>
            <ul class="list-disc list-inside space-y-1 text-gray-800">
                @foreach ($customerSpend as $customer => $spend)
                    <li>{{ $customer }}: <span class="font-semibold text-yellow-600">${{ $spend }}</span> total spent</li>
                @endforeach
            </ul>
        </section>

        <!-- 4. Customer Segmentation (Frequency) -->
        <section>
            <h2 class="text-xl font-semibold text-gray-700 mb-2 border-b border-yellow-400 pb-2">
                4. Customer Segmentation (Frequency)
                @if(Auth::user()->role !== 'admin')
                    <span class="text-sm font-normal text-gray-600">(Your customers only)</span>
                @endif
            </h2>
            <ul class="list-disc list-inside space-y-1 text-gray-800">
                @foreach ($customerFrequency as $customer => $count)
                    <li>{{ $customer }}: <span class="font-semibold text-yellow-600">{{ $count }}</span> orders</li>
                @endforeach
            </ul>
        </section>

        <!-- Analytics Charts -->
        <section>
            <h2 class="text-xl font-semibold text-gray-700 mb-6 border-b border-yellow-400 pb-2">
                5. Analytics Charts
                @if(Auth::user()->role !== 'admin')
                    <span class="text-sm font-normal text-gray-600">(Based on your data)</span>
                @endif
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Sales Quantity Over Time -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 text-yellow-600">Sales Quantity Over Time</h3>
                    <canvas id="salesQuantityChart" class="w-full h-64"></canvas>
                </div>

                <!-- Customer Spending -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 text-yellow-600">Customer Spending</h3>
                    <canvas id="customerSpendingChart" class="w-full h-64"></canvas>
                </div>

                <!-- Customer Order Frequency -->
                <div class="bg-white p-6 rounded-lg shadow md:col-span-2">
                    <h3 class="text-lg font-semibold mb-4 text-yellow-600">Customer Order Frequency</h3>
                    <canvas id="customerFrequencyChart" class="w-full h-64"></canvas>
                </div>
            </div>
        </section>

        <!-- 6. Demand Predictions -->
        <section class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b border-yellow-400 pb-2">
                6. Demand Predictions
                @if(Auth::user()->role !== 'admin')
                    <span class="text-sm font-normal text-gray-600">(Based on your sales data)</span>
                @endif
            </h2>

            @if($demandPredictions)
                <div class="space-y-6">
                    <!-- Demand Prediction Chart -->
                    <div class="bg-white rounded-lg">
                        <canvas id="demandPredictionChart" class="w-full h-64"></canvas>
                    </div>

                    <!-- Prediction Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-600 mb-2">7-Day Forecast</h3>
                            <div class="space-y-2">
                                @foreach($demandPredictions['predictions'] as $date => $quantity)
                                    <div class="flex justify-between items-center border-b border-gray-100 py-1">
                                        <span class="text-gray-600">{{ $date }}</span>
                                        <span class="font-semibold text-yellow-600">{{ $quantity }} units</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-600 mb-2">Insights</h3>
                            <ul class="list-disc list-inside space-y-2 text-gray-600">
                                @php
                                    $trend = end($demandPredictions['predictions']) - reset($demandPredictions['predictions']);
                                    $avgPrediction = array_sum($demandPredictions['predictions']) / count($demandPredictions['predictions']);
                                @endphp
                                <li>Predicted average daily demand: <span class="font-semibold">{{ round($avgPrediction) }} units</span></li>
                                <li>Trend direction: 
                                    <span class="font-semibold {{ $trend > 0 ? 'text-green-600' : ($trend < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                        {{ $trend > 0 ? '↑ Increasing' : ($trend < 0 ? '↓ Decreasing' : '→ Stable') }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-gray-600">No prediction data available.</p>
            @endif
        </section>

        <!-- 7. Customer Segmentation & Recommendations -->
        <section class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b border-yellow-400 pb-2">
                7. Customer Segmentation & Recommendations
                @if(Auth::user()->role !== 'admin')
                    <span class="text-sm font-normal text-gray-600">(Based on your customer data)</span>
                @endif
            </h2>

            @if($customerSegments)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- High-Value Customers -->
                    <div class="bg-gradient-to-br from-yellow-50 to-white p-4 rounded-lg border border-yellow-200">
                        <h3 class="text-lg font-semibold text-yellow-700 mb-3">High-Value Customers</h3>
                        @if(count($customerSegments['high_value']) > 0)
                            @foreach($customerSegments['high_value'] as $customer)
                                <div class="mb-4 last:mb-0">
                                    <div class="font-semibold text-gray-800">{{ $customer['name'] }}</div>
                                    <div class="text-sm text-gray-600 mb-2">
                                        Total Spent: ${{ number_format($customer['metrics']['total_spent'], 2) }} | 
                                        Orders: {{ $customer['metrics']['frequency'] }}
                                    </div>
                                    <ul class="list-disc list-inside text-sm text-yellow-600 pl-2">
                                        @foreach($customer['recommendations'] as $recommendation)
                                            <li>{{ $recommendation }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600">No high-value customers identified yet.</p>
                        @endif
                    </div>

                    <!-- Loyal Customers -->
                    <div class="bg-gradient-to-br from-green-50 to-white p-4 rounded-lg border border-green-200">
                        <h3 class="text-lg font-semibold text-green-700 mb-3">Loyal Customers</h3>
                        @if(count($customerSegments['loyal']) > 0)
                            @foreach($customerSegments['loyal'] as $customer)
                                <div class="mb-4 last:mb-0">
                                    <div class="font-semibold text-gray-800">{{ $customer['name'] }}</div>
                                    <div class="text-sm text-gray-600 mb-2">
                                        Total Spent: ${{ number_format($customer['metrics']['total_spent'], 2) }} | 
                                        Orders: {{ $customer['metrics']['frequency'] }}
                                    </div>
                                    <ul class="list-disc list-inside text-sm text-green-600 pl-2">
                                        @foreach($customer['recommendations'] as $recommendation)
                                            <li>{{ $recommendation }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600">No loyal customers identified yet.</p>
                        @endif
                    </div>

                    <!-- Potential Customers -->
                    <div class="bg-gradient-to-br from-blue-50 to-white p-4 rounded-lg border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-700 mb-3">Potential Growth Customers</h3>
                        @if(count($customerSegments['potential']) > 0)
                            @foreach($customerSegments['potential'] as $customer)
                                <div class="mb-4 last:mb-0">
                                    <div class="font-semibold text-gray-800">{{ $customer['name'] }}</div>
                                    <div class="text-sm text-gray-600 mb-2">
                                        Total Spent: ${{ number_format($customer['metrics']['total_spent'], 2) }} | 
                                        Orders: {{ $customer['metrics']['frequency'] }}
                                    </div>
                                    <ul class="list-disc list-inside text-sm text-blue-600 pl-2">
                                        @foreach($customer['recommendations'] as $recommendation)
                                            <li>{{ $recommendation }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600">No potential growth customers identified yet.</p>
                        @endif
                    </div>

                    <!-- At-Risk Customers -->
                    <div class="bg-gradient-to-br from-red-50 to-white p-4 rounded-lg border border-red-200">
                        <h3 class="text-lg font-semibold text-red-700 mb-3">At-Risk Customers</h3>
                        @if(count($customerSegments['at_risk']) > 0)
                            @foreach($customerSegments['at_risk'] as $customer)
                                <div class="mb-4 last:mb-0">
                                    <div class="font-semibold text-gray-800">{{ $customer['name'] }}</div>
                                    <div class="text-sm text-gray-600 mb-2">
                                        Total Spent: ${{ number_format($customer['metrics']['total_spent'], 2) }} | 
                                        Orders: {{ $customer['metrics']['frequency'] }}
                                    </div>
                                    <ul class="list-disc list-inside text-sm text-red-600 pl-2">
                                        @foreach($customer['recommendations'] as $recommendation)
                                            <li>{{ $recommendation }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600">No at-risk customers identified.</p>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-gray-600">No customer segmentation data available.</p>
            @endif
        </section>
    @else
        <div class="text-center py-12">
            <p class="text-gray-600 text-lg">No sales data available for your account.</p>
            @if(Auth::user()->role !== 'admin')
                <p class="text-sm text-gray-500 mt-2">This could be because you haven't made any sales yet or because you're viewing data for a specific role.</p>
            @endif
        </div>
    @endif
</div>

@push('scripts')
@if(count($sales) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Parse your PHP data into JS variables safely
    const salesData = {!! json_encode($sales) !!};
    const customerSpend = {!! json_encode($customerSpend) !!};
    const customerFrequency = {!! json_encode($customerFrequency) !!};
    const demandPredictions = {!! json_encode($demandPredictions) !!};

    // Prepare Sales Quantity Over Time data
    const quantityByDate = {};
    salesData.forEach(sale => {
        quantityByDate[sale.date] = (quantityByDate[sale.date] || 0) + parseInt(sale.quantity);
    });
    const salesDates = Object.keys(quantityByDate).sort();
    const salesQuantities = salesDates.map(date => quantityByDate[date]);

    // Sales Quantity Chart
    const ctxSales = document.getElementById('salesQuantityChart').getContext('2d');
    new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: salesDates,
            datasets: [{
                label: 'Quantity Sold',
                data: salesQuantities,
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.3)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Quantity' } },
                x: { title: { display: true, text: 'Date' } }
            }
        }
    });

    // Customer Spending Chart
    const ctxSpend = document.getElementById('customerSpendingChart').getContext('2d');
    new Chart(ctxSpend, {
        type: 'bar',
        data: {
            labels: Object.keys(customerSpend),
            datasets: [{
                label: 'Total Spend ($)',
                data: Object.values(customerSpend),
                backgroundColor: '#d97706',
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Spend ($)' } },
                x: { title: { display: true, text: 'Customer' } }
            }
        }
    });

    // Customer Order Frequency Chart
    const ctxFreq = document.getElementById('customerFrequencyChart').getContext('2d');
    new Chart(ctxFreq, {
        type: 'bar',
        data: {
            labels: Object.keys(customerFrequency),
            datasets: [{
                label: 'Number of Orders',
                data: Object.values(customerFrequency),
                backgroundColor: '#b45309',
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Orders' } },
                x: { title: { display: true, text: 'Customer' } }
            }
        }
    });

    // Demand Prediction Chart
    if (demandPredictions) {
        const historicalDates = Object.keys(demandPredictions.historical);
        const historicalValues = Object.values(demandPredictions.historical);
        const predictionDates = Object.keys(demandPredictions.predictions);
        const predictionValues = Object.values(demandPredictions.predictions);

        const ctxDemand = document.getElementById('demandPredictionChart').getContext('2d');
        new Chart(ctxDemand, {
            type: 'line',
            data: {
                labels: [...historicalDates, ...predictionDates],
                datasets: [
                    {
                        label: 'Historical Demand',
                        data: [...historicalValues, ...Array(predictionDates.length).fill(null)],
                        borderColor: '#d97706',
                        backgroundColor: 'rgba(217, 119, 6, 0.1)',
                        fill: true,
                        tension: 0.3,
                    },
                    {
                        label: 'Predicted Demand',
                        data: [...Array(historicalDates.length).fill(null), ...predictionValues],
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        fill: true,
                        tension: 0.3,
                        borderDash: [5, 5],
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        title: { display: true, text: 'Quantity' }
                    },
                    x: {
                        title: { display: true, text: 'Date' }
                    }
                }
            }
        });
    }
</script>
@endif
@endpush

@endsection


