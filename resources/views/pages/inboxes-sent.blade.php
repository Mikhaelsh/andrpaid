@extends('layouts.app')

@section('title', 'Sent Messages')

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/inboxes.css') }}">
@endsection

@section('content')
    @include('partials.navbarInbox')

    <div class="container py-4">
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-secondary">
                    <i class="bi bi-send me-2"></i>Sent
                </h5>
                <span class="text-muted small">{{ $sentInboxes->total() }} messages</span>
            </div>

            <div class="list-group list-group-flush">
                @forelse ($sentInboxes as $sent)
                    <div class="mail-item sent position-relative"
                         onclick="location.href='/inboxes/{{ $sent->inboxId }}'">

                        <img src="https://ui-avatars.com/api/?name={{ $sent->toUser->name }}&background=random&color=fff"
                             alt="Avatar" class="mail-avatar">

                        <div class="mail-content">
                            <div class="mail-header">
                                <span class="sent-recipient">
                                    <span class="prefix">To:</span>
                                    {{ $sent->toUser->name ?? 'Unknown Recipient' }}
                                </span>

                                <span class="mail-date">
                                    {{ $sent->created_at->format('d M Y') }}
                                </span>
                            </div>

                            <div class="mail-body-preview">
                                <span class="mail-subject text-dark fw-medium">
                                    {{ $sent->subject ?? '(No Subject)' }}
                                </span>
                                <span class="mx-1 text-muted">-</span>
                                <span>
                                    {{ $sent->body ? Str::limit(strip_tags($sent->body), 60) : 'No content...' }}
                                </span>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon text-muted">
                            <i class="bi bi-send-x"></i>
                        </div>
                        <h5>No sent messages</h5>
                        <p class="small text-muted">Messages you send will appear here.</p>
                        <a href="/inbox/compose" class="btn btn-outline-primary btn-sm mt-2 rounded-pill">
                            Compose New
                        </a>
                    </div>
                @endforelse
            </div>

            @if($sentInboxes->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    {{ $sentInboxes->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
