@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('header', 'Admin Dashboard')

@section('sidebar')
<div class="px-4">
    <div class="space-y-2">
        <!-- Vendor Management -->
        <div class="mb-4">
            <h3 class="text-xs uppercase text-gray-400 mb-2">Vendor Management</h3>
            <a href="{{ route('admin.vendors.validation') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg {{ request()->routeIs('admin.vendors.*') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-user-check mr-3"></i>
                <span>Vendor Validation</span>
            </a>
            <a href="{{ route('admin.facility-visits') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                <i class="fas fa-calendar-check mr-3"></i>
                <span>Facility Visits</span>
            </a>
        </div>

        <!-- Workforce Management -->
        <div class="mb-4">
            <h3 class="text-xs uppercase text-gray-400 mb-2">Workforce</h3>
            <a href="{{ route('admin.workforce') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                <i class="fas fa-users mr-3"></i>
                <span>Workforce Assignment</span>
            </a>
        </div>

        <!-- User Management -->
        <div class="mb-4">
            <h3 class="text-xs uppercase text-gray-400 mb-2">System</h3>
            <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                <i class="fas fa-user-cog mr-3"></i>
                <span>User Management</span>
            </a>
            <a href="{{ route('admin.reports') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                <i class="fas fa-file-alt mr-3"></i>
                <span>Reports</span>
            </a>
            <a href="{{ route('analytics.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                <i class="fas fa-chart-line mr-3"></i>
                <span>Analytics</span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Quick Stats -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Pending Validations</h3>
        <div class="text-3xl font-bold text-blue-600">{{ $pendingValidations ?? 0 }}</div>
        <p class="text-gray-600">Vendor applications awaiting review</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Active Users</h3>
        <div class="text-3xl font-bold text-green-600">{{ $activeUsers ?? 0 }}</div>
        <p class="text-gray-600">Total users on the platform</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Today's Orders</h3>
        <div class="text-3xl font-bold text-purple-600">{{ $todayOrders ?? 0 }}</div>
        <p class="text-gray-600">Orders processed today</p>
    </div>
</div>

<!-- Recent Activity -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentActivity ?? [] as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->action }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->user }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->time }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $activity->status }}
                                </span>
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