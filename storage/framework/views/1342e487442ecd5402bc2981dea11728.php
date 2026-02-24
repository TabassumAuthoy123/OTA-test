<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $__env->yieldContent('title', 'Book Flights at Best Price'); ?> - <?php echo e(config('app.name', 'SkyTrip')); ?></title>
    <meta name="description"
        content="<?php echo $__env->yieldContent('meta_description', 'Book domestic and international flights at the best price. Instant confirmation, secure payment, 24/7 support.'); ?>" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(url('assets')); ?>/img/favicon.svg" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="<?php echo e(url('assets')); ?>/nanopkg-assets/vendor/fontawesome-free-6.3.0-web/css/all.min.css"
        rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="<?php echo e(url('assets')); ?>/admin-assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- B2C Stylesheet -->
    <link href="<?php echo e(url('assets')); ?>/b2c/css/b2c.css?v=<?php echo e(time()); ?>" rel="stylesheet" />

    <!-- Toastr -->
    <link href="<?php echo e(url('assets')); ?>/nanopkg-assets/vendor/toastr/build/toastr.min.css" rel="stylesheet" />

    <?php echo $__env->yieldContent('styles'); ?>
</head>

<body class="b2c-body">
    <!-- Navbar -->
    <?php echo $__env->make('b2c.layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <?php echo $__env->make('b2c.layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Scripts -->
    <script src="<?php echo e(url('assets')); ?>/admin-assets/vendor/jQuery/jquery.min.js"></script>
    <script src="<?php echo e(url('assets')); ?>/admin-assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(url('assets')); ?>/nanopkg-assets/vendor/toastr/build/toastr.min.js"></script>

    <script>
        // CSRF setup for AJAX
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/b2c/layouts/master.blade.php ENDPATH**/ ?>