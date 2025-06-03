<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php if(auth()->guard()->check()): ?>
    <meta name="user-id" content="<?php echo e(Auth::id()); ?>">
    <?php endif; ?>
    <title><?php echo e(config('app.name', 'Supply Chain Management')); ?> - <?php echo $__env->yieldContent('title'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php echo $__env->yieldPushContent('styles'); ?>

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
        .dashboard-card,
        .validation-section,
        .recent-activity {
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

        .main-content {
            transition: margin-right 0.3s ease;
        }
        
        .main-content-shifted {
            margin-right: 384px;
        }
        
        @media (max-width: 768px) {
            .main-content-shifted {
                margin-right: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-4">
                <h2 class="text-2xl font-semibold">SCM System</h2>
            </div>
            
            <!-- Sidebar Navigation -->
            <nav class="mt-4">
                <?php echo $__env->yieldContent('sidebar'); ?>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow flex-shrink-0">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800"><?php echo $__env->yieldContent('header'); ?></h2>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-4"><?php echo e(Auth::user()->name); ?></span>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content and Messages Layout -->
            <div class="flex-1 flex overflow-hidden">
                <!-- Main Dashboard Content -->
                <div class="flex-1 overflow-y-auto bg-gray-50 p-6">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>

                <!-- Messages Section -->
                <?php if(auth()->guard()->check()): ?>
                <div class="w-96 border-l border-gray-200 bg-white flex-shrink-0 flex flex-col">
                    <div class="p-4 bg-gray-50 border-b">
                        <h3 class="font-semibold flex items-center gap-2">
                            <span class="text-lg">💬</span>
                            Messages
                        </h3>
                    </div>
                    <div class="flex-1 flex flex-col" id="app">
                        <chat-widget :user-id="<?php echo e(Auth::id()); ?>"></chat-widget>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script>
        // Handle main content margin when chat is expanded/collapsed
        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.getElementById('mainContent');
            
            // Create a MutationObserver to watch for class changes on the chat panel
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.target.classList.contains('chat-panel-collapsed')) {
                        mainContent.classList.remove('main-content-shifted');
                    } else {
                        mainContent.classList.add('main-content-shifted');
                    }
                });
            });

            // Start observing the chat panel
            const chatPanel = document.querySelector('.chat-panel');
            if (chatPanel) {
                observer.observe(chatPanel, { attributes: true, attributeFilter: ['class'] });
                // Set initial state
                if (!chatPanel.classList.contains('chat-panel-collapsed')) {
                    mainContent.classList.add('main-content-shifted');
                }
            }
        });
    </script>

    <style>
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
</body>
</html><?php /**PATH C:\xampp\htdocs\supply-chain-project\laravel-app\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>