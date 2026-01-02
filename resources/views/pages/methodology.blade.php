@extends('layouts.app')

@section('title', 'Methodology - ' . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
    <style>
        .editor-container {
            width: 100%;
            height: 85vh; 
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            margin-top: 20px;
        }
        iframe { width: 100%; height: 100%; border: none; }
        
        /* Overlay blocks clicks when finalized or read-only */
        .read-only-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255,255,255,0.0);
            z-index: 10;
        }
    </style>
@endsection

@section('content')
    @include('partials.navbarPaper', ['paper' => $paper])

    <div class="container-fluid px-4 py-4">
        
        {{-- HEADER AREA --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/workspace" class="text-decoration-none text-muted small fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Back to Workspace
                </a>
                <div class="d-flex align-items-center gap-3" style="margin-top: 20px;">
                    <div class="module-icon bg-info bg-opacity-10 text-info" style="width: 45px; height: 45px; font-size: 1.2rem; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <h3 class="fw-bold text-dark mt-2 mb-0">Research Methodology</h3>
                    
                    {{-- Status Badge --}}
                    @if($paper->methodology_finalized)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 mt-2">
                            <i class="bi bi-lock-fill me-1"></i> Finalized
                        </span>
                    @else
                        <span class="badge bg-light text-secondary border mt-2">Draft Mode</span>
                    @endif
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                {{-- Auto-save status indicator --}}
                <div id="statusMessage" class="text-success small fw-bold" style="opacity: 0; transition: opacity 0.5s;">
                    <i class="bi bi-check-circle-fill me-1"></i> Saved
                </div>

                {{-- FINALIZE BUTTON --}}
                @if($canEdit)
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/finalize-methodology" method="POST">
                        @csrf
                        @if($paper->methodology_finalized)
                            <button type="submit" class="btn btn-outline-success btn-sm" title="Click to Reopen">
                                <i class="bi bi-check-circle-fill me-1"></i> Finalized
                            </button>
                        @else
                            <button type="submit" class="btn btn-dark btn-sm">
                                <i class="bi bi-check2-circle me-1"></i> Finalize Diagram
                            </button>
                        @endif
                    </form>
                @endif
            </div>
        </div>

        {{-- DRAW.IO EDITOR --}}
        <div class="editor-container">
            {{-- 
               BLOCK EDITING IF: 
               1. User is not authorized ($canEdit is false)
               OR 
               2. Paper is finalized ($paper->methodology_finalized is true)
            --}}
            @if(!$canEdit || $paper->methodology_finalized)
                <div class="read-only-overlay" title="Read Only Mode (Finalized)"></div>
            @endif
            
            <iframe id="drawioFrame" src="https://embed.diagrams.net/?embed=1&ui=atlas&spin=1&proto=json&configure=1&saveAndExit=0&noSaveBtn=0"></iframe>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    const iframe = document.getElementById('drawioFrame');
    const existingXml = @json($paper->methodology_xml);
    
    // JS Logic: User can edit ONLY if authorized AND not finalized
    const isFinalized = @json($paper->methodology_finalized);
    const userCanEdit = @json($canEdit);
    const canInteract = userCanEdit && !isFinalized;

    const configuration = { compressXml: false, ui: 'atlas' };

    window.addEventListener('message', function(event) {
        if (event.source !== iframe.contentWindow) return;
        const msg = JSON.parse(event.data);

        if (msg.event === 'configure') {
            iframe.contentWindow.postMessage(JSON.stringify({ action: 'configure', config: configuration }), '*');
        }
        else if (msg.event === 'init') {
            iframe.contentWindow.postMessage(JSON.stringify({
                action: 'load',
                autosave: 0, 
                xml: existingXml || ''
            }), '*');
        }
        else if (msg.event === 'save' || msg.event === 'autosave') {
            // Block saving if not allowed
            if(!canInteract) return;
            saveToBackend(msg.xml);
        }
    });

    function saveToBackend(xmlData) {
        fetch('/{{ $user->profileId }}/paper/{{ $paper->paperId }}/save-methodology', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ xml: xmlData })
        })
        .then(response => response.json())
        .then(data => {
            const statusMsg = document.getElementById('statusMessage');
            statusMsg.style.opacity = '1';
            setTimeout(() => { statusMsg.style.opacity = '0'; }, 3000);
        })
        .catch(error => console.error('Error saving diagram:', error));
    }
</script>
@endpush