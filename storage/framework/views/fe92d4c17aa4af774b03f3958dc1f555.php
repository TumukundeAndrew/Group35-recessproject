

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Vendor Validation Dashboard</h2>

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

    <!-- Pending Applications -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Pending Applications</h3>
        </div>
        <div class="card-body">
            <?php if($pendingApplications->isEmpty()): ?>
                <p>No pending applications.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Submitted Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pendingApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($application->id); ?></td>
                                <td><?php echo e($application->company_name); ?></td>
                                <td><?php echo e($application->created_at->format('Y-m-d')); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($application->status_color); ?>">
                                        <?php echo e($application->status_label); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('vendor-applications.show', $application)); ?>" 
                                       class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                                    <button class="btn btn-sm btn-success validate-docs-btn"
                                            data-application-id="<?php echo e($application->id); ?>">
                                        Validate Documents
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($pendingApplications->links()); ?>

            <?php endif; ?>
        </div>
    </div>

    <!-- Validated Applications -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Validated Applications</h3>
        </div>
        <div class="card-body">
            <?php if($validatedApplications->isEmpty()): ?>
                <p>No validated applications.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Validation Date</th>
                                <th>Status</th>
                                <th>Results</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $validatedApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($application->id); ?></td>
                                <td><?php echo e($application->company_name); ?></td>
                                <td><?php echo e($application->validation_date ? $application->validation_date->format('Y-m-d H:i:s') : 'N/A'); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($application->status_color); ?>">
                                        <?php echo e($application->status_label); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($application->validation_results): ?>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#validationModal<?php echo e($application->id); ?>">
                                            View Results
                                        </button>
                                        
                                        <!-- Validation Results Modal -->
                                        <div class="modal fade" id="validationModal<?php echo e($application->id); ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Validation Results - <?php echo e($application->company_name); ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php
                                                            $results = json_decode($application->validation_results, true);
                                                        ?>
                                                        <?php if($results): ?>
                                                            <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $docType => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="mb-3">
                                                                    <h6><?php echo e(ucwords(str_replace('_', ' ', $docType))); ?></h6>
                                                                    <div class="ps-3">
                                                                        <p class="mb-1">
                                                                            Status: 
                                                                            <span class="badge bg-<?php echo e($result['success'] ? 'success' : 'danger'); ?>">
                                                                                <?php echo e($result['success'] ? 'Valid' : 'Invalid'); ?>

                                                                            </span>
                                                                        </p>
                                                                        <?php if(!$result['success'] && isset($result['message'])): ?>
                                                                            <p class="text-danger mb-1"><?php echo e($result['message']); ?></p>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php else: ?>
                                                            <p>No detailed results available.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">No results</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('vendor-applications.show', $application)); ?>" 
                                       class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php echo e($validatedApplications->links()); ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token to all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Handle document validation
    document.querySelectorAll('.validate-docs-btn').forEach(button => {
        button.addEventListener('click', function() {
            const applicationId = this.dataset.applicationId;
            const button = this;
            
            // Disable button and show loading state
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Validating...';

            fetch(`/vendor-applications/${applicationId}/validate-documents`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show results in a more user-friendly way
                    let message = data.message + '\n\n';
                    if (data.results) {
                        Object.entries(data.results).forEach(([doc, result]) => {
                            message += `${doc.replace('_', ' ').toUpperCase()}: ${result.success ? 'Valid' : 'Invalid'}\n`;
                            if (!result.success && result.message) {
                                message += `Reason: ${result.message}\n\n`;
                            }
                        });
                    }
                    alert(message);
                    
                    // Redirect if provided
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                } else {
                    // Handle error cases
                    alert('Error: ' + data.message);
                    button.disabled = false;
                    button.innerHTML = 'Validate Documents';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while validating documents. Please try again later.');
                button.disabled = false;
                button.innerHTML = 'Validate Documents';
            });
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\supply-chain-project\laravel-app\resources\views/admin/vendor-validation.blade.php ENDPATH**/ ?>