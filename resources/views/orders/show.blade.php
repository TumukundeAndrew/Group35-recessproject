@extends('layouts.dashboard')

@section('title', 'Order Details')

@section('header', 'Order Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">Order #{{ $order->id }}</h2>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                           ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                           'bg-blue-100 text-blue-800')) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Order Information</h3>
                        <dl class="grid grid-cols-1 gap-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Order Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->getOrderTypeLabel() }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Product</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->product->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Quantity</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->quantity }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                                <dd class="mt-1 text-sm text-gray-900">${{ number_format($order->total_amount, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('M d, Y H:i A') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Parties Involved</h3>
                        <dl class="grid grid-cols-1 gap-3">
                            @if($order->customer_id)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Customer</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->customer->name }}</dd>
                            </div>
                            @endif
                            @if($order->retailer_id)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Retailer</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->retailer->name }}</dd>
                            </div>
                            @endif
                            @if($order->wholesaler_id)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Wholesaler</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->wholesaler->name }}</dd>
                            </div>
                            @endif
                            @if($order->vendor_id)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Vendor</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->vendor->name }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                @if($order->shipmentLogs->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Shipment History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">From</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">To</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->shipmentLogs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->shipment_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->from_location }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->to_location }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ucfirst($log->status) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if(auth()->user()->role !== 'customer' && $order->status !== 'cancelled')
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Update Order Status</h3>
                    <form action="{{ route('orders.update-status', $order) }}" method="POST" class="flex items-center space-x-4">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipping" {{ $order->status === 'shipping' ? 'selected' : '' }}>Shipping</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Update Status
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-900">
                &larr; Back to Orders
            </a>
        </div>
    </div>
</div>
@endsection 