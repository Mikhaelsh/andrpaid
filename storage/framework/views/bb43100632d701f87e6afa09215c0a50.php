<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="styles/auth.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-8">
                    <div class="card auth-card border-0">
                        <div class="card-body">
                            <div class="auth-logo-container mb-1">
                                <img src="<?php echo e(asset('images/logo.jpeg')); ?>" alt="Logo" class="auth-logo mx-auto d-block">
                                <h1 class="h3 fw-bold text-center mb-1" style="color: var(--primary-blue);">AndRPaid | Login
                                </h1>
                            </div>

                            <form action="/login" method="POST" class="auth-form">
                                <?php echo csrf_field(); ?>

                                <div class="mb-2">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-person-fill"></i>
                                        </span>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="Enter your email" required autocomplete="email">
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="password" class="form-label">Password</label>
                                        <a href="/forgot-password" class="auth-link small">Forgot password?</a>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Enter your password" required autocomplete="current-password">
                                    </div>
                                </div>

                                <div class="d-grid mb-2">
                                    <button type="submit" class="btn auth-btn btn-primary btn-lg">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                    </button>
                                </div>

                                <?php if(session('errorLogin')): ?>
                                    <div class="alert alert-danger show">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <span id="errorMessage"><?php echo e(session('errorLogin')); ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="text-center pt-3 border-top">
                                    <p class="mb-0">
                                        New to AndRPaid?
                                        <a href="/register" class="auth-link">Create an account</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">

                <div class="modal-body">
                    <div class="mb-3 text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>

                    <h4 class="mb-2">Success!</h4>
                    <p class="mb-4"><?php echo e(session('success')); ?></p>

                    <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal">OK</button>
                </div>

            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <?php if(session('success')): ?>
            <script type="module">
                // "type=module" ensures this runs after Vite is done
                if (window.bootstrap) {
                    var myModal = new bootstrap.Modal(document.getElementById('successModal'));
                    myModal.show();
                } else {
                    console.error("Bootstrap is still not loaded!");
                }
            </script>
        <?php endif; ?>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/pages/login.blade.php ENDPATH**/ ?>