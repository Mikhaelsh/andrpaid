@extends('layouts.app')

@section('title', __('settings.title'))

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/setting.css') }}">
@endsection

@section('content')
    <div class="container py-5">
        <div class="row g-5">

            <div class="col-md-3 d-none d-md-block">
                <nav class="settings-sidebar position-sticky" style="top: 2rem;">
                    <h5 class="fw-bold mb-4 px-3">{{ __('settings.title') }}</h5>
                    <div class="list-group list-group-flush border-0">
                        <a href="#profile" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                            <i class="bi bi-person-circle me-2"></i> {{ __('settings.menu_profile') }}
                        </a>
                        @lecturer
                            <a href="#academic" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                                <i class="bi bi-mortarboard me-2"></i> {{ __('settings.menu_academic') }}
                            </a>
                        @endlecturer
                        <a href="#account" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                            <i class="bi bi-shield-lock me-2"></i> {{ __('settings.menu_account') }}
                        </a>
                    </div>
                </nav>
            </div>

            <div class="col-md-9">

                <section id="profile" class="mb-5 settings-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold">{{ __('settings.menu_profile') }}</h3>
                    </div>

                    <div class="card settings-card border-0 shadow-sm p-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom">
                                <div class="profile-avatar-wrapper">
                                    <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=random&color=fff&size=128"
                                        alt="Profile" class="rounded-circle profile-img">
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">{{ __('settings.profile_picture') }}</h5>
                                    <p class="text-muted small mb-0">
                                        {{ __('settings.profile_picture_desc') }}
                                    </p>
                                </div>
                            </div>

                            <form method="POST" action="/settings/update-public-profile">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ __('settings.display_name') }}</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $user->name }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ __('settings.profile_id') }}</label>
                                        <input type="text" class="form-control bg-light text-muted"
                                            value="{{ $user->profileId }}" readonly disabled
                                            style="cursor: not-allowed; font-family: monospace;">
                                        <div class="form-text">{{ __('settings.profile_id_desc') }}</div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">{{ __('settings.about_me') }}</label>
                                        <textarea class="form-control" name="description" rows="4"
                                            placeholder="{{ __('settings.about_me_placeholder') }}">{{ $user->description }}</textarea>
                                    </div>

                                    @notadmin
                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">{{ __('settings.province') }}</label>
                                            <select class="form-select" name="province_id">
                                                @foreach ($allProvinces as $eachProvince)
                                                    <option
                                                        {{ isset($province) && $eachProvince->name === $province->name ? 'selected' : '' }}
                                                        value="{{ $eachProvince->provinceId }}">
                                                        {{ $eachProvince->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endnotadmin

                                    @lecturer
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">{{ __('settings.linkedin_url') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white text-muted"><i
                                                        class="bi bi-linkedin"></i></span>
                                                <input type="url" class="form-control" name="linkedin_url"
                                                    placeholder="https://linkedin.com/in/..."
                                                    value="{{ $user->lecturer->linkedinUrl }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">{{ __('settings.portfolio_url') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white text-muted"><i
                                                        class="bi bi-globe"></i></span>
                                                <input type="url" class="form-control" name="portfolio_url"
                                                    placeholder="https://mywebsite.com"
                                                    value="{{ $user->lecturer->portfolioUrl }}">
                                            </div>
                                        </div>
                                    @endlecturer

                                    @university
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">{{ __('settings.website_url') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white text-muted"><i
                                                        class="bi bi-globe"></i></span>
                                                <input type="url" class="form-control" name="website_url"
                                                    placeholder="https://university.ac.id/..."
                                                    value="{{ $user->university->websiteUrl }}">
                                            </div>
                                        </div>
                                    @enduniversity
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit"
                                        class="btn btn-primary px-4">{{ __('settings.btn_save_profile') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

                @lecturer
                    <section id="academic" class="mb-5 settings-section">
                        <h3 class="fw-bold mb-4">{{ __('settings.academic_info') }}</h3>

                        <div class="card settings-card border-0 shadow-sm p-4">
                            <div class="card-body">

                                @php
                                    $affiliation = optional($user->lecturer)->affiliation;
                                @endphp

                                @if ($affiliation && $affiliation->status === 'verified')
                                    <div class="d-flex align-items-center gap-4 mb-4">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width: 70px; height: 70px;">
                                            <i class="bi bi-bank2 text-primary fs-2"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1">{{ $affiliation->university->user->name }}</h5>
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success px-2 rounded-pill">
                                                    <i class="bi bi-patch-check-fill me-1"></i>
                                                    {{ __('settings.verified_affiliation') }}
                                                </span>
                                            </div>
                                            <p class="text-muted small mb-0">
                                                <strong>NIDN:</strong> {{ $affiliation->nidn }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="alert alert-light border d-flex gap-3 align-items-start" role="alert">
                                        <i class="bi bi-info-circle-fill text-secondary mt-1"></i>
                                        <div class="small text-muted">
                                            <strong>{{ __('settings.affiliation_locked') }}</strong>
                                            {{ __('settings.affiliation_locked_desc') }}
                                        </div>
                                    </div>
                                @elseif ($affiliation && $affiliation->status === 'pending')
                                    <div class="text-center py-4">
                                        <div class="mb-3 position-relative d-inline-block">
                                            <i class="bi bi-building text-muted opacity-25" style="font-size: 4rem;"></i>
                                            <div class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm"
                                                style="width: 32px; height: 32px;">
                                                <i class="bi bi-hourglass-split text-white small"></i>
                                            </div>
                                        </div>

                                        <h5 class="fw-bold">{{ __('settings.verification_progress') }}</h5>
                                        <p class="text-muted col-md-8 mx-auto mb-4">
                                            {!! __('settings.verification_progress_desc', [
                                                'university' => $affiliation->university->user->name ?? 'your university',
                                            ]) !!}
                                        </p>

                                        <form action="/settings/cancel-affiliation" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm px-4 fw-bold rounded-pill">
                                                <i class="bi bi-x-lg me-1"></i> {{ __('settings.btn_cancel_request') }}
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="mb-4 pb-3 border-bottom">
                                        <h5 class="fw-bold mb-1">{{ __('settings.connect_institution') }}</h5>
                                        <p class="text-muted small mb-0">{{ __('settings.connect_institution_desc') }}
                                        </p>
                                    </div>

                                    @if ($affiliation && $affiliation->status === 'rejected')
                                        <div
                                            class="alert alert-danger border-danger bg-danger bg-opacity-10 d-flex align-items-start gap-3 mb-4 rounded-3">
                                            <i class="bi bi-x-circle-fill text-danger fs-5 mt-1"></i>
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h6 class="fw-bold text-danger mb-1">{{ __('settings.request_rejected') }}
                                                    </h6>
                                                    <span class="badge bg-danger text-white rounded-pill"
                                                        style="font-size: 0.7rem;">{{ $affiliation->updated_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="small text-dark mb-1">
                                                    Your request to join
                                                    <strong>{{ $affiliation->university->user->name }}</strong> was declined.
                                                </p>
                                                @if ($affiliation->rejection_reason)
                                                    <div
                                                        class="bg-white bg-opacity-50 p-2 rounded border border-danger border-opacity-25 mt-2">
                                                        <p class="small text-danger mb-0 fst-italic">
                                                            <strong>{{ __('settings.rejected_reason') }}</strong>
                                                            "{{ $affiliation->rejection_reason }}"
                                                        </p>
                                                    </div>
                                                @endif
                                                <p class="small text-muted mt-2 mb-0">{{ __('settings.rejected_retry') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <form action="/settings/request-affiliation" method="POST">
                                        @csrf

                                        <div class="row g-4">
                                            <div class="col-md-7">
                                                <label class="form-label fw-bold small text-uppercase text-muted"
                                                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                                    {{ __('settings.label_university') }}
                                                </label>

                                                <select class="form-select border-2 py-2 bg-light" name="university_id"
                                                    required style="border-radius: 8px;">
                                                    <option value="" selected disabled>
                                                        {{ __('settings.select_university') }}</option>
                                                    @foreach ($allUniversities ?? [] as $university)
                                                        <option value="{{ $university->id }}"
                                                            {{ $affiliation && $affiliation->university_id == $university->id ? 'selected' : '' }}>
                                                            {{ $university->user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <div class="d-flex align-items-start gap-2 mt-2">
                                                    <i class="bi bi-info-circle-fill text-muted mt-1"
                                                        style="font-size: 0.8rem;"></i>
                                                    <p class="form-text small text-muted mb-0" style="line-height: 1.4;">
                                                        {{ __('settings.university_note') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <label class="form-label fw-bold small text-uppercase text-muted"
                                                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                                    {{ __('settings.label_nidn') }}
                                                </label>
                                                <input type="text" class="form-control border-2 py-2 bg-light"
                                                    name="nidn" placeholder="e.g. 00123456"
                                                    value="{{ $affiliation && $affiliation->status === 'rejected' ? $affiliation->nidn : '' }}"
                                                    required style="border-radius: 8px;">
                                            </div>

                                            <div class="col-12">
                                                <div class="alert alert-warning bg-warning bg-opacity-10 border-warning border-opacity-25 d-flex gap-3 align-items-start rounded-3"
                                                    role="alert">
                                                    <i class="bi bi-exclamation-triangle-fill text-warning mt-1 fs-5"></i>
                                                    <div>
                                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">
                                                            {{ __('settings.important_notice') }}</h6>
                                                        <p class="small text-muted mb-0">
                                                            {!! __('settings.important_notice_desc') !!}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-3">
                                                    {{ $affiliation && $affiliation->status === 'rejected' ? __('settings.btn_resubmit') : __('settings.btn_send_request') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="card settings-card border-0 shadow-sm p-4">
                            <div class="card-body">
                                <h5 class="fw-bold mb-1">{{ __('settings.research_interests') }}</h5>
                                <p class="text-muted small mb-4">{{ __('settings.research_interests_desc') }}</p>

                                <form id="interestsForm" action="/settings/update-interests" method="POST">
                                    @csrf

                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-uppercase text-muted">
                                            {{ __('settings.select_fields') }} <span class="text-danger">*</span>
                                        </label>

                                        <div class="multi-select-wrapper" id="settings-interests-wrapper">
                                            <div class="multi-select-box">
                                                <input type="text" class="search-input-tag"
                                                    placeholder="{{ __('settings.search_fields') }}" autocomplete="off">
                                            </div>
                                            <div class="multi-select-dropdown"></div>
                                            <div class="hidden-inputs"></div>
                                        </div>

                                        <div id="interests-error" class="text-danger small mt-2 d-none">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            {{ __('settings.error_select_one') }}
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit"
                                            class="btn btn-primary px-4">{{ __('settings.btn_save_interests') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                @endlecturer

                <section id="account" class="mb-5 settings-section">
                    <h3 class="fw-bold mb-4">{{ __('settings.menu_account') }}</h3>
                    <div class="card settings-card border-0 shadow-sm p-4">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ __('settings.email_address') }}</h6>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                </div>
                                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseEmail" aria-expanded="false" aria-controls="collapseEmail">
                                    {{ __('settings.btn_change_email') }}
                                </button>
                            </div>

                            <div class="collapse mt-3" id="collapseEmail">
                                <div class="card card-body bg-light border-0">
                                    <form action="/settings/update-email" method="POST">
                                        @csrf

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label
                                                    class="form-label fw-semibold small">{{ __('settings.new_email') }}</label>
                                                <input type="email" class="form-control" name="email"
                                                    placeholder="Enter new email" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label
                                                    class="form-label fw-semibold small">{{ __('settings.confirm_password') }}</label>
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="{{ __('settings.password_placeholder') }}" required>
                                            </div>
                                            <div class="col-12 text-end">
                                                <button type="button" class="btn btn-sm btn-secondary me-1"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseEmail">{{ __('settings.btn_cancel') }}</button>
                                                <button type="submit"
                                                    class="btn btn-sm btn-primary">{{ __('settings.btn_update_email') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ __('settings.password') }}</h6>
                                    <p class="text-muted mb-0">
                                        {{ __('settings.last_changed', ['time' => $user->latest_password_updated_at->diffForHumans()]) }}
                                    </p>
                                </div>
                                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapsePassword" aria-expanded="false"
                                    aria-controls="collapsePassword">
                                    {{ __('settings.btn_change_password') }}
                                </button>
                            </div>

                            <div class="collapse mt-3" id="collapsePassword">
                                <div class="card card-body bg-light border-0">
                                    <form action="/settings/update-password" method="POST">
                                        @csrf

                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label
                                                    class="form-label fw-semibold small">{{ __('settings.current_password') }}</label>
                                                <input type="password" class="form-control" name="current_password"
                                                    required>
                                            </div>
                                            <div class="col-md-4">
                                                <label
                                                    class="form-label fw-semibold small">{{ __('settings.new_password') }}</label>
                                                <input type="password" class="form-control" name="new_password" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label
                                                    class="form-label fw-semibold small">{{ __('settings.confirm_new_password') }}</label>
                                                <input type="password" class="form-control"
                                                    name="new_password_confirmation" required>
                                            </div>

                                            <div class="col-12 text-end mt-3">
                                                <button type="button" class="btn btn-sm btn-secondary me-1"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapsePassword">{{ __('settings.btn_cancel') }}</button>
                                                <button type="submit"
                                                    class="btn btn-sm btn-primary">{{ __('settings.btn_update_password') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <hr class="my-4">

                            @notadmin
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-1 text-danger">{{ __('settings.delete_account') }}</h6>
                                        <p class="text-muted mb-0 small">{{ __('settings.delete_account_desc') }}</p>
                                    </div>
                                    <button class="btn btn-outline-danger btn-sm" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseDelete" aria-expanded="false"
                                        aria-controls="collapseDelete">
                                        {{ __('settings.btn_delete') }}
                                    </button>
                                </div>

                                <div class="collapse mt-3" id="collapseDelete">
                                    <div class="card card-body border-danger bg-danger bg-opacity-10">
                                        <h6 class="fw-bold text-danger mb-2">{{ __('settings.are_you_sure') }}</h6>
                                        <p class="small text-danger mb-3">
                                            {{ __('settings.delete_warning') }}
                                        </p>

                                        <form action="/settings/delete-account" method="POST">
                                            @csrf

                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-danger">
                                                    {!! __('settings.type_to_confirm') !!}
                                                </label>
                                                <input type="text" class="form-control border-danger"
                                                    id="deleteConfirmationInput" placeholder="DELETE ACCOUNT"
                                                    autocomplete="off">
                                            </div>

                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-light text-danger border"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseDelete">
                                                    {{ __('settings.btn_cancel') }}
                                                </button>

                                                <button type="submit" class="btn btn-sm btn-danger" id="finalDeleteBtn"
                                                    disabled>
                                                    {{ __('settings.btn_delete_confirm') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endnotadmin

                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deleteInput = document.getElementById('deleteConfirmationInput');
            const deleteBtn = document.getElementById('finalDeleteBtn');
            const confirmationPhrase = "DELETE ACCOUNT";

            if (deleteInput && deleteBtn) {
                deleteInput.addEventListener('input', function() {
                    if (this.value === confirmationPhrase) {
                        deleteBtn.removeAttribute('disabled');
                    } else {
                        deleteBtn.setAttribute('disabled', 'true');
                    }
                });
            }
        });
    </script>

    @lecturer
        <script>
            window.researchFieldsData = @json(
                $allResearchFields->map(fn($f) => [
                        'id' => $f->id,
                        'name' => $f->name,
                    ]));

            window.currentInterests = @json($user->lecturer ? $user->lecturer->researchFields->pluck('id') : []);
        </script>

        @push('scripts')
            <script type="module">
                document.addEventListener('DOMContentLoaded', function() {

                    function initMultiSelect(wrapperId, data, inputName, initialIds = []) {
                        const wrapper = document.getElementById(wrapperId);
                        if (!wrapper) return;

                        const visualBox = wrapper.querySelector('.multi-select-box');
                        const searchInput = wrapper.querySelector('.search-input-tag');
                        const dropdown = wrapper.querySelector('.multi-select-dropdown');
                        const hiddenContainer = wrapper.querySelector('.hidden-inputs');

                        let selectedIds = initialIds.map(String);

                        renderDropdown(data);
                        updateUI();

                        visualBox.addEventListener('click', () => {
                            searchInput.focus();
                            dropdown.classList.add('show');
                        });

                        document.addEventListener('click', (e) => {
                            if (!wrapper.contains(e.target)) {
                                dropdown.classList.remove('show');
                            }
                        });

                        searchInput.addEventListener('input', (e) => {
                            const query = e.target.value.toLowerCase();
                            const filtered = data.filter(item => item.name.toLowerCase().includes(query));
                            renderDropdown(filtered);
                            dropdown.classList.add('show');
                        });

                        searchInput.addEventListener('keydown', (e) => {
                            if (e.key === 'Backspace' && searchInput.value === '' && selectedIds.length > 0) {
                                removeSelection(selectedIds[selectedIds.length - 1]);
                            }
                        });

                        function renderDropdown(items) {
                            dropdown.innerHTML = '';
                            if (items.length === 0) {
                                dropdown.innerHTML = '<div class="p-2 text-muted small text-center">No results</div>';
                                return;
                            }

                            items.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'dropdown-option';
                                div.textContent = item.name;

                                if (selectedIds.includes(String(item.id))) {
                                    div.classList.add('selected');
                                }

                                div.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    addSelection(item);
                                    searchInput.value = '';
                                    searchInput.focus();
                                    renderDropdown(data);
                                });

                                dropdown.appendChild(div);
                            });
                        }

                        function addSelection(item) {
                            const id = String(item.id);
                            if (selectedIds.includes(id)) return;
                            if (selectedIds.length >= 5) return;

                            selectedIds.push(id);
                            updateUI();
                        }

                        function removeSelection(id) {
                            selectedIds = selectedIds.filter(i => i !== id);
                            updateUI();
                        }

                        function updateUI() {
                            const existingTags = visualBox.querySelectorAll('.selected-tag');
                            existingTags.forEach(t => t.remove());

                            selectedIds.forEach(id => {
                                const item = data.find(d => String(d.id) === id);
                                if (item) {
                                    const tag = document.createElement('div');
                                    tag.className = 'selected-tag';
                                    tag.innerHTML = `${item.name} <span class="remove-tag">&times;</span>`;

                                    tag.querySelector('.remove-tag').addEventListener('click', (e) => {
                                        e.stopPropagation();
                                        removeSelection(id);
                                    });
                                    visualBox.insertBefore(tag, searchInput);
                                }
                            });

                            hiddenContainer.innerHTML = '';
                            selectedIds.forEach(id => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = inputName;
                                input.value = id;
                                hiddenContainer.appendChild(input);
                            });
                            renderDropdown(data);
                        }
                    }

                    if (window.researchFieldsData) {
                        initMultiSelect(
                            'settings-interests-wrapper',
                            window.researchFieldsData,
                            'research_fields[]',
                            window.currentInterests
                        );
                    }


                    const form = document.getElementById('interestsForm');
                    const errorMsg = document.getElementById('interests-error');
                    const wrapper = document.getElementById('settings-interests-wrapper');
                    const visualBox = wrapper.querySelector('.multi-select-box');

                    if (form) {
                        form.addEventListener('submit', function(e) {
                            const selectedCount = form.querySelectorAll('input[name="research_fields[]"]').length;

                            if (selectedCount === 0) {
                                e.preventDefault();

                                errorMsg.classList.remove('d-none');
                                visualBox.style.borderColor = '#dc3545';

                                visualBox.classList.add('shake-animation');
                                setTimeout(() => visualBox.classList.remove('shake-animation'), 500);
                            } else {
                                errorMsg.classList.add('d-none');
                                visualBox.style.borderColor = '';
                            }
                        });

                        wrapper.addEventListener('click', function() {
                            errorMsg.classList.add('d-none');
                            visualBox.style.borderColor = '';
                        });
                    }
                });
            </script>
        @endpush
    @endlecturer

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


    @if (session('error'))
        <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content custom-modal-content type-error text-center p-4">

                    <div class="modal-body px-4 py-4">

                        <div class="modal-icon-wrapper mb-4 mx-auto">
                            <i class="bi bi-x-lg custom-icon"></i>
                        </div>

                        <h4 class="fw-bold mb-3 heading-text">{{ __('common.error') }}</h4>
                        <p class="text-muted mb-4 fs-5">{{ session('error') }}</p>

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
