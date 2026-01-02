<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="styles/auth.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__currentLoopData = $lecturers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lecturer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="/<?php echo e($lecturer->user->profileId); ?>/overview"><?php echo e($lecturer->user->name); ?></a>
        <br><br><hr><br><br>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/find.blade.php ENDPATH**/ ?>