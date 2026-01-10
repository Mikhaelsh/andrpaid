@extends('layouts.app')

@section('title', __('results.title_prefix') . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
@endsection

@section('content')
    @include('partials.navbarPaper', ['paper' => $paper])

    <div class="container py-5">

        @php
            $isLocked = $paper->results_finalized;
            $canInteract = $canEdit && !$isLocked;
        @endphp

        <div class="mb-4">
            <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/workspace"
                class="text-decoration-none text-muted small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> {{ __('results.back_workspace') }}
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="module-icon bg-warning bg-opacity-10 text-warning"
                        style="width: 45px; height: 45px; font-size: 1.2rem; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">{{ __('results.header_title') }}</h3>
                </div>

                <div class="d-flex align-items-center gap-2">
                    @php
                        $items = $paper->results_data ?? [];
                        $chartCount = 0;
                        $tableCount = 0;
                        foreach ($items as $item) {
                            if ($item['type'] === 'chart') {
                                $chartCount++;
                            }
                            if ($item['type'] === 'table') {
                                $tableCount++;
                            }
                        }
                    @endphp
                    <p class="text-muted mb-0 ms-1">
                        {{ __('results.counts_text', ['charts' => $chartCount, 'tables' => $tableCount]) }}
                    </p>

                    @if ($isLocked)
                        <span
                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 ms-2">
                            <i class="bi bi-lock-fill me-1"></i> {{ __('results.status_finalized') }}
                        </span>
                    @else
                        <span class="badge bg-light text-secondary border ms-2">{{ __('results.status_draft') }}</span>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2">
                @if ($canEdit)
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/finalize-results" method="POST">
                        @csrf
                        @if ($isLocked)
                            <button type="submit" class="btn btn-outline-success btn-sm me-2"
                                title="{{ __('results.tooltip_reopen') }}">
                                <i class="bi bi-check-circle-fill me-1"></i> {{ __('results.status_finalized') }}
                            </button>
                        @else
                            <button type="submit" class="btn btn-dark btn-sm me-2">
                                <i class="bi bi-check2-circle me-1"></i> {{ __('results.btn_finalize') }}
                            </button>
                        @endif
                    </form>
                @endif

                @if ($canInteract)
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/add-table" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-table me-1"></i> {{ __('results.btn_create_table') }}
                        </button>
                    </form>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addChartModal">
                        <i class="bi bi-image me-1"></i> {{ __('results.btn_insert_chart') }}
                    </button>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @if (empty($paper->results_data))
                    <div class="text-center py-5 border rounded-3 bg-light">
                        <i class="bi bi-bar-chart-steps empty-state-icon"></i>
                        <h5 class="fw-bold text-muted">{{ __('results.empty_title') }}</h5>
                        <p class="text-muted small mb-0">{{ __('results.empty_desc') }}</p>
                    </div>
                @else
                    @foreach ($paper->results_data as $item)
                        <div class="result-item-card" id="item-{{ $item['id'] }}">
                            <div class="result-header">
                                <span class="fw-bold text-uppercase small text-muted">
                                    <i class="bi {{ $item['type'] == 'chart' ? 'bi-image' : 'bi-table' }} me-2"></i>
                                    {{ $item['type'] }}
                                </span>
                                @if ($canInteract)
                                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/delete"
                                        method="POST" onsubmit="return confirm('{{ __('results.confirm_delete') }}');">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                        <button type="submit" class="btn btn-link text-danger p-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="result-body">
                                <div class="mb-3">
                                    @if ($canInteract)
                                        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/update"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                            <div class="input-group">
                                                <input type="text" name="title"
                                                    class="form-control fw-bold fs-5 border-0 shadow-none px-0"
                                                    value="{{ $item['title'] }}" style="background: transparent;"
                                                    onblur="this.form.submit()">
                                                <button class="btn btn-link text-muted" type="submit"><i
                                                        class="bi bi-pencil small"></i></button>
                                            </div>
                                        </form>
                                    @else
                                        <h5 class="fw-bold mb-3">{{ $item['title'] }}</h5>
                                    @endif
                                </div>

                                @if ($item['type'] === 'chart')
                                    <div class="text-center bg-light p-3 rounded mb-4">
                                        <img src="{{ asset('storage/' . $item['content']) }}" alt="Chart"
                                            class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                                    </div>
                                @elseif($item['type'] === 'table')
                                    <div class="custom-table-wrapper">
                                        <form
                                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/update"
                                            method="POST" id="form-table-{{ $item['id'] }}">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                            <input type="hidden" name="table_content"
                                                id="input-table-{{ $item['id'] }}">

                                            <table class="custom-table" id="table-{{ $item['id'] }}">
                                                @foreach ($item['content'] as $rowIndex => $row)
                                                    <tr>
                                                        @foreach ($row as $colIndex => $cell)
                                                            <td contenteditable="{{ $canInteract ? 'true' : 'false' }}"
                                                                class="editable-cell">{{ $cell }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </form>
                                    </div>

                                    @if ($canInteract)
                                        <div class="d-flex gap-2 mb-4">
                                            <button class="btn btn-sm btn-light border"
                                                onclick="tableAddRow('{{ $item['id'] }}')">{{ __('results.btn_add_row') }}</button>
                                            <button class="btn btn-sm btn-light border"
                                                onclick="tableAddCol('{{ $item['id'] }}')">{{ __('results.btn_add_col') }}</button>
                                            <button class="btn btn-sm btn-light border"
                                                onclick="saveTable('{{ $item['id'] }}')"><i class="bi bi-save"></i>
                                                {{ __('results.btn_save_table') }}</button>
                                        </div>
                                    @endif
                                @endif

                                <div class="analysis-box">
                                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-lightbulb me-2"></i>
                                        {{ __('results.analysis_title') }}
                                    </h6>

                                    <ul class="bullet-list ps-3 mb-3">
                                        @if (!empty($item['analysis']))
                                            @foreach ($item['analysis'] as $index => $point)
                                                <li class="d-flex justify-content-between">
                                                    <span>{{ $point }}</span>
                                                    @if ($canInteract)
                                                        <form
                                                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/update"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="item_id"
                                                                value="{{ $item['id'] }}">
                                                            <input type="hidden" name="remove_point_index"
                                                                value="{{ $index }}">
                                                            <button type="submit"
                                                                class="btn btn-link py-0 px-1 text-danger small"><i
                                                                    class="bi bi-x"></i></button>
                                                        </form>
                                                    @endif
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="text-muted fst-italic">{{ __('results.no_key_points') }}</li>
                                        @endif
                                    </ul>

                                    @if ($canInteract)
                                        <form
                                            action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/update"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="new_point" class="form-control"
                                                    placeholder="{{ __('results.placeholder_point') }}" required>
                                                <button class="btn btn-dark"
                                                    type="submit">{{ __('results.btn_add_point') }}</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @if ($canInteract)
        <div class="modal fade" id="addChartModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/add-chart"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('results.modal_chart_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('results.label_chart_title') }}</label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="{{ __('results.placeholder_chart_title') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('results.label_upload_image') }}</label>
                                <input type="file" name="chart_image" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{ __('results.btn_upload_chart') }}</button>
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
        // Pass localization strings to JS
        window.lang = {
            default_header: "{{ __('results.default_table_header') }}",
            default_data: "{{ __('results.default_table_data') }}"
        };

        function tableAddRow(id) {
            const table = document.getElementById('table-' + id);
            const colCount = table.rows[0].cells.length;
            const row = table.insertRow(-1);
            for (let i = 0; i < colCount; i++) {
                const cell = row.insertCell(i);
                cell.contentEditable = "true";
                cell.classList.add('editable-cell');
                cell.innerText = window.lang.default_data;
            }
        }

        function tableAddCol(id) {
            const table = document.getElementById('table-' + id);
            for (let i = 0; i < table.rows.length; i++) {
                const cell = table.rows[i].insertCell(-1);
                cell.contentEditable = "true";
                cell.classList.add('editable-cell');
                cell.innerText = i === 0 ? window.lang.default_header : window.lang.default_data;
            }
        }

        function saveTable(id) {
            const table = document.getElementById('table-' + id);
            let data = [];

            for (let i = 0; i < table.rows.length; i++) {
                let rowData = [];
                for (let j = 0; j < table.rows[i].cells.length; j++) {
                    rowData.push(table.rows[i].cells[j].innerText);
                }
                data.push(rowData);
            }

            document.getElementById('input-table-' + id).value = JSON.stringify(data);
            document.getElementById('form-table-' + id).submit();
        }
    </script>
@endpush
