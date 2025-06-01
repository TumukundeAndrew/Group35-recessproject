<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sunflower SCM</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js" integrity="sha384-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="bg-green-800 text-white w-64 space-y-6 py-7 px-2 fixed inset-y-0 left-0 transform transition duration-200 ease-in-out md:transform-none">
            <h2 class="text-2xl font-bold text-center">Sunflower SCM</h2>
            <nav>
                @auth
                    <a href="{{ route('dashboard', ['role' => auth()->user()->role]) }}" class="block py-2.5 px-4 rounded hover:bg-green-700 {{ request()->routeIs('dashboard') ? 'bg-amber-500' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('dashboard', ['role' => 'analytics']) }}" class="block py-2.5 px-4 rounded hover:bg-green-700 {{ request()->routeIs('dashboard') && request()->route('role') === 'analytics' ? 'bg-amber-500' : '' }}"><i class="fas fa-chart-line mr-2"></i> Analytics</a>
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-green-700"><i class="fas fa-users mr-2"></i> Vendors</a>
                    @elseif(auth()->user()->role === 'vendor')
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-green-700"><i class="fas fa-file-pdf mr-2"></i> Application</a>
                    @elseif(auth()->user()->role === 'supplier')
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-green-700"><i class="fas fa-seedling mr-2"></i> Inventory</a>
                        <a href="{{ route('orders.view') }}" class="block py-2.5 px-4 rounded hover:bg-green-700"><i class="fas fa-shopping-cart mr-2"></i> Orders</a>
                    @elseif(auth()->user()->role === 'manufacturer')
                        <a href="{{ route('dashboard', ['role' => 'analytics']) }}" class="block py-2.5 px-4 rounded hover:bg-green-700 {{ request()->routeIs('dashboard') && request()->route('role') === 'analytics' ? 'bg-amber-500' : '' }}"><i class="fas fa-chart-line mr-2"></i> Analytics</a>
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-green-700"><i class="fas fa-industry mr-2"></i> Production</a>
                    @elseif(auth()->user()->role === 'wholesaler')
                        <a href="{{ route('orders.view') }}" class="block py-2.5 px-4 rounded hover:bg-green-700"><i class="fas fa-shopping-cart mr-2"></i> Orders</a>
                    @elseif(auth()->user()->role === 'retailer')
                        <a href="{{ route('dashboard', ['role' => 'analytics']) }}" class="block py-2.5 px-4 rounded hover:bg-green-700 {{ request()->routeIs('dashboard') && request()->route('role') === 'analytics' ? 'bg-amber-500' : '' }}"><i class="fas fa-chart-line mr-2"></i> Analytics</a>
                    @elseif(auth()->user()->role === 'customer')
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-green-700"><i class="fas fa-star mr-2"></i> Feedback</a>
                    @endif
                    <a href="{{ route('logout') }}" class="block py-2.5 px-4 rounded hover:bg-green-700" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endauth
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-0 md:ml-64">
            <!-- Top Bar -->
            <header class="bg-green-700 text-white p-4 flex justify-between items-center shadow-md">
                <h1 class="text-xl font-semibold">{{ ucfirst(request()->route('role')) }} Dashboard</h1>
                @auth
                    <span class="text-sm">Welcome, {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                @endauth
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-6">
                @yield('content')
            </main>
        </div>
    </div>
    <script type="module" src="{{ Vite::asset('resources/js/app.js') }}"></script>
</body>
</html>


