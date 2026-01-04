@extends('layouts.app')

@section('title', 'Results & Analysis - ' . $paper->title)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/paper.css') }}">
    <style>
        .result-item-card { border: 1px solid #eee; border-radius: 12px; overflow: hidden; margin-bottom: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); background: #fff; }
        .result-header { background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .result-body { padding: 25px; }
        
        /* Table Editor Styles */
        .custom-table-wrapper { overflow-x: auto; margin-bottom: 15px; }
        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table td, .custom-table th { border: 1px solid #dee2e6; padding: 10px; min-width: 100px; position: relative; }
        .custom-table th { background: #f1f3f5; font-weight: 600; }
        .editable-cell:focus { outline: 2px solid #8e2de2; background: #fdfbf7; }

        /* Analysis Section */
        .analysis-box { background: #f8f9fa; border-left: 4px solid #8e2de2; padding: 20px; border-radius: 4px; margin-top: 20px; }
        .bullet-list li { margin-bottom: 8px; font-size: 0.95rem; color: #444; }
    </style>
@endsection

@section('content')
    @include('partials.navbarPaper', ['paper' => $paper])

    <div class="container py-5">
        
        {{-- CALCULATE PERMISSIONS --}}
        @php
            $isLocked = $paper->results_finalized;
            // User can only interact if they have permission AND it's not finalized
            $canInteract = $canEdit && !$isLocked; 
        @endphp

        {{-- Header Navigation --}}
        <div class="mb-4">
            <a href="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/workspace" class="text-decoration-none text-muted small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Back to Workspace
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="module-icon bg-warning bg-opacity-10 text-warning" style="width: 45px; height: 45px; font-size: 1.2rem; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">Results & Analysis</h3>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    @php
                        $items = $paper->results_data ?? [];
                        $chartCount = 0; $tableCount = 0;
                        foreach($items as $item) {
                            if($item['type'] === 'chart') $chartCount++;
                            if($item['type'] === 'table') $tableCount++;
                        }
                    @endphp
                    <p class="text-muted mb-0 ms-1">
                        {{ $chartCount }} Charts • {{ $tableCount }} Tables
                    </p>

                    {{-- STATUS BADGE --}}
                    @if($isLocked)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 ms-2">
                            <i class="bi bi-lock-fill me-1"></i> Finalized
                        </span>
                    @else
                        <span class="badge bg-light text-secondary border ms-2">Draft Mode</span>
                    @endif
                </div>
            </div>
            
            <div class="d-flex gap-2">
                {{-- FINALIZE BUTTON --}}
                @if($canEdit)
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/finalize-results" method="POST">
                        @csrf
                        @if($isLocked)
                            <button type="submit" class="btn btn-outline-success btn-sm me-2" title="Click to Reopen">
                                <i class="bi bi-check-circle-fill me-1"></i> Finalized
                            </button>
                        @else
                            <button type="submit" class="btn btn-dark btn-sm me-2">
                                <i class="bi bi-check2-circle me-1"></i> Finalize Results
                            </button>
                        @endif
                    </form>
                @endif

                {{-- ADD BUTTONS (Hidden if Locked) --}}
                @if($canInteract)
                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/add-table" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-table me-1"></i> Create Table
                        </button>
                    </form>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addChartModal">
                        <i class="bi bi-image me-1"></i> Insert Chart
                    </button>
                @endif
            </div>
        </div>

        {{-- CONTENT AREA --}}
        <div class="row">
            <div class="col-12">
                @if(empty($paper->results_data))
                    <div class="text-center py-5 border rounded-3 bg-light">
                        <i class="bi bi-bar-chart-steps empty-state-icon"></i>
                        <h5 class="fw-bold text-muted">No Results Added</h5>
                        <p class="text-muted small mb-0">Insert charts or create tables to document your findings.</p>
                    </div>
                @else
                    @foreach($paper->results_data as $item)
                        <div class="result-item-card" id="item-{{ $item['id'] }}">
                            <div class="result-header">
                                <span class="fw-bold text-uppercase small text-muted">
                                    <i class="bi {{ $item['type'] == 'chart' ? 'bi-image' : 'bi-table' }} me-2"></i>
                                    {{ $item['type'] }}
                                </span>
                                @if($canInteract)
                                    <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/delete" method="POST" onsubmit="return confirm('Delete this item?');">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                        <button type="submit" class="btn btn-link text-danger p-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            
                            <div class="result-body">
                                {{-- EDITABLE TITLE --}}
                                <div class="mb-3">
                                    @if($canInteract)
                                        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/update" method="POST">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                            <div class="input-group">
                                                <input type="text" name="title" class="form-control fw-bold fs-5 border-0 shadow-none px-0" value="{{ $item['title'] }}" style="background: transparent;" onblur="this.form.submit()"> 
                                                <button class="btn btn-link text-muted" type="submit"><i class="bi bi-pencil small"></i></button>
                                            </div>
                                        </form>
                                    @else
                                        <h5 class="fw-bold mb-3">{{ $item['title'] }}</h5>
                                    @endif
                                </div>

                                {{-- DISPLAY CONTENT --}}
                                @if($item['type'] === 'chart')
                                    <div class="text-center bg-light p-3 rounded mb-4">
                                        <img src="{{ asset('storage/' . $item['content']) }}" alt="Chart" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                                    </div>
                                @elseif($item['type'] === 'table')
                                    <div class="custom-table-wrapper">
                                        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/update" method="POST" id="form-table-{{ $item['id'] }}">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                            <input type="hidden" name="table_content" id="input-table-{{ $item['id'] }}">
                                            
                                            <table class="custom-table" id="table-{{ $item['id'] }}">
                                                @foreach($item['content'] as $rowIndex => $row)
                                                    <tr>
                                                        @foreach($row as $colIndex => $cell)
                                                            <td contenteditable="{{ $canInteract ? 'true' : 'false' }}" class="editable-cell">{{ $cell }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </form>
                                    </div>
                                    
                                    @if($canInteract)
                                        <div class="d-flex gap-2 mb-4">
                                            <button class="btn btn-sm btn-light border" onclick="tableAddRow('{{ $item['id'] }}')">+ Row</button>
                                            <button class="btn btn-sm btn-light border" onclick="tableAddCol('{{ $item['id'] }}')">+ Col</button>
                                            <button class="btn btn-sm btn-light border" onclick="saveTable('{{ $item['id'] }}')"><i class="bi bi-save"></i> Save Table</button>
                                        </div>
                                    @endif
                                @endif

                                {{-- ANALYSIS SECTION --}}
                                <div class="analysis-box">
                                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-lightbulb me-2"></i>Key Findings & Analysis</h6>
                                    
                                    <ul class="bullet-list ps-3 mb-3">
                                        @if(!empty($item['analysis']))
                                            @foreach($item['analysis'] as $index => $point)
                                                <li class="d-flex justify-content-between">
                                                    <span>{{ $point }}</span>
                                                    @if($canInteract)
                                                        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/update" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                                            <input type="hidden" name="remove_point_index" value="{{ $index }}">
                                                            <button type="submit" class="btn btn-link py-0 px-1 text-danger small"><i class="bi bi-x"></i></button>
                                                        </form>
                                                    @endif
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="text-muted fst-italic">No key points added yet.</li>
                                        @endif
                                    </ul>

                                    @if($canInteract)
                                        <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/update" method="POST">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="new_point" class="form-control" placeholder="Add a key finding (bullet point)..." required>
                                                <button class="btn btn-dark" type="submit">Add</button>
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

    {{-- MODAL: Add Chart (Only include if not locked) --}}
    @if($canInteract)
    <div class="modal fade" id="addChartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/{{ $user->profileId }}/paper/{{ $paper->paperId }}/results/add-chart" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Insert Chart</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Chart Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Respondent Demographics" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Image</label>
                            <input type="file" name="chart_image" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Upload Chart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>
    // --- TABLE EDITOR LOGIC ---

    function tableAddRow(id) {
        const table = document.getElementById('table-' + id);
        const colCount = table.rows[0].cells.length;
        const row = table.insertRow(-1);
        for (let i = 0; i < colCount; i++) {
            const cell = row.insertCell(i);
            cell.contentEditable = "true";
            cell.classList.add('editable-cell');
            cell.innerText = "Data";
        }
    }

    function tableAddCol(id) {
        const table = document.getElementById('table-' + id);
        for (let i = 0; i < table.rows.length; i++) {
            const cell = table.rows[i].insertCell(-1);
            cell.contentEditable = "true";
            cell.classList.add('editable-cell');
            cell.innerText = i === 0 ? "Header" : "Data";
        }
    }

    function saveTable(id) {
        const table = document.getElementById('table-' + id);
        let data = [];
        
        // Loop through rows
        for (let i = 0; i < table.rows.length; i++) {
            let rowData = [];
            // Loop through cells
            for (let j = 0; j < table.rows[i].cells.length; j++) {
                rowData.push(table.rows[i].cells[j].innerText);
            }
            data.push(rowData);
        }

        // Put JSON into hidden input and submit
        document.getElementById('input-table-' + id).value = JSON.stringify(data);
        document.getElementById('form-table-' + id).submit();
    }
</script>
@endpush