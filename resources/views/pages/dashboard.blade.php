@extends('layouts.app')

@section('title', 'Dashboard')

@section('additionalCSS')
    <style>
        .stat-card {
            border: 1px solid #f0f0f0;
            border-radius: 16px;
            padding: 24px;
            background: #fff;
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-color: transparent;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 16px;
        }

        .project-card {
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            padding: 20px;
            background: #fff;
            margin-bottom: 16px;
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }

        .project-card:hover {
            border-color: #f0f0f0;
            border-left-color: #0d6efd;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
            background-color: #fafbff;
        }

        .sidebar-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .user-avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }

        .avatar-group {
            display: flex;
            padding-left: 10px;
        }

        .avatar-group .user-avatar-sm {
            margin-left: -10px;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .welcome-pattern {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            opacity: 0.1;
            background-image: radial-gradient(#fff 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
@endsection

@section('content')
    @include('partials.navbarProfile')

    <div class="container py-5">
        <div class="welcome-banner shadow">
            <div class="welcome-pattern"></div>
            <div class="row align-items-center position-relative z-1">
                <div class="col-lg-8">
                    <h1 class="fw-bold mb-2">Welcome back, {{ $user->name }}!</h1>
                    <p class="mb-0 opacity-75 fs-5">
                        You have <span class="fw-bold text-white border-bottom border-2">{{ $pendingRequestsCount }} pending
                            tasks</span> requiring your attention today.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="/papers/create" class="btn btn-light text-primary fw-bold px-4 py-2 shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i>New Project
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1">Active Projects</div>
                    <h2 class="fw-bold mb-0 text-dark">{{ $activeProjectsCount }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1">Pending Requests</div>
                    <h2 class="fw-bold mb-0 text-dark">{{ $pendingRequestsCount }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1">Total Stars</div>
                    <h2 class="fw-bold mb-0 text-dark">{{ number_format($totalStars) }}</h2>
                    <div class="mt-2 text-muted small" style="font-size: 0.8rem;">
                        <i class="bi bi-info-circle me-1"></i> Lifetime stars received
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-chat-left-text-fill"></i>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1">Messages</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h2 class="fw-bold mb-0 text-dark">{{ $messageCount }}</h2>
                        @if ($unreadMessages > 0)
                            <span class="badge bg-danger rounded-pill small">{{ $unreadMessages }} New</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0">Active Collaborations</h5>
                    <a href="#" class="text-decoration-none small fw-bold">View All</a>
                </div>

                @if ($activePapers->count() > 0)
                    @foreach ($activePapers as $paper)
                        @php
                            $statusLabel = 'Draft';
                            $statusColor = 'secondary';

                            if ($paper->conclusion_finalized) {
                                $statusLabel = 'Published';
                                $statusColor = 'success';
                            } elseif ($paper->results_finalized) {
                                $statusLabel = 'Conclusion';
                                $statusColor = 'info';
                            } elseif ($paper->methodology_finalized) {
                                $statusLabel = 'Results';
                                $statusColor = 'warning';
                            } elseif ($paper->lit_review_finalized) {
                                $statusLabel = 'Methodology';
                                $statusColor = 'primary';
                            }
                        @endphp

                        <div class="project-card position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex gap-2">
                                    <span
                                        class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} border border-{{ $statusColor }} border-opacity-25">
                                        {{ $statusLabel }}
                                    </span>
                                    <span class="badge bg-light text-secondary border">
                                        {{ $paper->paperType->name ?? 'Research' }}
                                    </span>
                                </div>
                                <small class="text-muted">{{ $paper->updated_at->diffForHumans(null, true, true) }}
                                    ago</small>
                            </div>

                            <h5 class="fw-bold text-dark mb-1">
                                <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/workspace"
                                    class="text-decoration-none text-dark stretched-link">
                                    {{ $paper->title }}
                                </a>
                            </h5>
                            <p class="text-muted small mb-3 text-truncate">
                                {{ Str::limit($paper->description, 140) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                                <div class="d-flex align-items-center gap-2 text-muted small">
                                    <i class="bi bi-people-fill"></i>
                                    <span>Team:</span>
                                    <div class="avatar-group">
                                        <img src="https://ui-avatars.com/api/?name={{ $paper->lecturer->user->name }}&background=random"
                                            class="user-avatar-sm shadow-sm"
                                            title="Owner: {{ $paper->lecturer->user->name }}">
                                    </div>
                                </div>

                                <div class="text-muted small">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    {{ $paper->paperStars->count() ?? 0 }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5 border rounded-3 bg-light">
                        <i class="bi bi-folder-plus text-muted opacity-50 mb-3" style="font-size: 3rem;"></i>
                        <h6 class="fw-bold text-muted">No active projects</h6>
                        <p class="text-muted small mb-3">You haven't started any research papers yet.</p>
                        <a href="/papers/create" class="btn btn-outline-primary btn-sm">Create First Project</a>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="sidebar-card">
                    <h6 class="fw-bold text-dark mb-3">Recommended for You</h6>

                    <div class="d-flex flex-column gap-3">
                        @foreach ($recommendations as $rec)
                            @php
                                if ($rec instanceof \App\Models\User) {
                                    $recUser = $rec;
                                    $displayName = $rec->name;
                                    $subText = 'University';
                                } else {
                                    $recUser = $rec->user;
                                    $displayName = $recUser->name;
                                    $subText = 'Lecturer';
                                }
                            @endphp

                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ $displayName }}&background=random"
                                    class="rounded-circle" width="45" height="45">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="fw-bold mb-0 text-truncate small">
                                        <a href="/{{ $recUser->profileId }}/overview"
                                            class="text-dark text-decoration-none">
                                            {{ $displayName }}
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-0 text-truncate">
                                        {{ $subText }}
                                    </p>
                                </div>
                                <a href="/{{ $recUser->profileId }}/overview"
                                    class="btn btn-sm btn-light border text-primary rounded-circle"
                                    style="width: 32px; height: 32px; padding: 0; display:flex; align-items:center; justify-content:center;">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-3 border-top text-center">
                        <a href="/find" class="text-decoration-none small fw-bold">Explore More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
