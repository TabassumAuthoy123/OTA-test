
<?php $__env->startSection('header_css'); ?>
    <style>
        .cms-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .pages-table {
            width: 100%;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .pages-table th {
            background: #f8f9fa;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 16px;
        }

        .pages-table td {
            padding: 12px 16px;
            font-size: 13px;
            border-top: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 4px;
        }

        .status-dot.active {
            background: #198754;
        }

        .status-dot.inactive {
            background: #dc3545;
        }

        .cms-modal .form-label {
            position: static !important;
            transform: none !important;
            pointer-events: auto !important;
            padding: 0 0 4px 0 !important;
            height: auto !important;
            font-size: 13px !important;
        }

        .cms-modal .form-control,
        .cms-modal .form-select,
        .cms-modal textarea {
            padding: 8px 12px !important;
            height: auto !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="cms-header">
        <div>
            <h4 class="mb-0 fw-bold">📄 Static Pages</h4><small class="text-muted">Manage About Us, Terms, Privacy Policy,
                etc.</small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i
                class="fas fa-plus me-1"></i> Add Page</button>
    </div>

    <table class="pages-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Status</th>
                <th>URL</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($i + 1); ?></td>
                    <td><strong><?php echo e($item->title); ?></strong></td>
                    <td><code><?php echo e($item->slug); ?></code></td>
                    <td><span
                            class="status-dot <?php echo e($item->is_active ? 'active' : 'inactive'); ?>"></span><?php echo e($item->is_active ? 'Active' : 'Inactive'); ?>

                    </td>
                    <td><a href="<?php echo e(url('page/' . $item->slug)); ?>" target="_blank" class="text-primary"><i
                                class="fas fa-external-link-alt"></i> View</a></td>
                    <td>
                        <a href="<?php echo e(url('cms/pages/' . $item->id . '/edit')); ?>" class="btn btn-sm btn-outline-primary"><i
                                class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(<?php echo e($item->id); ?>)"><i
                                class="fas fa-trash"></i></button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No pages yet. Click "Add Page" to create one.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="modal fade cms-modal" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="<?php echo e(url('cms/pages')); ?>" method="POST"><?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Add Static Page</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Title *</label><input type="text"
                                    name="title" class="form-control" required placeholder="About Us"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Slug *</label><input type="text"
                                    name="slug" class="form-control" required placeholder="about-us"></div>
                        </div>
                        <div class="mb-3"><label class="form-label fw-bold">Content *</label><textarea name="content"
                                class="form-control" rows="10" required
                                placeholder="Write page content (HTML supported)..."></textarea></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Meta Title (SEO)</label><input
                                    type="text" name="meta_title" class="form-control"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Meta Description (SEO)</label><input
                                    type="text" name="meta_description" class="form-control"></div>
                        </div>
                        <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input"
                                checked><label class="form-check-label">Active</label></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Create
                            Page</button></div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_js'); ?>
    <script>
        function deleteItem(id) { if (!confirm('Delete this page?')) return; $.ajax({ url: '<?php echo e(url("cms/pages")); ?>/' + id, type: 'DELETE', data: { _token: '<?php echo e(csrf_token()); ?>' }, success: () => location.reload() }); }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/cms/pages.blade.php ENDPATH**/ ?>