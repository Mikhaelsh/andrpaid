@extends('layouts.app')

@section('title', 'Reset Password')
@section('hideNavbar', true)
@section('hideFooter', true)

@section('additionalCSS')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('styles/auth.css') }}">
@endsection

@section('content')
    <div class="login-page-wrapper">
        <ul class="theme-picker">
            <li data-theme="barney" class="barney"></li>
            <li data-theme="firewatch" class="firewatch"></li>
            <li data-theme="citrus" class="citrus"></li>
            <li data-theme="marsh" class="marsh"></li>
            <li data-theme="frost" class="frost"></li>
            <li data-theme="slate" class="slate"></li>
            <li data-theme="candy" class="candy"></li>
        </ul>

        <form action="/login/reset-password" method="POST" class="form auth-3d-form" id="resetPasswordForm">
            @csrf

            <div class="header-section">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="auth-logo">
                <h1>Reset Password</h1>
                <p>Create a new strong password</p>
            </div>

            @if (session('error') || $errors->any())
                <div class="theme-alert">
                    <i class='bx bx-error-circle'></i>
                    <span>
                        {{ session('error') ?? $errors->first() }}
                    </span>
                </div>
            @endif

            <div class="input-wrapper">
                <input type="email" name="email" placeholder="Enter your email" required autocomplete="email"
                    value="{{ old('email') }}" />
                <i class="bx bxs-envelope"></i>
            </div>

            <div class="input-wrapper">
                <input type="password" name="password" id="password" placeholder="New Password" required />
                <i class="bx bx-lock-alt"></i>
            </div>

            <div class="input-wrapper">
                <input type="password" name="password_confirmation" id="password_confirmation"
                    placeholder="Confirm Password" required />
                <i class="bx bx-check-shield"></i>
            </div>

            <div class="button-wrapper">
                <button type="button" id="btnTriggerCaptcha">
                    Reset Password
                    <i class="bx bx-right-arrow-alt"></i>
                </button>
            </div>

            <div class="form-footer">
                <p>Remembered it? <a href="/login">Back to Login</a></p>
            </div>
        </form>

        <div class="modal fade custom-modal-backdrop" id="captchaModal" tabindex="-1" aria-hidden="true"
            data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content custom-modal-content p-4 text-center">
                    <div class="modal-header border-0 pb-0 justify-content-center">
                        <h5 class="modal-title fw-bold">Security Check</h5>
                    </div>
                    <div class="modal-body px-4 pt-2 pb-4">
                        <p class="text-muted mb-3">Please solve this math problem to continue.</p>

                        <div class="captcha-question bg-light rounded py-3 border">
                            <span id="mathQuestion" class="fs-4 fw-bold"></span>
                        </div>

                        <div class="mt-3">
                            <input type="number" id="captchaInput" class="form-control text-center form-control-lg"
                                placeholder="?">
                            <div id="captchaError" class="text-danger small mt-2" style="display:none;">
                                <i class="bi bi-x-circle me-1"></i> Incorrect. Try again.
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="button" id="btnVerifyCaptcha" class="btn btn-primary fw-bold py-2">
                                VERIFY & CHANGE
                            </button>
                            <button type="button" class="btn btn-link text-muted text-decoration-none"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const themeList = document.querySelector(".theme-picker");
                const defaultThemeItem = themeList.firstElementChild;
                const themeListItems = themeList.childNodes;
                const backgroundWrapper = document.querySelector('.login-page-wrapper');
                const form = document.querySelector(".form");

                const themeState = {
                    selected: null,
                    set: (t) => {
                        themeState.selected = t;
                    },
                    get: () => themeState.selected
                };

                const itemState = {
                    selected: null,
                    set: (i) => {
                        itemState.selected = i;
                    },
                    get: () => itemState.selected
                };

                function initTheme() {
                    themeListItems.forEach(el => el.addEventListener("click", handleThemeChange));
                    if (defaultThemeItem) {
                        const defaultTheme = defaultThemeItem.dataset.theme;
                        setTheme(defaultTheme);
                        setSelectedThemeItem(defaultThemeItem);
                    }
                }

                function handleThemeChange(event) {
                    let selectedItem = event.target;
                    if (!selectedItem.dataset.theme) return;

                    let selectedTheme = selectedItem.dataset.theme;

                    if (!selectedItem.classList.contains("pressed") && !form.classList.contains("rotate")) {
                        form.classList.add("rotate");
                        setSelectedThemeItem(selectedItem);
                        setTimeout(() => {
                            setTheme(selectedTheme);
                        }, 600);
                        setTimeout(() => {
                            form.classList.remove("rotate");
                        }, 1200);
                    }
                }

                function setTheme(selectedTheme) {
                    if (themeState.get()) {
                        backgroundWrapper.classList.remove(themeState.get());
                    }
                    themeState.set(selectedTheme);
                    backgroundWrapper.classList.add(themeState.get());
                }

                function setSelectedThemeItem(selectedItem) {
                    const current = itemState.get();
                    if (current) current.classList.remove("pressed");
                    itemState.set(selectedItem);
                    selectedItem.classList.add("pressed");
                }

                initTheme();

                const resetForm = document.getElementById('resetPasswordForm');
                const triggerBtn = document.getElementById('btnTriggerCaptcha');
                const verifyBtn = document.getElementById('btnVerifyCaptcha');
                const mathQuestionEl = document.getElementById('mathQuestion');
                const captchaInput = document.getElementById('captchaInput');
                const captchaError = document.getElementById('captchaError');

                let correctAnswer = 0;
                let captchaModal = null;

                if (window.bootstrap) {
                    captchaModal = new bootstrap.Modal(document.getElementById('captchaModal'));
                }

                function generateMathProblem() {
                    const num1 = Math.floor(Math.random() * 20) + 1;
                    const num2 = Math.floor(Math.random() * 20) + 1;
                    correctAnswer = num1 + num2;
                    mathQuestionEl.textContent = `${num1} + ${num2} = ?`;
                    captchaInput.value = '';
                    captchaError.style.display = 'none';
                }

                if (triggerBtn) {
                    triggerBtn.addEventListener('click', function() {
                        if (resetForm.checkValidity()) {
                            generateMathProblem();
                            captchaModal.show();
                        } else {
                            resetForm.reportValidity();
                        }
                    });
                }

                if (verifyBtn) {
                    verifyBtn.addEventListener('click', function() {
                        const userAnswer = parseInt(captchaInput.value);
                        if (userAnswer === correctAnswer) {
                            resetForm.submit();
                        } else {
                            captchaError.style.display = 'block';
                            captchaInput.classList.add('is-invalid');
                            setTimeout(() => {
                                generateMathProblem();
                            }, 1000);
                        }
                    });
                }

                if (captchaInput) {
                    captchaInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            verifyBtn.click();
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
