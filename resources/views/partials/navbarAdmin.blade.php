<nav class="admin-subnav-wrapper">
    <div class="container">
        <div class="admin-nav-container">
            <ul class="admin-nav-scroll">
                <li class="admin-nav-item">
                    <a href="/admin-panel" class="admin-nav-link {{ request()->is('admin-panel') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>{{ __('navbarAdmin.overview') }}</span>
                    </a>
                </li>

                <div class="admin-divider-vertical"></div>

                <li class="admin-nav-item dropdown">
                    <a href="#"
                        class="admin-nav-link dropdown-toggle {{ request()->is('admin-panel/master-data*') ? 'active' : '' }}"
                        id="masterDataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-database-fill-gear"></i>
                        <span>{{ __('navbarAdmin.master_data') }}</span>
                    </a>
                    <ul class="dropdown-menu admin-dropdown-menu" aria-labelledby="masterDataDropdown">
                        <li>
                            <h6 class="dropdown-header text-uppercase small fw-bold text-muted mb-2 px-3">
                                {{ __('navbarAdmin.publications') }}
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item admin-dropdown-item" href="/admin-panel/master-data/research-fields">
                                <i class="bi bi-lightbulb"></i> {{ __('navbarAdmin.research_fields') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item admin-dropdown-item" href="/admin-panel/master-data/paper-types">
                                <i class="bi bi-file-earmark-text"></i> {{ __('navbarAdmin.paper_types') }}
                            </a>
                        </li>
                    </ul>
                </li>

                @php
                    $existNewReport = \App\Models\Report::where("status", "pending")->exists();
                @endphp

                <li class="admin-nav-item dropdown">
                    <a href="#"
                        class="admin-nav-link dropdown-toggle {{ request()->is('admin-panel/request*') ? 'active' : '' }}"
                        id="requestsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-inbox-fill"></i>
                        <span>{{ __('navbarAdmin.request') }}</span>
                    </a>
                    <ul class="dropdown-menu admin-dropdown-menu" aria-labelledby="requestsDropdown">
                        <li>
                            <a class="dropdown-item admin-dropdown-item d-flex justify-content-between"
                                href="/admin-panel/request/user-report">
                                <span><i class="bi bi-chat-square-quote"></i> {{ __('navbarAdmin.user_report') }}</span>
                                @if ($existNewReport)
                                    <span class="badge bg-danger rounded-pill">{{ __('navbarAdmin.new') }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="admin-nav-item dropdown">
                    <a href="#"
                        class="admin-nav-link dropdown-toggle {{ request()->is('admin-panel/monitoring*') ? 'active' : '' }}"
                        id="monitoringDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-activity"></i>
                        <span>{{ __('navbarAdmin.monitoring') }}</span>
                    </a>
                    <ul class="dropdown-menu admin-dropdown-menu" aria-labelledby="monitoringDropdown">
                        <li>
                            <a class="dropdown-item admin-dropdown-item" href="/admin-panel/monitoring/global-statistics">
                                <i class="bi bi-bar-chart-line"></i> {{ __('navbarAdmin.global_statistics') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item admin-dropdown-item" href="/admin-panel/monitoring/activity-logs">
                                <i class="bi bi-clock-history"></i> {{ __('navbarAdmin.activity_logs') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <div class="admin-divider-vertical ms-auto"></div>
            </ul>
        </div>
    </div>
</nav>
