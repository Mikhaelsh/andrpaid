@extends('layouts.app')

@section('title', 'Register')
@section('hideNavbar', true)
@section('hideFooter', true)

@section('additionalCSS')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="{{ asset('styles/auth.css') }}">

    <style>
        .auth-3d-form.wide-panel {
            max-width: 800px;
        }

        .input-wrapper textarea,
        .input-wrapper select {
            background-color: var(--input-bg-color);
            border: 1px solid transparent;
            border-radius: 6px;
            color: #fff;
            font-size: 15px;
            padding: 12px 15px;
            width: 100%;
            transition: all 0.25s;
            font-family: inherit;
        }

        .input-wrapper textarea:focus,
        .input-wrapper select:focus {
            outline: none;
            background-color: rgba(0, 0, 0, 0.6);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .input-wrapper select option {
            background-color: #333;
            color: #fff;
        }

        .role-option-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            transition: transform 0.2s, background 0.2s;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .role-option-card:hover {
            background: rgba(0, 0, 0, 0.4);
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .role-icon i {
            font-size: 50px;
            color: #fff;
        }
    </style>
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

        @if ($type === 'selectRole')
            <div class="form auth-3d-form wide-panel">
                <div class="header-section">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="auth-logo">
                    <h1>Join AndRPaid</h1>
                    <p>Choose the role that best describes you.</p>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <div class="role-option-card">
                            <div class="role-icon mb-3">
                                <i class='bx bxs-id-card'></i>
                            </div>
                            <h3 class="h5 fw-bold text-white mb-2">Lecturer</h3>
                            <p class="small text-white-50 mb-4">
                                For lecturers seeking teaching and academic contracts.
                            </p>
                            <div class="button-wrapper mt-auto w-100">
                                <a href="/register/lecturer" style="text-decoration: none;">
                                    <button type="button">
                                        Join as Lecturer
                                        <i class='bx bx-right-arrow-alt'></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="role-option-card">
                            <div class="role-icon mb-3">
                                <i class='bx bxs-graduation'></i>
                            </div>
                            <h3 class="h5 fw-bold text-white mb-2">University</h3>
                            <p class="small text-white-50 mb-4">
                                For Universities looking to hire and manage academic talent.
                            </p>
                            <div class="button-wrapper mt-auto w-100">
                                <a href="/register/university" style="text-decoration: none;">
                                    <button type="button">
                                        Join as University
                                        <i class='bx bx-right-arrow-alt'></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <p>Already have an account? <a href="/login">Sign in here</a></p>
                </div>
            </div>
        @elseif ($type === 'specificRole')
            <form action="/register/{{ $role }}/insert" method="POST" class="form auth-3d-form">
                @csrf

                <div class="header-section">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="auth-logo">
                    <h1>{{ Str::ucfirst($role) }} Register</h1>
                    <p>Create your new account.</p>
                </div>

                @if ($errors->any())
                    <div class="theme-alert">
                        <i class='bx bx-error-circle'></i>
                        <div style="text-align: left;">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="input-wrapper">
                    <input type="text" name="name"
                        placeholder="Name (e.g., {{ $role === 'lecturer' ? 'Waguri' : 'Univology' }})"
                        value="{{ old('name') }}" required />
                    <i class='bx bxs-building'></i>
                </div>

                <div class="input-wrapper">
                    <input type="email" name="email"
                        placeholder="Email (e.g., {{ $role === 'lecturer' ? 'waguri@binus.ac.id' : 'univology@uni.edu' }})"
                        value="{{ old('email') }}" required />
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="input-wrapper">
                    <textarea name="description" rows="2" placeholder="Short Bio (Optional, max 200 chars)" maxlength="200">{{ old('description') }}</textarea>
                </div>

                <div class="input-wrapper">
                    <select name="province" required>
                        <option value="" disabled selected>Select Your Province...</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province->provinceId }}"
                                {{ old('province') === $province->provinceId ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-wrapper">
                    <input type="password" name="password" placeholder="Create a password" required />
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="input-wrapper">
                    <input type="password" name="confirmPassword" placeholder="Confirm password" required />
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="button-wrapper mt-3">
                    <button type="submit">
                        Register {{ Str::ucfirst($role) }}
                        <i class='bx bx-right-arrow-alt'></i>
                    </button>
                </div>

                <div class="form-footer">
                    <p>
                        <a href="/register">
                            <i class='bx bx-arrow-back'></i> Back to Role Selection
                        </a>
                    </p>
                    <p class="mt-2">Already have an account? <a href="/login">Sign In</a></p>
                </div>
            </form>
        @endif

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

                function init() {
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

                init();

                if (window.bootstrap && document.getElementById('statusModal')) {
                    setTimeout(() => {
                        var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
                        myModal.show();
                    }, 300);
                }
            });
        </script>
    @endpush
@endsection
