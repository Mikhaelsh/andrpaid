@extends('layouts.app')

@section('title', 'Inbox')

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/inboxes.css') }}">
@endsection

@section('content')
    @include('partials.navbarInbox')

    <div class="container py-4">
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Inbox</h5>
                <span class="text-muted small">{{ $inboxes->total() }} messages</span>
            </div>

            <div class="list-group list-group-flush">
                @forelse ($inboxes as $inbox)
                    <div class="mail-item {{ $inbox->marked_read ? 'read' : 'unread' }}"
                        onclick="location.href='/inboxes/{{ $inbox->inboxId }}'">

                        <img src="https://ui-avatars.com/api/?name={{ $inbox->fromUser->name ?? 'System' }}&background=random&color=fff"
                            alt="Avatar" class="mail-avatar">

                        <div class="mail-content">
                            <div class="mail-header">
                                <span class="mail-sender">
                                    {{ $inbox->fromUser->name ?? 'System Notification' }}
                                </span>
                                <span class="mail-date">
                                    {{ $inbox->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="mail-body-preview">
                                <span class="mail-subject">{{ $inbox->subject ?? '(No Subject)' }}</span>
                                <span class="mx-1 text-muted">-</span>
                                <span>{{ Str::limit(strip_tags($inbox->body ?? 'No content...'), 60) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <h5>Your inbox is empty</h5>
                        <p class="small">Messages from other researchers will appear here.</p>
                    </div>
                @endforelse
            </div>

            @if ($inboxes->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    {{ $inboxes->links('pagination::bootstrap-5') }}
                </div>
            @endif
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

                        <h4 class="fw-bold mb-3 heading-text">Success!</h4>
                        <p class="text-muted mb-4 fs-5">{{ session('success') }}</p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm" data-bs-dismiss="modal">
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
