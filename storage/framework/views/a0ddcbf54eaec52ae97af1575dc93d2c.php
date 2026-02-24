

<?php $__env->startSection('header_css'); ?>
    <style>
        .cms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px;
        }

        .cms-item {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .cms-item img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .cms-item h6 {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .cms-item .meta {
            font-size: 12px;
            color: #6c757d;
        }

        .cms-item .actions {
            display: flex;
            gap: 6px;
            margin-top: 10px;
        }

        .cms-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
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

        /* Fix modal form labels */
        .cms-modal .form-label {
            position: static !important;
            transform: none !important;
            pointer-events: auto !important;
            padding: 0 0 4px 0 !important;
            height: auto !important;
            font-size: 13px !important;
            color: #212529 !important;
            white-space: normal !important;
            z-index: auto !important;
            border: none !important;
            opacity: 1 !important;
        }

        .cms-modal .form-control,
        .cms-modal .form-select {
            padding: 8px 12px !important;
            height: auto !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="cms-header">
        <div>
            <h4 class="mb-0 fw-bold">🖼️ CMS Banners</h4>
            <small class="text-muted">Manage hero banners for the B2C landing page</small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-1"></i> Add Banner
        </button>
    </div>

    <div class="cms-grid">
        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="cms-item">
                <?php if($item->image): ?>
                    <img src="<?php echo e(url($item->image)); ?>" alt="<?php echo e($item->title); ?>">
                <?php else: ?>
                    <div
                        style="height:140px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
                        <i class="fas fa-image fa-2x text-muted"></i>
                    </div>
                <?php endif; ?>
                <h6><?php echo e($item->title); ?></h6>
                <p class="meta mb-1"><?php echo e(Str::limit($item->subtitle, 60)); ?></p>
                <div class="meta">
                    <span class="status-dot <?php echo e($item->is_active ? 'active' : 'inactive'); ?>"></span>
                    <?php echo e($item->is_active ? 'Active' : 'Inactive'); ?> · Position: <?php echo e($item->position); ?>

                </div>
                <div class="actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="editItem(<?php echo e(json_encode($item)); ?>)">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(<?php echo e($item->id); ?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center text-muted py-5" style="grid-column:1/-1;">
                <i class="fas fa-image fa-3x mb-3 d-block"></i>
                No banners yet. Click "Add Banner" to create one.
            </div>
        <?php endif; ?>
    </div>

    
    <div class="modal fade cms-modal" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(url('cms/banners')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Add Banner</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold">Title *</label><input type="text" name="title"
                                class="form-control" required></div>
                        <div class="mb-3"><label class="form-label fw-bold">Subtitle</label><input type="text"
                                name="subtitle" class="form-control"></div>
                        <div class="mb-3"><label class="form-label fw-bold">Image</label><input type="file" name="image"
                                class="form-control" accept="image/*"></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">CTA Text</label><input type="text"
                                    name="cta_text" class="form-control" placeholder="Book Now"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">CTA URL</label><input type="text"
                                    name="cta_url" class="form-control" placeholder="/flights"></div>
                        </div>
                        <div class="row">
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Position</label><input type="number"
                                    name="position" class="form-control" value="0"></div>
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Starts At</label><input type="date"
                                    name="starts_at" class="form-control"></div>
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Expires At</label><input type="date"
                                    name="expires_at" class="form-control"></div>
                        </div>
                        <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input"
                                id="addActive" checked><label class="form-check-label" for="addActive">Active</label></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Cancel</button><button type="submit"
                            class="btn btn-primary">Create</button></div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade cms-modal" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Banner</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold">Title *</label><input type="text" name="title"
                                id="eTitle" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label fw-bold">Subtitle</label><input type="text"
                                name="subtitle" id="eSubtitle" class="form-control"></div>
                        <div class="mb-3"><label class="form-label fw-bold">Image (leave empty to keep
                                current)</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">CTA Text</label><input type="text"
                                    name="cta_text" id="eCta" class="form-control"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">CTA URL</label><input type="text"
                                    name="cta_url" id="eCtaUrl" class="form-control"></div>
                        </div>
                        <div class="row">
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Position</label><input type="number"
                                    name="position" id="ePos" class="form-control"></div>
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Starts At</label><input type="date"
                                    name="starts_at" id="eStart" class="form-control"></div>
                            <div class="col-4 mb-3"><label class="form-label fw-bold">Expires At</label><input type="date"
                                    name="expires_at" id="eExpire" class="form-control"></div>
                        </div>
                        <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input"
                                id="eActive"><label class="form-check-label" for="eActive">Active</label></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Cancel</button><button type="submit"
                            class="btn btn-primary">Update</button></div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_js'); ?>
    <script>
        function editItem(item) {
            $('#editForm').attr('action', '<?php echo e(url("cms/banners")); ?>/' + item.id);
            $('#eTitle').val(item.title);
            $('#eSubtitle').val(item.subtitle);
            $('#eCta').val(item.cta_text);
            $('#eCtaUrl').val(item.cta_url);
            $('#ePos').val(item.position);
            $('#eStart').val(item.starts_at ? item.starts_at.substring(0, 10) : '');
            $('#eExpire').val(item.expires_at ? item.expires_at.substring(0, 10) : '');
            $('#eActive').prop('checked', item.is_active);
            $('#editModal').modal('show');
        }
        function deleteItem(id) {
            if (!confirm('Delete this banner?')) return;
            $.ajax({ url: '<?php echo e(url("cms/banners")); ?>/' + id, type: 'DELETE', data: { _token: '<?php echo e(csrf_token()); ?>' }, success: () => location.reload() });
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/cms/banners.blade.php ENDPATH**/ ?>