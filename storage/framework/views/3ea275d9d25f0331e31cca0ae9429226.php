
<?php $__env->startSection('header_css'); ?>
    <style>
        .cms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .route-code {
            font-size: 20px;
            font-weight: 700;
            color: #0d6efd;
            letter-spacing: 1px;
        }

        .route-arrow {
            color: #6c757d;
            margin: 0 4px;
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

        .cms-modal .form-label {
            position: static !important;
            transform: none !important;
            pointer-events: auto !important;
            padding: 0 0 4px 0 !important;
            height: auto !important;
            font-size: 13px !important;
        }

        .cms-modal .form-control {
            padding: 8px 12px !important;
            height: auto !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="cms-header">
        <div>
            <h4 class="mb-0 fw-bold">✈️ Popular Routes</h4><small class="text-muted">Manage featured destination
                cards</small>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i
                class="fas fa-plus me-1"></i> Add Route</button>
    </div>

    <div class="cms-grid">
        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="cms-item">
                <?php if($item->image): ?><img src="<?php echo e(url($item->image)); ?>" alt=""><?php endif; ?>
                <div class="route-code"><?php echo e($item->origin_code); ?> <span class="route-arrow">→</span>
                    <?php echo e($item->destination_code); ?></div>
                <div style="font-size:13px;color:#495057;"><?php echo e($item->origin_city); ?> → <?php echo e($item->destination_city); ?></div>
                <?php if($item->starting_price): ?>
                    <div style="font-size:14px;font-weight:600;color:#198754;margin-top:4px;">From
                ৳<?php echo e(number_format($item->starting_price)); ?></div><?php endif; ?>
                <div style="font-size:12px;color:#6c757d;margin-top:4px;"><span
                        class="status-dot <?php echo e($item->is_active ? 'active' : 'inactive'); ?>"></span><?php echo e($item->is_active ? 'Active' : 'Inactive'); ?>

                </div>
                <div style="display:flex;gap:6px;margin-top:10px;">
                    <button class="btn btn-sm btn-outline-primary" onclick="editItem(<?php echo e(json_encode($item)); ?>)"><i
                            class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteItem(<?php echo e($item->id); ?>)"><i
                            class="fas fa-trash"></i></button>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center text-muted py-5" style="grid-column:1/-1;"><i class="fas fa-route fa-3x mb-3 d-block"></i>No
                popular routes yet.</div>
        <?php endif; ?>
    </div>

    <div class="modal fade cms-modal" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(url('cms/routes')); ?>" method="POST" enctype="multipart/form-data"><?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Add Popular Route</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Origin City *</label><input
                                    type="text" name="origin_city" class="form-control" required placeholder="Dhaka"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Origin Code</label><input type="text"
                                    name="origin_code" class="form-control" placeholder="DAC" maxlength="4"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Destination City *</label><input
                                    type="text" name="destination_city" class="form-control" required
                                    placeholder="Cox's Bazar"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Destination Code</label><input
                                    type="text" name="destination_code" class="form-control" placeholder="CXB"
                                    maxlength="4"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Starting Price (৳)</label><input
                                    type="number" name="starting_price" class="form-control" step="0.01"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Position</label><input type="number"
                                    name="position" class="form-control" value="0"></div>
                        </div>
                        <div class="mb-3"><label class="form-label fw-bold">Destination Image</label><input type="file"
                                name="image" class="form-control" accept="image/*"></div>
                        <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input"
                                checked><label class="form-check-label">Active</label></div>
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
                <form id="editForm" method="POST" enctype="multipart/form-data"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Route</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Origin City *</label><input
                                    type="text" name="origin_city" id="eOCity" class="form-control" required></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Origin Code</label><input type="text"
                                    name="origin_code" id="eOCode" class="form-control"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Destination City *</label><input
                                    type="text" name="destination_city" id="eDCity" class="form-control" required></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Destination Code</label><input
                                    type="text" name="destination_code" id="eDCode" class="form-control"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Starting Price</label><input
                                    type="number" name="starting_price" id="ePrice" class="form-control" step="0.01"></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold">Position</label><input type="number"
                                    name="position" id="ePos" class="form-control"></div>
                        </div>
                        <div class="mb-3"><label class="form-label fw-bold">Image</label><input type="file" name="image"
                                class="form-control" accept="image/*"></div>
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
            $('#editForm').attr('action', '<?php echo e(url("cms/routes")); ?>/' + item.id);
            $('#eOCity').val(item.origin_city); $('#eOCode').val(item.origin_code);
            $('#eDCity').val(item.destination_city); $('#eDCode').val(item.destination_code);
            $('#ePrice').val(item.starting_price); $('#ePos').val(item.position);
            $('#eActive').prop('checked', item.is_active); $('#editModal').modal('show');
        }
        function deleteItem(id) { if (!confirm('Delete?')) return; $.ajax({ url: '<?php echo e(url("cms/routes")); ?>/' + id, type: 'DELETE', data: { _token: '<?php echo e(csrf_token()); ?>' }, success: () => location.reload() }); }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/cms/popular-routes.blade.php ENDPATH**/ ?>