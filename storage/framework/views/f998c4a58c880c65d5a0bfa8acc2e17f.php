<?php $__env->startSection('title', 'Reset Password'); ?>

<?php $__env->startSection('hideNavbar', true); ?>

<?php $__env->startSection('hideFooter', true); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/auth.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-8">
                    <div class="card auth-card border-0">
                        <div class="card-body">
                            <div class="auth-logo-container mb-3">
                                <img src="<?php echo e(asset('images/logo.jpeg')); ?>" alt="Logo" class="auth-logo mx-auto d-block">
                                <h1 class="h3 fw-bold text-center mb-1" style="color: var(--primary-blue);">AndRPaid | Reset Password</h1>
                                <p class="text-center text-muted small">Enter your email and new password.</p>
                            </div>

                            <form action="/login/reset-password" method="POST" class="auth-form" id="resetPasswordForm">
                                <?php echo csrf_field(); ?>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-envelope-fill"></i>
                                        </span>
                                        <input type="email" name="email" id="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            placeholder="e.g. user@example.com" required autocomplete="email" value="<?php echo e(old('email')); ?>">

                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback">
                                                <?php echo e($message); ?>

                                            </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" name="password" id="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            placeholder="Enter new password" required>

                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback">
                                                <?php echo e($message); ?>

                                            </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-shield-lock-fill"></i>
                                        </span>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                                            placeholder="Repeat new password" required>
                                    </div>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="button" id="btnTriggerCaptcha" class="btn auth-btn btn-primary btn-lg">
                                        <i class="bi bi-arrow-repeat me-2"></i>Change Password
                                    </button>
                                </div>

                                <?php if(session('error')): ?>
                                    <div class="alert alert-danger show">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <span><?php echo e(session('error')); ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="text-center pt-3 border-top">
                                    <p class="mb-0">
                                        Remember your password?
                                        <a href="/login" class="auth-link">Back to Login</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade custom-modal-backdrop" id="captchaModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-content p-4 text-center">
                <div class="modal-header border-0 pb-0 justify-content-center">
                    <h5 class="modal-title fw-bold">Security Check</h5>
                </div>
                <div class="modal-body px-4 pt-2 pb-4">
                    <p class="text-muted mb-3">Please solve this math problem to continue.</p>

                    <div class="captcha-question bg-light rounded py-3 border">
                        <span id="mathQuestion"></span>
                    </div>

                    <div class="mt-3">
                        <input type="number" id="captchaInput" class="form-control text-center form-control-lg" placeholder="Type answer here...">
                        <div id="captchaError" class="captcha-error">
                            <i class="bi bi-x-circle me-1"></i> Incorrect answer. Please try again.
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="button" id="btnVerifyCaptcha" class="btn btn-primary fw-bold py-2">
                            VERIFY & CHANGE PASSWORD
                        </button>
                        <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('resetPasswordForm');
            const triggerBtn = document.getElementById('btnTriggerCaptcha');
            const verifyBtn = document.getElementById('btnVerifyCaptcha');

            const mathQuestionEl = document.getElementById('mathQuestion');
            const captchaInput = document.getElementById('captchaInput');
            const captchaError = document.getElementById('captchaError');

            let correctAnswer = 0;
            let captchaModal = null;

            if (window.bootstrap) {
                captchaModal = new bootstrap.Modal(document.getElementById('captchaModal'));
            }

            function generateMathProblem() {
                const num1 = Math.floor(Math.random() * 1000) + 1;
                const num2 = Math.floor(Math.random() * 1000) + 1;

                correctAnswer = num1 + num2;
                mathQuestionEl.textContent = `${num1} + ${num2} = ?`;

                captchaInput.value = '';
                captchaError.style.display = 'none';
            }

            triggerBtn.addEventListener('click', function () {
                if (form.checkValidity()) {
                    generateMathProblem();
                    captchaModal.show();
                } else {
                    form.reportValidity();
                }
            });

            verifyBtn.addEventListener('click', function () {
                const userAnswer = parseInt(captchaInput.value);

                if (userAnswer === correctAnswer) {
                    form.submit();
                } else {
                    captchaError.style.display = 'block';
                    captchaInput.classList.add('is-invalid');
                    setTimeout(() => {
                        generateMathProblem();
                    }, 1000);
                }
            });

            captchaInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    verifyBtn.click();
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/pages/login-forgotPassword.blade.php ENDPATH**/ ?>