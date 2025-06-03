@extends('layouts.dashboard')

@section('title', 'Manage inventory')

@section('header')
    @if(auth()->user()->role === 'vendor')
        Vendor Dashboard
    @else
        Manage Inventory
    @endif
@endsection

@section('sidebar')
<div class="px-4">
    <div class="space-y-2">
        @if(auth()->user()->role === 'vendor')
            <!-- Vendor Sidebar -->
            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Application</h3>
                <a href="{{ route('supply.application') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-file-upload mr-3"></i>
                    <span>Submit Application</span>
                </a>
                <a href="{{ route('supply.validation-status') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-clipboard-check mr-3"></i>
                    <span>Validation Status</span>
                </a>
            </div>

            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Support</h3>
                <a href="{{ route('supply.chat') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-comments mr-3"></i>
                    <span>Chat with Admin</span>
                </a>
            </div>

        @else
            <!-- Supplier Sidebar -->
            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Inventory</h3>
                <a href="{{ route('supply.inventory') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-warehouse mr-3"></i>
                    <span>Manage Inventory</span>
                </a>
                <a href="{{ route('supply.stock-update') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-boxes mr-3"></i>
                    <span>Update Stock</span>
                </a>
            </div>

            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Orders</h3>
                <a href="{{ route('supply.orders') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-shopping-cart mr-3"></i>
                    <span>Process Orders</span>
                </a>
                <a href="{{ route('supply.shipments') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-truck mr-3"></i>
                    <span>Manage Shipments</span>
                </a>
            </div>

            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Communication</h3>
                <a href="{{ route('supply.chat-manufacturer') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-comments mr-3"></i>
                    <span>Chat with Manufacturer</span>
                </a>
            </div>

            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Reports</h3>
                <a href="{{ route('supply.reports') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>View Reports</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @if(auth()->user()->role === 'vendor')
        <!-- Vendor Dashboard Content -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Application Status</h3>
            <div class="text-3xl font-bold text-blue-600">{{ $applicationStatus ?? 'Pending' }}</div>
            <p class="text-gray-600">Current validation status</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Required Documents</h3>
            <div class="text-3xl font-bold text-yellow-600">{{ $requiredDocs ?? 0 }}</div>
            <p class="text-gray-600">Documents needed</p>
        </div>

    @else
        <!-- Supplier Dashboard Content -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Pending Orders</h3>
            <div class="text-3xl font-bold text-blue-600">{{ $pendingOrders ?? 0 }}</div>
            <p class="text-gray-600">Orders to process</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Stock Status</h3>
            <div class="text-3xl font-bold text-green-600">{{ $stockStatus ?? '0%' }}</div>
            <p class="text-gray-600">Current inventory level</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Active Shipments</h3>
            <div class="text-3xl font-bold text-purple-600">{{ $activeShipments ?? 0 }}</div>
            <p class="text-gray-600">In transit</p>
        </div>
    @endif
</div>

<!-- Recent Activity -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentActivity ?? [] as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $activity->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                No recent activity
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 