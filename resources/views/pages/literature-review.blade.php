@extends('layouts.app')

@section('title', 'Literature Review - ' . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
    <style>
        .reference-card { transition: transform 0.2s; cursor: pointer; }
        .reference-card:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .empty-state-icon { font-size: 4rem; color: #dee2e6; margin-bottom: 1rem; }

        /* CITATION BOX STYLES */
        .citation-box {
            background-color: #f8f9fa;
            border-left: 3px solid #6c757d;
            font-family: 'Georgia', serif;
            font-size: 0.85rem;
            color: #495057;
            margin-top: 15px;
            padding: 10px 15px;
            border-radius: 4px;
            position: relative;
        }

        /* SYNTHESIS STUDIO STYLES */
        .synthesis-container { display: flex; height: 75vh; border-radius: 15px; overflow: hidden; background: #fff; box-shadow: 0 30px 60px rgba(0,0,0,0.5); }
        .synthesis-sources { flex: 0.8; background: #2c3e50; color: #ecf0f1; padding: 25px; overflow-y: auto; border-right: 1px solid #34495e; }
        .source-item { background: rgba(255,255,255,0.1); border-radius: 8px; padding: 15px; margin-bottom: 15px; font-size: 0.9rem; }
        .source-citation { font-size: 0.75rem; color: #bdc3c7; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        .source-points li { margin-bottom: 5px; }
        .synthesis-editor { flex: 1.2; padding: 40px; background: #fff; display: flex; flex-direction: column; }
        .editor-textarea { flex-grow: 1; border: none; font-family: 'Georgia', serif; font-size: 1.1rem; line-height: 1.8; color: #2c3e50; resize: none; outline: none; background: transparent; }
        .editor-textarea::placeholder { color: #ccc; font-style: italic; }
        .synthesis-header { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.2rem; margin-bottom: 20px; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; display: flex; justify-content: space-between; }
    </style>
@endsection

@section('content')
    @include('partials.navbarPaper', ['paper' => $paper])

    <div class="container py-5">
        {{-- Header Navigation --}}
        <div class="mb-4">
            <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/workspace" class="text-decoration-none text-muted small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Back to Workspace
            </a>
        </div>

        {{-- Title Area --}}
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="module-icon bg-primary bg-opacity-10 text-primary" style="width: 45px; height: 45px; font-size: 1.2rem;">
                        <i class="bi bi-book"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">Literature Review</h3>
                </div>
                <p class="text-muted mb-0 ms-1">Manage your bibliography and synthesize key themes.</p>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                {{-- CITATION SELECTOR --}}
                <select class="form-select form-select-sm" id="citationStyleSelector" onchange="updateCitations()" style="width: auto; cursor: pointer; font-weight: 500;">
                    <option value="apa">APA Style</option>
                    <option value="mla">MLA Style</option>
                    <option value="harvard">Harvard Style</option>
                    <option value="chicago">Chicago Style</option>
                </select>

                {{-- EXPORT BUTTON --}}
                <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/export-bibtex" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download me-1"></i> Export BibTeX
                </a>
                
                @if($canEdit)
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSourceModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Source
                    </button>
                @endif
            </div>
        </div>

        <div class="row g-4">
            {{-- LEFT COLUMN: Synthesis & Stats --}}
            <div class="col-lg-4 order-lg-2">
                {{-- Synthesis Status Card --}}
                <div class="workspace-card p-4 mb-4">
                    <h6 class="fw-bold text-dark mb-3">Synthesis Progress</h6>
                    <div class="mb-4">
                        @php
                            $totalRefs = !empty($paper->references_data) ? count($paper->references_data) : 0;
                            $analyzedRefs = 0;
                            if($totalRefs > 0) {
                                foreach($paper->references_data as $ref) {
                                    if(isset($ref['is_analyzed']) && $ref['is_analyzed']) $analyzedRefs++;
                                }
                            }
                            $progress = $totalRefs > 0 ? ($analyzedRefs / $totalRefs) * 100 : 0;
                        @endphp
                        
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Sources Analyzed</span>
                            <span class="fw-bold text-primary">{{ $analyzedRefs }}/{{ $totalRefs }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    @if($canEdit)
                        <div class="d-grid">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#synthesisModal">
                                <i class="bi bi-pencil-square me-2"></i>Write Synthesis
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Key Themes / Tags --}}
                <div class="workspace-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">Key Themes</h6>
                        @if($canEdit)
                            <button class="btn btn-link p-0 text-decoration-none small" onclick="toggleThemeForm()">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        
                        {{-- 1. LIST EXISTING THEMES --}}
                        @if(!empty($paper->themes))
                            @foreach($paper->themes as $theme)
                                <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/remove-theme" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="theme_name" value="{{ $theme }}">
                                    <button type="submit" class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3 py-2 text-decoration-none d-flex align-items-center gap-2" style="cursor: pointer; border: none; background: none;">
                                        {{ $theme }}
                                        <i class="bi bi-x-circle-fill opacity-50 hover-opacity-100" style="font-size: 0.7rem;"></i>
                                    </button>
                                </form>
                            @endforeach
                        @else
                            <span class="text-muted small fst-italic">No themes defined yet.</span>
                        @endif

                        {{-- 2. ADD BUTTON (Visual) --}}
                        @if($canEdit)
                            <span class="badge bg-light text-secondary border rounded-pill px-3 py-2 border-dashed" 
                                  id="addThemeBtn" 
                                  style="cursor: pointer;"
                                  onclick="toggleThemeForm()">
                                + Add Tag
                            </span>
                        @endif

                        {{-- 3. HIDDEN FORM --}}
                        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/add-theme" 
                              method="POST" 
                              id="addThemeForm" 
                              style="display: none;" 
                              class="w-100 mt-2">
                            @csrf
                            <div class="input-group input-group-sm">
                                <input type="text" name="theme_name" class="form-control" placeholder="Theme name..." required autofocus>
                                <button class="btn btn-primary" type="submit"><i class="bi bi-check"></i></button>
                                <button class="btn btn-outline-secondary" type="button" onclick="toggleThemeForm()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
                
                {{-- ACTIONS CARD (Finalize) --}}
                @if($canEdit)
                    <div class="workspace-card p-4 mt-4">
                        <h6 class="fw-bold text-dark mb-2">Review Status</h6>
                        <p class="text-muted small mb-3">
                            @if($paper->lit_review_finalized)
                                This section is marked as complete. You can reopen it if you need to add more sources.
                            @else
                                Once you have synthesized your sources, mark this section as finalized.
                            @endif
                        </p>
                        
                        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/finalize-lit-review" method="POST">
                            @csrf
                            @if($paper->lit_review_finalized)
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="bi bi-check-circle-fill me-2"></i> Finalized
                                </button>
                            @else
                                <button type="submit" class="btn btn-dark w-100">
                                    <i class="bi bi-check2-circle me-2"></i> Finalize Review
                                </button>
                            @endif
                        </form>
                    </div>
                @endif

            </div>

            {{-- RIGHT COLUMN: Reference List --}}
            <div class="col-lg-8 order-lg-1">
                @if(empty($paper->references_data))
                    <div class="text-center py-5 border rounded-3 bg-light">
                        <i class="bi bi-journal-bookmark-fill empty-state-icon"></i>
                        <h5 class="fw-bold text-muted">No References Yet</h5>
                        <p class="text-muted small mb-4">Start your research by adding your first source to the board.</p>
                        
                        @if($canEdit)
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSourceModal">
                                Add Reference
                            </button>
                        @endif
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach(array_reverse($paper->references_data) as $ref) 
                            {{-- Add Data Attributes for JS --}}
                            <div class="workspace-card reference-card p-4"
                                 data-title="{{ $ref['title'] }}" 
                                 data-author="{{ $ref['author'] }}" 
                                 data-year="{{ $ref['year'] }}" 
                                 data-journal="{{ $ref['publication'] ?? '' }}"
                                 data-url="{{ $ref['url'] ?? '' }}">

                                <div class="d-flex gap-3">
                                    <div class="module-icon {{ $ref['is_analyzed'] ? 'bg-success text-success' : 'bg-secondary text-secondary' }} bg-opacity-10 flex-shrink-0" style="width: 50px; height: 50px;">
                                        <i class="bi {{ $ref['is_analyzed'] ? 'bi-check-circle-fill' : 'bi-hourglass-split' }} fs-5"></i>
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="fw-bold text-dark mb-1">{{ $ref['title'] }}</h6>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <p class="text-muted small mb-2">
                                            {{ $ref['author'] }} ({{ $ref['year'] }}) 
                                            @if(!empty($ref['publication'])) • <i>{{ $ref['publication'] }}</i> @endif
                                        </p>
                                        
                                        @if(!empty($ref['key_points']))
                                            <div class="mt-2 pt-2 border-top">
                                                <span class="small fw-bold text-muted me-2"><i class="bi bi-pin-angle-fill me-1"></i>Key Points:</span>
                                                @foreach($ref['key_points'] as $point)
                                                    <span class="key-point-badge">{{ $point }}</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- DYNAMIC CITATION BOX --}}
                                        <div class="citation-box">
                                            <span class="badge bg-secondary position-absolute top-0 start-0 translate-middle ms-3" style="font-size: 0.6rem;" id="citation-badge">APA</span>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="citation-text fst-italic">Loading citation...</span>
                                                <button class="btn btn-link btn-sm p-0 ms-2 text-muted" onclick="copyCitation(this)" title="Copy to clipboard">
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

    @if($canEdit)
        {{-- ADD SOURCE MODAL --}}
        <div class="modal fade" id="addSourceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-detective">
                <div class="modal-content">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/add-reference" method="POST">
                        @csrf
                        <input type="hidden" name="key_points" id="keyPointsJson">

                        <div class="dossier-container">
                            <div class="dossier-paper">
                                <div class="dossier-title">
                                    <span>Adding Reference</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="dossier-label">Title of Work</label>
                                        <input type="text" name="title" class="dossier-input" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="dossier-label">Primary Author</label>
                                        <input type="text" name="author" class="dossier-input" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="dossier-label">Publication Year</label>
                                        <input type="number" name="year" class="dossier-input" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="dossier-label">Journal / Conference</label>
                                        <input type="text" name="journal" class="dossier-input">
                                    </div>
                                    <div class="col-12">
                                        <label class="dossier-label">DOI / Link</label>
                                        <input type="url" name="url" class="dossier-input">
                                    </div>
                                    <div class="col-12 mt-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_analyzed" id="flexSwitchCheckDefault">
                                                <label class="form-check-label small" for="flexSwitchCheckDefault">Mark as "Analyzed"</label>
                                            </div>
                                            <button type="submit" class="btn btn-dark px-4 rounded-pill">
                                                Save Reference <i class="bi bi-arrow-right ms-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dossier-board">
                                <div class="board-header">
                                    <i class="bi bi-pin-angle-fill text-danger"></i> Key Findings
                                </div>
                                <div class="sticky-note-container" id="stickyContainer"></div>
                                <div class="add-note-wrapper">
                                    <textarea id="noteInput" rows="2" class="note-input" placeholder="Type a key point..."></textarea>
                                    <button type="button" class="btn-pin" onclick="addStickyNote()">
                                        <i class="bi bi-pin-fill me-1"></i> Pin
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- SYNTHESIS STUDIO MODAL --}}
        <div class="modal fade" id="synthesisModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content bg-transparent border-0">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/save-synthesis" method="POST">
                        @csrf
                        <div class="synthesis-container">
                            <div class="synthesis-sources">
                                <h5 class="mb-4"><i class="bi bi-layers-fill me-2"></i>Source Material</h5>
                                @if(empty($paper->references_data))
                                    <p class="text-white-50 small">No references added yet.</p>
                                @else
                                    @foreach($paper->references_data as $ref)
                                        @if(!empty($ref['key_points']))
                                            <div class="source-item">
                                                <div class="source-citation">
                                                    {{ $ref['author'] }} ({{ $ref['year'] }})
                                                </div>
                                                <ul class="ps-3 mb-0 source-points">
                                                    @foreach($ref['key_points'] as $point)
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
                                    <span>Synthesis Draft</span>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                </div>
                                <textarea name="synthesis_text" class="editor-textarea" placeholder="Start synthesizing your findings here...">{{ $paper->synthesis_text }}</textarea>
                                <div class="mt-3 text-end">
                                    <button type="submit" class="btn btn-dark px-4">
                                        <i class="bi bi-save me-1"></i> Save Draft
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    // --- 1. CITATION LOGIC ---
    document.addEventListener('DOMContentLoaded', () => {
        updateCitations(); // Run immediately on load
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
        const author  = data.author || 'Unknown author';
        const year    = data.year || 'n.d.';
        const title   = data.title || 'Untitled';
        const journal = data.journal || '';
        const url     = data.url || '';

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
        // Prevent clicking the button from triggering the card click (if card has a link)
        event.stopPropagation();
        
        // Use parentElement twice to reach citation-box from the button
        const text = btn.parentElement.querySelector('.citation-text').innerText;
        navigator.clipboard.writeText(text).then(() => {
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2 text-success"></i>';
            setTimeout(() => { btn.innerHTML = originalIcon; }, 1500);
        });
    }

    // --- 2. STICKY NOTE LOGIC ---
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