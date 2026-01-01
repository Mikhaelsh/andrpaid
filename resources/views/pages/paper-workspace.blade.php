@extends('layouts.app')

@section('title', 'Workspace - ' . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
@endsection

@section('content')
    @include('partials.navbarPaper', ['paper' => $paper])

    <div class="container py-5">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold text-dark mb-1">Research Workspace</h3>
                <p class="text-muted mb-0">Select a module to begin writing or editing.</p>
            </div>
            {{-- Global Actions --}}
            <div>
                <button class="btn btn-outline-secondary btn-sm me-2">
                    <i class="bi bi-clock-history me-1"></i> History
                </button>
                <button class="btn btn-primary btn-sm">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Preview PDF
                </button>
            </div>
        </div>

        {{-- The 4 Modules Grid --}}
        <div class="row g-4">

            {{-- 1. LITERATURE REVIEW --}}
            <div class="col-md-6">
                <a href="#" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-book"></i>
                            </div>
                            <span class="badge bg-light text-secondary border">Draft</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Literature Review</h5>
                        <p class="text-muted small mb-4">Manage references, key points, and synthesize your theoretical framework.</p>

                        {{-- Mini Footer --}}
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="small text-muted">12 References</span>
                            <span class="small fw-bold text-primary">Open <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- 2. METHODOLOGY --}}
            <div class="col-md-6">
                <a href="#" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <span class="badge bg-light text-secondary border">Empty</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Methodology</h5>
                        <p class="text-muted small mb-4">Design your research flow, diagram your process, and define variables.</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="small text-muted">No diagrams yet</span>
                            <span class="small fw-bold text-primary">Open <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- 3. RESULTS --}}
            <div class="col-md-6">
                <a href="#" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-bar-chart-line"></i>
                            </div>
                            <span class="badge bg-light text-secondary border">In Progress</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Results & Analysis</h5>
                        <p class="text-muted small mb-4">Input data tables, generate charts, and interpret your findings.</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="small text-muted">2 Tables, 1 Chart</span>
                            <span class="small fw-bold text-primary">Open <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- 4. CONCLUSION --}}
            <div class="col-md-6">
                <a href="#" class="text-decoration-none">
                    <div class="workspace-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="module-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-chat-square-quote"></i>
                            </div>
                            <span class="badge bg-light text-secondary border">Locked</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Conclusion</h5>
                        <p class="text-muted small mb-4">Summarize your findings and suggest future research directions.</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="small text-muted">Waiting for Results</span>
                            <span class="small fw-bold text-primary">Open <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
@endsection
