@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/profile.css') }}">
@endsection

@section('content')
    @include('partials.navbarProfile')

    <div class="profile-header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                    <div class="profile-avatar-wrapper">
                        <span class="profile-avatar-initials">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                </div>

                <div class="col-md text-center text-md-start">
                    <h2 class="fw-bold text-white mb-1">{{ $user->name }}</h2>

                    <p class="text-white-50 mb-2 fs-5">
                        @if ($user->lecturer && $user->lecturer->affiliation)
                            <i class="bi bi-building me-2"></i>{{ $user->lecturer->affiliation->university->user->name }}
                        @endif

                        @if ($user->lecturer->province)
                            <span class="mx-2 opacity-50">|</span>
                            <i class="bi bi-geo-alt-fill me-1"></i> {{ $user->lecturer->province->name }}
                        @endif
                    </p>

                    <div class="d-flex justify-content-center justify-content-md-start gap-2 mt-3">
                        <span class="badge bg-dark border border-secondary text-light px-3 py-2 rounded-pill">
                            <i class="bi bi-person-badge me-1"></i> Lecturer
                        </span>
                        @if ($user->lecturer && $user->lecturer->affiliation)
                            <span class="badge bg-success bg-opacity-25 text-white border border-success px-3 py-2 rounded-pill">
                                <i class="bi bi-check-circle-fill me-1"></i> Verified
                            </span>
                        @endif
                    </div>
                </div>

                @php
                    $paperCount = $user->lecturer ? $user->lecturer->papers->count() : 0;
                    $starCount = $user->lecturer ? \App\Models\PaperStar::whereIn('paper_id', $user->lecturer->papers->select('id'))->count() : 0;
                    $collabCount = $user->lecturer ? $user->lecturer->papers->where('openCollaboration', true)->count() : 0;

                    $totalActivity = $paperCount + $starCount + $collabCount;
                @endphp

                @if ($totalActivity > 0)
                    <div class="col-md-auto mt-4 mt-md-0">
                        <div class="d-flex gap-4 justify-content-center stats-container bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur dynamic-stats-box">

                            @if ($paperCount > 0)
                                <div class="text-center px-3 stat-item">
                                    <div class="h3 fw-bold text-white mb-0">{{ $paperCount }}</div>
                                    <div class="small text-white-50">Papers</div>
                                </div>
                            @endif

                            @if ($starCount > 0)
                                <div class="text-center px-3 stat-item">
                                    <div class="h3 fw-bold text-warning mb-0">{{ $starCount }}</div>
                                    <div class="small text-white-50">Total Stars</div>
                                </div>
                            @endif

                            @if ($collabCount > 0)
                                <div class="text-center px-3 stat-item">
                                    <div class="h3 fw-bold text-info mb-0">{{ $collabCount }}</div>
                                    <div class="small text-white-50">Open Collabs</div>
                                </div>
                            @endif

                        </div>
                    </div>
                @endif
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
                    @if($user->description)
                        <p class="text-muted" style="line-height: 1.7;">
                            {{ $user->description }}
                        </p>
                    @else
                        <p class="text-muted small fst-italic">This user hasn't written a bio yet.</p>
                    @endif
                </div>

                @if ($user->lecturer && $user->lecturer->researchFields->count() > 0)
                    <div class="mb-5">
                        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                            <i class="bi bi-lightbulb-fill me-2 text-primary"></i> Research Interests
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($user->lecturer->researchFields as $researchField)
                                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-medium">
                                    {{ $researchField->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card border-0 bg-light rounded-4 p-4">
                    <h6 class="fw-bold mb-3">Connect</h6>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                        <li>
                            <a href="mailto:{{ $user->email }}" class="text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary">
                                <div class="bg-white p-2 rounded-circle shadow-sm">
                                    <i class="bi bi-envelope-fill text-primary"></i>
                                </div>
                                <span>{{ $user->email }}</span>
                            </a>
                        </li>

                        @if(optional($user->lecturer)->linkedin_url)
                            <li>
                                <a href="{{ $user->lecturer->linkedin_url }}" target="_blank" class="text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary">
                                    <div class="bg-white p-2 rounded-circle shadow-sm">
                                        <i class="bi bi-linkedin text-primary"></i>
                                    </div>
                                    <span>LinkedIn Profile</span>
                                </a>
                            </li>
                        @endif

                        @if(optional($user->lecturer)->portfolio_url)
                            <li>
                                <a href="{{ $user->lecturer->portfolio_url }}" target="_blank" class="text-decoration-none text-muted d-flex align-items-center gap-2 hover-primary">
                                    <div class="bg-white p-2 rounded-circle shadow-sm">
                                        <i class="bi bi-globe text-primary"></i>
                                    </div>
                                    <span>Portfolio Website</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

            </div>

            <div class="col-lg-8">
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-2">
                        <h4 class="fw-bold text-dark mb-0">
                            <i class="bi bi-trophy-fill text-warning me-2"></i> Top Rated Research
                        </h4>
                        <a href="/{{ $user->profileId }}/papers?sort=stars" class="text-decoration-none small fw-bold">View All</a>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse ($topPapers as $paper)
                            <div class="card border-0 shadow-sm rounded-3 overflow-hidden position-relative featured-paper-card">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between">
                                        <div class="col-10">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill small">
                                                    <i class="bi bi-star-fill me-1"></i> Top Rated
                                                </span>
                                                <span class="text-muted small px-2 border-start">
                                                    {{ $paper->paperType->name }}
                                                </span>
                                            </div>
                                            <h5 class="fw-bold mb-2">
                                                <a href="#" class="text-decoration-none text-dark stretched-link">{{ $paper->title }}</a>
                                            </h5>
                                            <p class="text-muted small mb-3 text-truncate-2">
                                                {{ $paper->description }}
                                            </p>

                                            <div class="d-flex gap-2">
                                                @foreach($paper->researchFields as $field)
                                                    <span class="badge bg-light text-secondary border">{{ $field->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <h3 class="fw-bold text-warning mb-0">{{ $paper->paper_stars_count }}</h3>
                                            <span class="small text-muted">Stars</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 start-0 bottom-0 bg-warning" style="width: 4px;"></div>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-light rounded-3 border border-dashed">
                                <p class="text-muted mb-0">No papers published yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-2">
                        <h4 class="fw-bold text-dark mb-0">
                            <i class="bi bi-people-fill text-primary me-2"></i> Open for Collaboration
                        </h4>
                        <a href="/{{ $user->profileId }}/papers?collab[]=1" class="text-decoration-none small fw-bold">View All</a>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse ($collabPapers as $paper)
                            <div class="card border-0 shadow-sm rounded-3 collab-paper-card" style="background-color: #f8faff;">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span class="paper-status-badge collab-open">
                                                    <i class="bi bi-people-fill me-1"></i> Looking for Collaborators
                                                </span>
                                                <span class="text-muted small">
                                                    Updated {{ $paper->updated_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <h5 class="fw-bold mb-2">
                                                <a href="#" class="text-decoration-none text-dark">{{ $paper->title }}</a>
                                            </h5>
                                            <p class="text-muted small mb-0 text-truncate-2">
                                                {{ $paper->description }}
                                            </p>
                                        </div>
                                        <a href="mailto:{{ $user->email }}?subject=Collaboration Interest: {{ $paper->title }}"
                                           class="btn btn-primary btn-sm rounded-pill px-3 fw-bold ms-3" style="white-space: nowrap;">
                                            Contact
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-light rounded-3 border border-dashed">
                                <p class="text-muted mb-0">No active collaboration requests at the moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
