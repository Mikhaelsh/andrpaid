@extends('layouts.app')

@section('title', __('paperCollaborations.title_prefix') . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
@endsection

@section('content')
    @include('partials.navbarPaper')

    <div class="container py-5">
        @if (!$isOwner && $myPendingInvite)
            <div class="card border-0 shadow-sm mb-5 overflow-hidden border-start border-4 border-info">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-envelope-paper-heart-fill fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">{{ __('paperCollaborations.invited_title') }}</h5>
                                <p class="text-muted mb-0">
                                    {!! __('paperCollaborations.invited_text', ['name' => $myPendingInvite->fromLecturer->user->name]) !!}
                                    <span class="badge bg-info text-dark bg-opacity-25 border border-info px-2">
                                        {{ $myPendingInvite->collaboration->role }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-2 w-100 w-md-auto justify-content-end">
                            <form
                                action="/{{ $paper->lecturer->user->profileId }}/paper/{{ $paper->paperId }}/collaborations/accept-invitation"
                                method="POST">
                                @csrf
                                <input type="hidden" name="invitation_id" value="{{ $myPendingInvite->id }}">
                                <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                                    <i class="bi bi-check-lg me-2"></i> {{ __('paperCollaborations.btn_accept') }}
                                </button>
                            </form>

                            <button type="button" class="btn btn-outline-danger fw-bold px-4 rounded-pill"
                                data-bs-toggle="modal" data-bs-target="#rejectInviteModal">
                                {{ __('paperCollaborations.btn_decline') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="rejectInviteModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-4">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold">{{ __('paperCollaborations.modal_decline_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="text-muted small">{{ __('paperCollaborations.modal_decline_desc') }}</p>

                            <form
                                action="/{{ $paper->lecturer->user->profileId }}/paper/{{ $paper->paperId }}/collaborations/reject-invitation"
                                method="POST">
                                @csrf
                                <input type="hidden" name="invitation_id" value="{{ $myPendingInvite->id }}">

                                <div class="mb-3">
                                    <textarea name="message" class="form-control bg-light" rows="3"
                                        placeholder="{{ __('paperCollaborations.placeholder_decline_reason') }}"></textarea>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit"
                                        class="btn btn-danger fw-bold">{{ __('paperCollaborations.btn_decline_confirm') }}</button>
                                    <button type="button" class="btn btn-light text-muted"
                                        data-bs-dismiss="modal">{{ __('paperCollaborations.btn_cancel') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($isOwner)
            <div class="card border-0 shadow-sm mb-5 overflow-hidden">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div
                            class="rounded-circle p-3 {{ $paper->openCollaboration ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                            <i class="bi {{ $paper->openCollaboration ? 'bi-unlock-fill' : 'bi-lock-fill' }} fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">
                                {{ $paper->openCollaboration ? __('paperCollaborations.collab_open') : __('paperCollaborations.collab_closed') }}
                            </h5>
                            <p class="text-muted mb-0 small">
                                {{ $paper->openCollaboration ? __('paperCollaborations.desc_open') : __('paperCollaborations.desc_closed') }}
                            </p>
                        </div>
                    </div>

                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/toggle-collaboration"
                        method="POST">
                        @csrf
                        @if ($paper->openCollaboration)
                            <button class="btn btn-outline-danger fw-bold rounded-pill px-4">
                                <i class="bi bi-x-circle me-2"></i> {{ __('paperCollaborations.btn_close_collab') }}
                            </button>
                        @else
                            <button class="btn btn-success fw-bold rounded-pill px-4">
                                <i class="bi bi-check-circle me-2"></i> {{ __('paperCollaborations.btn_open_collab') }}
                            </button>
                        @endif
                    </form>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar {{ $paper->openCollaboration ? 'bg-success' : 'bg-secondary' }}"
                        style="width: 100%"></div>
                </div>
            </div>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-end mb-4 gap-3" id="roles">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ __('paperCollaborations.team_title') }}</h4>
                <p class="text-muted mb-0">{{ __('paperCollaborations.team_desc') }}</p>
            </div>

            @if ($isOwner)
                <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal"
                    data-bs-target="#addRoleModal">
                    <i class="bi bi-plus-lg me-2"></i> {{ __('paperCollaborations.btn_add_role') }}
                </button>
            @endif
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card collab-card h-100 rounded-4 border-primary border-opacity-25">
                    <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-primary mb-0 text-uppercase small" style="letter-spacing: 1px;">
                                {{ __('paperCollaborations.lead_researcher') }}</h6>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="member-avatar flex-shrink-0" style="width: 50px; height: 50px;">
                                <img src="https://ui-avatars.com/api/?name={{ $paper->lecturer->user->name }}&background=0d6efd&color=fff"
                                    class="shadow-sm" alt="">
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $paper->lecturer->user->name }}</h6>
                                @if ($paper->lecturer->affiliation)
                                    <small class="text-muted text-truncate d-block" style="max-width: 150px;">
                                        {{ $paper->lecturer->affiliation->university->user->name }}
                                    </small>
                                @endif
                            </div>
                        </div>
                        <p class="small text-muted mb-0">{{ __('paperCollaborations.owner_desc') }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-4 pt-0">
                        <a href="/{{ $paper->lecturer->user->profileId }}/overview"
                            class="btn btn-outline-light text-dark border-secondary border-opacity-25 btn-sm w-100 rounded-pill">{{ __('paperCollaborations.btn_view_profile') }}</a>
                    </div>
                </div>
            </div>

            @foreach ($slots as $slot)
                <div class="col-md-6 col-lg-4">
                    @if ($slot->lecturer)
                        <div class="card collab-card h-100 rounded-4">
                            <div class="card-header bg-light border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold text-dark mb-0">{{ $slot->role }}</h6>
                                    @if ($isOwner)
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                <li>
                                                    <button class="dropdown-item small edit-role-trigger"
                                                        data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                                        data-id="{{ $slot->id }}" data-role="{{ $slot->role }}"
                                                        data-description="{{ $slot->description }}">
                                                        {{ __('paperCollaborations.menu_edit_role') }}
                                                    </button>
                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>
                                                    <button class="dropdown-item text-danger small remove-member-trigger"
                                                        type="button" data-bs-toggle="modal"
                                                        data-bs-target="#removeMemberModal" data-id="{{ $slot->id }}"
                                                        data-name="{{ $slot->lecturer->user->name }}">
                                                        {{ __('paperCollaborations.menu_remove_member') }}
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="member-avatar flex-shrink-0" style="width: 50px; height: 50px;">
                                        <img src="https://ui-avatars.com/api/?name={{ $slot->lecturer->user->name }}&background=0d6efd&color=fff"
                                            class="shadow-sm" alt="">
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $slot->lecturer->user->name }}</h6>
                                        <small class="text-muted">{{ $slot->lecturer->affiliation }}</small>
                                    </div>
                                </div>
                                <p class="small text-muted mb-0">{{ $slot->description }}</p>
                            </div>
                            <div class="card-footer bg-white border-0 pb-4 pt-0">
                                <a href="/{{ $slot->lecturer->user->profileId }}/overview"
                                    class="btn btn-outline-light text-dark border-secondary border-opacity-25 btn-sm w-100 rounded-pill">{{ __('paperCollaborations.btn_view_profile') }}</a>
                            </div>
                        </div>
                    @else
                        <div class="card vacant-card h-100 rounded-4">
                            <div class="card-header bg-transparent border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold text-muted mb-0">{{ $slot->role }}</h6>
                                    @if ($isOwner)
                                        <form
                                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/remove-role"
                                            method="POST">
                                            @csrf

                                            <input type="hidden" name="roleId" value="{{ $slot->id }}">

                                            <button class="btn btn-link text-danger p-0" title="Delete Slot"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                                <div class="mb-3">
                                    <div class="bg-white border rounded-circle d-inline-flex align-items-center justify-content-center text-muted"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi bi-person-plus fs-4"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">{{ __('paperCollaborations.vacant_position') }}</h6>
                                <p class="small text-muted mb-3">{{ $slot->description }}</p>

                                @if ($isOwner)
                                    <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold invite-trigger"
                                        data-bs-toggle="modal" data-bs-target="#inviteModal"
                                        data-slot-id="{{ $slot->id }}">
                                        {{ __('paperCollaborations.btn_invite') }}
                                    </button>
                                @else
                                    @if ($paper->openCollaboration)
                                        @php
                                            $isRequested = false;

                                            if (Auth::check() && Auth::user()->lecturer) {
                                                $isRequested = \App\Models\CollaborationRequest::where(
                                                    'collaboration_id',
                                                    $slot->id,
                                                )
                                                    ->where('from_lecturer_id', Auth::user()->lecturer->id)
                                                    ->exists();
                                            }
                                        @endphp

                                        @if ($isRequested)
                                            <button type="button"
                                                class="btn btn-secondary btn-sm rounded-pill px-4 fw-bold" disabled>
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                {{ __('paperCollaborations.btn_requested') }}
                                            </button>
                                        @else
                                            <button type="button"
                                                class="btn btn-outline-success btn-sm rounded-pill px-4 fw-bold join-request-trigger"
                                                data-bs-toggle="modal" data-bs-target="#joinRequestModal"
                                                data-slot-id="{{ $slot->id }}" data-role-name="{{ $slot->role }}">
                                                {{ __('paperCollaborations.btn_apply') }}
                                            </button>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <span id="invitations"></span>
        @if ($isOwner && $invitations->count() != 0)
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-send-fill text-secondary me-2"></i>
                        {{ __('paperCollaborations.section_invitations') }}
                        <span class="badge bg-secondary rounded-pill ms-2">{{ count($invitations) }}</span>
                    </h5>

                    @php
                        $hasFinalized = $invitations->whereIn('status', ['accepted', 'rejected'])->isNotEmpty();
                    @endphp

                    @if ($hasFinalized)
                        <form
                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/clear-invitation-history"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted text-decoration-none btn-sm fw-bold">
                                <i class="bi bi-trash3 me-1"></i> {{ __('paperCollaborations.btn_clear_history') }}
                            </button>
                        </form>
                    @endif
                </div>

                <div
                    class="list-group shadow-sm rounded-3 overflow-hidden border-0 border-start border-4 border-secondary">
                    @foreach ($invitations as $inv)
                        <div class="list-group-item p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                <div class="d-flex align-items-start gap-3 w-100">
                                    <div class="member-avatar flex-shrink-0" style="width: 56px; height: 56px;">
                                        <img src="https://ui-avatars.com/api/?name={{ $inv->toLecturer->user->name }}&background=random&color=fff"
                                            alt="Profile" class="rounded-circle border">
                                    </div>

                                    <div class="w-100">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h6 class="fw-bold mb-0 text-dark fs-5">{{ $inv->toLecturer->user->name }}
                                            </h6>
                                        </div>

                                        <div class="d-flex flex-wrap align-items-center gap-3 text-muted small mb-2">
                                            @if ($inv->toLecturer->affiliation)
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-building me-1"></i>
                                                    {{ $inv->toLecturer->affiliation->university->user->name }}
                                                </div>
                                            @endif
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-briefcase me-1"></i>
                                                {{ __('paperCollaborations.invited_as') }} <strong
                                                    class="text-dark ms-1">{{ $inv->collaboration->role }}</strong>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $inv->updated_at->diffForHumans() }}
                                            </div>
                                        </div>

                                        @if ($inv->status === 'rejected' && $inv->message)
                                            <div
                                                class="bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3 p-3 mt-2">
                                                <div class="d-flex gap-2">
                                                    <i class="bi bi-info-circle-fill text-danger mt-1"></i>
                                                    <div>
                                                        <small class="fw-bold text-danger text-uppercase"
                                                            style="font-size: 0.7rem;">{{ __('paperCollaborations.reason_decline') }}</small>
                                                        <p class="mb-0 text-dark small fst-italic">"{{ $inv->message }}"
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex flex-column align-items-end gap-2 mt-2 mt-md-0">
                                    <div class="text-end">
                                        @if ($inv->status === 'pending')
                                            <span
                                                class="badge bg-warning text-dark bg-opacity-25 border border-warning rounded-pill px-3">
                                                <i class="bi bi-hourglass-split me-1"></i>
                                                {{ __('paperCollaborations.status_pending') }}
                                            </span>
                                        @elseif($inv->status === 'accepted')
                                            <span
                                                class="badge bg-success text-success bg-opacity-10 border border-success rounded-pill px-3">
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                {{ __('paperCollaborations.status_accepted') }}
                                            </span>
                                        @elseif($inv->status === 'rejected')
                                            <span
                                                class="badge bg-danger text-danger bg-opacity-10 border border-danger rounded-pill px-3">
                                                <i class="bi bi-x-circle-fill me-1"></i>
                                                {{ __('paperCollaborations.status_declined') }}
                                            </span>
                                        @endif
                                    </div>

                                    <form
                                        action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/cancel-invitation"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="invitation_id" value="{{ $inv->id }}">

                                        @if ($inv->status === 'pending')
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm fw-bold px-3 rounded-pill"
                                                title="Cancel Invitation">
                                                <i class="bi bi-x-lg me-1"></i>
                                                {{ __('paperCollaborations.btn_cancel_invite') }}
                                            </button>
                                        @else
                                            <button type="submit"
                                                class="btn btn-light text-muted btn-sm border fw-bold px-3 rounded-pill"
                                                title="Remove from list">
                                                <i class="bi bi-trash me-1"></i>
                                                {{ __('paperCollaborations.btn_remove') }}
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>

                            <div class="mt-3 d-block d-md-none">
                                @if ($inv->status === 'pending')
                                    <span
                                        class="badge bg-warning text-dark bg-opacity-25 border border-warning rounded-pill w-100 py-2">{{ __('paperCollaborations.status_pending') }}</span>
                                @elseif($inv->status === 'rejected')
                                    <span
                                        class="badge bg-danger text-danger bg-opacity-10 border border-danger rounded-pill w-100 py-2">{{ __('paperCollaborations.status_declined') }}</span>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($isOwner && $requests->count() != 0)
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-inbox-fill text-primary me-2"></i>
                        {{ __('paperCollaborations.section_requests') }}
                        <span class="badge bg-primary rounded-pill ms-2">{{ count($requests) }}</span>
                    </h5>

                    @php
                        $hasFinalizedRequests = $requests->whereIn('status', ['accepted', 'rejected'])->isNotEmpty();
                    @endphp

                    @if ($hasFinalizedRequests)
                        <form
                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/clear-request-history"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted text-decoration-none btn-sm fw-bold">
                                <i class="bi bi-trash3 me-1"></i> {{ __('paperCollaborations.btn_clear_history') }}
                            </button>
                        </form>
                    @endif
                </div>

                <div class="list-group shadow-sm rounded-3 overflow-hidden border-0 border-start border-4 border-primary">
                    @foreach ($requests as $req)
                        <div class="list-group-item p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                <div class="d-flex gap-3 w-100">
                                    <div class="member-avatar flex-shrink-0" style="width: 48px; height: 48px;">
                                        <img src="https://ui-avatars.com/api/?name={{ $req->fromLecturer->user->name }}&background=random&color=fff"
                                            alt="Profile" class="rounded-3">
                                    </div>

                                    <div class="w-100">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $req->fromLecturer->user->name }}</h6>
                                            <span class="text-muted small">&bull;
                                                {{ $req->created_at->diffForHumans() }}</span>
                                        </div>

                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                            @if ($req->toLecturer->affiliation)
                                                <span class="small text-muted">
                                                    <i class="bi bi-building me-1"></i>
                                                    {{ $req->toLecturer->affiliation->university->user->name }}
                                                </span>
                                            @endif

                                            <span class="badge bg-light text-dark border fw-normal small">
                                                {{ __('paperCollaborations.applying_for') }}
                                                <strong>{{ $req->collaboration->role }}</strong>
                                            </span>
                                        </div>

                                        <div class="bg-light p-3 rounded-3 fst-italic small text-dark mb-2">
                                            "{{ $req->message }}"
                                        </div>

                                        @if ($req->status === 'rejected')
                                            <div
                                                class="bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3 p-3 mt-2">
                                                <div class="d-flex gap-2">
                                                    <i class="bi bi-info-circle-fill text-danger mt-1"></i>
                                                    <div>
                                                        <small class="fw-bold text-danger text-uppercase"
                                                            style="font-size: 0.7rem;">{{ __('paperCollaborations.you_declined') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex flex-column align-items-end gap-2 mt-2 mt-md-0">
                                    @if ($req->status === 'accepted')
                                        <span
                                            class="badge bg-success text-success bg-opacity-10 border border-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            {{ __('paperCollaborations.status_accepted') }}
                                        </span>
                                    @elseif ($req->status === 'rejected')
                                        <span
                                            class="badge bg-danger text-danger bg-opacity-10 border border-danger rounded-pill px-3 py-2">
                                            <i class="bi bi-x-circle-fill me-1"></i>
                                            {{ __('paperCollaborations.status_declined') }}
                                        </span>
                                    @endif

                                    @if ($req->status === 'pending')
                                        <form
                                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/accept-request"
                                            method="POST" class="w-100">
                                            @csrf
                                            <input type="hidden" name="requestId" value="{{ $req->id }}">
                                            <button type="submit"
                                                class="btn btn-primary btn-sm fw-bold px-3 w-100 mb-2">{{ __('paperCollaborations.btn_accept_request') }}</button>
                                        </form>

                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm fw-bold px-3 w-100 reject-request-trigger"
                                            data-bs-toggle="modal" data-bs-target="#rejectRequestModal"
                                            data-request-id="{{ $req->id }}"
                                            data-applicant-name="{{ $req->toLecturer->user->name }}">
                                            {{ __('paperCollaborations.btn_reject_request') }}
                                        </button>
                                    @else
                                        <form
                                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/remove-request"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="requestId" value="{{ $req->id }}">
                                            <button type="submit"
                                                class="btn btn-light text-muted btn-sm border fw-bold px-3 rounded-pill"
                                                title="Remove from list">
                                                <i class="bi bi-trash me-1"></i>
                                                {{ __('paperCollaborations.btn_remove') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="joinRequestModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">{{ __('paperCollaborations.modal_apply_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="alert alert-light border d-flex gap-3 align-items-start mb-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px;">
                            <i class="bi bi-briefcase-fill small"></i>
                        </div>
                        <div class="small text-muted">
                            {!! __('paperCollaborations.apply_alert') !!}
                        </div>
                    </div>

                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/apply-for-role"
                        method="POST">
                        @csrf

                        <input type="hidden" name="slot_id" id="applySlotId">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('paperCollaborations.label_message') }}</label>
                            <textarea class="form-control bg-light" name="message" rows="4"
                                placeholder="{{ __('paperCollaborations.placeholder_apply_message') }}" required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success fw-bold py-2">
                                {{ __('paperCollaborations.btn_send_request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRoleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">{{ __('paperCollaborations.modal_edit_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/edit-role"
                        method="POST">
                        @csrf

                        <input type="hidden" name="slot_id" id="editSlotId">

                        <div class="mb-3">
                            <label
                                class="form-label fw-semibold">{{ __('paperCollaborations.label_role_title') }}</label>
                            <input type="text" class="form-control" name="role" id="editRoleTitle" required>
                        </div>
                        <div class="mb-3">
                            <label
                                class="form-label fw-semibold">{{ __('paperCollaborations.label_description') }}</label>
                            <textarea class="form-control" rows="3" name="description" id="editRoleDesc" required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit"
                                class="btn btn-primary fw-bold">{{ __('paperCollaborations.btn_save_changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeMemberModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger">{{ __('paperCollaborations.modal_remove_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/remove-member"
                        method="POST">
                        @csrf

                        <input type="hidden" name="slot_id" id="removeSlotId">

                        <div class="mb-4">
                            <p class="text-dark mb-1">
                                {!! __('paperCollaborations.remove_confirm') !!}
                            </p>
                            <p class="small text-muted mb-0">
                                {{ __('paperCollaborations.remove_desc') }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted"
                                style="letter-spacing: 0.5px;">
                                {{ __('paperCollaborations.label_remove_reason') }}
                            </label>
                            <textarea class="form-control bg-light border-0" name="reason" rows="3"
                                placeholder="{{ __('paperCollaborations.placeholder_remove_reason') }}"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger fw-bold py-2">
                                {{ __('paperCollaborations.btn_confirm_removal') }}
                            </button>
                            <button type="button" class="btn btn-light text-muted fw-bold py-2" data-bs-dismiss="modal">
                                {{ __('paperCollaborations.btn_cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRoleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">{{ __('paperCollaborations.modal_create_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/create-new-role"
                        method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold"
                                for="role">{{ __('paperCollaborations.label_role_title') }}</label>
                            <input type="text" class="form-control"
                                placeholder="{{ __('paperCollaborations.placeholder_role_title') }}" name="role"
                                id="role" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold"
                                for="description">{{ __('paperCollaborations.label_description') }}</label>
                            <textarea class="form-control" rows="3" placeholder="{{ __('paperCollaborations.placeholder_role_desc') }}"
                                name="description" id="description" required></textarea>
                        </div>
                        <div class="alert alert-light border small text-muted">
                            <i class="bi bi-info-circle me-1"></i> {{ __('paperCollaborations.create_alert') }}
                        </div>
                        <div class="d-grid">
                            <button type="submit"
                                class="btn btn-primary fw-bold">{{ __('paperCollaborations.btn_create_slot') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="inviteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">{{ __('paperCollaborations.modal_assign_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="targetSlotId">

                    <p class="text-muted small mb-3">{{ __('paperCollaborations.assign_desc') }}</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="userSearchInput" class="form-control border-start-0 bg-light"
                            placeholder="{{ __('paperCollaborations.placeholder_search_user') }}" autocomplete="off">
                    </div>

                    <div id="userSearchResults" class="list-group mb-3">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-success text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-check-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">{{ __('paperCollaborations.success_title') }}</h4>
                        <p class="text-muted mb-4 fs-5">{{ session('success') }}</p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm"
                            data-bs-dismiss="modal">
                            {{ __('paperCollaborations.btn_continue') }}
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

    <div class="modal fade" id="rejectRequestModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger">
                        {{ __('paperCollaborations.modal_reject_request_title') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/collaborations/reject-request"
                        method="POST">
                        @csrf
                        <input type="hidden" name="requestId" id="rejectRequestId">

                        <div class="mb-3">
                            <p class="text-dark mb-1">
                                {!! __('paperCollaborations.reject_request_desc') !!}
                            </p>
                            <p class="small text-muted">
                                {{ __('paperCollaborations.reject_request_sub') }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <label
                                class="form-label fw-bold small text-uppercase text-muted">{{ __('paperCollaborations.label_reject_reason') }}</label>
                            <textarea class="form-control bg-light" name="message" rows="3"
                                placeholder="{{ __('paperCollaborations.placeholder_reject_reason') }}" required></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit"
                                class="btn btn-danger fw-bold py-2">{{ __('paperCollaborations.btn_confirm_rejection') }}</button>
                            <button type="button" class="btn btn-light text-muted fw-bold py-2"
                                data-bs-dismiss="modal">{{ __('paperCollaborations.btn_cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('userSearchInput');
            const resultsContainer = document.getElementById('userSearchResults');
            const targetSlotIdInput = document.getElementById('targetSlotId');
            const paperId = "{{ $paper->paperId }}";
            const ownerId = "{{ $paper->lecturer->user->profileId }}";
            const currentUserId = {{ Auth::id() }};
            let timeout = null;

            const inviteModal = document.getElementById('inviteModal');
            inviteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const slotId = button.getAttribute('data-slot-id');
                targetSlotIdInput.value = slotId;

                searchInput.value = '';
                resultsContainer.innerHTML = '';
            });

            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = this.value;

                resultsContainer.innerHTML = '';

                if (query.length < 2) return;

                timeout = setTimeout(() => {
                    fetchUsers(query);
                }, 300);
            });

            function fetchUsers(query) {
                fetch(`{{ route('api.users.search.lecturer') }}?q=${query}`)
                    .then(response => response.json())
                    .then(users => {
                        renderResults(users);
                    })
                    .catch(error => console.error('Error:', error));
            }

            function renderResults(users) {
                const filteredUsers = users.filter(user => user.id != currentUserId);

                if (filteredUsers.length === 0) {
                    resultsContainer.innerHTML =
                        '<div class="text-muted small text-center p-3">{{ __('paperCollaborations.no_users_found') }}</div>';
                    return;
                }

                const slotId = targetSlotIdInput.value;

                const html = filteredUsers.map(user => `
        <form action="/${ownerId}/paper/${paperId}/collaborations/invite" method="POST" class="m-0">
            @csrf
            <input type="hidden" name="user_id" value="${user.id}">
            <input type="hidden" name="slot_id" value="${slotId}">

            <button type="submit" class="list-group-item list-group-item-action d-flex align-items-center gap-3 border-0 bg-light rounded-3 mb-2 w-100 text-start">
                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=random&color=fff" class="rounded-circle" width="32">
                <div class="w-100">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0 small fw-bold text-dark">${user.name}</h6>
                        <span class="badge bg-primary rounded-pill small">{{ __('paperCollaborations.btn_list_invite') }}</span>
                    </div>
                    <small class="text-muted" style="font-size: 0.7rem;">${user.email}</small>
                </div>
            </button>
        </form>
    `).join('');

                resultsContainer.innerHTML = html;
            }

            const removeMemberModal = document.getElementById('removeMemberModal');
            if (removeMemberModal) {
                removeMemberModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');

                    document.getElementById('removeSlotId').value = id;
                    document.getElementById('removeMemberName').textContent = name;
                });
            }

            const editRoleModal = document.getElementById('editRoleModal');
            if (editRoleModal) {
                editRoleModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    const id = button.getAttribute('data-id');
                    const role = button.getAttribute('data-role');
                    const desc = button.getAttribute('data-description');

                    document.getElementById('editSlotId').value = id;
                    document.getElementById('editRoleTitle').value = role;
                    document.getElementById('editRoleDesc').value = desc;
                });
            }

            const joinRequestModal = document.getElementById('joinRequestModal');
            if (joinRequestModal) {
                joinRequestModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    const slotId = button.getAttribute('data-slot-id');
                    const roleName = button.getAttribute('data-role-name');

                    document.getElementById('applySlotId').value = slotId;
                    document.getElementById('applyRoleName').textContent = roleName;
                });
            }

            const rejectRequestModal = document.getElementById('rejectRequestModal');
            if (rejectRequestModal) {
                rejectRequestModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    const requestId = button.getAttribute('data-request-id');
                    const applicantName = button.getAttribute('data-applicant-name');

                    document.getElementById('rejectRequestId').value = requestId;
                    document.getElementById('rejectApplicantName').textContent = applicantName;
                });
            }
        });
    </script>
@endsection
