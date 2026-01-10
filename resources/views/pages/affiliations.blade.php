@extends('layouts.app')

@section('title', __('affiliations.title'))

@section('content')
    @include('partials.navbarProfile', ['user' => $user])

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">{{ __('affiliations.header_title') }}</h2>
                <p class="text-muted mb-0">{{ __('affiliations.header_desc') }}</p>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px;">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 me-3">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">{{ __('affiliations.stat_total') }}</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_lecturers'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px;">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3 me-3">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">{{ __('affiliations.stat_pending') }}</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['pending_requests'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($pendingRequests->count() > 0)
            <div class="card border-0 shadow-sm mb-5" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header bg-warning bg-opacity-10 border-0 p-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-circle-fill text-warning fs-5"></i>
                        <h5 class="fw-bold text-dark mb-0">
                            {{ __('affiliations.pending_section_title', ['count' => $pendingRequests->count()]) }}
                        </h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-secondary small text-uppercase">{{ __('affiliations.th_lecturer') }}</th>
                                    <th class="py-3 text-secondary small text-uppercase">{{ __('affiliations.th_nidn') }}</th>
                                    <th class="py-3 text-secondary small text-uppercase">{{ __('affiliations.th_requested') }}</th>
                                    <th class="pe-4 py-3 text-end text-secondary small text-uppercase">{{ __('affiliations.th_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingRequests as $req)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="https://ui-avatars.com/api/?name={{ $req->lecturer->user->name }}&background=random"
                                                    class="rounded-circle" width="40" height="40">
                                                <div>
                                                    <a href="/{{ $req->lecturer->user->profileId }}/overview"
                                                        class="fw-bold text-dark text-decoration-none mb-0 d-block">
                                                        {{ $req->lecturer->user->name }}
                                                    </a>
                                                    <span class="text-muted small">{{ $req->lecturer->user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-dark fw-medium">{{ $req->nidn }}</td>
                                        <td class="text-muted small">{{ $req->created_at->format('M d, Y') }}</td>
                                        <td class="pe-4 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $req->id }}">
                                                    {{ __('affiliations.btn_reject') }}
                                                </button>

                                                <form action="{{ route('affiliation.accept') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="affiliation_id"
                                                        value="{{ $req->id }}">
                                                    <button type="submit"
                                                        class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
                                                        <i class="bi bi-check-lg me-1"></i> {{ __('affiliations.btn_verify') }}
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="modal fade text-start" id="rejectModal{{ $req->id }}"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <form action="{{ route('affiliation.reject') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="affiliation_id"
                                                                value="{{ $req->id }}">
                                                            <div class="modal-header border-0 pb-0">
                                                                <h5 class="modal-title fw-bold text-danger">
                                                                    {{ __('affiliations.modal_reject_title') }}
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="text-muted mb-3">
                                                                    {{ __('affiliations.modal_reject_desc') }}
                                                                    <strong>{{ $req->lecturer->user->name }}</strong>.
                                                                </p>
                                                                <textarea name="reason" class="form-control bg-light border-0" rows="3" placeholder="{{ __('affiliations.placeholder_reason') }}"
                                                                    required></textarea>
                                                            </div>
                                                            <div class="modal-footer border-0 pt-0">
                                                                <button type="button" class="btn btn-light rounded-pill"
                                                                    data-bs-dismiss="modal">{{ __('affiliations.btn_cancel') }}</button>
                                                                <button type="submit"
                                                                    class="btn btn-danger rounded-pill px-4">
                                                                    {{ __('affiliations.btn_confirm_reject') }}
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom border-light p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">{{ __('affiliations.affiliated_section_title') }}</h5>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($activeLecturers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-secondary small text-uppercase">{{ __('affiliations.th_lecturer') }}</th>
                                    <th class="py-3 text-secondary small text-uppercase">{{ __('affiliations.th_nidn') }}</th>
                                    <th class="py-3 text-secondary small text-uppercase">{{ __('affiliations.th_joined') }}</th>
                                    <th class="pe-4 py-3 text-end text-secondary small text-uppercase">{{ __('affiliations.th_status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activeLecturers as $lec)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="https://ui-avatars.com/api/?name={{ $lec->lecturer->user->name }}&background=random"
                                                    class="rounded-circle" width="40" height="40">
                                                <div>
                                                    <a href="/{{ $lec->lecturer->user->profileId }}/overview"
                                                        class="fw-bold text-dark text-decoration-none mb-0 d-block">
                                                        {{ $lec->lecturer->user->name }}
                                                    </a>
                                                    <span
                                                        class="text-muted small">{{ $lec->lecturer->user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-dark fw-medium">{{ $lec->nidn }}</td>
                                        <td class="text-muted small">{{ $lec->updated_at->format('M d, Y') }}</td>
                                        <td class="pe-4 text-end">
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-check-circle-fill me-1"></i> {{ __('affiliations.status_verified') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-top">
                        {{ $activeLecturers->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-people text-secondary fs-1"></i>
                        </div>
                        <h6 class="text-muted">{{ __('affiliations.empty_state') }}</h6>
                    </div>
                @endif
            </div>
        </div>
    </div>

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
