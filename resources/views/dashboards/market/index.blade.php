@extends('layouts.dashboard')

@section('title', 'Market Dashboard')

@section('header')
    @if(auth()->user()->role === 'wholesaler')
        Wholesaler Dashboard
    @elseif(auth()->user()->role === 'retailer')
        Retailer Dashboard
    @else
        Customer Dashboard
    @endif
@endsection

@section('sidebar')
<div class="px-4">
    <div class="space-y-2">
        @if(auth()->user()->role === 'wholesaler')
            <!-- Wholesaler Sidebar -->
            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Orders</h3>
                <a href="{{ route('market.bulk-orders') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-boxes mr-3"></i>
                    <span>Manage Bulk Orders</span>
                </a>
                <a href="{{ route('market.supplier-coordination') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-handshake mr-3"></i>
                    <span>Supplier Coordination</span>
                </a>
            </div>

            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Analytics</h3>
                <a href="{{ route('analytics.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>View Analytics</span>
                </a>
            </div>

        @elseif(auth()->user()->role === 'retailer')
            <!-- Retailer Sidebar -->
            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Store Management</h3>
                <a href="{{ route('market.place-orders') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-shopping-cart mr-3"></i>
                    <span>Place Orders</span>
                </a>
                <a href="{{ route('market.inventory') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-box mr-3"></i>
                    <span>Manage Inventory</span>
                </a>
                <a href="{{ route('market.deliveries') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-truck mr-3"></i>
                    <span>Track Deliveries</span>
                </a>
            </div>

            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Analytics</h3>
                <a href="{{ route('analytics.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>View Analytics</span>
                </a>
            </div>

        @else
            <!-- Customer Sidebar -->
            <div class="mb-4">
                <h3 class="text-xs uppercase text-gray-400 mb-2">Shopping</h3>
                <a href="{{ route('market.products') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-store mr-3"></i>
                    <span>View Products</span>
                </a>
                <a href="{{ route('market.orders') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-shopping-bag mr-3"></i>
                    <span>My Orders</span>
                </a>
                <a href="{{ route('market.track-orders') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                    <i class="fas fa-truck mr-3"></i>
                    <span>Track Orders</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @if(auth()->user()->role === 'wholesaler')
        <!-- Wholesaler Dashboard Content -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Pending Orders</h3>
            <div class="text-3xl font-bold text-blue-600">{{ $pendingOrders ?? 0 }}</div>
            <p class="text-gray-600">Orders awaiting processing</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Stock Level</h3>
            <div class="text-3xl font-bold text-green-600">{{ $stockLevel ?? '0%' }}</div>
            <p class="text-gray-600">Current warehouse capacity</p>
        </div>

    @elseif(auth()->user()->role === 'retailer')
        <!-- Retailer Dashboard Content -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Today's Sales</h3>
            <div class="text-3xl font-bold text-blue-600">{{ $todaySales ?? 0 }}</div>
            <p class="text-gray-600">Total sales today</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Low Stock Items</h3>
            <div class="text-3xl font-bold text-red-600">{{ $lowStockItems ?? 0 }}</div>
            <p class="text-gray-600">Items needing restock</p>
        </div>

    @else
        <!-- Customer Dashboard Content -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Active Orders</h3>
            <div class="text-3xl font-bold text-blue-600">{{ $activeOrders ?? 0 }}</div>
            <p class="text-gray-600">Orders in progress</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Purchases</h3>
            <div class="text-3xl font-bold text-green-600">{{ $recentPurchases ?? 0 }}</div>
            <p class="text-gray-600">In the last 30 days</p>
        </div>
    @endif
</div>

<!-- Recent Activity Table -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentActivity ?? [] as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">#{{ $activity->order_id }}</td>
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