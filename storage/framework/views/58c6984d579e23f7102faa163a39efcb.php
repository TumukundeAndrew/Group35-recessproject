

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('header', 'Admin Dashboard'); ?>

<?php $__env->startSection('sidebar'); ?>
<div class="px-4">
    <div class="space-y-2">
        <!-- Chat Section -->
        <div class="mb-4">
            <h3 class="text-xs uppercase text-gray-400 mb-2">Communication</h3>
            <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg" data-bs-toggle="list" data-target="#chat">
                <i class="fas fa-comments mr-3"></i>
                <span>Chat</span>
                <span class="badge bg-danger float-end total-unread-count d-none ml-2">0</span>
            </a>
        </div>

        <!-- Vendor Management -->
        <div class="mb-4">
            <h3 class="text-xs uppercase text-gray-400 mb-2">Vendor Management</h3>
            <a href="<?php echo e(route('admin.vendors.validation')); ?>" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg <?php echo e(request()->routeIs('admin.vendors.*') ? 'bg-gray-700' : ''); ?>">
                <i class="fas fa-user-check mr-3"></i>
                <span>Vendor Validation</span>
            </a>
            <a href="<?php echo e(route('admin.facility-visits')); ?>" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                <i class="fas fa-calendar-check mr-3"></i>
                <span>Facility Visits</span>
            </a>
        </div>

        <!-- Workforce Management -->
        <div class="mb-4">
            <h3 class="text-xs uppercase text-gray-400 mb-2">Workforce</h3>
            <a href="<?php echo e(route('admin.workforce')); ?>" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                <i class="fas fa-users mr-3"></i>
                <span>Workforce Assignment</span>
            </a>
        </div>

        <!-- User Management -->
        <div class="mb-4">
            <h3 class="text-xs uppercase text-gray-400 mb-2">System</h3>
            <a href="<?php echo e(route('admin.users')); ?>" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                <i class="fas fa-user-cog mr-3"></i>
                <span>User Management</span>
            </a>
            <a href="<?php echo e(route('admin.reports')); ?>" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                <i class="fas fa-file-alt mr-3"></i>
                <span>Reports</span>
            </a>
            <a href="<?php echo e(route('analytics.index')); ?>" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">
                <i class="fas fa-chart-line mr-3"></i>
                <span>Analytics</span>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="tab-content">
    <!-- Chat Tab -->
    <div class="tab-pane fade show active" id="chat">
        <div class="card">
            <div class="card-header">
                <h5>Messages</h5>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Contacts List -->
                    <div class="col-md-4 border-end">
                        <div class="contacts-list overflow-auto" style="height: calc(100vh - 250px);">
                            <?php $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="contact-item p-3 border-bottom" 
                                    data-contact-id="<?php echo e($contact->id); ?>"
                                    role="button">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0"><?php echo e($contact->name); ?></h6>
                                            <small class="text-muted"><?php echo e(ucfirst($contact->role)); ?></small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill message-count d-none" 
                                            id="unread-count-<?php echo e($contact->id); ?>">0</span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Chat Area -->
                    <div class="col-md-8">
                        <div class="chat-container d-flex flex-column" style="height: calc(100vh - 250px);">
                            <!-- Chat Header -->
                            <div class="chat-header p-3 border-bottom" id="chat-header">
                                <h5 class="mb-0">Select a contact to start chatting</h5>
                            </div>

                            <!-- Messages Area -->
                            <div class="chat-messages flex-grow-1 p-3 overflow-auto" id="chat-messages">
                                <!-- Messages will be loaded here -->
                            </div>

                            <!-- Message Input -->
                            <div class="chat-input p-3 border-top">
                                <form id="message-form" class="d-none">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="message-input" 
                                            placeholder="Type your message...">
                                        <button class="btn btn-primary" type="submit">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="tab-pane fade" id="dashboard">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Pending Validations</h3>
                <div class="text-3xl font-bold text-blue-600"><?php echo e($pendingValidations ?? 0); ?></div>
                <p class="text-gray-600">Vendor applications awaiting review</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Active Users</h3>
                <div class="text-3xl font-bold text-green-600"><?php echo e($activeUsers ?? 0); ?></div>
                <p class="text-gray-600">Total users on the platform</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Today's Orders</h3>
                <div class="text-3xl font-bold text-purple-600"><?php echo e($todayOrders ?? 0); ?></div>
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
                            <?php $__empty_1 = true; $__currentLoopData = $recentActivity ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo e($activity['action'] ?? 'Unknown Action'); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo e($activity['user'] ?? 'Unknown User'); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo e($activity['time'] ?? 'Unknown Time'); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <?php echo e($activity['status'] ?? 'Unknown Status'); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        No recent activity
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .cursor-pointer { cursor: pointer; }
    .cursor-pointer:hover { background-color: #f8f9fa; }
    .chat-message {
        max-width: 70%;
        margin-bottom: 1rem;
        padding: 0.75rem;
        border-radius: 1rem;
    }
    .message-sent {
        background-color: #007bff;
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 0.25rem;
    }
    .message-received {
        background-color: #e9ecef;
        margin-right: auto;
        border-bottom-left-radius: 0.25rem;
    }
    .contact-item {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    .contact-item:hover {
        background-color: #f8f9fa;
    }
    .contact-item.active {
        background-color: #e9ecef;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Remove duplicate JavaScript code and just include chat.js -->
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\supply-chain-project\laravel-app\resources\views/dashboards/admin/index.blade.php ENDPATH**/ ?>