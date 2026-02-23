<!-- Start Services Area -->
    <div class="services">
        <div class="container">
            <div class="row">
                <div class="col-12 position-relative">
                    <div class="swiper services-slider">
                        <div class="swiper-wrapper">
                            <!-- Slider Item -->

                            <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($banner->url); ?>" target="_blank" class="swiper-slide">
                                <img src="<?php echo e(url($banner->image)); ?>" alt="OTA" />
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>

                    </div>
                    <div class="swiper-pagination"></div>

                </div>
            </div>
        </div>
    </div>
    <!-- End Servies Area -->
<?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/promotional_banners.blade.php ENDPATH**/ ?>