@extends('layouts.app')

@section('title', __('inboxesCompose.title'))

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/inboxes.css') }}">
@endsection

@section('content')
    @include('partials.navbarInbox')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="/inboxes/compose/{{ $inbox->inboxId }}" method="POST">
                    @csrf

                    <div class="compose-card">
                        <div class="compose-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-pencil-fill me-2 text-primary"></i>{{ __('inboxesCompose.header') }}
                            </h5>
                            <a href="/inboxes" class="btn-close" aria-label="Close"></a>
                        </div>

                        <div class="compose-body">
                            <div class="mb-4">
                                <label for="email" class="form-label-custom">{{ __('inboxesCompose.label_recipient') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="email" name="email" id="email"
                                           class="form-control form-control-custom border-start-0"
                                           placeholder="{{ __('inboxesCompose.placeholder_recipient') }}"
                                           value="{{ old('email', optional($inbox->toUser ?? null)->email) }}">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="subject" class="form-label-custom">{{ __('inboxesCompose.label_subject') }}</label>
                                <input type="text" name="subject" id="subject"
                                       class="form-control form-control-custom"
                                       placeholder="{{ __('inboxesCompose.placeholder_subject') }}"
                                       value="{{ old('subject', $inbox->subject) }}">
                            </div>

                            <div class="mb-2">
                                <label for="body" class="form-label-custom">{{ __('inboxesCompose.label_body') }}</label>
                                <textarea name="body" id="body" rows="8"
                                          class="form-control form-control-custom"
                                          placeholder="{{ __('inboxesCompose.placeholder_body') }}"
                                          style="resize: none;">{{ old('body', $inbox->body) }}</textarea>
                            </div>

                            <div class="btn-action-group">
                                <button type="submit" name="action" value="draft"
                                        class="btn btn-light text-muted fw-medium border">
                                    <i class="bi bi-file-earmark me-2"></i>{{ __('inboxesCompose.btn_save_draft') }}
                                </button>

                                <div class="d-flex gap-2">
                                    <button type="button"
                                            class="btn btn-outline-danger border-0"
                                            onclick="confirmDeleteDraft('/inboxes/compose/{{ $inbox->inboxId }}/delete-draft')">
                                        {{ __('inboxesCompose.btn_discard') }}
                                    </button>

                                    <button type="submit" name="action" value="send"
                                            class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                                        <i class="bi bi-send-fill me-2"></i>{{ __('inboxesCompose.btn_send') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteDraftModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3 text-danger bg-danger-subtle rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-trash3-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-2">{{ __('inboxesCompose.discard_title') }}</h5>
                    <p class="text-muted small mb-4">{{ __('inboxesCompose.discard_desc') }}</p>

                    <form id="deleteDraftForm" method="POST" action="">
                        @csrf

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger fw-bold">{{ __('inboxesCompose.btn_confirm_discard') }}</button>
                            <button type="button" class="btn btn-light text-muted fw-bold" data-bs-dismiss="modal">{{ __('inboxesCompose.btn_cancel_discard') }}</button>
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

    @if (session('error'))
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-error text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-x-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">{{ __('common.error') }}</h4>
                        <p class="text-muted mb-4 fs-5">{{ session('error') }}</p>

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
