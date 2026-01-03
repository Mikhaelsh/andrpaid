

<?php $__env->startSection('title', 'Conclusion - ' . $paper->title); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/paper.css')); ?>">
    <style>
        .section-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 25px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            height: 100%;
            transition: transform 0.2s;
        }
        .section-card:hover { transform: translateY(-2px); }
        
        .section-icon {
            width: 40px; height: 40px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        textarea {
            resize: none;
            border: 1px solid #dee2e6;
            background-color: #fcfcfc;
            line-height: 1.6;
            font-size: 0.95rem;
        }
        textarea:focus {
            background-color: #fff;
            border-color: #8e2de2;
            box-shadow: 0 0 0 3px rgba(142, 45, 226, 0.1);
        }
        
        /* Read-only look */
        .finalized-text {
            background: #f8f9fa;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 6px;
            color: #555;
            white-space: pre-line; /* Preserves line breaks */
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarPaper', ['paper' => $paper], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-5">
        
        <?php
            $isLocked = $paper->conclusion_finalized;
            $canInteract = $canEdit && !$isLocked;
        ?>

        
        <div class="mb-4">
            <a href="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/workspace" class="text-decoration-none text-muted small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Back to Workspace
            </a>
        </div>

        
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="module-icon bg-success bg-opacity-10 text-success" style="width: 45px; height: 45px; font-size: 1.2rem; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                        <i class="bi bi-check-all"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">Conclusion & Future Works</h3>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <p class="text-muted mb-0 ms-1">Summarize your findings, acknowledge limitations, and suggest next steps.</p>
                    
                    
                    <?php if($isLocked): ?>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 ms-2">
                            <i class="bi bi-lock-fill me-1"></i> Finalized
                        </span>
                    <?php else: ?>
                        <span class="badge bg-light text-secondary border ms-2">Draft Mode</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                
                <?php if($canEdit): ?>
                    <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/finalize-conclusion" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php if($isLocked): ?>
                            <button type="submit" class="btn btn-outline-success btn-sm me-2" title="Click to Reopen">
                                <i class="bi bi-check-circle-fill me-1"></i> Finalized
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-dark btn-sm me-2">
                                <i class="bi bi-check2-circle me-1"></i> Finalize Conclusion
                            </button>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        
        <form action="/<?php echo e($user->profileId); ?>/paper/<?php echo e($paper->paperId); ?>/save-conclusion" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="row g-4">
                
                <div class="col-12">
                    <div class="section-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="section-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-card-text"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Summary of Findings</h5>
                        </div>
                        
                        <?php if($isLocked): ?>
                            <div class="finalized-text">
                                <?php echo e($paper->conclusion_summary ?? 'No summary provided.'); ?>

                            </div>
                        <?php else: ?>
                            <textarea name="summary" class="form-control" rows="6" placeholder="Synthesize the key results of your research here..." <?php echo e($canEdit ? '' : 'disabled'); ?>><?php echo e($paper->conclusion_summary); ?></textarea>
                            <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i> Briefly restate the problem and how your results addressed it.</div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="col-md-6">
                    <div class="section-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="section-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Limitations</h5>
                        </div>

                        <?php if($isLocked): ?>
                            <div class="finalized-text">
                                <?php echo e($paper->conclusion_limitations ?? 'No limitations noted.'); ?>

                            </div>
                        <?php else: ?>
                            <textarea name="limitations" class="form-control" rows="8" placeholder="What were the constraints? (e.g. Sample size, time, data availability)" <?php echo e($canEdit ? '' : 'disabled'); ?>><?php echo e($paper->conclusion_limitations); ?></textarea>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="col-md-6">
                    <div class="section-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="section-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-signpost-split"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Future Works</h5>
                        </div>

                        <?php if($isLocked): ?>
                            <div class="finalized-text">
                                <?php echo e($paper->conclusion_future_works ?? 'No future works suggested.'); ?>

                            </div>
                        <?php else: ?>
                            <textarea name="future_works" class="form-control" rows="8" placeholder="Suggest avenues for further research..." <?php echo e($canEdit ? '' : 'disabled'); ?>><?php echo e($paper->conclusion_future_works); ?></textarea>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <?php if($canInteract): ?>
                <div class="fixed-bottom bg-white border-top py-3 shadow-lg" style="z-index: 100;">
                    <div class="container d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            <i class="bi bi-clock-history me-1"></i> 
                            Last saved: <?php echo e($paper->updated_at->diffForHumans()); ?>

                        </span>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-save me-2"></i> Save Changes
                        </button>
                    </div>
                </div>
                
                <div style="height: 80px;"></div>
            <?php endif; ?>

        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/conclusion.blade.php ENDPATH**/ ?>