<footer class="glass-footer mt-auto">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center py-4">

        <div class="footer-brand mb-3 mb-md-0">
            <span class="text-white-50 small">© <?php echo e(date('Y')); ?></span>
            <span class="fw-bold text-white ms-1">AndRPaid</span>
            <span class="text-white-50 small ms-1">| All rights reserved.</span>
        </div>

        <div>
            <button type="button" class="btn btn-glass-feedback d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                <div class="icon-circle">
                    <i class="bi bi-chat-heart-fill"></i>
                </div>
                <span>Feedback & Reports</span>
            </button>
        </div>
    </div>
</footer>

<div class="modal fade" id="feedbackModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="horizon-card">

                <div class="horizon-sidebar">
                    <div class="horizon-sidebar-icon">
                        <i class="bi bi-chat-heart-fill"></i>
                    </div>
                    <div>
                        <h4 class="horizon-sidebar-title">We Listen</h4>
                        <p class="horizon-sidebar-text">Your feedback directly shapes the future of AndRPaid.</p>
                    </div>
                </div>

                <div class="horizon-main">

                    <button type="button" class="horizon-close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>

                    <h3 class="fw-bold text-dark mb-4">Share Thoughts</h3>

                    <form action="/feedback/submit" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label class="horizon-label">Topic</label>
                            <select class="horizon-input" name="type" required>
                                <option value="" selected disabled>Select...</option>
                                <option value="bug">Report a Bug</option>
                                <option value="feature">Request Feature</option>
                                <option value="ui">UI Polish</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="horizon-label">Message</label>
                            <textarea class="horizon-input" name="description" rows="3"
                                placeholder="What's on your mind?" required></textarea>
                        </div>

                        <button type="submit" class="horizon-submit-btn">
                            Send Feedback
                        </button>
                    </form>

                </div>
            </div>
            </div>
    </div>
</div>
<?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/partials/footer.blade.php ENDPATH**/ ?>