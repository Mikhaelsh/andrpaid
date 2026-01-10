@extends('layouts.app')

@section('title', __('admin.title'))

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/admin.css') }}">
@endsection

@section('content')
    @include('partials.navbarAdmin')

    <div class="container py-4">
        <div class="welcome-banner shadow-sm">
            <div class="row align-items-center position-relative" style="z-index: 2;">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-1">{{ __('admin.welcome_back') }}</h2>
                    <p class="mb-0 opacity-75">{{ __('admin.welcome_sub') }}</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-calendar3 me-2"></i>{{ now()->translatedFormat('l, d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">{{ __('admin.total_users') }}</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ number_format($totalUsers) }}</h2>
                    </div>
                    <div class="icon-box" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;"><i
                            class="bi bi-people-fill"></i></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">{{ __('admin.universities') }}</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ number_format($totalUniversities) }}</h2>
                    </div>
                    <div class="icon-box" style="background: rgba(25, 135, 84, 0.1); color: #198754;"><i
                            class="bi bi-buildings"></i></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">{{ __('admin.lecturers') }}</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ number_format($totalLecturers) }}</h2>
                    </div>
                    <div class="icon-box" style="background: rgba(255, 193, 7, 0.1); color: #ffc107;"><i
                            class="bi bi-mortarboard-fill"></i></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">{{ __('admin.published_papers') }}</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ number_format($totalPapers) }}</h2>
                    </div>
                    <div class="icon-box" style="background: rgba(111, 66, 193, 0.1); color: #6f42c1;"><i
                            class="bi bi-journal-text"></i></div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="dashboard-card mb-4">
                    <div class="dashboard-card-header">
                        <div>
                            <h6 class="fw-bold mb-0">{{ __('admin.traffic_title') }}</h6>
                            <small class="text-muted">{{ __('admin.traffic_sub') }}</small>
                        </div>
                        <a href="/admin-panel/monitoring/activity-logs"
                            class="btn btn-sm btn-light text-muted">{{ __('admin.view_details') }}</a>
                    </div>
                    <div class="p-4">
                        <div style="position: relative; height: 250px; width: 100%;">
                            <canvas id="overviewChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h6 class="fw-bold mb-0">{{ __('admin.newest_users') }}</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">{{ __('admin.th_user') }}</th>
                                    <th>{{ __('admin.th_role') }}</th>
                                    <th>{{ __('admin.th_joined') }}</th>
                                    <th>{{ __('admin.th_status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=random"
                                                    class="user-avatar-tiny me-2">
                                                <span class="fw-medium text-dark">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($user->university)
                                                <span
                                                    class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ __('admin.role_university') }}</span>
                                            @elseif($user->lecturer)
                                                <span
                                                    class="badge bg-success-subtle text-success border border-success-subtle">{{ __('admin.role_lecturer') }}</span>
                                            @else
                                                <span
                                                    class="badge bg-secondary-subtle text-secondary">{{ __('admin.role_user') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">{{ $user->created_at->diffForHumans() }}</td>
                                        <td><span
                                                class="badge bg-light text-dark border">{{ __('admin.status_active') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">{{ __('admin.no_users') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="dashboard-card mb-4">
                    <div class="dashboard-card-header">
                        <h6 class="fw-bold mb-0">{{ __('admin.quick_actions') }}</h6>
                    </div>
                    <div class="p-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="/admin-panel/request/user-report"
                                    class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-chat-left-text fs-4 mb-2"></i>
                                    <span class="small fw-bold">{{ __('admin.btn_user_report') }}</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="/admin-panel/monitoring/activity-logs"
                                    class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-shield-check fs-4 mb-2"></i>
                                    <span class="small fw-bold">{{ __('admin.btn_audit_logs') }}</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="/admin-panel/monitoring/global-statistics"
                                    class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-graph-up fs-4 mb-2"></i>
                                    <span class="small fw-bold">{{ __('admin.btn_analytics') }}</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="/admin-panel/master-data/paper-types"
                                    class="btn btn-outline-danger w-100 py-3 d-flex flex-column align-items-center">
                                    <i class="bi bi-gear fs-4 mb-2"></i>
                                    <span class="small fw-bold">{{ __('admin.btn_system_control') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h6 class="fw-bold mb-0">{{ __('admin.recent_activity') }}</h6>
                        <small class="text-muted">{{ __('admin.live_feed') }}</small>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($recentActivities as $log)
                            <div class="list-group-item px-4 py-3 border-0 border-bottom">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold text-dark small">{{ $log->user->name }}</span>
                                    <small class="text-muted"
                                        style="font-size: 0.7rem;">{{ $log->created_at->format('H:i') }}</small>
                                </div>
                                <p class="mb-0 text-muted small">
                                    @if ($log->type == 'login')
                                        <i class="bi bi-box-arrow-in-right text-success me-1"></i>
                                        {{ __('admin.logged_in') }}
                                    @elseif($log->type == 'logout')
                                        <i class="bi bi-box-arrow-right text-warning me-1"></i>
                                        {{ __('admin.logged_out') }}
                                    @else
                                        <i class="bi bi-circle-fill text-secondary me-1"></i> {{ ucfirst($log->type) }}
                                    @endif
                                </p>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted small">{{ __('admin.no_activity') }}</div>
                        @endforelse
                    </div>
                    <div class="p-3 text-center border-top">
                        <a href="/admin-panel/monitoring/activity-logs"
                            class="text-decoration-none small fw-bold">{{ __('admin.view_all_activity') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('overviewChart').getContext('2d');

                const labels = @json($chartLabels);
                const loginData = @json($loginData);
                const logoutData = @json($logoutData);

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
                        datasets: [{
                                label: "{{ __('admin.chart_logins') }}",
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
                                label: "{{ __('admin.chart_logouts') }}",
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
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8
                                }
                            },
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
                                grid: {
                                    borderDash: [2, 4],
                                    color: '#f0f0f0'
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
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
    @endpush
@endsection
