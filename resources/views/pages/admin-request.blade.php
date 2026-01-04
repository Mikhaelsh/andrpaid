@extends('layouts.app')

@section('title', 'User Reports & Feedback')

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/admin.css') }}">
    <style>
        /* Specific Report Styles */
        .report-stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.2s;
        }

        .report-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .bg-pending {
            background: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }

        .bg-resolved {
            background: rgba(25, 135, 84, 0.15);
            color: #198754;
        }

        .bg-total {
            background: rgba(13, 110, 253, 0.15);
            color: #0d6efd;
        }

        .report-table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #888;
        }

        .report-table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .type-badge {
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #eee;
            background: #f9f9f9;
            color: #555;
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #f0f0f0;
            height: 100%;
        }
    </style>
@endsection

@section('content')
    @include('partials.navbarAdmin')

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">User Reports Center</h4>
                <p class="text-muted small mb-0">Manage bugs, feature requests, and user feedback.</p>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-4 d-flex flex-column gap-3">
                <div class="report-stat-card">
                    <div class="stat-icon bg-total"><i class="bi bi-inbox-fill"></i></div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-0">Total Reports</h6>
                        <h2 class="fw-bold mb-0">{{ $stats['total'] }}</h2>
                    </div>
                </div>
                <div class="report-stat-card">
                    <div class="stat-icon bg-pending"><i class="bi bi-hourglass-split"></i></div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-0">Pending</h6>
                        <h2 class="fw-bold mb-0 text-warning">{{ $stats['pending'] }}</h2>
                    </div>
                </div>
                <div class="report-stat-card">
                    <div class="stat-icon bg-resolved"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-0">Resolved</h6>
                        <h2 class="fw-bold mb-0 text-success">{{ $stats['resolved'] }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="chart-container shadow-sm">
                    <h6 class="fw-bold mb-3">Response Efficiency (Last 7 Days)</h6>
                    <div style="height: 250px;">
                        <canvas id="reportAnalyticsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <form action="{{ url()->current() }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Search user or ID..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="type" onchange="this.form.submit()">
                            <option value="all">All Types</option>
                            @foreach ($reportTypes as $type)
                                <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="status" onchange="this.form.submit()">
                            <option value="all">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewing" {{ request('status') == 'reviewing' ? 'selected' : '' }}>In Review
                            </option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved
                            </option>
                            <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Dismissed
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ url()->current() }}" class="btn btn-light text-muted w-100">Reset</a>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table report-table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td class="ps-4 text-muted">#{{ $report->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name={{ $report->user->name }}&background=random"
                                            class="rounded-circle" width="30" height="30">
                                        <div class="lh-1">
                                            <div class="fw-bold text-dark">{{ $report->user->name }}</div>
                                            <small class="text-muted"
                                                style="font-size:0.75rem">{{ $report->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="type-badge">{{ $report->reportType->name ?? 'General' }}</span>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 250px;">
                                        {{ $report->description }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $report->created_at->format('d M Y') }}</td>
                                <td>
                                    @if ($report->status === 'pending')
                                        <span
                                            class="badge bg-warning-subtle text-warning border border-warning-subtle">Pending</span>
                                    @elseif($report->status === 'resolved')
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle">Resolved</span>
                                    @elseif($report->status === 'dismissed')
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Dismissed</span>
                                    @else
                                        <span
                                            class="badge bg-info-subtle text-info border border-info-subtle">{{ ucfirst($report->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-dark"
                                        onclick="openReportModal({{ json_encode($report) }})">
                                        Manage
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-clipboard-x display-6 d-block mb-3 opacity-50"></i>
                                    No reports found matching your filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-top-0 py-3">
                {{ $reports->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Manage Report #<span id="modalReportId"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateReportForm" method="POST">
                        @csrf

                        <div class="p-3 bg-light rounded-3 mb-3">
                            <label class="small text-muted fw-bold text-uppercase">Reported By</label>
                            <div class="fw-bold text-dark" id="modalUserName">User Name</div>
                            <div class="small text-muted" id="modalUserEmail">email@example.com</div>
                            <div class="mt-2 pt-2 border-top border-secondary-subtle">
                                <label class="small text-muted fw-bold text-uppercase">Issue Type</label>
                                <div class="text-primary fw-bold" id="modalReportType">Bug Report</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea class="form-control bg-white" id="modalDescription" rows="4" readonly></textarea>
                        </div>

                        <hr class="text-muted opacity-25">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Update Status</label>
                            <select class="form-select form-select-lg" style="font-size: 0.9em;" name="status"
                                id="modalStatusSelect">
                                <option value="pending">Pending</option>
                                <option value="reviewing">In Review</option>
                                <option value="resolved">Resolved</option>
                                <option value="dismissed">Dismissed</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold py-2">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('reportAnalyticsChart').getContext('2d');

                const dates = @json($dates);
                const incomingData = @json($incomingData);
                const resolvedData = @json($resolvedData);

                let gradIncoming = ctx.createLinearGradient(0, 0, 0, 300);
                gradIncoming.addColorStop(0, 'rgba(13, 110, 253, 0.1)');
                gradIncoming.addColorStop(1, 'rgba(13, 110, 253, 0.0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dates,
                        datasets: [{
                                label: 'New Reports',
                                data: incomingData,
                                borderColor: '#0d6efd',
                                backgroundColor: gradIncoming,
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 3
                            },
                            {
                                label: 'Resolved',
                                data: resolvedData,
                                borderColor: '#198754',
                                backgroundColor: 'transparent',
                                borderDash: [5, 5],
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                align: 'end',
                                labels: {
                                    boxWidth: 10,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255,255,255,0.95)',
                                titleColor: '#000',
                                bodyColor: '#666',
                                borderColor: '#eee',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [4, 4],
                                    color: '#f5f5f5'
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
                        }
                    }
                });

                window.openReportModal = function(report) {
                    document.getElementById('modalReportId').innerText = report.id;
                    document.getElementById('modalUserName').innerText = report.user ? report.user.name : 'Unknown';
                    document.getElementById('modalUserEmail').innerText = report.user ? report.user.email : '';
                    document.getElementById('modalReportType').innerText = report.report_type ? report.report_type
                        .name : 'General';
                    document.getElementById('modalDescription').value = report.description;

                    document.getElementById('modalStatusSelect').value = report.status;

                    const form = document.getElementById('updateReportForm');
                    form.action = `/admin-panel/request/user-report/${report.id}`;

                    const myModal = new bootstrap.Modal(document.getElementById('reportModal'));
                    myModal.show();
                }
            });
        </script>
    @endpush

    @if (session('success'))
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-success text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-check-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">Success!</h4>
                        <p class="text-muted mb-4 fs-5">{{ session('success') }}</p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm"
                            data-bs-dismiss="modal">
                            CONTINUE
                        </button>
                    </div>

                </div>
            </div>
        </div>

        @push('scripts')
            <script type="module">
                if (window.bootstrap) {
                    setTimeout(() => {
                        var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
                        myModal.show();
                    }, 300);
                }
            </script>
        @endpush
    @endif
@endsection
