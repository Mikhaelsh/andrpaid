<?php $__env->startSection('title', 'Settings - ' . $paper->title); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/paper.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarPaper', ['paper' => $paper], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-5">
        <div class="row g-5">

            
            <div class="col-md-3 d-none d-md-block">
                <div class="list-group list-group-flush position-sticky" style="top: 6rem;">
                    <a href="#general" class="list-group-item list-group-item-action border-0 rounded-3 active fw-medium mb-1">
                        General
                    </a>
                    <a href="#collaborators" class="list-group-item list-group-item-action border-0 rounded-3 fw-medium mb-1">
                        Collaborators
                    </a>
                    <a href="#danger" class="list-group-item list-group-item-action border-0 rounded-3 fw-medium text-danger">
                        Danger Zone
                    </a>
                </div>
            </div>

            <div class="col-md-9">

                
                <div id="general" class="mb-5">
                    <h4 class="fw-bold text-dark mb-4">General Settings</h4>
                    <div class="card border-0 shadow-sm p-4">
                        <form action="#" method="POST"> 
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Paper Title</label>
                                <input type="text" class="form-control" value="<?php echo e($paper->title); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description / Abstract</label>
                                <textarea class="form-control" rows="4"><?php echo e($paper->description); ?></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Research Type</label>
                                <select class="form-select">
                                    <option>Journal Article</option>
                                    <option>Conference Paper</option>
                                    <option>Thesis</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                
                <div id="collaborators" class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-dark mb-0">Collaborators</h4>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#inviteModal">
                            <i class="bi bi-person-plus-fill me-2"></i>Add Member
                        </button>
                    </div>

                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="list-group list-group-flush">

                            
                            <div class="list-group-item p-4 d-flex justify-content-between align-items-center bg-light">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="member-avatar" style="width: 48px; height: 48px;">
                                        <img src="https://ui-avatars.com/api/?name=<?php echo e($paper->lecturer->user->name); ?>&background=0d6efd&color=fff"
                                             class="rounded-3" alt="">
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark"><?php echo e($paper->lecturer->user->name); ?></h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">Owner</span>
                                            <span class="text-muted small"><?php echo e($paper->lecturer->user->email); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-muted small fst-italic">Cannot remove</span>
                            </div>

                            
                            <div class="list-group-item p-4 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="member-avatar" style="width: 48px; height: 48px;">
                                        <span class="avatar-initials bg-light text-dark rounded-3 d-flex align-items-center justify-content-center fw-bold h-100 w-100">JD</span>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">John Doe</h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border">Editor</span>
                                            <span class="text-muted small">john.doe@university.edu</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-icon-only text-muted" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li><a class="dropdown-item" href="#">Change Role</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#">Remove Access</a></li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                
                <div id="danger" class="mb-5">
                    <h4 class="fw-bold text-danger mb-4">Danger Zone</h4>
                    <div class="card border-danger bg-danger bg-opacity-10 p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-danger mb-1">Delete this paper</h6>
                                <p class="text-danger text-opacity-75 small mb-0">
                                    Once you delete a paper, there is no going back. Please be certain.
                                </p>
                            </div>
                            <button class="btn btn-outline-danger fw-bold bg-white">Delete Paper</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    
    <div class="modal fade" id="inviteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Add Collaborator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Search for researchers by email or name to add them to this project.</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0 bg-light" placeholder="Search users...">
                    </div>
                    
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4">Add User</button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/pages/paper-setting.blade.php ENDPATH**/ ?>