<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/setting.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-5">
        <div class="row g-5">

            <div class="col-md-3 d-none d-md-block">
                <nav class="settings-sidebar position-sticky" style="top: 2rem;">
                    <h5 class="fw-bold mb-4 px-3">Settings</h5>
                    <div class="list-group list-group-flush border-0">
                        <a href="#profile" class="list-group-item list-group-item-action active border-0 rounded-3 mb-1">
                            <i class="bi bi-person-circle me-2"></i> Public Profile
                        </a>
                        <a href="#account" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                            <i class="bi bi-shield-lock me-2"></i> Account & Security
                        </a>
                        <a href="#preferences" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                            <i class="bi bi-sliders me-2"></i> Preferences
                        </a>
                    </div>
                </nav>
            </div>

            <div class="col-md-9">

                <section id="profile" class="mb-5 settings-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold">Public Profile</h3>
                    </div>

                    <div class="card settings-card border-0 shadow-sm p-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom">
                                <div class="profile-avatar-wrapper">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo e($user->name); ?>&background=random&color=fff&size=128"
                                        alt="Profile" class="rounded-circle profile-img">
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Profile Picture</h5>
                                    <p class="text-muted small mb-0">
                                        Generated automatically based on your display name.
                                    </p>
                                </div>
                            </div>

                            <form method="POST" action="/settings/update-public-profile">
                                <?php echo csrf_field(); ?>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Display Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo e($user->name); ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Profile ID</label>
                                        <input type="text" class="form-control bg-light text-muted"
                                            value="<?php echo e($user->profileId); ?>" readonly disabled
                                            style="cursor: not-allowed; font-family: monospace;">
                                        <div class="form-text">This is your unique system identifier.</div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">About Me</label>
                                        <textarea class="form-control" name="description" rows="4" placeholder="Tell us a little bit about yourself..."><?php echo e($user->description); ?></textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Province / Location</label>
                                        <select class="form-select" name="province_id">
                                            <?php $__currentLoopData = $allProvinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eachProvince): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e($eachProvince->name === $province->name ? "selected" : ""); ?> value="<?php echo e($eachProvince->provinceId); ?>"><?php echo e($eachProvince->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary px-4">Save Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

                <section id="account" class="mb-5 settings-section">
                    <h3 class="fw-bold mb-4">Account & Security</h3>
                    <div class="card settings-card border-0 shadow-sm p-4">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1">Email Address</h6>
                                    <p class="text-muted mb-0"><?php echo e($user->email); ?></p>
                                </div>
                                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseEmail" aria-expanded="false" aria-controls="collapseEmail">
                                    Change Email
                                </button>
                            </div>

                            <div class="collapse mt-3" id="collapseEmail">
                                <div class="card card-body bg-light border-0">
                                    <form action="/settings/update-email" method="POST">
                                        <?php echo csrf_field(); ?>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">New Email Address</label>
                                                <input type="email" class="form-control" name="email"
                                                    placeholder="Enter new email" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Confirm Password</label>
                                                <input type="password" class="form-control"
                                                    name="password" placeholder="Required for security" required>
                                            </div>
                                            <div class="col-12 text-end">
                                                <button type="button" class="btn btn-sm btn-secondary me-1"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseEmail">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-primary">Update Email</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1">Password</h6>
                                    <p class="text-muted mb-0">Last changed <?php echo e($user->latest_password_updated_at->diffForHumans()); ?></p>
                                </div>
                                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapsePassword" aria-expanded="false"
                                    aria-controls="collapsePassword">
                                    Change Password
                                </button>
                            </div>

                            <div class="collapse mt-3" id="collapsePassword">
                                <div class="card card-body bg-light border-0">
                                    <form action="/settings/update-password" method="POST">
                                        <?php echo csrf_field(); ?>

                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small">Current Password</label>
                                                <input type="password" class="form-control" name="current_password" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small">New Password</label>
                                                <input type="password" class="form-control" name="new_password" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small">Confirm New Password</label>
                                                <input type="password" class="form-control"
                                                    name="new_password_confirmation" required>
                                            </div>

                                            <div class="col-12 text-end mt-3">
                                                <button type="button" class="btn btn-sm btn-secondary me-1"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapsePassword">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-primary">Update
                                                    Password</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1 text-danger">Delete Account</h6>
                                    <p class="text-muted mb-0 small">Permanently remove your account and all data.</p>
                                </div>
                                <button class="btn btn-outline-danger btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseDelete" aria-expanded="false"
                                    aria-controls="collapseDelete">
                                    Delete
                                </button>
                            </div>

                            <div class="collapse mt-3" id="collapseDelete">
                                <div class="card card-body border-danger bg-danger bg-opacity-10">
                                    <h6 class="fw-bold text-danger mb-2">Are you absolutely sure?</h6>
                                    <p class="small text-danger mb-3">
                                        This action cannot be undone. This will permanently delete your profile, papers, and
                                        remove your data from our servers.
                                    </p>

                                    <form action="/settings/delete-account" method="POST">
                                        <?php echo csrf_field(); ?>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-danger">
                                                Type "<span class="user-select-all">DELETE ACCOUNT</span>" to confirm
                                            </label>
                                            <input type="text" class="form-control border-danger"
                                                id="deleteConfirmationInput" placeholder="DELETE ACCOUNT"
                                                autocomplete="off">
                                        </div>

                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-light text-danger border"
                                                data-bs-toggle="collapse" data-bs-target="#collapseDelete">
                                                Cancel
                                            </button>

                                            <button type="submit" class="btn btn-sm btn-danger" id="finalDeleteBtn"
                                                disabled>
                                                Delete Account
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>

                <section id="preferences" class="mb-5 settings-section">
                    <h3 class="fw-bold mb-4">Preferences</h3>

                    <div class="card settings-card border-0 shadow-sm p-4">
                        <div class="card-body">

                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-bell-fill me-2"></i>Notifications
                            </h6>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <label class="form-check-label fw-medium" for="prefEmailDigest">Weekly Email
                                        Digest</label>
                                    <div class="text-muted small">Receive a summary of top papers in your field every
                                        Monday.</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="prefEmailDigest"
                                        checked>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <label class="form-check-label fw-medium" for="prefNewFollower">New Follower
                                        Alerts</label>
                                    <div class="text-muted small">Get notified when someone follows your profile.</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="prefNewFollower"
                                        checked>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-eye-fill me-2"></i>Privacy
                            </h6>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <label class="form-check-label fw-medium" for="prefShowEmail">Show Email
                                        Address</label>
                                    <div class="text-muted small">Allow other users to see your email on your public
                                        profile.</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="prefShowEmail">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <label class="form-check-label fw-medium" for="prefIndexing">Search Engine
                                        Indexing</label>
                                    <div class="text-muted small">Allow search engines (Google, Bing) to index your profile
                                        page.</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="prefIndexing"
                                        checked>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-laptop me-2"></i>Interface
                            </h6>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <label class="form-check-label fw-medium" for="prefCompactMode">Compact List
                                        View</label>
                                    <div class="text-muted small">Reduce padding in paper lists to show more content at
                                        once.</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="prefCompactMode">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-5">
                                <button type="button" class="btn btn-primary px-4">Save Preferences</button>
                            </div>

                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>


    
    <?php if(session('success')): ?>
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-success text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-check-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">Success!</h4>
                        <p class="text-muted mb-4 fs-5"><?php echo e(session('success')); ?></p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm"
                            data-bs-dismiss="modal">
                            CONTINUE
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


    
    <?php if(session('error')): ?>
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-error text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-x-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">Error!</h4>
                        <p class="text-muted mb-4 fs-5"><?php echo e(session('error')); ?></p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm"
                            data-bs-dismiss="modal">
                            CONTINUE
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sections = document.querySelectorAll(".settings-section");
            const navLi = document.querySelectorAll(".settings-sidebar .list-group-item");

            window.onscroll = () => {
                var current = "";
                sections.forEach((section) => {
                    const sectionTop = section.offsetTop;
                    if (scrollY >= sectionTop - 200) {
                        current = section.getAttribute("id");
                    }
                });

                navLi.forEach((li) => {
                    li.classList.remove("active");
                    if (li.getAttribute("href").includes(current)) {
                        li.classList.add("active");
                    }
                });
            };


            // DELETE ACCOUNT
            const deleteInput = document.getElementById('deleteConfirmationInput');
            const deleteBtn = document.getElementById('finalDeleteBtn');
            const confirmationPhrase = "DELETE ACCOUNT";

            if (deleteInput && deleteBtn) {
                deleteInput.addEventListener('input', function() {
                    // Check if input matches exactly
                    if (this.value === confirmationPhrase) {
                        deleteBtn.removeAttribute('disabled');
                    } else {
                        deleteBtn.setAttribute('disabled', 'true');
                    }
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/pages/settings.blade.php ENDPATH**/ ?>