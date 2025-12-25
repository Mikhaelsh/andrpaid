<footer class="glass-footer mt-auto">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center py-4">

        <div class="footer-brand mb-3 mb-md-0">
            <span class="text-white-50 small">© {{ date('Y') }}</span>
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

        <div class="modal-content sophisticated-card border-0">

            <div class="card-gradient-strip"></div>

            <div class="p-4 pt-3">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Feedback & Ideas</h5>
                        <p class="text-muted small mb-0">Help us improve the AndRPaid experience.</p>
                    </div>
                    <button type="button" class="btn-close-sophisticated" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form action="/feedback/submit" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-secondary ls-1">Category</label>
                        <select class="form-select input-velvet" name="type" required>
                            <option value="" selected disabled>Select...</option>
                            <option value="bug">Bug Report</option>
                            <option value="feature">New Feature</option>
                            <option value="ui">Design / UI</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-secondary ls-1">Description</label>
                        <textarea class="form-control input-velvet" name="description" rows="4" placeholder="What's on your mind? Be specific!" required></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-sophisticated-primary">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
