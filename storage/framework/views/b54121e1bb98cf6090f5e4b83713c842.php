<?php $__env->startSection('title', $user->name . ' - Profile'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/profile.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarProfile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if($user->isLecturer()): ?>
        <div class="profile-header-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                        <div class="profile-avatar-wrapper">
                            <span class="profile-avatar-initials"><?php echo e(substr($user->name, 0, 1)); ?></span>
                        </div>
                    </div>

                    <div class="col-md text-center text-md-start">
                        <h2 class="fw-bold text-white mb-1"><?php echo e($user->name); ?></h2>

                        <p class="text-white-50 mb-2 fs-5">
                            <?php if($user->lecturer && $user->lecturer->affiliation): ?>
                                <i
                                    class="bi bi-building me-2"></i><?php echo e($user->lecturer->affiliation->university->user->name); ?>

                            <?php endif; ?>

                            <?php if($user->lecturer->province): ?>
                                <span class="mx-2 opacity-50">|</span>
                                <i class="bi bi-geo-alt-fill me-1"></i> <?php echo e($user->lecturer->province->name); ?>

                            <?php endif; ?>
                        </p>

                        <div class="d-flex justify-content-center justify-content-md-start gap-2 mt-3">
                            <span class="badge bg-dark border border-secondary text-light px-3 py-2 rounded-pill">
                                <i class="bi bi-person-badge me-1"></i> Lecturer
                            </span>
                            <?php if($user->lecturer && $user->lecturer->affiliation): ?>
                                <span
                                    class="badge bg-success bg-opacity-25 text-white border border-success px-3 py-2 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> Verified
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php
                        $paperCount = $user->lecturer ? $user->lecturer->papers->count() : 0;
                        $starCount = $user->lecturer
                            ? \App\Models\PaperStar::whereIn('paper_id', $user->lecturer->papers->select('id'))->count()
                            : 0;
                        $collabCount = $user->lecturer
                            ? $user->lecturer->papers->where('openCollaboration', true)->count()
                            : 0;

                        $totalActivity = $paperCount + $starCount + $collabCount;
                    ?>

                    <?php if($totalActivity > 0): ?>
                        <div class="col-md-auto mt-4 mt-md-0">
                            <div
                                class="d-flex gap-4 justify-content-center stats-container bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur dynamic-stats-box">

                                <?php if($paperCount > 0): ?>
                                    <div class="text-center px-3 stat-item">
                                        <div class="h3 fw-bold text-white mb-0"><?php echo e($paperCount); ?></div>
                                        <div class="small text-white-50">Papers</div>
                                    </div>
                                <?php endif; ?>

                                <?php if($starCount > 0): ?>
                                    <div class="text-center px-3 stat-item">
                                        <div class="h3 fw-bold text-warning mb-0"><?php echo e($starCount); ?></div>
                                        <div class="small text-white-50">Total Stars</div>
                                    </div>
                                <?php endif; ?>

                                <?php if($collabCount > 0): ?>
                                    <div class="text-center px-3 stat-item">
                                        <div class="h3 fw-bold text-info mb-0"><?php echo e($collabCount); ?></div>
                                        <div class="small text-white-50">Open Collabs</div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="mb-5">
                        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                            <i class="bi bi-person-lines-fill me-2 text-primary"></i> About Me
                        </h5>
                        <?php if($user->description): ?>
                            <p class="text-muted" style="line-height: 1.7;">
                                <?php echo e($user->description); ?>

                            </p>
                        <?php else: ?>
                            <p class="text-muted small fst-italic">This user hasn't written a bio yet.</p>
                        <?php endif; ?>
                    </div>

                    <?php if($user->lecturer && $user->lecturer->researchFields->count() > 0): ?>
                        <div class="mb-5">
                            <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                                <i class="bi bi-lightbulb-fill me-2 text-primary"></i> Research Interests
                            </h5>
                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = $user->lecturer->researchFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $researchField): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-medium">
                                        <?php echo e($researchField->name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="card border-0 bg-light rounded-4 p-4">
                        <h6 class="fw-bold mb-3">Connect</h6>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                            <li>
                                <a href="mailto:<?php echo e($user->email); ?>"
                                    class="text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary">
                                    <div class="bg-white p-2 rounded-circle shadow-sm">
                                        <i class="bi bi-envelope-fill text-primary"></i>
                                    </div>
                                    <span><?php echo e($user->email); ?></span>
                                </a>
                            </li>

                            <?php if(optional($user->lecturer)->linkedin_url): ?>
                                <li>
                                    <a href="<?php echo e($user->lecturer->linkedin_url); ?>" target="_blank"
                                        class="text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary">
                                        <div class="bg-white p-2 rounded-circle shadow-sm">
                                            <i class="bi bi-linkedin text-primary"></i>
                                        </div>
                                        <span>LinkedIn Profile</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if(optional($user->lecturer)->portfolio_url): ?>
                                <li>
                                    <a href="<?php echo e($user->lecturer->portfolio_url); ?>" target="_blank"
                                        class="text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary">
                                        <div class="bg-white p-2 rounded-circle shadow-sm">
                                            <i class="bi bi-globe text-primary"></i>
                                        </div>
                                        <span>Portfolio Website</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                </div>

                <div class="col-lg-8">
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-2">
                            <h4 class="fw-bold text-dark mb-0">
                                <i class="bi bi-trophy-fill text-warning me-2"></i> Top Rated Research
                            </h4>
                            <a href="/<?php echo e($user->profileId); ?>/papers?sort=stars"
                                class="text-decoration-none small fw-bold">View
                                All</a>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <?php $__empty_1 = true; $__currentLoopData = $topPapers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div
                                    class="card border-0 shadow-sm rounded-3 overflow-hidden position-relative featured-paper-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between">
                                            <div class="col-10">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <span
                                                        class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill small">
                                                        <i class="bi bi-star-fill me-1"></i> Top Rated
                                                    </span>
                                                    <span class="text-muted small px-2 border-start">
                                                        <?php echo e($paper->paperType->name); ?>

                                                    </span>
                                                </div>
                                                <h5 class="fw-bold mb-2">
                                                    <a href="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/overview"
                                                        class="text-decoration-none text-dark stretched-link"><?php echo e($paper->title); ?></a>
                                                </h5>
                                                <p class="text-muted small mb-3 text-truncate-2">
                                                    <?php echo e($paper->description); ?>

                                                </p>

                                                <div class="d-flex gap-2">
                                                    <?php $__currentLoopData = $paper->researchFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span
                                                            class="badge bg-light text-secondary border"><?php echo e($field->name); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <h3 class="fw-bold text-warning mb-0"><?php echo e($paper->paper_stars_count); ?></h3>
                                                <span class="small text-muted">Stars</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="position-absolute top-0 start-0 bottom-0 bg-warning" style="width: 4px;">
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-4 bg-light rounded-3 border border-dashed">
                                    <p class="text-muted mb-0">No papers published yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-2">
                            <h4 class="fw-bold text-dark mb-0">
                                <i class="bi bi-people-fill text-primary me-2"></i> Open for Collaboration
                            </h4>
                            <a href="/<?php echo e($user->profileId); ?>/papers?collab[]=1"
                                class="text-decoration-none small fw-bold">View
                                All</a>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <?php $__empty_1 = true; $__currentLoopData = $collabPapers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="card border-0 shadow-sm rounded-3 collab-paper-card"
                                    style="background-color: #f8faff;">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <span class="paper-status-badge collab-open">
                                                        <i class="bi bi-people-fill me-1"></i> Looking for Collaborators
                                                    </span>
                                                    <span class="text-muted small">
                                                        Updated <?php echo e($paper->updated_at->diffForHumans()); ?>

                                                    </span>
                                                </div>
                                                <h5 class="fw-bold mb-2">
                                                    <a href="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/overview"
                                                        class="text-decoration-none text-dark"><?php echo e($paper->title); ?></a>
                                                </h5>
                                                <p class="text-muted small mb-0 text-truncate-2">
                                                    <?php echo e($paper->description); ?>

                                                </p>
                                            </div>
                                            <a href="mailto:<?php echo e($user->email); ?>?subject=Collaboration Interest: <?php echo e($paper->title); ?>"
                                                class="btn btn-primary btn-sm rounded-pill px-3 fw-bold ms-3"
                                                style="white-space: nowrap;">
                                                Contact
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-4 bg-light rounded-3 border border-dashed">
                                    <p class="text-muted mb-0">No active collaboration requests at the moment.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($user->isUniversity()): ?>
        <div class="profile-header-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                        <div class="profile-avatar-wrapper">
                            <img src="https://ui-avatars.com/api/?name=<?php echo e($user->name); ?>&background=fff&color=0d6efd&size=128"
                                class="rounded-circle p-1 bg-white" alt="University Logo">
                        </div>
                    </div>

                    <div class="col-md text-center text-md-start">
                        <h2 class="fw-bold text-white mb-1"><?php echo e($user->name); ?></h2>

                        <div
                            class="text-white-50 mb-3 fs-5 d-flex flex-wrap justify-content-center justify-content-md-start gap-3">

                            <?php if($user->university->province): ?>
                                <span class="mx-2 opacity-50">|</span>
                                <span>
                                    <i class="bi bi-geo-alt-fill me-1"></i> <?php echo e($user->university->province->name); ?>

                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-center justify-content-md-start gap-2">
                            <span class="badge bg-dark border border-secondary text-light px-3 py-2 rounded-pill">
                                <i class="bi bi-building me-1"></i> University
                            </span>
                        </div>
                    </div>

                    <?php
                        $affiliatedLecturersCount = $user->university->affiliations->count();

                        $totalInstitutionPapers = \App\Models\Paper::whereHas('lecturer.affiliation', function (
                            $q,
                        ) use ($user) {
                            $q->where('university_id', $user->university->id);
                        })->count();

                        $totalUnivActivity = $affiliatedLecturersCount + $totalInstitutionPapers;
                    ?>

                    <?php if($totalUnivActivity > 0): ?>
                        <div class="col-md-auto mt-4 mt-md-0">
                            <div
                                class="d-flex gap-4 justify-content-center stats-container bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur dynamic-stats-box">

                                <?php if($totalInstitutionPapers > 0): ?>
                                    <div class="text-center px-3 stat-item border-end-0">
                                        <div class="h3 fw-bold text-white mb-0"><?php echo e($totalInstitutionPapers); ?></div>
                                        <div class="small text-white-50">Publications</div>
                                    </div>
                                <?php endif; ?>

                                <?php if($affiliatedLecturersCount > 0): ?>
                                    <div
                                        class="text-center px-3 stat-item <?php echo e($totalInstitutionPapers > 0 ? 'border-start border-light border-opacity-25' : ''); ?>">
                                        <div class="h3 fw-bold text-warning mb-0"><?php echo e($affiliatedLecturersCount); ?></div>
                                        <div class="small text-white-50">Researchers</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="mb-5">
                        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                            <i class="bi bi-info-circle-fill me-2 text-primary"></i> About Institution
                        </h5>
                        <?php if($user->description): ?>
                            <p class="text-muted" style="line-height: 1.7;"><?php echo e($user->description); ?></p>
                        <?php else: ?>
                            <p class="text-muted small fst-italic">No description provided.</p>
                        <?php endif; ?>
                    </div>

                    <div class="card border-0 bg-light rounded-4 p-4">
                        <h6 class="fw-bold mb-3">Contact Information</h6>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                            <li>
                                <a href="mailto:<?php echo e($user->email); ?>"
                                    class="text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary">
                                    <div class="bg-white p-2 rounded-circle shadow-sm"><i
                                            class="bi bi-envelope-fill text-primary"></i></div>
                                    <span><?php echo e($user->email); ?></span>
                                </a>
                            </li>
                            <?php if($user->university->websiteUrl): ?>
                                <li>
                                    <a href="<?php echo e($user->university->websiteUrl); ?>" target="_blank"
                                        class="text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary">
                                        <div class="bg-white p-2 rounded-circle shadow-sm"><i
                                                class="bi bi-globe text-primary"></i></div>
                                        <span>Visit Website</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-2">
                            <h4 class="fw-bold text-dark mb-0">
                                <i class="bi bi-journal-text text-primary me-2"></i> Recent Publications
                            </h4>
                            <a href="/<?php echo e($user->profileId); ?>/papers" class="text-decoration-none small fw-bold">View
                                All</a>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <?php $__empty_1 = true; $__currentLoopData = $recentUnivPapers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div
                                    class="card border-0 shadow-sm rounded-3 overflow-hidden position-relative publication-card">
                                    <div class="card-body p-4">

                                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                            <span
                                                class="badge bg-light text-secondary border small"><?php echo e($paper->paperType->name); ?></span>

                                            <?php if($paper->openCollaboration): ?>
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success small">
                                                    <i class="bi bi-people-fill me-1"></i> Open Collab
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <h5 class="fw-bold mb-2">
                                            <a href="/<?php echo e($paper->lecturer->user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/overview"
                                                class="text-decoration-none text-dark hover-primary stretched-link">
                                                <?php echo e($paper->title); ?>

                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-3 text-truncate-2"><?php echo e($paper->description); ?></p>

                                        <?php if($paper->researchFields->count() > 0): ?>
                                            <div class="d-flex gap-2 mb-3 flex-wrap">
                                                <?php $__currentLoopData = $paper->researchFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge bg-light text-secondary border fw-normal"
                                                        style="font-size: 0.8rem;">
                                                        <?php echo e($field->name); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="d-flex align-items-center gap-2 border-top pt-3 mt-3">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo e($paper->lecturer->user->name); ?>&background=random&size=32"
                                                class="rounded-circle">
                                            <div style="line-height: 1.2;">
                                                <span class="small text-muted d-block">Authored by</span>
                                                <a href="/<?php echo e($paper->lecturer->user->profileId); ?>/overview"
                                                    class="fw-bold text-dark small text-decoration-none position-relative z-2 hover-underline">
                                                    <?php echo e($paper->lecturer->user->name); ?>

                                                </a>
                                            </div>
                                            <span
                                                class="text-muted small ms-auto"><?php echo e($paper->created_at->diffForHumans()); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-5 bg-light rounded-3 border border-dashed">
                                    <p class="text-muted mb-0">No publications found for this institution yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/pages/profile-overview.blade.php ENDPATH**/ ?>