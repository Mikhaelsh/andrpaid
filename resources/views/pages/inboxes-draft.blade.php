@extends('layouts.app')

@section('title', __('inboxesDraft.title'))

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/inboxes.css') }}">
@endsection

@section('content')
    @include('partials.navbarInbox')

    <div class="container py-4">
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-secondary">
                    <i class="bi bi-file-earmark me-2"></i>{{ __('inboxesDraft.header') }}
                </h5>
                <span class="text-muted small">{{ __('inboxesDraft.drafts_count', ['count' => $draftInboxes->total()]) }}</span>
            </div>

            <div class="list-group list-group-flush">
                @forelse ($draftInboxes as $draft)
                    <div class="mail-item read position-relative"
                        onclick="location.href='/inboxes/compose/{{ $draft->inboxId }}'">

                        <div class="text-muted fs-5 ps-1 pe-2">
                            <i class="bi bi-pencil-square"></i>
                        </div>

                        @if ($draft->toUser)
                            <img src="https://ui-avatars.com/api/?name={{ $draft->toUser->name }}&background=random&color=fff"
                                alt="Avatar" class="mail-avatar">
                        @else
                            <div
                                class="mail-avatar bg-light d-flex align-items-center justify-content-center text-muted border">
                                <i class="bi bi-question-lg"></i>
                            </div>
                        @endif

                        <div class="mail-content">
                            <div class="mail-header">
                                <span class="draft-recipient">
                                    <span class="prefix">{{ __('inboxesDraft.label_to') }}</span>
                                    {{ $draft->toUser->name ?? __('inboxesDraft.no_recipient') }}
                                </span>

                                <span class="mail-date">
                                    {{ $draft->updated_at->diffForHumans() }}
                                </span>
                            </div>

                            <div class="mail-body-preview">
                                <span class="text-danger small fw-bold me-2">{{ __('inboxesDraft.draft_tag') }}</span>
                                <span class="mail-subject text-dark fw-medium">
                                    {{ $draft->subject ?? __('inboxesDraft.no_subject') }}
                                </span>
                                <span class="mx-1 text-muted">-</span>
                                <span>
                                    {{ $draft->body ? Str::limit(strip_tags($draft->body), 60) : __('inboxesDraft.no_content') }}
                                </span>
                            </div>
                        </div>

                        <button type="button" class="btn-delete-draft" title="{{ __('inboxesDraft.tooltip_discard') }}"
                            onclick="event.stopPropagation(); confirmDeleteDraft('/inboxes/compose/{{ $draft->inboxId }}/delete-draft')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon text-muted">
                            <i class="bi bi-file-earmark-x"></i>
                        </div>
                        <h5>{{ __('inboxesDraft.empty_title') }}</h5>
                        <p class="small text-muted">{{ __('inboxesDraft.empty_desc') }}</p>
                        <a href="/inboxes/compose" class="btn btn-outline-primary btn-sm mt-2 rounded-pill">
                            {{ __('inboxesDraft.btn_compose_new') }}
                        </a>
                    </div>
                @endforelse
            </div>

            @if ($draftInboxes->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    {{ $draftInboxes->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="deleteDraftModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3 text-danger bg-danger-subtle rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-trash3-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-2">{{ __('inboxesDraft.discard_title') }}</h5>
                    <p class="text-muted small mb-4">{{ __('inboxesDraft.discard_desc') }}</p>
                    <form id="deleteDraftForm" method="POST" action="">
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger fw-bold">{{ __('inboxesDraft.btn_confirm_discard') }}</button>
                            <button type="button" class="btn btn-light text-muted fw-bold"
                                data-bs-dismiss="modal">{{ __('inboxesDraft.btn_cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDeleteDraft(deleteUrl) {
                const form = document.getElementById('deleteDraftForm');
                form.action = deleteUrl;

                const myModal = new bootstrap.Modal(document.getElementById('deleteDraftModal'));
                myModal.show();
            }
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

                        <h4 class="fw-bold mb-3 heading-text">{{ __('common.success') }}</h4>
                        <p class="text-muted mb-4 fs-5">{{ session('success') }}</p>

                        <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm"
                            data-bs-dismiss="modal">
                            {{ __('common.continue') }}
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
