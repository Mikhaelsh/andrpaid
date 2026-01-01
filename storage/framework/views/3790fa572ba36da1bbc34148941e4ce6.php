<?php
    $profileId = $paper->lecturer->user->profileId;
?>


<div class="paper-context-header border-bottom bg-white pt-3 pb-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2 text-truncate">
                <i class="bi bi-journal-bookmark-fill text-muted fs-5"></i>

                <a href="/<?php echo e($profileId); ?>/papers" class="paper-breadcrumb-link text-muted">
                    <?php echo e($paper->lecturer->user->name); ?>

                </a>

                <span class="text-muted opacity-50">/</span>

                <a href="/<?php echo e($profileId); ?>/paper/<?php echo e($paper->paperId); ?>/overview"
                    class="paper-breadcrumb-link fw-bold text-dark">
                    <?php echo e($paper->title); ?>

                </a>

                <span class="badge rounded-pill ms-2 <?php echo e($paper->visibility === 'public' ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary'); ?> border">
                    <?php echo e(ucfirst($paper->visibility)); ?>

                </span>

                <?php if($paper->openCollaboration): ?>
                    <span class="badge rounded-pill ms-1 bg-primary bg-opacity-10 text-primary border border-primary-subtle"
                          title="This project is accepting new researchers">
                        <i class="bi bi-people-fill me-1"></i> Open Collab
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="paper-navbar-sticky border-bottom bg-white sticky-top">
    <div class="container">
        <div class="paper-nav-scroller">
            <nav class="nav paper-nav-underline">
                <a class="nav-link <?php echo e(request()->is('*/overview') ? 'active' : ''); ?>"
                    href="/<?php echo e($profileId); ?>/paper/<?php echo e($paper->paperId); ?>/overview">
                    <i class="bi bi-columns-gap me-2"></i>Overview
                </a>

                <a class="nav-link <?php echo e(request()->is('*/workspace*') ? 'active' : ''); ?>"
                    href="/<?php echo e($profileId); ?>/paper/<?php echo e($paper->paperId); ?>/workspace">
                    <i class="bi bi-book me-2"></i>Workspace
                </a>

                <a class="nav-link <?php echo e(request()->is('*/collaborations') ? 'active' : ''); ?>"
                    href="/<?php echo e($profileId); ?>/paper/<?php echo e($paper->paperId); ?>/collaborations">
                    <i class="bi bi-people me-2"></i>Collaborations

                    <?php if($paper->joinRequests_count > 0 && Auth::id() == $paper->lecturer->user->id): ?>
                        <span class="badge bg-danger rounded-pill ms-1"
                            style="font-size: 0.6rem;"><?php echo e($paper->joinRequests_count); ?></span>
                    <?php endif; ?>
                </a>

                <?php if(Auth::user()->isLecturer() && $paper->lecturer->id === Auth::user()->lecturer->id): ?>
                    <a class="nav-link <?php echo e(request()->is('*/settings') ? 'active' : ''); ?>"
                        href="/<?php echo e($profileId); ?>/paper/<?php echo e($paper->paperId); ?>/settings">
                        <i class="bi bi-gear me-2"></i>Settings
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</div>
<?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/partials/navbarPaper.blade.php ENDPATH**/ ?>