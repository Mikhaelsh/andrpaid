@extends('layouts.app')

@section('title', __('paperWorkspace.title_prefix') . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
@endsection

@section('content')
    @include('partials.navbarPaper', ['paper' => $paper])

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold text-dark mb-1">{{ __('paperWorkspace.header_title') }}</h3>
                <p class="text-muted mb-0">{{ __('paperWorkspace.header_desc') }}</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/lit-review" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-book"></i>
                            </div>
                            @php
                                $refs = $paper->references_data;
                                if(is_string($refs)) $refs = json_decode($refs, true);
                                $refCount = is_array($refs) ? count($refs) : 0;
                            @endphp

                            @if($paper->lit_review_finalized)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                    <i class="bi bi-check-lg me-1"></i> {{ __('paperWorkspace.status_finalized') }}
                                </span>
                            @elseif($refCount > 0)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ __('paperWorkspace.status_in_progress') }}</span>
                            @else
                                <span class="badge bg-light text-secondary border">{{ __('paperWorkspace.status_draft') }}</span>
                            @endif
                        </div>
                        <h5 class="fw-bold text-dark mb-2">{{ __('paperWorkspace.module_lit_review') }}</h5>
                        <p class="text-muted small mb-4">{{ __('paperWorkspace.desc_lit_review') }}</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            @php
                                $refs = $paper->references_data;
                                if(is_string($refs)) $refs = json_decode($refs, true);
                                $refCount = is_array($refs) ? count($refs) : 0;
                            @endphp

                            <span class="small text-muted">{{ __('paperWorkspace.footer_references', ['count' => $refCount]) }}</span>
                            <span class="small fw-bold text-primary">{{ __('paperWorkspace.open') }} <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-diagram-3"></i>
                            </div>

                            @if($paper->methodology_finalized)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                    <i class="bi bi-check-lg me-1"></i> {{ __('paperWorkspace.status_finalized') }}
                                </span>
                            @elseif(!empty($paper->methodology_xml))
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ __('paperWorkspace.status_in_progress') }}</span>
                            @else
                                <span class="badge bg-light text-secondary border">{{ __('paperWorkspace.status_empty') }}</span>
                            @endif
                        </div>
                        <h5 class="fw-bold text-dark mb-2">{{ __('paperWorkspace.module_methodology') }}</h5>
                        <p class="text-muted small mb-4">{{ __('paperWorkspace.desc_methodology') }}</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="small text-muted">
                                @if(!empty($paper->methodology_xml))
                                    {{ __('paperWorkspace.footer_diagram_available') }}
                                @else
                                    {{ __('paperWorkspace.footer_no_diagrams') }}
                                @endif
                            </span>
                            <span class="small fw-bold text-primary">{{ __('paperWorkspace.open') }} <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-bar-chart-fill"></i>
                            </div>

                            @php
                                $items = $paper->results_data ?? [];
                                $hasItems = !empty($items);
                            @endphp

                            @if($paper->results_finalized)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                    <i class="bi bi-check-lg me-1"></i> {{ __('paperWorkspace.status_finalized') }}
                                </span>
                            @elseif($hasItems)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ __('paperWorkspace.status_in_progress') }}</span>
                            @else
                                <span class="badge bg-light text-secondary border">{{ __('paperWorkspace.status_empty') }}</span>
                            @endif
                        </div>
                        <h5 class="fw-bold text-dark mb-2">{{ __('paperWorkspace.module_results') }}</h5>
                        <p class="text-muted small mb-4">{{ __('paperWorkspace.desc_results') }}</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            @php
                                $chartCount = 0; $tableCount = 0;
                                if(is_array($items)) {
                                    foreach($items as $item) {
                                        if($item['type'] === 'chart') $chartCount++;
                                        if($item['type'] === 'table') $tableCount++;
                                    }
                                }
                            @endphp
                            <span class="small text-muted">
                                {{ __('paperWorkspace.footer_results_count', ['tables' => $tableCount, 'charts' => $chartCount]) }}
                            </span>
                            <span class="small fw-bold text-primary">{{ __('paperWorkspace.open') }} <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/conclusion" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-check-all"></i>
                            </div>

                            @php
                                $hasContent = !empty($paper->conclusion_summary);
                            @endphp

                            @if($paper->conclusion_finalized)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                    <i class="bi bi-check-lg me-1"></i> {{ __('paperWorkspace.status_finalized') }}
                                </span>
                            @elseif($hasContent)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ __('paperWorkspace.status_in_progress') }}</span>
                            @else
                                <span class="badge bg-light text-secondary border">{{ __('paperWorkspace.status_draft') }}</span>
                            @endif
                        </div>
                        <h5 class="fw-bold text-dark mb-2">{{ __('paperWorkspace.module_conclusion') }}</h5>
                        <p class="text-muted small mb-4">{{ __('paperWorkspace.desc_conclusion') }}</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="small text-muted">
                                {{ $hasContent ? __('paperWorkspace.footer_draft_started') : __('paperWorkspace.footer_not_started') }}
                            </span>
                            <span class="small fw-bold text-primary">{{ __('paperWorkspace.open') }} <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
@endsection
