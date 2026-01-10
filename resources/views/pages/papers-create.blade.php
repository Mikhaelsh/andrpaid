@extends('layouts.app')

@section('title', __('papersCreate.title'))

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/papers.css') }}">
@endsection

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="mb-5 border-bottom pb-3">
                    <h2 class="fw-bold text-dark mb-1">{{ __('papersCreate.header_title') }}</h2>
                    <p class="text-muted">
                        {{ __('papersCreate.header_desc') }}
                    </p>
                </div>

                <form action="/papers/create-new-paper" method="POST" id="createPaperForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">{{ __('papersCreate.label_owner_title') }} <span
                                class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-2">

                            <div
                                class="owner-badge d-flex align-items-center gap-2 px-3 py-2 rounded-3 border bg-light text-secondary">
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random"
                                    class="rounded-circle" width="20" height="20">
                                <span class="fw-bold small">{{ Auth::user()->name }}</span>
                            </div>

                            <span class="text-muted fs-4">/</span>

                            <div class="flex-grow-1">
                                <input type="text" name="title" class="form-control paper-form-input"
                                    placeholder="{{ __('papersCreate.placeholder_title') }}" required autofocus>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">{{ __('papersCreate.label_description') }} <span
                                class="text-muted fw-normal">{{ __('papersCreate.optional') }}</span></label>
                        <textarea name="description" class="form-control paper-form-input" rows="3"
                            placeholder="{{ __('papersCreate.placeholder_description') }}"></textarea>
                    </div>

                    <hr class="my-4 text-secondary opacity-25">

                    <div class="mb-4">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark mb-1">{{ __('papersCreate.label_type') }}</label>
                                <select name="paperType" class="form-select paper-form-select" required>
                                    <option value="" disabled selected>{{ __('papersCreate.select_type') }}</option>
                                    @foreach ($paperTypes as $paperType)
                                        <option value="{{ $paperType->paperTypeId }}">{{ $paperType->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark mb-1">
                                    {{ __('papersCreate.label_fields') }} <span class="text-danger">*</span>
                                    <span class="text-muted fw-normal small ms-1">{{ __('papersCreate.max_fields') }}</span>
                                </label>

                                <div class="field-selector-wrapper position-relative" id="fieldSelector">

                                    <div class="form-control paper-form-input d-flex flex-wrap align-items-center gap-2"
                                        id="field-visual-box" style="min-height: 45px; cursor: text;">

                                        <input type="text" id="field-search-input"
                                            class="border-0 bg-transparent p-0 m-0"
                                            style="outline: none; flex-grow: 1; min-width: 100px;"
                                            placeholder="{{ __('papersCreate.placeholder_fields') }}" autocomplete="off">
                                    </div>

                                    <div class="field-dropdown-menu shadow-sm border rounded-3 mt-1 d-none" id="field-list">
                                    </div>

                                    <div id="hidden-inputs-container">
                                    </div>

                                    <div id="field-error-msg" class="text-danger small mt-1 d-none">
                                        {{ __('papersCreate.error_fields') }}
                                    </div>
                                </div>

                                <script>
                                    window.researchFieldsData = {!! json_encode($researchFields) !!};
                                    window.lang = {
                                        no_fields_found: "{{ __('papersCreate.no_fields_found') }}",
                                        max_tooltip: "{{ __('papersCreate.max_tooltip') }}",
                                        placeholder: "{{ __('papersCreate.placeholder_fields') }}"
                                    };
                                </script>
                            </div>

                        </div>
                    </div>

                    <hr class="my-4 text-secondary opacity-25">

                    <div class="mb-5">
                        <label class="form-label fw-bold text-dark mb-3">{{ __('papersCreate.label_visibility') }}</label>

                        <div class="visibility-option mb-2">
                            <input type="radio" name="visibility" id="vis-public" value="public" checked>
                            <label for="vis-public" class="d-flex align-items-start gap-3 p-3 rounded-3 border">
                                <div class="vis-icon-box">
                                    <i class="bi bi-globe-americas fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ __('papersCreate.vis_public') }}</div>
                                    <div class="small text-muted">{{ __('papersCreate.vis_public_desc') }}</div>
                                </div>
                            </label>
                        </div>

                        <div class="visibility-option">
                            <input type="radio" name="visibility" id="vis-private" value="private">
                            <label for="vis-private" class="d-flex align-items-start gap-3 p-3 rounded-3 border">
                                <div class="vis-icon-box">
                                    <i class="bi bi-lock-fill fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ __('papersCreate.vis_private') }}</div>
                                    <div class="small text-muted">{{ __('papersCreate.vis_private_desc') }}
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 border-top pt-4">
                        <button type="submit" class="btn btn-create-repo py-2 px-4">
                            {{ __('papersCreate.btn_create') }}
                        </button>
                        <a href="/{{ Auth::user()->profileId }}/papers"
                            class="btn btn-link text-decoration-none text-secondary">{{ __('papersCreate.btn_cancel') }}</a>
                    </div>

                </form>
            </div>
        </div>

    </div>

    @push('scripts')
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {

                const allFields = window.researchFieldsData || [];
                const wrapper = document.getElementById('fieldSelector');
                const visualBox = document.getElementById('field-visual-box');
                const searchInput = document.getElementById('field-search-input');
                const dropdown = document.getElementById('field-list');
                const hiddenContainer = document.getElementById('hidden-inputs-container');
                const errorMsg = document.getElementById('field-error-msg');

                let selectedIds = [];
                const MAX_SELECTION = 3;

                renderDropdown(allFields);

                visualBox.addEventListener('click', () => {
                    searchInput.focus();
                    showDropdown();
                });

                searchInput.addEventListener('input', (e) => {
                    const query = e.target.value.toLowerCase();
                    const filtered = allFields.filter(field =>
                        field.name.toLowerCase().includes(query)
                    );
                    renderDropdown(filtered);
                    showDropdown();
                });

                document.addEventListener('click', (e) => {
                    if (!wrapper.contains(e.target)) {
                        hideDropdown();
                    }
                });

                const form = document.getElementById('createPaperForm');
                form.addEventListener('submit', (e) => {
                    if (selectedIds.length === 0) {
                        e.preventDefault();
                        visualBox.style.borderColor = '#dc3545';
                        errorMsg.classList.remove('d-none');
                        visualBox.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                });

                function showDropdown() {
                    dropdown.classList.remove('d-none');
                }

                function hideDropdown() {
                    dropdown.classList.add('d-none');
                }

                function renderDropdown(fields) {
                    dropdown.innerHTML = '';

                    if (fields.length === 0) {
                        dropdown.innerHTML = `<div class="p-2 text-muted small text-center">${window.lang.no_fields_found}</div>`;
                        return;
                    }

                    fields.forEach(field => {
                        const div = document.createElement('div');
                        div.className = 'field-option';
                        div.textContent = field.name;

                        // Check state
                        const isSelected = selectedIds.includes(String(field.researchFieldId));
                        const isFull = selectedIds.length >= MAX_SELECTION;

                        if (isSelected) {
                            div.classList.add('selected');
                        } else if (isFull) {
                            div.classList.add('disabled');
                            div.title = window.lang.max_tooltip;
                        }

                        // Click Handler
                        if (!isSelected && !isFull) {
                            div.addEventListener('click', (e) => {
                                e.stopPropagation();
                                addSelection(field);
                                searchInput.value = '';
                                renderDropdown(allFields);
                                searchInput.focus();
                            });
                        }

                        dropdown.appendChild(div);
                    });
                }

                function addSelection(field) {
                    if (selectedIds.length >= MAX_SELECTION) return;

                    const id = String(field.researchFieldId);
                    if (selectedIds.includes(id)) return;

                    // Add to state
                    selectedIds.push(id);

                    // Update UI
                    renderTags();
                    updateHiddenInputs();

                    // Remove error styling if exists
                    visualBox.style.borderColor = '#d0d7de';
                    errorMsg.classList.add('d-none');

                    // Re-render dropdown to update disabled states
                    renderDropdown(allFields);
                }

                function removeSelection(id) {
                    selectedIds = selectedIds.filter(itemId => itemId !== id);
                    renderTags();
                    updateHiddenInputs();
                    renderDropdown(allFields);
                }

                function renderTags() {
                    // Clear current tags (keep input)
                    const tags = visualBox.querySelectorAll('.field-tag');
                    tags.forEach(t => t.remove());

                    // Add new tags before the input
                    selectedIds.forEach(id => {
                        const field = allFields.find(f => String(f.researchFieldId) === id);
                        if (!field) return;

                        const tag = document.createElement('div');
                        tag.className = 'field-tag';
                        tag.innerHTML = `
                ${field.name}
                <span class="remove-tag" onclick="window.removeFieldTag('${id}')">&times;</span>
            `;
                        // Insert before the search input
                        visualBox.insertBefore(tag, searchInput);
                    });

                    // Update placeholder
                    if (selectedIds.length > 0) {
                        searchInput.placeholder = "";
                    } else {
                        searchInput.placeholder = window.lang.placeholder;
                    }
                }

                function updateHiddenInputs() {
                    hiddenContainer.innerHTML = '';
                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'category_ids[]';
                        input.value = id;
                        hiddenContainer.appendChild(input);
                    });
                }

                window.removeFieldTag = function(id) {
                    removeSelection(id);
                };

            });
        </script>
    @endpush
@endsection
