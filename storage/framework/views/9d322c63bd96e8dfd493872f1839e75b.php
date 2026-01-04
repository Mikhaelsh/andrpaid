<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AndRPaid | <?php echo $__env->yieldContent('title'); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo e(asset('styles/main.css')); ?>">
    <?php echo $__env->yieldContent('additionalCSS'); ?>
</head>

<body>
    <div class="page-wrapper-foot-nav">

        <?php if (! (View::hasSection('hideNavbar'))): ?>
            <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>

        <main class="flex-grow-1">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <?php if (! (View::hasSection('hideFooter'))): ?>
            <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>

    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>









<?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/layouts/app.blade.php ENDPATH**/ ?>