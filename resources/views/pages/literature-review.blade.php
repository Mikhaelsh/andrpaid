@extends('layouts.app')

@section('title', __('literatureReview.title_prefix') . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
@endsection

@section('content')
    @include('partials.navbarPaper', ['paper' => $paper])

    <div class="container py-5">
        <div class="mb-4">
            <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/workspace"
                class="text-decoration-none text-muted small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> {{ __('literatureReview.back_workspace') }}
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="module-icon bg-primary bg-opacity-10 text-primary"
                        style="width: 45px; height: 45px; font-size: 1.2rem;">
                        <i class="bi bi-book"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">{{ __('literatureReview.header_title') }}</h3>
                </div>
                <p class="text-muted mb-0 ms-1">{{ __('literatureReview.header_desc') }}</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <select class="form-select form-select-sm" id="citationStyleSelector" onchange="updateCitations()"
                    style="width: auto; cursor: pointer; font-weight: 500;">
                    <option value="apa">APA Style</option>
                    <option value="mla">MLA Style</option>
                    <option value="harvard">Harvard Style</option>
                    <option value="chicago">Chicago Style</option>
                </select>

                <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/export-bibtex"
                    class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download me-1"></i> {{ __('literatureReview.export_bibtex') }}
                </a>

                @if ($canEdit)
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSourceModal">
                        <i class="bi bi-plus-lg me-1"></i> {{ __('literatureReview.add_source') }}
                    </button>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 order-lg-2">
                <div class="workspace-card p-4 mb-4">
                    <h6 class="fw-bold text-dark mb-3">{{ __('literatureReview.synthesis_progress') }}</h6>
                    <div class="mb-4">
                        @php
                            $totalRefs = !empty($paper->references_data) ? count($paper->references_data) : 0;
                            $analyzedRefs = 0;
                            if ($totalRefs > 0) {
                                foreach ($paper->references_data as $ref) {
                                    if (isset($ref['is_analyzed']) && $ref['is_analyzed']) {
                                        $analyzedRefs++;
                                    }
                                }
                            }
                            $progress = $totalRefs > 0 ? ($analyzedRefs / $totalRefs) * 100 : 0;
                        @endphp

                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">{{ __('literatureReview.sources_analyzed') }}</span>
                            <span class="fw-bold text-primary">{{ $analyzedRefs }}/{{ $totalRefs }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress }}%">
                            </div>
                        </div>
                    </div>

                    @if ($canEdit)
                        <div class="d-grid">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#synthesisModal">
                                <i class="bi bi-pencil-square me-2"></i>{{ __('literatureReview.btn_write_synthesis') }}
                            </button>
                        </div>
                    @endif
                </div>

                <div class="workspace-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">{{ __('literatureReview.key_themes') }}</h6>
                        @if ($canEdit)
                            <button class="btn btn-link p-0 text-decoration-none small" onclick="toggleThemeForm()">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @if (!empty($paper->themes))
                            @foreach ($paper->themes as $theme)
                                <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/remove-theme"
                                    method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="theme_name" value="{{ $theme }}">
                                    <button type="submit"
                                        class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3 py-2 text-decoration-none d-flex align-items-center gap-2"
                                        style="cursor: pointer; border: none; background: none;">
                                        {{ $theme }}
                                        <i class="bi bi-x-circle-fill opacity-50 hover-opacity-100"
                                            style="font-size: 0.7rem;"></i>
                                    </button>
                                </form>
                            @endforeach
                        @else
                            <span class="text-muted small fst-italic">{{ __('literatureReview.no_themes') }}</span>
                        @endif

                        @if ($canEdit)
                            <span class="badge bg-light text-secondary border rounded-pill px-3 py-2 border-dashed"
                                id="addThemeBtn" style="cursor: pointer;" onclick="toggleThemeForm()">
                                {{ __('literatureReview.add_tag') }}
                            </span>
                        @endif

                        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/add-theme" method="POST"
                            id="addThemeForm" style="display: none;" class="w-100 mt-2">
                            @csrf
                            <div class="input-group input-group-sm">
                                <input type="text" name="theme_name" class="form-control"
                                    placeholder="{{ __('literatureReview.placeholder_theme') }}" required autofocus>
                                <button class="btn btn-primary" type="submit"><i class="bi bi-check"></i></button>
                                <button class="btn btn-outline-secondary" type="button" onclick="toggleThemeForm()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="workspace-card p-4 mt-4">
                    <h6 class="fw-bold text-dark mb-2">{{ __('literatureReview.review_status') }}</h6>
                    <p class="text-muted small mb-3">
                        @if ($paper->lit_review_finalized)
                            {{ __('literatureReview.status_complete_desc') }}
                        @else
                            {{ __('literatureReview.status_incomplete_desc') }}
                        @endif
                    </p>

                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/finalize-lit-review" method="POST">
                        @csrf
                        @if ($paper->lit_review_finalized)
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ __('literatureReview.btn_finalized') }}
                            </button>
                        @else
                            <button type="submit" class="btn btn-dark w-100">
                                <i class="bi bi-check2-circle me-2"></i> {{ __('literatureReview.btn_finalize') }}
                            </button>
                        @endif
                    </form>
                </div>

            </div>

            <div class="col-lg-8 order-lg-1">
                @if (empty($paper->references_data))
                    <div class="text-center py-5 border rounded-3 bg-light">
                        <i class="bi bi-journal-bookmark-fill empty-state-icon"></i>
                        <h5 class="fw-bold text-muted">{{ __('literatureReview.empty_title') }}</h5>
                        <p class="text-muted small mb-4">{{ __('literatureReview.empty_desc') }}</p>

                        @if ($canEdit)
                            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#addSourceModal">
                                {{ __('literatureReview.add_source') }}
                            </button>
                        @endif
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach (array_reverse($paper->references_data) as $ref)
                            <div class="workspace-card reference-card p-4" data-title="{{ $ref['title'] }}"
                                data-author="{{ $ref['author'] }}" data-year="{{ $ref['year'] }}"
                                data-journal="{{ $ref['publication'] ?? '' }}" data-url="{{ $ref['url'] ?? '' }}">

                                <div class="d-flex gap-3">
                                    <div class="module-icon {{ $ref['is_analyzed'] ? 'bg-success text-success' : 'bg-secondary text-secondary' }} bg-opacity-10 flex-shrink-0"
                                        style="width: 50px; height: 50px;">
                                        <i
                                            class="bi {{ $ref['is_analyzed'] ? 'bi-check-circle-fill' : 'bi-hourglass-split' }} fs-5"></i>
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="fw-bold text-dark mb-1">{{ $ref['title'] }}</h6>

                                            <div class="d-flex align-items-center gap-2">
                                                @if (!empty($ref['pdf_path']))
                                                    <a href="{{ asset('storage/' . $ref['pdf_path']) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-danger" title="View PDF">
                                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                                        {{ __('literatureReview.btn_view_pdf') }}
                                                    </a>
                                                @endif

                                                @if ($canEdit)
                                                    @if (!isset($ref['is_analyzed']) || !$ref['is_analyzed'])
                                                        <form
                                                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/mark-reference-analyzed"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="reference_id"
                                                                value="{{ $ref['id'] }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                                title="Mark as Analyzed">
                                                                <i class="bi bi-check2"></i>
                                                                {{ __('literatureReview.btn_mark_analyzed') }}
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-link text-secondary p-0 ms-1"
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                                            style="font-size: 1.2rem; line-height: 1;">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                            <li>
                                                                <form
                                                                    action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/remove-reference"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="reference_id"
                                                                        value="{{ $ref['id'] }}">
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger d-flex align-items-center gap-2">
                                                                        <i class="bi bi-trash"></i>
                                                                        {{ __('literatureReview.btn_delete_ref') }}
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="text-muted small mb-2">
                                            {{ $ref['author'] }} ({{ $ref['year'] }})
                                            @if (!empty($ref['publication']))
                                                • <i>{{ $ref['publication'] }}</i>
                                            @endif
                                        </p>

                                        @if (!empty($ref['key_points']))
                                            <div class="mt-2 pt-2 border-top">
                                                <span class="small fw-bold text-muted me-2"><i
                                                        class="bi bi-pin-angle-fill me-1"></i>{{ __('literatureReview.label_key_points') }}</span>
                                                @foreach ($ref['key_points'] as $point)
                                                    <span class="key-point-badge">{{ $point }}</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="citation-box">
                                            <span
                                                class="badge bg-secondary position-absolute top-0 start-0 translate-middle ms-3"
                                                style="font-size: 0.6rem;" id="citation-badge">APA</span>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span
                                                    class="citation-text fst-italic">{{ __('literatureReview.citation_loading') }}</span>
                                                <button class="btn btn-link btn-sm p-0 ms-2 text-muted"
                                                    onclick="copyCitation(this)" title="Copy to clipboard">
                                                    <i class="bi bi-clipboard"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($canEdit)
        <div class="modal fade" id="addSourceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-detective">
                <div class="modal-content">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/add-reference" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="key_points" id="keyPointsJson">

                        <div class="dossier-container">
                            <div class="dossier-paper">
                                <div class="dossier-title">
                                    <span>{{ __('literatureReview.modal_add_title') }}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="dossier-label">{{ __('literatureReview.label_work_title') }}</label>
                                        <input type="text" name="title" class="dossier-input" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="dossier-label">{{ __('literatureReview.label_primary_author') }}</label>
                                        <input type="text" name="author" class="dossier-input" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="dossier-label">{{ __('literatureReview.label_pub_year') }}</label>
                                        <input type="number" name="year" class="dossier-input" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="dossier-label">{{ __('literatureReview.label_journal') }}</label>
                                        <input type="text" name="journal" class="dossier-input">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="dossier-label">{{ __('literatureReview.label_doi') }}</label>
                                        <input type="url" name="url" class="dossier-input">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="dossier-label">{{ __('literatureReview.label_upload_pdf') }}</label>
                                        <input type="file" name="pdf_file"
                                            class="form-control form-control-sm mt-1 dossier-file-input" accept=".pdf">
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_analyzed"
                                                    id="flexSwitchCheckDefault">
                                                <label class="form-check-label small"
                                                    for="flexSwitchCheckDefault">{{ __('literatureReview.switch_analyzed') }}</label>
                                            </div>
                                            <button type="submit" class="btn btn-dark px-4 rounded-pill">
                                                {{ __('literatureReview.btn_save_ref') }} <i
                                                    class="bi bi-arrow-right ms-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="dossier-board">
                                <div class="board-header">
                                    <i class="bi bi-pin-angle-fill text-danger"></i>
                                    {{ __('literatureReview.board_header') }}
                                </div>
                                <div class="sticky-note-container" id="stickyContainer"></div>
                                <div class="add-note-wrapper">
                                    <textarea id="noteInput" rows="2" class="note-input"
                                        placeholder="{{ __('literatureReview.placeholder_sticky') }}"></textarea>
                                    <button type="button" class="btn-pin" onclick="addStickyNote()">
                                        <i class="bi bi-pin-fill me-1"></i> {{ __('literatureReview.btn_pin') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="synthesisModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content bg-transparent border-0">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/save-synthesis" method="POST">
                        @csrf
                        <div class="synthesis-container">
                            <div class="synthesis-sources">
                                <h5 class="mb-4"><i
                                        class="bi bi-layers-fill me-2"></i>{{ __('literatureReview.source_material') }}
                                </h5>
                                @if (empty($paper->references_data))
                                    <p class="text-white-50 small">{{ __('literatureReview.no_refs') }}</p>
                                @else
                                    @foreach ($paper->references_data as $ref)
                                        @if (!empty($ref['key_points']))
                                            <div class="source-item">
                                                <div class="source-citation">
                                                    {{ $ref['author'] }} ({{ $ref['year'] }})
                                                </div>
                                                <ul class="ps-3 mb-0 source-points">
                                                    @foreach ($ref['key_points'] as $point)
                                                        <li>{{ $point }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="synthesis-editor">
                                <div class="synthesis-header">
                                    <span>{{ __('literatureReview.synthesis_draft') }}</span>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                </div>
                                <textarea name="synthesis_text" class="editor-textarea"
                                    placeholder="{{ __('literatureReview.placeholder_synthesis') }}">{{ $paper->synthesis_text }}</textarea>
                                <div class="mt-3 text-end">
                                    <button type="submit" class="btn btn-dark px-4">
                                        <i class="bi bi-save me-1"></i> {{ __('literatureReview.btn_save_draft') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            updateCitations();
        });

        function updateCitations() {
            const style = document.getElementById('citationStyleSelector').value;
            const cards = document.querySelectorAll('.reference-card');

            cards.forEach(card => {
                const data = {
                    title: card.dataset.title,
                    author: card.dataset.author,
                    year: card.dataset.year,
                    journal: card.dataset.journal,
                    url: card.dataset.url
                };

                const textElement = card.querySelector('.citation-text');
                const badgeElement = card.querySelector('#citation-badge');

                badgeElement.innerText = style.toUpperCase();
                textElement.innerHTML = formatCitation(data, style);
            });
        }

        function formatCitation(data, style) {
            const author = data.author || 'Unknown author';
            const year = data.year || 'n.d.';
            const title = data.title || 'Untitled';
            const journal = data.journal || '';
            const url = data.url || '';

            switch (style) {
                case 'apa': {
                    let text = `${author} (${year}). <i>${title}</i>.`;
                    if (journal) text += ` <i>${journal}</i>.`;
                    if (url) text += ` ${url}`;
                    return text;
                }

                case 'mla': {
                    let text = `${author}. "${title}."`;
                    if (journal) text += ` <i>${journal}</i>,`;
                    text += ` ${year}.`;
                    if (url) text += ` ${url}.`;
                    return text;
                }

                case 'harvard': {
                    let text = `${author} (${year}) '${title}'.`;
                    if (journal) text += ` <i>${journal}</i>.`;
                    if (url) text += ` Available at: ${url}.`;
                    return text;
                }

                case 'chicago': {
                    let text = `${author}. "${title}."`;
                    if (journal) text += ` <i>${journal}</i>`;
                    text += ` (${year}).`;
                    if (url) text += ` ${url}.`;
                    return text;
                }

                default:
                    return `${author} (${year}). ${title}.`;
            }
        }

        function copyCitation(btn) {
            event.stopPropagation();

            const text = btn.parentElement.querySelector('.citation-text').innerText;
            navigator.clipboard.writeText(text).then(() => {
                const originalIcon = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check2 text-success"></i>';
                setTimeout(() => {
                    btn.innerHTML = originalIcon;
                }, 1500);
            });
        }

        let keyPoints = [];

        function addStickyNote() {
            const input = document.getElementById('noteInput');
            const container = document.getElementById('stickyContainer');
            const text = input.value.trim();

            if (text === '') return;

            keyPoints.push(text);
            updateHiddenInput();

            const note = document.createElement('div');
            note.classList.add('sticky-note');
            note.innerText = text;

            const closeBtn = document.createElement('i');
            closeBtn.classList.add('bi', 'bi-x', 'sticky-close');
            closeBtn.onclick = function() {
                const index = keyPoints.indexOf(text);
                if (index > -1) keyPoints.splice(index, 1);
                updateHiddenInput();
                note.remove();
            };

            note.appendChild(closeBtn);
            container.appendChild(note);

            input.value = '';
            container.scrollTop = container.scrollHeight;
        }

        function updateHiddenInput() {
            document.getElementById('keyPointsJson').value = JSON.stringify(keyPoints);
        }

        function toggleThemeForm() {
            const btn = document.getElementById('addThemeBtn');
            const form = document.getElementById('addThemeForm');

            if (form.style.display === 'none') {
                form.style.display = 'flex';
                btn.style.display = 'none';
                form.querySelector('input').focus();
            } else {
                form.style.display = 'none';
                btn.style.display = 'inline-block';
            }
        }
    </script>
@endpush
