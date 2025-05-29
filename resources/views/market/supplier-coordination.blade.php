@extends('layouts.dashboard')

@section('title', 'Supplier Coordination')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Supplier Coordination</h1>
        <p class="text-gray-600">Manage your vendor relationships and pending orders</p>
    </div>

    <!-- Pending Orders Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Pending Orders</h2>
        @if($pendingOrders->isEmpty())
            <p class="text-gray-600">No pending orders at the moment.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">#{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->vendor->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Vendors List Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Connected Vendors</h2>
        @if($vendors->isEmpty())
            <p class="text-gray-600">No vendors found.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($vendors as $vendor)
                    <div class="border rounded-lg p-4">
                        <h3 class="font-semibold text-lg mb-2">{{ $vendor->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2">{{ $vendor->email }}</p>
                        <div class="mt-4">
                            <a href="{{ route('orders.create', ['seller_id' => $vendor->id]) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Place Order
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection 