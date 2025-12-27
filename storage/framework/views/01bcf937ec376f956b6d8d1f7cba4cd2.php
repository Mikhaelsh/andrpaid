<?php $__env->startSection('title', 'Papers'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/profile.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarProfile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div style="background-color: #0d1117; padding-top: 2rem;">
        <div class="container text-white pb-3">
            <h3><?php echo e($user->name); ?>'s Papers</h3>
            <p><?php echo e($papers->count() ?? 'No'); ?> Paper<?php echo e($papers->count() <= 1 ? '' : 's'); ?></p>
        </div>
    </div>

    <div class="container py-5">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 gap-4">

            <div class="paper-showcase-control-bar d-flex flex-grow-1 gap-2 w-90 p-2">

                <div class="position-relative flex-grow-1">
                    <i
                        class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary opacity-75"></i>
                    <input type="text" class="form-control paper-showcase-search-input ps-5"
                        placeholder="Search titles, authors, or abstracts...">
                </div>

                <button class="btn paper-showcase-filter-trigger d-flex align-items-center gap-2" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                    <i class="bi bi-sliders2"></i>
                    <span class="d-none d-md-inline">Filters</span>
                </button>
            </div>

            <a href="/papers/create" class="btn paper-showcase-create-action d-flex align-items-center gap-2">
                <div class="icon-box"><i class="bi bi-plus-lg"></i></div>
                <span>New Paper</span>
            </a>
        </div>


        <div class="offcanvas offcanvas-end paper-filter-offcanvas" tabindex="-1" id="filterOffcanvas">

            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold">Refine Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body p-4">

                <form>
                    <div class="mb-4">
                        <label class="filter-section-label">Sort By</label>
                        <select class="form-select filter-modern-select">
                            <option selected>Newest First</option>
                            <option>Oldest First</option>
                            <option>Most Stars</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="filter-section-label">Visibility</label>
                        <div class="d-flex flex-wrap gap-2">
                            <input type="checkbox" class="btn-check" id="vis-public" checked>
                            <label class="btn filter-chip" for="vis-public">
                                <i class="bi bi-globe-americas me-1"></i> Public
                            </label>

                            <input type="checkbox" class="btn-check" id="vis-private">
                            <label class="btn filter-chip" for="vis-private">
                                <i class="bi bi-lock-fill me-1"></i> Private
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="filter-section-label">Paper Type</label>
                        <div class="d-flex flex-wrap gap-2">

                            <?php $__currentLoopData = $paperTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paperType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="checkbox" class="btn-check" id="<?php echo e($paperType->paperTypeId); ?>" checked>
                                <label class="btn filter-chip" for="<?php echo e($paperType->paperTypeId); ?>"><?php echo e($paperType->name); ?></label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="filter-section-label">Research Field</label>
                        <div class="d-flex flex-wrap gap-2">

                            <?php $__currentLoopData = $researchFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $researchField): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="checkbox" class="btn-check" id="<?php echo e($researchField->researchFieldId); ?>" checked>
                                <label class="btn filter-chip" for="<?php echo e($researchField->researchFieldId); ?>"><?php echo e($researchField->name); ?></label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </form>
            </div>

            <div class="offcanvas-footer p-3 border-top bg-light">
                <div class="d-flex gap-2">
                    <button class="btn btn-light flex-grow-1 border fw-bold" data-bs-dismiss="offcanvas">Clear</button>
                    <button class="btn paper-showcase-create-action flex-grow-1 w-100 justify-content-center"
                        data-bs-dismiss="offcanvas">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">

            <?php $__empty_1 = true; $__currentLoopData = $papers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="paper-showcase-card p-4">
                    <div class="d-flex justify-content-between align-items-start">

                        <div class="d-flex flex-column gap-2 col-md-10">

                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h5 class="mb-0 fw-bold paper-showcase-title me-2">
                                    <a href="#" class="text-decoration-none stretched-link"><?php echo e($paper->title); ?></a>
                                </h5>

                                <span class="paper-status-badge <?php echo e($paper->visibility); ?>">
                                    <i class="bi bi-globe-americas me-1"></i> <?php echo e($paper->visibility); ?>

                                </span>

                                <span class="paper-status-badge <?php echo e($paper->status); ?>">
                                    <i class="bi bi-check-circle-fill me-1"></i> <?php echo e($paper->status); ?>

                                </span>
                            </div>

                            <p class="paper-showcase-description mb-1">
                                <?php echo e($paper->description); ?>

                            </p>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="small text-muted fw-bold text-uppercase"
                                    style="font-size: 0.7rem; letter-spacing: 0.05em;">Fields:</span>
                                <?php $__currentLoopData = $paper->researchFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $researchField): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span
                                        class="badge bg-light text-secondary border fw-bold"><?php echo e($researchField->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="row align-items-center mt-1 border-top pt-3 gy-2">

                                <div class="col-5 col-md-4 col-lg-3">
                                    <span
                                        class="paper-type-pill <?php echo e(Str::replace('_', '-', $paper->paperType->paperTypeId)); ?> d-block w-100">
                                        <span class="dot"></span> <?php echo e($paper->paperType->name); ?>

                                    </span>
                                </div>

                                <div class="col-auto">
                                    <span class="paper-meta-text text-dark fw-medium">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span
                                            id="star-count-<?php echo e($paper->paperId); ?>"><?php echo e($paper->paperStars->count()); ?></span>
                                    </span>
                                </div>

                                <div class="col-auto">
                                    <span class="paper-meta-text text-muted">
                                        Updated <?php echo e($paper->updated_at->diffForHumans()); ?>

                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="position-relative z-2 ms-3">
                            <?php
                                $isStarred = Auth::check() && $paper->paperStars->contains('user_id', Auth::user()->id);
                            ?>

                            <button class="btn <?php echo e($isStarred ? 'btn-warning' : 'paper-action-star-btn'); ?>"
                                id="star-btn-<?php echo e($paper->paperId); ?>" onclick="toggleStar(`<?php echo e($paper->paperId); ?>`)"
                                title="Star this paper">

                                <i class="bi <?php echo e($isStarred ? 'bi-star-fill' : 'bi-star'); ?>"
                                    id="star-icon-<?php echo e($paper->paperId); ?>"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="paper-empty-state text-center d-flex flex-column align-items-center justify-content-center">

                    <div class="empty-state-icon">
                        <i class="bi bi-folder2-open"></i>
                    </div>

                    <h4 class="fw-bold text-dark mb-2">No Research Papers Yet</h4>

                    <p class="text-muted mb-4 col-md-8 mx-auto" style="font-size: 0.95rem; line-height: 1.6;">
                        It looks like you haven't created any paper repositories.
                        Start documenting your research, manage drafts, and collaborate with another lecturer here.
                    </p>

                    <a href="/papers/create" class="btn paper-showcase-create-action d-flex align-items-center gap-2">
                        <i class="bi bi-plus-lg"></i>
                        Create New Paper Repository
                    </a>

                </div>
            <?php endif; ?>

            

            

        </div>
    </div>


    <?php if(session('successNewPaper')): ?>
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-success text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-check-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">Success!</h4>
                        <p class="text-muted mb-4 fs-5"><?php echo e(session('successNewPaper')); ?></p>

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


    <?php $__env->startPush('scripts'); ?>
        <script>
            async function toggleStar(paperId) {
                const btn = document.getElementById(`star-btn-${paperId}`);
                const icon = document.getElementById(`star-icon-${paperId}`);
                const countSpan = document.getElementById(`star-count-${paperId}`);
                const navbarProfileStarsCount = document.getElementById(`navbarProfileStarsCount`)

                try {
                    const response = await fetch(`/papers/${paperId}/star`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                    });

                    const data = await response.json();

                    // Update UI based on server response
                    if (data.is_starred) {
                        btn.classList.remove('paper-action-star-btn');
                        btn.classList.add('btn-warning');
                        icon.classList.remove('bi-star');
                        icon.classList.add('bi-star-fill');
                        navbarProfileStarsCount.innerText = parseInt(navbarProfileStarsCount.innerText) + 1;
                    } else {
                        btn.classList.remove('btn-warning');
                        btn.classList.add('paper-action-star-btn');
                        icon.classList.remove('bi-star-fill');
                        icon.classList.add('bi-star');
                        navbarProfileStarsCount.innerText = parseInt(navbarProfileStarsCount.innerText) - 1;
                    }

                    // Update the number
                    countSpan.innerText = data.new_count;

                } catch (error) {
                    console.error('Error toggling star:', error);
                }
            }
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/pages/papers.blade.php ENDPATH**/ ?>