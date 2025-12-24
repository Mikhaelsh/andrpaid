@extends('layouts.app')

@section('title', 'Register')

@section('additionalCSS')

    <link rel="stylesheet" href="{{ asset('styles/auth.css') }}">
@endsection

@section('content')
    <div class="auth-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    @if ($type === 'selectRole')
                        <div class="card auth-card border-0">
                            <div class="card-body p-4 p-md-5">
                                <div class="auth-logo-container mb-5">
                                    <img src="{{ asset('images/logo.jpeg') }}" alt="AndRPaid Logo"
                                        class="auth-logo mx-auto d-block mb-4">
                                    <h1 class="h3 fw-bold text-center mb-3">AndRPaid |
                                        Register</h1>
                                </div>

                                <div class="text-center mb-5">
                                    <h2 class="h5 fw-semibold mb-4">Select Your Account
                                        Type</h2>
                                    <p class="text-muted mb-5">Choose the role that best
                                        describes you</p>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="h-100">
                                            <div class="role-card card border h-100 hover-shadow transition-all">
                                                <div class="card-body text-center">
                                                    <div class="role-icon mb-4">
                                                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 80px; height: 80px;">
                                                            <i class="bi bi-person-badge fs-1 text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <h3 class="h5 fw-bold mb-3">Lecturer</h3>

                                                    <p class="text-muted mb-4 small">
                                                        For lecturers seeking teaching and academic contracts.
                                                    </p>

                                                    <a href="/register/lecturer"
                                                        class="btn auth-btn btn-primary w-100 py-3 mt-auto">
                                                        <i class="bi bi-arrow-right-circle me-2"></i>
                                                        Join as
                                                        Lecturer
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="h-100">
                                            <div class="role-card card border h-100 hover-shadow transition-all">
                                                <div class="card-body text-center">
                                                    <div class="role-icon mb-4">
                                                        <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 80px; height: 80px;">
                                                            <i class="bi bi-mortarboard-fill fs-2 text-info"></i>
                                                        </div>
                                                    </div>
                                                    <h3 class="h5 fw-bold mb-3">University</h3>

                                                    <p class="text-muted mb-4 small">
                                                        For
                                                        Universities looking to hire and manage academic talent
                                                        efficiently.
                                                    </p>

                                                    <a href="/register/university"
                                                        class="btn auth-btn btn-primary w-100 py-3 mt-auto">
                                                        <i class="bi bi-arrow-right-circle me-2"></i>
                                                        Join as University
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-5 pt-3 border-top">
                                    <p class="mb-0">
                                        Already have an account?
                                        <a href="/login" class="auth-link">Sign in
                                            here</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif ($type === 'specificRole')
                        <div class="card auth-card border-0">
                            <div class="card-body p-4 p-md-5">
                                <div class="auth-logo-container mb-4">
                                    <img src="{{ asset('images/logo.jpg') }}" alt="AndRPaid Logo"
                                        class="auth-logo mx-auto d-block mb-3">
                                    <h1 class="h4 fw-bold text-center mb-1">{{ Str::ucfirst($role) }} Registration</h1>
                                    <p class="text-muted text-center">Create your account.</p>
                                </div>

                                <form action="/register/{{ $role }}/insert" method="POST" class="auth-form">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                                placeholder="e.g., {{ $role === 'lecturer' ? 'Waguri' : 'Univology' }}"
                                                required>

                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                                placeholder="e.g., {{ $role === 'lecturer' ? 'waguri@binus.ac.id' : 'univology@uni.edu' }}"
                                                required>

                                                @error('email')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                        </div>

                                    </div>

                                    <div class="mb-4">
                                        <label for="description" class="form-label">Short Bio (Optional)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                            <textarea name="description" id="description" rows="2" class="form-control @error('description') is-invalid @enderror"
                                                placeholder="Briefly describe your mission and scope (max 200 chars)" maxlength="200">{{ old('description') }}</textarea>

                                                @error('description')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                        </div>

                                    </div>

                                    <div class="mb-3">
                                        <label for="province" class="form-label">Province</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-grid-fill"></i></span>
                                            <select name="province" id="province" class="form-select" required>
                                                <option value="">Select Your Province...</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->name }}" {{ old('province') === $province->name ? 'selected' : '' }}>
                                                        {{ $province->getDisplayName() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Create a password" required>

                                                @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                        </div>

                                    </div>

                                    <div class="mb-4">
                                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                            <input type="password" name="confirmPassword" id="confirmPassword"
                                                class="form-control @error('confirmPassword') is-invalid @enderror" placeholder="Type your password again" required>

                                                @error('confirmPassword')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                        </div>

                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn auth-btn btn-primary w-100 py-3 mt-auto">
                                            <i class="bi bi-person-fill-lock me-2"></i> Register
                                            {{ $role === 'lecturer' ? 'Lecturer' : 'Institution' }}
                                        </button>
                                    </div>

                                    {{-- @if ($errors->any())
                                    <div class="alert alert-danger d-flex align-items-start mt-2" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>

                                        <div>
                                            <div class="fw-bold mb-1">Please fix the following errors:</div>

                                            <ul class="mb-0 ps-3">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    @endif --}}

                                    <div class="text-center mt-4 pt-3 border-top">
                                        <p class="mb-0">
                                            <i class="bi bi-arrow-left me-1"></i>
                                            <a href="/register" class="auth-link">Back to Role Selection</a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
