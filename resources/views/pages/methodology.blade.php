@extends('layouts.app')

@section('title', __('methodology.title_prefix') . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
@endsection

@section('content')
    @include('partials.navbarPaper', ['paper' => $paper])

    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/workspace"
                    class="text-decoration-none text-muted small fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> {{ __('methodology.back_workspace') }}
                </a>
                <div class="d-flex align-items-center gap-3" style="margin-top: 20px;">
                    <div class="module-icon bg-info bg-opacity-10 text-info"
                        style="width: 45px; height: 45px; font-size: 1.2rem; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <h3 class="fw-bold text-dark mt-2 mb-0">{{ __('methodology.header_title') }}</h3>

                    @if ($paper->methodology_finalized)
                        <span
                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 mt-2">
                            <i class="bi bi-lock-fill me-1"></i> {{ __('methodology.status_finalized') }}
                        </span>
                    @else
                        <span class="badge bg-light text-secondary border mt-2">{{ __('methodology.status_draft') }}</span>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div id="statusMessage" class="text-success small fw-bold" style="opacity: 0; transition: opacity 0.5s;">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ __('methodology.status_saved') }}
                </div>

                @if ($canEdit)
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/finalize-methodology" method="POST">
                        @csrf
                        @if ($paper->methodology_finalized)
                            <button type="submit" class="btn btn-outline-success btn-sm"
                                title="{{ __('methodology.tooltip_reopen') }}">
                                <i class="bi bi-check-circle-fill me-1"></i> {{ __('methodology.status_finalized') }}
                            </button>
                        @else
                            <button type="submit" class="btn btn-dark btn-sm">
                                <i class="bi bi-check2-circle me-1"></i> {{ __('methodology.btn_finalize') }}
                            </button>
                        @endif
                    </form>
                @endif
            </div>
        </div>

        <div class="editor-container">
            @if (!$canEdit || $paper->methodology_finalized)
                <div class="read-only-overlay" title="{{ __('methodology.overlay_readonly') }}"></div>
            @endif

            <iframe id="drawioFrame"
                src="https://embed.diagrams.net/?embed=1&ui=atlas&spin=1&proto=json&configure=1&saveAndExit=0&noSaveBtn=0"></iframe>
        </div>
    </div>

    <div class="row mt-5 g-4" style="margin-bottom: 50px; margin-left: 50px; margin-right: 50px;">
        <div class="col-lg-6">
            <div class="methodology-card shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i
                            class="bi bi-database-fill me-2 text-primary"></i>{{ __('methodology.section_datasets') }}
                    </h5>
                    @if ($canEdit && !$paper->methodology_finalized)
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#addDatasetModal">
                            <i class="bi bi-plus-lg"></i> {{ __('methodology.btn_add_dataset') }}
                        </button>
                    @endif
                </div>

                @if (empty($paper->datasets))
                    <div class="text-center py-4 text-muted small bg-light rounded">
                        {{ __('methodology.no_datasets') }}
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach ($paper->datasets as $ds)
                            <div class="border rounded p-3 position-relative bg-white">
                                <div class="row">
                                    @if (!empty($ds['image_path']))
                                        <div class="col-4">
                                            <a href="{{ asset('storage/' . $ds['image_path']) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $ds['image_path']) }}"
                                                    class="dataset-preview-img" alt="Sample">
                                            </a>
                                        </div>
                                        <div class="col-8">
                                        @else
                                            <div class="col-12">
                                    @endif

                                    <h6 class="fw-bold mb-1">{{ $ds['name'] }}</h6>

                                    @if (!empty($ds['link']))
                                        <a href="{{ $ds['link'] }}" target="_blank"
                                            class="small text-primary text-decoration-none mb-2 d-inline-block">
                                            <i class="bi bi-link-45deg"></i> {{ __('methodology.link_source') }}
                                        </a>
                                    @else
                                        <span class="badge bg-light text-secondary border small mb-2">
                                            {{ __('methodology.manual_collection') }}
                                        </span>
                                    @endif

                                    <p class="small text-muted mb-0">{{ $ds['description'] }}</p>
                                </div>
                            </div>

                            @if ($canEdit && !$paper->methodology_finalized)
                                <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                                    <button class="btn btn-link text-secondary p-0 small"
                                        onclick='openEditDatasetModal(@json($ds))'>
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form
                                        action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/remove-dataset"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $ds['id'] }}">
                                        <button type="submit" class="btn btn-link text-danger p-0 small"
                                            onclick="return confirm('{{ __('methodology.confirm_remove_dataset') }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-6">
        <div class="methodology-card shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i
                        class="bi bi-calculator-fill me-2 text-info"></i>{{ __('methodology.section_formulas') }}
                </h5>
                @if ($canEdit && !$paper->methodology_finalized)
                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#addFormulaModal">
                        <i class="bi bi-plus-lg"></i> {{ __('methodology.btn_add_formula') }}
                    </button>
                @endif
            </div>
            @if (empty($paper->formulas))
                <div class="text-center py-4 text-muted small bg-light rounded">
                    {{ __('methodology.no_formulas') }}
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach ($paper->formulas as $form)
                        <div class="border rounded p-3 position-relative bg-white">
                            <div id="formula-display-{{ $form['id'] }}" class="mb-2 text-center py-3 bg-light rounded"
                                style="min-height: 50px; font-size: 1.2rem; overflow-x: auto;">
                            </div>

                            <p class="small text-muted mb-1">{{ $form['description'] }}</p>

                            @php
                                $refTitle = __('methodology.unknown_ref');
                                if (!empty($paper->references_data)) {
                                    foreach ($paper->references_data as $ref) {
                                        if (($ref['id'] ?? '') == $form['reference_id']) {
                                            $refTitle = $ref['author'] . ' (' . $ref['year'] . ')';
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            @if (!empty($form['reference_id']))
                                <div class="small fw-bold text-secondary">
                                    <i class="bi bi-journal-bookmark me-1"></i> {{ __('methodology.source_label') }}
                                    {{ $refTitle }}
                                </div>
                            @endif

                            @if ($canEdit && !$paper->methodology_finalized)
                                <form
                                    action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/remove-formula"
                                    method="POST" class="position-absolute top-0 end-0 m-2">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $form['id'] }}">
                                    <button type="submit" class="btn btn-link text-danger p-0 small"
                                        onclick="return confirm('{{ __('methodology.confirm_remove_formula') }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="col-12" style="margin-top: 50px;">
        <div class="methodology-card shadow-sm border-top-0" style="border-top: 4px solid #F7931E;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-code-slash me-2"
                            style="color: #F7931E;"></i>{{ __('methodology.section_code') }}</h5>
                    <p class="text-muted small mb-0">{{ __('methodology.code_desc') }}</p>
                </div>
                @if ($canEdit && !$paper->methodology_finalized)
                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addCodeModal">
                        <i class="bi bi-plus-lg"></i> {{ __('methodology.btn_embed_code') }}
                    </button>
                @endif
            </div>

            @if (empty($paper->code_blocks))
                <div class="text-center py-5 bg-light rounded">
                    <i class="bi bi-file-earmark-code" style="font-size: 2rem; color: #ccc;"></i>
                    <p class="text-muted small mt-2">{{ __('methodology.no_code') }}</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($paper->code_blocks as $code)
                        <div class="col-lg-6">
                            <div class="code-card">
                                <div class="code-header">
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($code['platform'] == 'colab')
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/Google_Colaboratory_SVG_Logo.svg"
                                                width="20">
                                        @elseif($code['platform'] == 'github')
                                            <i class="bi bi-github"></i>
                                        @else
                                            <i class="bi bi-code-square"></i>
                                        @endif
                                        <span class="fw-bold small">{{ $code['title'] }}</span>
                                    </div>
                                    @if ($canEdit && !$paper->methodology_finalized)
                                        <form
                                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/remove-code"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $code['id'] }}">
                                            <button type="submit"
                                                class="btn btn-link text-secondary p-0 small hover-white">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="code-content">

                                    <div class="ratio ratio-16x9">
                                        {!! $code['embed_code'] !!}
                                    </div>
                                    <div class="mt-2 text-muted small border-top pt-2">
                                        {{ $code['description'] }}
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
        <div class="modal fade" id="addDatasetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/add-dataset"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('methodology.modal_add_ds_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_ds_name') }}</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_sample_img') }}</label>
                                <input type="file" name="sample_image" class="form-control" accept="image/*">
                                <div class="form-text">{{ __('methodology.help_sample_img') }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_link') }}</label>
                                <input type="url" name="link" class="form-control" placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_desc') }}</label>
                                <textarea name="description" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{ __('methodology.btn_submit_ds') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editDatasetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/update-dataset"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="item_id" id="edit_ds_id">

                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('methodology.modal_edit_ds_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_ds_name') }}</label>
                                <input type="text" name="name" id="edit_ds_name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_update_img') }}</label>
                                <input type="file" name="sample_image" class="form-control" accept="image/*">
                                <div class="form-text">{{ __('methodology.help_update_img') }}</div>
                                <div id="current_image_preview" class="mt-2 d-none">
                                    <small class="text-muted">{{ __('methodology.label_current_img') }}</small><br>
                                    <img src="" id="edit_ds_img_preview"
                                        style="height: 60px; border-radius: 4px; border: 1px solid #ddd;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_link') }}</label>
                                <input type="url" name="link" id="edit_ds_link" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_desc') }}</label>
                                <textarea name="description" id="edit_ds_desc" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{ __('methodology.btn_save_changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addFormulaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/add-formula"
                        method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('methodology.modal_add_formula_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_latex') }}</label>
                                <input type="text" name="latex" id="latexInput" class="form-control font-monospace"
                                    placeholder="e.g. a^2 + b^2 = c^2" required oninput="renderPreview()">
                                <div id="latexPreview" class="latex-preview mt-2">{{ __('methodology.preview_text') }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_ref') }}</label>
                                <select name="reference_id" class="form-select">
                                    <option value="">{{ __('methodology.select_no_ref') }}</option>
                                    @if (!empty($paper->references_data))
                                        @foreach ($paper->references_data as $ref)
                                            <option value="{{ $ref['id'] ?? '' }}">
                                                {{ $ref['author'] }} ({{ $ref['year'] }}) -
                                                {{ Str::limit($ref['title'], 30) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_desc') }}</label>
                                <input type="text" name="description" class="form-control"
                                    placeholder="{{ __('methodology.placeholder_formula_desc') }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{ __('methodology.btn_submit_formula') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addCodeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/methodology/add-code" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('methodology.modal_embed_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_title') }}</label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="{{ __('methodology.placeholder_title_code') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_platform') }}</label>
                                <select name="platform" class="form-select">
                                    <option value="colab">{{ __('methodology.opt_colab') }}</option>
                                    <option value="github">{{ __('methodology.opt_github') }}</option>
                                    <option value="generic">{{ __('methodology.opt_generic') }}</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_embed') }}</label>
                                <textarea name="embed_code" class="form-control font-monospace" rows="4"
                                    placeholder="<script src='...'> or <iframe src='...'>"></textarea>
                                <div class="form-text">{{ __('methodology.help_embed') }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('methodology.label_desc') }}</label>
                                <input type="text" name="description" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark">{{ __('methodology.btn_submit_embed') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
    <script>
        const iframe = document.getElementById('drawioFrame');
        const existingXml = @json($paper->methodology_xml);

        const isFinalized = @json($paper->methodology_finalized);
        const userCanEdit = @json($canEdit);
        const canInteract = userCanEdit && !isFinalized;

        const configuration = {
            compressXml: false,
            ui: 'atlas'
        };

        window.addEventListener('message', function(event) {
            if (event.source !== iframe.contentWindow) return;
            const msg = JSON.parse(event.data);

            if (msg.event === 'configure') {
                iframe.contentWindow.postMessage(JSON.stringify({
                    action: 'configure',
                    config: configuration
                }), '*');
            } else if (msg.event === 'init') {
                iframe.contentWindow.postMessage(JSON.stringify({
                    action: 'load',
                    autosave: 0,
                    xml: existingXml || ''
                }), '*');
            } else if (msg.event === 'save' || msg.event === 'autosave') {
                if (!canInteract) return;
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
                    body: JSON.stringify({
                        xml: xmlData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const statusMsg = document.getElementById('statusMessage');
                    statusMsg.style.opacity = '1';
                    setTimeout(() => {
                        statusMsg.style.opacity = '0';
                    }, 3000);
                })
                .catch(error => console.error('Error saving diagram:', error));
        }

        document.addEventListener("DOMContentLoaded", function() {

            const formulas = @json($paper->formulas ?? []);

            formulas.forEach(form => {
                const element = document.getElementById('formula-display-' + form.id);

                if (element) {
                    let rawLatex = form.latex || "";
                    rawLatex = rawLatex.replaceAll('$$', '').replaceAll('$', '');

                    try {
                        katex.render(rawLatex, element, {
                            throwOnError: false,
                            displayMode: true
                        });
                    } catch (e) {
                        console.error(e);
                        element.innerHTML =
                            "<span class='text-danger small'>{{ __('methodology.invalid_formula') }}</span>";
                    }
                }
            });
        });

        function renderPreview() {
            const input = document.getElementById('latexInput').value;
            const preview = document.getElementById('latexPreview');

            let cleanInput = input.replaceAll('$$', '').replaceAll('$', '');

            preview.innerHTML = '';

            try {
                katex.render(cleanInput, preview, {
                    throwOnError: false,
                    displayMode: true
                });
            } catch (e) {
                preview.innerText = "{{ __('methodology.invalid_formula') }}";
            }
        }

        function openEditDatasetModal(dataset) {
            document.getElementById('edit_ds_id').value = dataset.id;
            document.getElementById('edit_ds_name').value = dataset.name;
            document.getElementById('edit_ds_link').value = dataset.link || '';
            document.getElementById('edit_ds_desc').value = dataset.description;

            const previewDiv = document.getElementById('current_image_preview');
            const imgTag = document.getElementById('edit_ds_img_preview');

            if (dataset.image_path) {
                previewDiv.classList.remove('d-none');
                imgTag.src = "/storage/" + dataset.image_path;
            } else {
                previewDiv.classList.add('d-none');
            }

            new bootstrap.Modal(document.getElementById('editDatasetModal')).show();
        }
    </script>
@endpush
