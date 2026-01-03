@extends('layouts.app')

@section('title', 'Login')

@section('hideNavbar', true)

@section('hideFooter', true)

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/auth.css') }}">
@endsection

@section('content')
    <div class="auth-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-8">
                    <div class="card auth-card border-0">
                        <div class="card-body">
                            <div class="auth-logo-container mb-1">
                                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="auth-logo mx-auto d-block">
                                <h1 class="h3 fw-bold text-center mb-1" style="color: var(--primary-blue);">AndRPaid | Login
                                </h1>
                            </div>

                            <form action="/login" method="POST" class="auth-form">
                                @csrf

                                <div class="mb-2">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-person-fill"></i>
                                        </span>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="Enter your email" required autocomplete="email">
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="password" class="form-label">Password</label>
                                        <a href="/login/forgot-password" class="auth-link small">Forgot password?</a>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Enter your password" required autocomplete="current-password">
                                    </div>
                                </div>

                                <div class="d-grid mb-2">
                                    <button type="submit" class="btn auth-btn btn-primary btn-lg">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                    </button>
                                </div>

                                @if (session('errorLogin'))
                                    <div class="alert alert-danger show">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <span id="errorMessage">{{ session('errorLogin') }}</span>
                                    </div>
                                @endif

                                <div class="text-center pt-3 border-top">
                                    <p class="mb-0">
                                        New to AndRPaid?
                                        <a href="/register" class="auth-link">Create an account</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
@endsection
