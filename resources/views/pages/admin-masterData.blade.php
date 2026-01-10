@extends('layouts.app')

@section('title', __('adminMasterData.title'))

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/admin-masterData.css') }}">
@endsection

@section('content')
    @include('partials.navbarAdmin')

    @if ($type === 'researchFields')
        <div class="container master-data-container">
            <div class="row justify-content-center">
                <div class="col-xl-11">
                    <div class="master-data-card">
                        <div class="master-data-header">
                            <div>
                                <h4 class="fw-bold mb-1">{{ __('adminMasterData.rf_title') }}</h4>
                                <p class="text-muted small mb-0">{{ __('adminMasterData.rf_subtitle') }}</p>
                            </div>

                            <div class="d-flex gap-3">
                                <div class="master-data-search-wrapper">
                                    <i class="bi bi-search master-data-search-icon"></i>
                                    <input type="text" id="searchInput" class="form-control master-data-search-input"
                                        placeholder="      {{ __('adminMasterData.rf_search_placeholder') }}">
                                </div>

                                <button class="btn btn-primary d-flex align-items-center gap-2 px-4 fw-medium"
                                    data-bs-toggle="modal" data-bs-target="#createModal">
                                    <i class="bi bi-plus-lg"></i> {{ __('adminMasterData.rf_btn_new') }}
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table master-data-table mb-0" id="dataTable">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>{{ __('adminMasterData.rf_table_name') }}</th>
                                        <th>{{ __('adminMasterData.rf_table_id') }}</th>
                                        <th>{{ __('adminMasterData.table_created') }}</th>
                                        <th class="text-end">{{ __('adminMasterData.table_actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($researchFields as $index => $field)
                                        <tr>
                                            <td class="text-muted font-monospace">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-bold text-dark search-target">{{ $field->name }}</span>
                                            </td>
                                            <td>
                                                <span class="master-data-badge">{{ $field->researchFieldId }}</span>
                                            </td>
                                            <td class="text-muted small">
                                                {{ $field->created_at ? $field->created_at->format('M d, Y') : '-' }}
                                            </td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="master-data-action-btn master-data-btn-edit me-1 edit-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-id="{{ $field->id }}" data-name="{{ $field->name }}"
                                                    data-slug="{{ $field->researchFieldId }}">
                                                    <i class="bi bi-pencil-fill" style="font-size: 0.9rem;"></i>
                                                </button>

                                                <button type="button"
                                                    class="master-data-action-btn master-data-btn-delete delete-btn"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-id="{{ $field->id }}" data-name="{{ $field->name }}">
                                                    <i class="bi bi-trash-fill" style="font-size: 0.9rem;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="emptyRow">
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted opacity-25 mb-3">
                                                    <i class="bi bi-inbox-fill" style="font-size: 3.5rem;"></i>
                                                </div>
                                                <h6 class="fw-bold text-secondary">{{ __('adminMasterData.no_data') }}</h6>
                                                <p class="text-muted small">{{ __('adminMasterData.start_adding') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse

                                    <tr id="noResultsRow" class="d-none">
                                        <td colspan="5" class="text-center py-5">
                                            <h6 class="fw-bold text-secondary">{{ __('adminMasterData.no_results') }}</h6>
                                            <button class="btn btn-link btn-sm text-decoration-none"
                                                onclick="document.getElementById('searchInput').value = ''; document.getElementById('searchInput').dispatchEvent(new Event('keyup'));">
                                                {{ __('adminMasterData.clear_search') }}
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CREATE MODAL --}}
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content master-data-modal-content">
                    <form method="POST" action="/admin-panel/master-data/research-fields/create">
                        @csrf
                        <div class="master-data-modal-header">
                            <h5 class="modal-title fw-bold">{{ __('adminMasterData.rf_modal_create_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="master-data-label">{{ __('adminMasterData.label_display_name') }}</label>
                                <input type="text" class="form-control form-control-lg" id="createName" name="name"
                                    placeholder="e.g. Artificial Intelligence" required>
                            </div>
                            <div class="mb-2">
                                <label class="master-data-label">{{ __('adminMasterData.rf_table_id') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">#</span>
                                    <input type="text" class="form-control bg-light border-start-0 text-muted"
                                        id="createSlug" name="slug" placeholder="artificial-intelligence" readonly>
                                </div>
                                <div class="form-text small">{{ __('adminMasterData.label_id_auto') }}</div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('adminMasterData.btn_cancel') }}</button>
                            <button type="submit" class="btn btn-primary px-4">{{ __('adminMasterData.btn_create') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- EDIT MODAL --}}
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content master-data-modal-content">
                    <form id="editForm" method="POST" action="/admin-panel/master-data/research-fields/update">
                        @csrf
                        <input type="hidden" id="editId" name="id">
                        <div class="master-data-modal-header">
                            <h5 class="modal-title fw-bold">{{ __('adminMasterData.rf_modal_edit_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="master-data-label">{{ __('adminMasterData.label_display_name') }}</label>
                                <input type="text" class="form-control form-control-lg" id="editName" name="name" required>
                            </div>
                            <div class="mb-2">
                                <label class="master-data-label">{{ __('adminMasterData.rf_table_id') }}</label>
                                <input type="text" class="form-control bg-light text-muted" id="editSlug" name="slug" readonly>
                                <div class="form-text small">{{ __('adminMasterData.label_id_immutable') }}</div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('adminMasterData.btn_cancel') }}</button>
                            <button type="submit" class="btn btn-primary px-4">{{ __('adminMasterData.btn_save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- DELETE MODAL --}}
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content master-data-modal-content text-center p-4">
                    <form id="deleteForm" method="POST" action="/admin-panel/master-data/research-fields/delete">
                        @csrf
                        <input type="hidden" id="deleteId" name="id">
                        <div class="mb-3 text-danger bg-soft-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; background: #fff5f5;">
                            <i class="bi bi-trash3-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('adminMasterData.rf_modal_delete_title') }}</h5>
                        <p class="text-muted small mb-4">
                            {!! __('adminMasterData.delete_confirm', ['name' => '<span id="deleteNamePlaceholder" class="fw-bold text-dark"></span>']) !!}
                        </p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">{{ __('adminMasterData.btn_delete') }}</button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('adminMasterData.btn_cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if ($type === 'paperTypes')
        <div class="container master-data-container">
            <div class="row justify-content-center">
                <div class="col-xl-11">
                    <div class="master-data-card">
                        <div class="master-data-header">
                            <div>
                                <h4 class="fw-bold mb-1">{{ __('adminMasterData.pt_title') }}</h4>
                                <p class="text-muted small mb-0">{{ __('adminMasterData.pt_subtitle') }}</p>
                            </div>

                            <div class="d-flex gap-3">
                                <div class="master-data-search-wrapper">
                                    <i class="bi bi-search master-data-search-icon"></i>
                                    <input type="text" id="searchInput" class="form-control master-data-search-input"
                                        placeholder="      {{ __('adminMasterData.pt_search_placeholder') }}">
                                </div>

                                <button class="btn btn-primary d-flex align-items-center gap-2 px-4 fw-medium"
                                    data-bs-toggle="modal" data-bs-target="#createModal">
                                    <i class="bi bi-plus-lg"></i> {{ __('adminMasterData.pt_btn_new') }}
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table master-data-table mb-0" id="dataTable">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>{{ __('adminMasterData.pt_table_name') }}</th>
                                        <th>{{ __('adminMasterData.pt_table_id') }}</th>
                                        <th>{{ __('adminMasterData.table_created') }}</th>
                                        <th class="text-end">{{ __('adminMasterData.table_actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($paperTypes as $index => $item)
                                        <tr>
                                            <td class="text-muted font-monospace">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-bold text-dark search-target">{{ $item->name }}</span>
                                            </td>
                                            <td>
                                                <span class="master-data-badge">{{ $item->paperTypeId }}</span>
                                            </td>
                                            <td class="text-muted small">
                                                {{ $item->created_at ? $item->created_at->format('M d, Y') : '-' }}
                                            </td>
                                            <td class="text-end">
                                                <button type="button"
                                                    class="master-data-action-btn master-data-btn-edit me-1 edit-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                    data-slug="{{ $item->paperTypeId }}">
                                                    <i class="bi bi-pencil-fill" style="font-size: 0.9rem;"></i>
                                                </button>

                                                <button type="button"
                                                    class="master-data-action-btn master-data-btn-delete delete-btn"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                                    <i class="bi bi-trash-fill" style="font-size: 0.9rem;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="emptyRow">
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted opacity-25 mb-3">
                                                    <i class="bi bi-file-earmark-text-fill" style="font-size: 3.5rem;"></i>
                                                </div>
                                                <h6 class="fw-bold text-secondary">{{ __('adminMasterData.no_data_pt') }}</h6>
                                                <p class="text-muted small">{{ __('adminMasterData.start_adding') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse

                                    <tr id="noResultsRow" class="d-none">
                                        <td colspan="5" class="text-center py-5">
                                            <h6 class="fw-bold text-secondary">{{ __('adminMasterData.no_results') }}</h6>
                                            <button class="btn btn-link btn-sm text-decoration-none"
                                                onclick="document.getElementById('searchInput').value = ''; document.getElementById('searchInput').dispatchEvent(new Event('keyup'));">
                                                {{ __('adminMasterData.clear_search') }}
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CREATE MODAL PAPER TYPES --}}
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content master-data-modal-content">
                    <form method="POST" action="/admin-panel/master-data/paper-types/create">
                        @csrf
                        <div class="master-data-modal-header">
                            <h5 class="modal-title fw-bold">{{ __('adminMasterData.pt_modal_create_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="master-data-label">{{ __('adminMasterData.label_display_name') }}</label>
                                <input type="text" class="form-control form-control-lg" id="createName" name="name"
                                    placeholder="e.g. Journal" required>
                            </div>
                            <div class="mb-2">
                                <label class="master-data-label">{{ __('adminMasterData.pt_table_id') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">#</span>
                                    <input type="text" class="form-control bg-light border-start-0 text-muted"
                                        id="createSlug" name="slug" placeholder="journal" readonly>
                                </div>
                                <div class="form-text small">{{ __('adminMasterData.label_id_auto') }}</div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('adminMasterData.btn_cancel') }}</button>
                            <button type="submit" class="btn btn-primary px-4">{{ __('adminMasterData.btn_create') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- EDIT MODAL PAPER TYPES --}}
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content master-data-modal-content">
                    <form id="editForm" method="POST" action="/admin-panel/master-data/paper-types/update">
                        @csrf
                        <input type="hidden" id="editId" name="id">
                        <div class="master-data-modal-header">
                            <h5 class="modal-title fw-bold">{{ __('adminMasterData.pt_modal_edit_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="master-data-label">{{ __('adminMasterData.label_display_name') }}</label>
                                <input type="text" class="form-control form-control-lg" id="editName" name="name" required>
                            </div>
                            <div class="mb-2">
                                <label class="master-data-label">{{ __('adminMasterData.pt_table_id') }}</label>
                                <input type="text" class="form-control bg-light text-muted" id="editSlug" name="slug" readonly>
                                <div class="form-text small">{{ __('adminMasterData.label_id_immutable') }}</div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('adminMasterData.btn_cancel') }}</button>
                            <button type="submit" class="btn btn-primary px-4">{{ __('adminMasterData.btn_save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- DELETE MODAL PAPER TYPES --}}
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content master-data-modal-content text-center p-4">
                    <form id="deleteForm" method="POST" action="/admin-panel/master-data/paper-types/delete">
                        @csrf
                        <input type="hidden" id="deleteId" name="id">
                        <div class="mb-3 text-danger bg-soft-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; background: #fff5f5;">
                            <i class="bi bi-trash3-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('adminMasterData.pt_modal_delete_title') }}</h5>
                        <p class="text-muted small mb-4">
                            {!! __('adminMasterData.delete_confirm', ['name' => '<span id="deleteNamePlaceholder" class="fw-bold text-dark"></span>']) !!}
                        </p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">{{ __('adminMasterData.btn_delete') }}</button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('adminMasterData.btn_cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- SCRIPTS SHARED BY BOTH --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Search Logic
                const searchInput = document.getElementById('searchInput');
                const tableRows = document.querySelectorAll('#dataTable tbody tr:not(#noResultsRow):not(#emptyRow)');
                const noResultsRow = document.getElementById('noResultsRow');

                if (searchInput) {
                    searchInput.addEventListener('keyup', function(e) {
                        const term = e.target.value.toLowerCase();
                        let hasResults = false;

                        tableRows.forEach(row => {
                            const nameText = row.querySelector('.search-target').textContent.toLowerCase();
                            if (nameText.includes(term)) {
                                row.classList.remove('d-none');
                                hasResults = true;
                            } else {
                                row.classList.add('d-none');
                            }
                        });

                        if (!hasResults && tableRows.length > 0) {
                            noResultsRow.classList.remove('d-none');
                        } else {
                            noResultsRow.classList.add('d-none');
                        }
                    });
                }

                // Slug Generation logic (shared)
                const createNameInput = document.getElementById('createName');
                const createSlugInput = document.getElementById('createSlug');
                if (createNameInput && createSlugInput) {
                    createNameInput.addEventListener('input', function() {
                        createSlugInput.value = this.value.toLowerCase()
                            .replace(/[^a-z0-9\s-]/g, '')
                            .trim()
                            .replace(/\s+/g, '_');
                    });
                }

                // Edit Modal Populate
                const editButtons = document.querySelectorAll('.edit-btn');
                const editNameInput = document.getElementById('editName');
                const editSlugInput = document.getElementById('editSlug');
                const editIdInput = document.getElementById('editId');

                editButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        editIdInput.value = this.getAttribute('data-id');
                        editNameInput.value = this.getAttribute('data-name');
                        editSlugInput.value = this.getAttribute('data-slug');
                    });
                });

                // Delete Modal Populate
                const deleteButtons = document.querySelectorAll('.delete-btn');
                const deleteNamePlaceholder = document.getElementById('deleteNamePlaceholder');
                const deleteIdInput = document.getElementById('deleteId');

                deleteButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        deleteIdInput.value = this.getAttribute('data-id');
                        deleteNamePlaceholder.textContent = this.getAttribute('data-name');
                    });
                });
            });
        </script>
    @endpush

    {{-- SUCCESS/ERROR MODALS (COMMON) --}}
    @foreach(['success', 'error'] as $status)
        @if (session($status))
            <div class="modal fade custom-modal-backdrop" id="statusModal{{$status}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content custom-modal-content type-{{$status}} text-center p-4">
                        <div class="modal-body px-4 py-4">
                            <div class="modal-icon-wrapper mb-4 mx-auto">
                                <i class="bi bi-{{ $status === 'success' ? 'check-lg' : 'x-lg' }} custom-icon"></i>
                            </div>
                            <h4 class="fw-bold mb-3 heading-text">{{ ucfirst($status) }}!</h4>
                            <p class="text-muted mb-4 fs-5">{{ session($status) }}</p>
                            <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm" data-bs-dismiss="modal">
                                {{ __('common.continue') ?? 'CONTINUE' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @push('scripts')
                <script type="module">
                    if (window.bootstrap) {
                        setTimeout(() => {
                            new bootstrap.Modal(document.getElementById('statusModal{{$status}}')).show();
                        }, 300);
                    }
                </script>
            @endpush
        @endif
    @endforeach
@endsection
