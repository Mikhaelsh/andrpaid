<nav class="admin-subnav-wrapper">
    <div class="container">
        <div class="admin-nav-container">
            <ul class="admin-nav-scroll">
                <li class="admin-nav-item">
                    <a href="/admin-panel" class="admin-nav-link <?php echo e(request()->is('admin-panel') ? 'active' : ''); ?>">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Overview</span>
                    </a>
                </li>

                <div class="admin-divider-vertical"></div>

                <li class="admin-nav-item dropdown">
                    <a href="#"
                        class="admin-nav-link dropdown-toggle <?php echo e(request()->is('admin-panel/master-data*') ? 'active' : ''); ?>"
                        id="masterDataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-database-fill-gear"></i>
                        <span>Master Data</span>
                    </a>
                    <ul class="dropdown-menu admin-dropdown-menu" aria-labelledby="masterDataDropdown">
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted mb-2 px-3">Publications
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item admin-dropdown-item" href="/admin-panel/master-data/research-fields">
                                <i class="bi bi-lightbulb"></i> Research Fields
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item admin-dropdown-item" href="/admin-panel/master-data/paper-types">
                                <i class="bi bi-file-earmark-text"></i> Paper Types
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="admin-nav-item dropdown">
                    <a href="#"
                        class="admin-nav-link dropdown-toggle <?php echo e(request()->is('admin-panel/requests*') ? 'active' : ''); ?>"
                        id="requestsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-inbox-fill"></i>
                        <span>Requests</span>
                    </a>
                    <ul class="dropdown-menu admin-dropdown-menu" aria-labelledby="requestsDropdown">
                        <li>
                            <a class="dropdown-item admin-dropdown-item d-flex justify-content-between"
                                href="/admin-panel/requests/feedback">
                                <span><i class="bi bi-chat-square-quote"></i> User Feedback</span>
                                <span class="badge bg-danger rounded-pill">New</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item admin-dropdown-item" href="/admin-panel/requests/reposts">
                                <i class="bi bi-share"></i> Repost Requests
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="admin-nav-item dropdown">
                    <a href="#"
                        class="admin-nav-link dropdown-toggle <?php echo e(request()->is('admin-panel/monitoring*') ? 'active' : ''); ?>"
                        id="monitoringDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-activity"></i>
                        <span>Monitoring</span>
                    </a>
                    <ul class="dropdown-menu admin-dropdown-menu" aria-labelledby="monitoringDropdown">
                        <li>
                            <a class="dropdown-item admin-dropdown-item" href="/admin-panel/monitoring/global-statistics">
                                <i class="bi bi-bar-chart-line"></i> Global Statistics
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item admin-dropdown-item" href="/admin-panel/monitoring/activity-logs">
                                <i class="bi bi-clock-history"></i> User Activity Logs
                            </a>
                        </li>
                    </ul>
                </li>

                <div class="admin-divider-vertical ms-auto"></div>

                <li class="admin-nav-item">
                    <a href="/admin-panel/system/maintenance"
                        class="admin-nav-link text-danger <?php echo e(request()->is('admin-panel/system*') ? 'active' : ''); ?>">
                        <i class="bi bi-power text-danger"></i>
                        <span>System Control</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
<?php /**PATH C:\Tempat Coding\web programming\andrpaid\resources\views/partials/navbarAdmin.blade.php ENDPATH**/ ?>