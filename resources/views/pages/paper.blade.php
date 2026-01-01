@extends('layouts.app')

@section('title', $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
@endsection

@section('content')
    @include('partials.navbarPaper')

    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="paper-section-card p-4 p-md-5 mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="section-icon-box text-primary bg-primary bg-opacity-10">
                                <i class="bi bi-file-earmark-text-fill"></i>
                            </div>
                            <h4 class="fw-bold mb-0 text-dark">Description</h4>
                        </div>

                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                            <i class="bi bi-bookmark-fill text-muted me-1"></i>
                            {{ $paper->paperType->name ?? 'Research Paper' }}
                        </span>
                    </div>

                    <div class="paper-abstract-text">
                        {{ $paper->description ?? 'No description provided for this research.' }}
                    </div>
                </div>

                <div class="paper-section-card p-4 p-md-5">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div class="section-icon-box text-success bg-success bg-opacity-10">
                            <i class="bi bi-activity"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-dark">Recent Activity</h4>
                    </div>

                    <div class="timeline-empty-state text-center py-4 border rounded-3 bg-light border-dashed">
                        <p class="text-muted mb-0 small">No recent updates logged for this paper.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar-panel p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold text-uppercase text-muted small mb-0 tracking-wide">Research Team</h6>

                        @if ($paper->lecturer->id === Auth::user()->lecturer->id)
                            <a href="/{{ $paper->lecturer->user->profileId }}/paper/{{ $paper->paperId }}/collaborations" class="text-decoration-none small fw-bold text-primary">
                                Manage
                            </a>
                        @endif
                    </div>

                    <div class="team-list">
                        <div class="team-member-row position-relative mb-2">
                            <a href="/{{ $paper->lecturer->user->profileId }}/overview"
                            class="d-flex align-items-center gap-3 text-decoration-none text-reset w-100 p-2 rounded-3 member-link">

                                <div class="member-avatar flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ $paper->lecturer->user->name }}&background=0d6efd&color=fff"
                                        alt="{{ $paper->lecturer->user->name }}">
                                    <div class="role-badge" title="Lead Researcher">
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                </div>

                                <div class="member-info">
                                    <h6 class="member-name mb-0">{{ $paper->lecturer->user->name }}</h6>
                                    <span class="member-role text-primary fw-bold d-block mb-1" style="font-size: 0.85rem;">Lead Researcher</span>

                                    @if($paper->lecturer->affiliation)
                                        <div class="d-flex align-items-center text-muted small" style="font-size: 0.75rem;">
                                            <i class="bi bi-building me-1"></i>
                                            <span class="text-truncate" style="max-width: 150px;">
                                                {{ $paper->lecturer->affiliation->university->user->name }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </div>

                        @foreach ($paper->collaborations as $collab)
                            @if ($collab->lecturer)
                                <div class="team-member-row position-relative mb-2">
                                    <a href="/{{ $collab->lecturer->user->profileId }}/overview"
                                    class="d-flex align-items-center gap-3 text-decoration-none text-reset w-100 p-2 rounded-3 member-link">

                                        <div class="member-avatar flex-shrink-0">
                                            <img src="https://ui-avatars.com/api/?name={{ $collab->lecturer->user->name }}&background=6c757d&color=fff"
                                                alt="{{ $collab->lecturer->user->name }}">
                                        </div>

                                        <div class="member-info">
                                            <h6 class="member-name mb-0">{{ $collab->lecturer->user->name }}</h6>
                                            <span class="member-role text-muted d-block mb-1" style="font-size: 0.85rem;">{{ $collab->role }}</span>

                                            @if($collab->lecturer->affiliation)
                                                <div class="d-flex align-items-center text-muted small" style="font-size: 0.75rem;">
                                                    <i class="bi bi-building me-1"></i>
                                                    <span class="text-truncate" style="max-width: 150px;">
                                                        {{ $collab->lecturer->affiliation->university->user->name }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>

                <div class="sidebar-panel p-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-3 tracking-wide">Research Fields</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($paper->researchFields as $field)
                            <span class="field-tag">{{ $field->name }}</span>
                        @empty
                            <span class="text-muted small fst-italic">No fields tagged.</span>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 px-2">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Created</span>
                        <span class="fw-medium small text-dark">{{ $paper->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Last Updated</span>
                        <span class="fw-medium small text-dark">{{ $paper->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted small">Visibility</span>
                        <span class="fw-medium small text-capitalize {{ $paper->visibility == 'public' ? 'text-success' : 'text-secondary' }}">
                            {{ $paper->visibility }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
