@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Demand Prediction</h3>
            <p>View ML-based demand forecasts.</p>
        </div>
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manufacturer')
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-green-800 mb-4">Workforce Efficiency</h3>
                <p>Analyze workforce productivity stats.</p>
            </div>
        @endif
    </div>
@endsection
