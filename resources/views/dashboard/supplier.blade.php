@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Raw Material Inventory</h3>
            <p>Monitor and manage raw material stock (sunflower seeds, bottles).</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Orders</h3>
            @if($orders->isEmpty())
                <p>No orders found.</p>
            @else
                <ul>
                    @foreach($orders as $order)
                        <li class="mb-2">Order #{{ $order->id }} - {{ $order->quantity }} units (Status: {{ $order->status }})</li>
                    @endforeach
                </ul>
            @endif
            <form action="/orders" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="bg-amber-500 text-white p-2 rounded hover:bg-amber-600">View Orders</button>
            </form>
        </div>
    </div>
@endsection
