<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('styles/admin.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.navbarAdmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container py-4">
        <div class="welcome-banner shadow-sm">
            <div class="row align-items-center position-relative" style="z-index: 2;">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-1">Welcome back, Admin!</h2>
                    <p class="mb-0 opacity-75">Here's what's happening in your system today.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-calendar3 me-2"></i><?php echo e(now()->format('l, d M Y')); ?>

                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Users</h6>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo e(number_format($totalUsers)); ?></h2>
                    </div>
                    <div class="icon-box" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Universities</h6>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo e(number_format($totalUniversities)); ?></h2>
                    </div>
                    <div class="icon-box" style="background: rgba(25, 135, 84, 0.1); color: #198754;"><i class="bi bi-buildings"></i></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Lecturers</h6>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo e(number_format($totalLecturers)); ?></h2>
                    </div>
                    <div class="icon-box" style="background: rgba(255, 193, 7, 0.1); color: #ffc107;"><i class="bi bi-mortarboard-fill"></i></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Published Papers</h6>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo e(number_format($totalPapers)); ?></h2>
                    </div>
                    <div class="icon-box" style="background: rgba(111, 66, 193, 0.1); color: #6f42c1;"><i class="bi bi-journal-text"></i></div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="dashboard-card mb-4">
                    <div class="dashboard-card-header">
                        <div>
                            <h6 class="fw-bold mb-0">System Traffic (Last 7 Days)</h6>
                            <small class="text-muted">Login vs Logout Activity</small>
                        </div>
                        <a href="/admin-panel/monitoring/activity-logs" class="btn btn-sm btn-light text-muted">View Details</a>
                    </div>
                    <div class="p-4">
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="overviewChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h6 class="fw-bold mb-0">Newest Users</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">User</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=<?php echo e($user->name); ?>&background=random" class="user-avatar-tiny me-2">
                                                <span class="fw-medium text-dark"><?php echo e($user->name); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($user->university): ?> <span class="badge bg-primary-subtle text-primary border border-primary-subtle">University</span>
                                            <?php elseif($user->lecturer): ?> <span class="badge bg-success-subtle text-success border border-success-subtle">Lecturer</span>
                                            <?php else: ?> <span class="badge bg-secondary-subtle text-secondary">User</span> <?php endif; ?>
                                        </td>
                                        <td class="text-muted small"><?php echo e($user->created_at->diffForHumans()); ?></td>
                                        <td><span class="badge bg-light text-dark border">Active</span></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="4" class="text-center py-4 text-muted">No users found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="dashboard-card mb-4">
                    <div class="dashboard-card-header">
                        <h6 class="fw-bold mb-0">Quick Actions</h6>
                    </div>
                    <div class="p-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="/admin-panel/request/user-report" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-chat-left-text fs-4 mb-2"></i>
                                    <span class="small fw-bold">User Report</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="/admin-panel/monitoring/activity-logs" class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-shield-check fs-4 mb-2"></i>
                                    <span class="small fw-bold">Audit Logs</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="/admin-panel/monitoring/global-statistics" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-graph-up fs-4 mb-2"></i>
                                    <span class="small fw-bold">Analytics</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="btn btn-outline-danger w-100 py-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-gear fs-4 mb-2"></i>
                                    <span class="small fw-bold">System Control</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h6 class="fw-bold mb-0">Recent Activity</h6>
                        <small class="text-muted">Live Feed</small>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="list-group-item px-4 py-3 border-0 border-bottom">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold text-dark small"><?php echo e($log->user->name); ?></span>
                                    <small class="text-muted" style="font-size: 0.7rem;"><?php echo e($log->created_at->format('H:i')); ?></small>
                                </div>
                                <p class="mb-0 text-muted small">
                                    <?php if($log->type == 'login'): ?> <i class="bi bi-box-arrow-in-right text-success me-1"></i> Logged in
                                    <?php elseif($log->type == 'logout'): ?> <i class="bi bi-box-arrow-right text-warning me-1"></i> Logged out
                                    <?php else: ?> <i class="bi bi-circle-fill text-secondary me-1"></i> <?php echo e(ucfirst($log->type)); ?> <?php endif; ?>
                                </p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="p-4 text-center text-muted small">No recent activity.</div>
                        <?php endif; ?>
                    </div>
                    <div class="p-3 text-center border-top">
                        <a href="/admin-panel/monitoring/activity-logs" class="text-decoration-none small fw-bold">View All Activity</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('overviewChart').getContext('2d');

                const labels = <?php echo json_encode($chartLabels, 15, 512) ?>;
                const loginData = <?php echo json_encode($loginData, 15, 512) ?>;
                const logoutData = <?php echo json_encode($logoutData, 15, 512) ?>;

                let loginGradient = ctx.createLinearGradient(0, 0, 0, 300);
                loginGradient.addColorStop(0, 'rgba(25, 135, 84, 0.2)');
                loginGradient.addColorStop(1, 'rgba(25, 135, 84, 0.0)');

                let logoutGradient = ctx.createLinearGradient(0, 0, 0, 300);
                logoutGradient.addColorStop(0, 'rgba(255, 193, 7, 0.2)');
                logoutGradient.addColorStop(1, 'rgba(255, 193, 7, 0.0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Logins',
                                data: loginData,
                                borderColor: '#198754',
                                backgroundColor: loginGradient,
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 3,
                                pointHoverRadius: 5
                            },
                            {
                                label: 'Logouts',
                                data: logoutData,
                                borderColor: '#ffc107',
                                backgroundColor: logoutGradient,
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 3,
                                pointHoverRadius: 5
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                titleColor: '#000',
                                bodyColor: '#666',
                                borderColor: '#ddd',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [2, 4], color: '#f0f0f0' },
                                ticks: { precision: 0 }
                            },
                            x: {
                                grid: { display: false }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/pages/admin.blade.php ENDPATH**/ ?>