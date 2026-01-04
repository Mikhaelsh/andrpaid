<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AndRPaid | @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('styles/main.css') }}">
    @yield('additionalCSS')
</head>

<body>
    <div class="page-wrapper-foot-nav">

        @unless (View::hasSection('hideNavbar'))
            @include('partials.navbar')
        @endunless

        <main class="flex-grow-1">
            @yield('content')
        </main>

        @unless (View::hasSection('hideFooter'))
            @include('partials.footer')
        @endunless

    </div>

    @stack('scripts')
</body>
</html>


{{-- POP UP MODAL -- Show Information --}}
{{--
@if (session('success'))
    <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content custom-modal-content type-success text-center p-4">

                <div class="modal-body px-4 py-4">

                    <div class="modal-icon-wrapper mb-4 mx-auto">
                        <i class="bi bi-check-lg custom-icon"></i>
                    </div>

                    <h4 class="fw-bold mb-3 heading-text">Success!</h4>
                    <p class="text-muted mb-4 fs-5">{{ session('success') }}</p>

                    <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm" data-bs-dismiss="modal">
                        CONTINUE
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
--}}

{{-- ============================================================================================================== --}}

{{-- POP UP MODAL -- FORM --}}
{{-- <div class="modal fade" id="feedbackModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content sophisticated-card border-0">

            <div class="card-gradient-strip"></div>

            <div class="p-4 pt-3">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Feedback & Ideas</h5>
                        <p class="text-muted small mb-0">Help us improve the AndRPaid experience.</p>
                    </div>
                    <button type="button" class="btn-close-sophisticated" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form action="/feedback/submit" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase text-secondary ls-1">Your Name</label>
                            <input type="text" class="form-control input-velvet" name="name"
                                   value="{{ Auth::user()->name ?? '' }}" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase text-secondary ls-1">Category</label>
                            <select class="form-select input-velvet" name="type" required>
                                <option value="" selected disabled>Select...</option>
                                <option value="bug">🐛 Bug Report</option>
                                <option value="feature">✨ New Feature</option>
                                <option value="ui">🎨 Design / UI</option>
                                <option value="other">💬 Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-secondary ls-1">Description</label>
                        <textarea class="form-control input-velvet" name="description" rows="4" placeholder="What's on your mind? Be specific!" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase text-secondary ls-1">Screenshot (Optional)</label>
                        <input class="form-control input-velvet file-input-fix" type="file" name="attachment" accept="image/*,.pdf">
                        <div class="form-text small text-muted">Supported formats: JPG, PNG, PDF (Max 2MB)</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-sophisticated-primary">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
