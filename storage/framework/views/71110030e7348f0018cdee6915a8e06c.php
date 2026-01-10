<footer class="glass-footer mt-auto">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center py-4">

        <div class="footer-brand mb-3 mb-md-0 d-flex align-items-center">
            <span class="text-white-50 small">© <?php echo e(date('Y')); ?></span>
            <span class="fw-bold text-white ms-1"><?php echo e(__('footer.brand')); ?></span>
            <span class="text-white-50 small ms-1 me-3"><?php echo e(__('footer.rights')); ?></span>

            <div class="vr bg-white opacity-25 me-3" style="height: 15px;"></div>

            <div class="d-flex align-items-center gap-2 small">
                <a href="<?php echo e(route('lang.switch', 'en')); ?>"
                    class="text-decoration-none <?php echo e(app()->getLocale() == 'en' ? 'text-white fw-bold' : 'text-white-50'); ?>">
                    EN
                </a>
                <span class="text-white-50">/</span>
                <a href="<?php echo e(route('lang.switch', 'id')); ?>"
                    class="text-decoration-none <?php echo e(app()->getLocale() == 'id' ? 'text-white fw-bold' : 'text-white-50'); ?>">
                    ID
                </a>
            </div>
        </div>

        <?php if (\Illuminate\Support\Facades\Blade::check('notadmin')): ?>
            <div>
                <button type="button" class="btn btn-glass-feedback d-flex align-items-center gap-2" data-bs-toggle="modal"
                    data-bs-target="#feedbackModal">
                    <div class="icon-circle">
                        <i class="bi bi-chat-heart-fill"></i>
                    </div>
                    <span><?php echo e(__('footer.feedback_btn')); ?></span>
                </button>
            </div>
        <?php endif; ?>
    </div>
</footer>

<?php if (\Illuminate\Support\Facades\Blade::check('notadmin')): ?>
    <?php
        $reportTypes = \App\Models\ReportType::all();
    ?>

    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="horizon-card">
                    <div class="horizon-sidebar">
                        <div class="horizon-sidebar-icon">
                            <i class="bi bi-chat-heart-fill"></i>
                        </div>
                        <div>
                            <h4 class="horizon-sidebar-title"><?php echo e(__('footer.sidebar_title')); ?></h4>
                            <p class="horizon-sidebar-text"><?php echo e(__('footer.sidebar_desc')); ?></p>
                        </div>
                    </div>

                    <div class="horizon-main">

                        <button type="button" class="horizon-close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>

                        <h3 class="fw-bold text-dark mb-4"><?php echo e(__('footer.modal_title')); ?></h3>

                        <form action="/report/submit" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label class="horizon-label"><?php echo e(__('footer.label_topic')); ?></label>
                                <select class="horizon-input" name="type" required>
                                    <option value="" selected disabled><?php echo e(__('footer.select_placeholder')); ?></option>
                                    <?php $__currentLoopData = $reportTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reportType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($reportType->reportTypeId); ?>"><?php echo e($reportType->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="horizon-label"><?php echo e(__('footer.label_message')); ?></label>
                                <textarea class="horizon-input" name="description" rows="3" placeholder="<?php echo e(__('footer.placeholder_message')); ?>"
                                    required></textarea>
                            </div>

                            <button type="submit" class="horizon-submit-btn">
                                <?php echo e(__('footer.btn_submit')); ?>

                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(session('successReport')): ?>
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-success text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-check-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text"><?php echo e(__('common.success')); ?></h4>
                        <p class="text-muted mb-4 fs-5"><?php echo e(session('successReport')); ?></p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm" data-bs-dismiss="modal">
                            <?php echo e(__('common.continue')); ?>

                        </button>
                    </div>

                </div>
            </div>
        </div>

        <?php $__env->startPush('scripts'); ?>
            <script type="module">
                if (window.bootstrap) {
                    setTimeout(() => {
                        var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
                        myModal.show();
                    }, 300);
                }
            </script>
        <?php $__env->stopPush(); ?>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/partials/footer.blade.php ENDPATH**/ ?>