<?php $__env->startSection('header_css'); ?>
    <link href="<?php echo e(url('assets')); ?>/admin-assets/css/switchery.min.css" rel="stylesheet" />
    <style>
        .box{
            border: 1px #084277;
            border-radius: 4px;
            padding: 15px;
            border-style: solid;
        }

        .box a.settings_btn{
            display: inline-block;
            background: #084277;
            padding: 4px 12px;
            border-radius: 4px;
            color: white;
            font-size: 15px;
            text-shadow: 1px 1px 3px black;
        }

        .gds_logo {
            display: block;
            width: 100%;
            position: relative;
            height: 50px; /* Adjust this height as needed */
        }

        .gds_logo img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 100%;
            height: auto;
        }
    </style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="alert alert-success mb-0" role="alert">
                        <h5 class="alert-heading mb-0">Setup GDS</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <?php $__currentLoopData = $gds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-6 mb-3">
                            <div class="box">
                                <div class="row">
                                    <div class="col-lg-2" style="padding-right: 0px;">
                                        <div class="gds_logo">
                                            <img src="<?php echo e(url($item->logo)); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-10">
                                        <h5 style="margin-bottom: 5px"><?php echo e($item->name); ?></h5>
                                        <p class="mb-0" style="font-size: 12px"><?php echo e($item->description); ?></p>
                                    </div>
                                </div>
                                <hr style="background: #084277;">
                                <div class="row">
                                    <div class="col-lg-6">

                                        <?php if($item->code == 'amadeus'): ?>
                                        <a href="javascript:void(0)" onclick="gdsSetupNotice()" class="settings_btn"><i class="fas fa-cog"></i> Settings</a>
                                        <?php endif; ?>

                                        <?php if($item->code == 'sabre'): ?>
                                        <a href="<?php echo e(url('edit/gds')); ?>/<?php echo e($item->code); ?>" class="settings_btn"><i class="fas fa-cog"></i> Settings</a>
                                        <?php endif; ?>

                                        <?php if($item->code == 'flyhub'): ?>
                                        <a href="<?php echo e(url('edit/gds')); ?>/<?php echo e($item->code); ?>" class="settings_btn"><i class="fas fa-cog"></i> Settings</a>
                                        <?php endif; ?>

                                    </div>
                                    <div class="col-lg-6 text-end">
                                        <label for="<?php echo e($item->code); ?>"><b>Status:</b></label>
                                        <input type="checkbox" id="<?php echo e($item->code); ?>" class="switchery_checkbox" <?php if($item->status == 1): ?> checked="" <?php endif; ?> value="<?php echo e($item->code); ?>" onchange="changeGdsStatus(this.value)" data-size="small" data-toggle="switchery" data-color="#53c024" data-secondary-color="#df3554">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_js'); ?>
    <script src="<?php echo e(url('assets')); ?>/admin-assets/js/switchery.min.js"></script>
    <script>
        $('[data-toggle="switchery"]').each(function (idx, obj) {
            new Switchery($(this)[0], $(this).data());
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function gdsSetupNotice(){
            toastr.error("Amadeus is not Configured Yet, Contact with Developer");
            return false;
        }

        function changeGdsStatus(gds_code){

            var formData = new FormData();
            formData.append("gds_code", gds_code);

            if ($('#'+gds_code).prop('checked')) {
                formData.append("gds_status", 1);
            } else {
                formData.append("gds_status", 0);
            }

            $.ajax({
                data: formData,
                url: "<?php echo e(url('gds/status/update')); ?>",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                    if ($('#'+gds_code).prop('checked')) {
                        toastr.success("Gds is Activated");
                    } else {
                        toastr.error("Gds is Inactivated");
                    }

                },
                error: function (data) {
                    toastr.error("Someting Went Wrong! Please Try Again");
                }
            });


        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/setup_gds.blade.php ENDPATH**/ ?>