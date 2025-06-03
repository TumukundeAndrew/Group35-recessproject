

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Facility Visits Management</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Scheduled Visits -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Scheduled Facility Visits</h3>
        </div>
        <div class="card-body">
            <?php if($scheduledVisits->isEmpty()): ?>
                <p>No scheduled facility visits.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Visit Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $scheduledVisits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($visit->id); ?></td>
                                <td><?php echo e($visit->company_name); ?></td>
                                <td><?php echo e($visit->site_visit_date->format('Y-m-d H:i')); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($visit->status_color); ?>">
                                        <?php echo e($visit->status_label); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('vendor-applications.show', $visit)); ?>" 
                                       class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                                    <?php if($visit->status === \App\Models\VendorApplication::STATUS_SITE_VISIT_SCHEDULED): ?>
                                        <form action="<?php echo e(route('vendor-applications.complete-visit', $visit)); ?>" 
                                              method="POST" 
                                              class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-success">
                                                Mark as Completed
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($scheduledVisits->links()); ?>

            <?php endif; ?>
        </div>
    </div>

    <!-- Completed Visits -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Completed Facility Visits</h3>
        </div>
        <div class="card-body">
            <?php if($completedVisits->isEmpty()): ?>
                <p>No completed facility visits.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Visit Date</th>
                                <th>Completion Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $completedVisits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($visit->id); ?></td>
                                <td><?php echo e($visit->company_name); ?></td>
                                <td><?php echo e($visit->site_visit_date->format('Y-m-d H:i')); ?></td>
                                <td><?php echo e($visit->updated_at->format('Y-m-d H:i')); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($visit->status_color); ?>">
                                        <?php echo e($visit->status_label); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('vendor-applications.show', $visit)); ?>" 
                                       class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                                    <?php if($visit->status === \App\Models\VendorApplication::STATUS_SITE_VISIT_COMPLETED): ?>
                                        <div class="btn-group">
                                            <form action="<?php echo e(route('vendor-applications.approve', $visit)); ?>" 
                                                  method="POST" 
                                                  class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    Approve Vendor
                                                </button>
                                            </form>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal<?php echo e($visit->id); ?>">
                                                Reject Vendor
                                            </button>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal<?php echo e($visit->id); ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="<?php echo e(route('vendor-applications.reject', $visit)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Vendor Application</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="rejection_reason">Rejection Reason</label>
                                                                <textarea name="rejection_reason" 
                                                                          id="rejection_reason" 
                                                                          class="form-control" 
                                                                          rows="3" 
                                                                          required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject Application</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($completedVisits->links()); ?>

            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\supply-chain-project\laravel-app\resources\views/admin/facility-visits.blade.php ENDPATH**/ ?>