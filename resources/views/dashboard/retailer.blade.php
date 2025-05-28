@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Sales Analytics</h3>
            <p>View sales trends and performance.</p>
            <canvas id="salesChart" class="mt-4"></canvas>
            <script>
                const ctx = document.getElementById('salesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'Sales (Liters)',
                            data: @json($data),
                            backgroundColor: '#FFCA28'
                        }]
                    },
                    options: { scales: { y: { beginAtZero: true } } }
                });
            </script>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Customer Segments</h3>
            <p>Analyze customer data and preferences.</p>
        </div>
    </div>
@endsection
