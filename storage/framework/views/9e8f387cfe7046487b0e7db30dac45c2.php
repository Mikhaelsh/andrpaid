<?php $__env->startSection('title', 'Sent Messages'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/inboxes.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('styles/inbox.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarInbox', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="message-card">
                    <div class="message-toolbar">
                        <div>
                            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-muted border">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="message-header">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <h4 class="fw-bold mb-0 text-dark"><?php echo e($inbox->subject ?? '(No Subject)'); ?></h4>
                            <span class="badge bg-light text-secondary border">
                                <?php echo e($inbox->created_at->format('M d, Y, h:i A')); ?>

                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=<?php echo e($inbox->fromUser->name); ?>&background=random&color=fff"
                                 class="sender-avatar shadow-sm">

                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark fs-5">
                                    <?php echo e($inbox->fromUser->name); ?>

                                </div>

                                <div class="message-meta d-flex flex-column flex-md-row gap-md-2">
                                    <span>
                                        <span class="text-muted">From:</span>
                                        &lt;<?php echo e($inbox->fromUser->email ?? 'no-reply'); ?>&gt;
                                    </span>

                                    <span class="d-none d-md-inline text-muted">•</span>

                                    <span>
                                        <span class="text-muted">To:</span>
                                        <span class="text-dark fw-medium"><?php echo e($inbox->toUser->name); ?> &lt;<?php echo e($inbox->fromUser->email ?? 'no-reply'); ?>&gt; <?php echo e($inbox->toUser->id === Auth::user()->id ? '(Me)' : ''); ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="message-body">
                        <?php if(!empty($inbox->body)): ?>
                            <?php echo nl2br(e($inbox->body)); ?>

                        <?php else: ?>
                            <div class="text-muted fst-italic p-3 bg-light rounded border border-light">
                                <i class="bi bi-info-circle me-2"></i> No content provided in this message.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/pages/inbox.blade.php ENDPATH**/ ?>