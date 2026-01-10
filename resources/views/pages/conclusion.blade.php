@extends('layouts.app')

@section('title', __('conclusion.title_prefix') . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
    <style>
        .section-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 25px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            height: 100%;
            transition: transform 0.2s;
        }

        .section-card:hover {
            transform: translateY(-2px);
        }

        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        textarea {
            resize: none;
            border: 1px solid #dee2e6;
            background-color: #fcfcfc;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        textarea:focus {
            background-color: #fff;
            border-color: #8e2de2;
            box-shadow: 0 0 0 3px rgba(142, 45, 226, 0.1);
        }

        .finalized-text {
            background: #f8f9fa;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 6px;
            color: #555;
            white-space: pre-line;
        }
    </style>
@endsection

@section('content')
    @include('partials.navbarPaper', ['paper' => $paper])

    <div class="container py-5">
        @php
            $isLocked = $paper->conclusion_finalized;
            $canInteract = $canEdit && !$isLocked;
        @endphp

        <div class="mb-4">
            <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/workspace"
                class="text-decoration-none text-muted small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> {{ __('conclusion.back_workspace') }}
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="module-icon bg-success bg-opacity-10 text-success"
                        style="width: 45px; height: 45px; font-size: 1.2rem; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                        <i class="bi bi-check-all"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">{{ __('conclusion.header_title') }}</h3>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <p class="text-muted mb-0 ms-1">{{ __('conclusion.header_desc') }}</p>

                    @if ($isLocked)
                        <span
                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 ms-2">
                            <i class="bi bi-lock-fill me-1"></i> {{ __('conclusion.status_finalized') }}
                        </span>
                    @else
                        <span class="badge bg-light text-secondary border ms-2">{{ __('conclusion.status_draft') }}</span>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2">
                @if ($canEdit)
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/finalize-conclusion" method="POST">
                        @csrf
                        @if ($isLocked)
                            <button type="submit" class="btn btn-outline-success btn-sm me-2"
                                title="{{ __('conclusion.tooltip_reopen') }}">
                                <i class="bi bi-check-circle-fill me-1"></i> {{ __('conclusion.status_finalized') }}
                            </button>
                        @else
                            <button type="submit" class="btn btn-dark btn-sm me-2">
                                <i class="bi bi-check2-circle me-1"></i> {{ __('conclusion.btn_finalize') }}
                            </button>
                        @endif
                    </form>
                @endif
            </div>
        </div>

        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/save-conclusion" method="POST">
            @csrf

            <div class="row g-4">
                <div class="col-12">
                    <div class="section-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="section-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-card-text"></i>
                            </div>
                            <h5 class="fw-bold mb-0">{{ __('conclusion.section_summary') }}</h5>
                        </div>

                        @if ($isLocked)
                            <div class="finalized-text">
                                {{ $paper->conclusion_summary ?? __('conclusion.no_summary') }}
                            </div>
                        @else
                            <textarea name="summary" class="form-control" rows="6"
                                placeholder="{{ __('conclusion.placeholder_summary') }}" {{ $canEdit ? '' : 'disabled' }}>{{ $paper->conclusion_summary }}</textarea>
                            <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>
                                {{ __('conclusion.help_summary') }}</div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="section-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="section-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <h5 class="fw-bold mb-0">{{ __('conclusion.section_limitations') }}</h5>
                        </div>

                        @if ($isLocked)
                            <div class="finalized-text">
                                {{ $paper->conclusion_limitations ?? __('conclusion.no_limitations') }}
                            </div>
                        @else
                            <textarea name="limitations" class="form-control" rows="8"
                                placeholder="{{ __('conclusion.placeholder_limitations') }}" {{ $canEdit ? '' : 'disabled' }}>{{ $paper->conclusion_limitations }}</textarea>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="section-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="section-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-signpost-split"></i>
                            </div>
                            <h5 class="fw-bold mb-0">{{ __('conclusion.section_future_works') }}</h5>
                        </div>

                        @if ($isLocked)
                            <div class="finalized-text">
                                {{ $paper->conclusion_future_works ?? __('conclusion.no_future_works') }}
                            </div>
                        @else
                            <textarea name="future_works" class="form-control" rows="8"
                                placeholder="{{ __('conclusion.placeholder_future_works') }}" {{ $canEdit ? '' : 'disabled' }}>{{ $paper->conclusion_future_works }}</textarea>
                        @endif
                    </div>
                </div>
            </div>

            @if ($canInteract)
                <div class="fixed-bottom bg-white border-top py-3 shadow-lg" style="z-index: 100;">
                    <div class="container d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            <i class="bi bi-clock-history me-1"></i>
                            {{ __('conclusion.last_saved', ['time' => $paper->updated_at->diffForHumans()]) }}
                        </span>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-save me-2"></i> {{ __('conclusion.btn_save') }}
                        </button>
                    </div>
                </div>

                <div style="height: 80px;"></div>
            @endif
        </form>
    </div>

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
