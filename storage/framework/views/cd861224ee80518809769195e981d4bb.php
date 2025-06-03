<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php if(auth()->guard()->check()): ?>
    <meta name="user-id" content="<?php echo e(Auth::id()); ?>">
    <?php endif; ?>

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js" integrity="sha384-i+dHPTzZw7YVZOx9lbH5l6lP74sLRtMtwN2XjVqjf3uAGAREAF4LMIUDTWEVs4LI" crossorigin="anonymous"></script>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        /* Stacking context management */
        .main-content {
            position: relative;
            z-index: 1;
        }
        
        .sidebar {
            position: relative;
            z-index: 2;
        }
        
        .topbar {
            position: relative;
            z-index: 3;
        }
        
        /* Force other elements to stay below chat */
        .dashboard-card {
            position: relative;
            z-index: 1;
        }
        
        /* Ensure chat stays on top */
        #app {
            position: relative;
            z-index: 99999 !important;
            isolation: isolate;
        }
        
        /* Create new stacking context for all content */
        body {
            isolation: isolate;
        }

        .chat-overlay {
            position: fixed;
            bottom: 0;
            right: 0;
            width: auto;
            height: auto;
            pointer-events: none;
            z-index: 100;
        }
        .chat-overlay > * {
            pointer-events: auto;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-900 text-white w-64 space-y-6 py-7 px-2 fixed inset-y-0 left-0 transform transition duration-200 ease-in-out md:transform-none flex-shrink-0">
            <h2 class="text-2xl font-bold text-center">Sunflower SCM</h2>
            <nav>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard', ['role' => auth()->user()->role])); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('dashboard') ? 'bg-gray-700' : ''); ?>"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
                    <?php if(auth()->user()->role === 'admin'): ?>
                        <a href="<?php echo e(route('dashboard', ['role' => 'analytics'])); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('dashboard') && request()->route('role') === 'analytics' ? 'bg-gray-700' : ''); ?>"><i class="fas fa-chart-line mr-2"></i> Analytics</a>
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-800"><i class="fas fa-users mr-2"></i> Vendors</a>
                    <?php elseif(auth()->user()->role === 'vendor'): ?>
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-800"><i class="fas fa-file-pdf mr-2"></i> Application</a>
                    <?php elseif(auth()->user()->role === 'supplier'): ?>
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-800"><i class="fas fa-seedling mr-2"></i> Inventory</a>
                        <a href="<?php echo e(route('supply.orders.view')); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800"><i class="fas fa-shopping-cart mr-2"></i> Orders</a>
                        <a href="<?php echo e(route('dashboard', ['role' => 'analytics'])); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('dashboard') && request()->route('role') === 'analytics' ? 'bg-gray-700' : ''); ?>"><i class="fas fa-chart-line mr-2"></i> Analytics</a>
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-800"><i class="fas fa-industry mr-2"></i> Production</a>
                    <?php elseif(auth()->user()->role === 'manufacturer'): ?>
                        <a href="<?php echo e(route('dashboard', ['role' => 'analytics'])); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('dashboard') && request()->route('role') === 'analytics' ? 'bg-gray-700' : ''); ?>"><i class="fas fa-chart-line mr-2"></i> Analytics</a>
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-800"><i class="fas fa-industry mr-2"></i> Production</a>
                    <?php elseif(auth()->user()->role === 'wholesaler'): ?>
                        <a href="<?php echo e(route('market.orders')); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800"><i class="fas fa-shopping-cart mr-2"></i> Orders</a>
                    <?php elseif(auth()->user()->role === 'retailer'): ?>
                        <a href="<?php echo e(route('dashboard', ['role' => 'analytics'])); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('dashboard') && request()->route('role') === 'analytics' ? 'bg-gray-700' : ''); ?>"><i class="fas fa-chart-line mr-2"></i> Analytics</a>
                    <?php elseif(auth()->user()->role === 'customer'): ?>
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-800"><i class="fas fa-star mr-2"></i> Feedback</a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('logout')); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('chat.index')); ?>">
                                <i class="fas fa-comments"></i> Chat
                                <span class="badge bg-danger total-unread-count d-none">0</span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col ml-0 md:ml-64">
            <!-- Top Bar -->
            <header class="bg-gray-800 text-white p-4 flex justify-between items-center shadow-md flex-shrink-0">
                <h1 class="text-xl font-semibold"><?php echo e(ucfirst(request()->route('role'))); ?> Dashboard</h1>
                <?php if(auth()->guard()->check()): ?>
                    <span class="text-sm">Welcome, <?php echo e(auth()->user()->name); ?> (<?php echo e(ucfirst(auth()->user()->role)); ?>)</span>
                <?php endif; ?>
            </header>

            <!-- Content and Chat Container -->
            <div class="flex-1 flex overflow-hidden">
                <!-- Main Dashboard Content -->
                <div class="flex-1 overflow-y-auto bg-gray-100 p-6">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>

                <!-- Chat Section -->
                <?php if(auth()->guard()->check()): ?>
                <div class="w-96 border-l border-gray-200 bg-white flex-shrink-0">
                    <div id="app" class="h-full">
                        <chat-widget :user-id="<?php echo e(Auth::id()); ?>"></chat-widget>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\supply-chain-project\laravel-app\resources\views/layouts/app.blade.php ENDPATH**/ ?>