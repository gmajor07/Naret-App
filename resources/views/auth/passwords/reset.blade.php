@extends('layouts.app')

@section('content')
@include('auth.passwords._auth_styles')

<div class="auth-reset-shell">
    <div class="auth-reset-card">
        <div class="auth-reset-visual">
            <div class="auth-reset-visual__content">
                <span class="auth-reset-kicker">
                    <i class="fas fa-shield-alt"></i>
                    Secure reset
                </span>
                <h1>Create a new password and keep your account protected.</h1>
                <p>Choose a strong password before returning to the Naret Company dashboard.</p>
            </div>
        </div>

        <div class="auth-reset-panel">
            <div class="auth-reset-form">
                <img src="{{ asset('assets/dist/img/naret.jpg') }}" alt="Naret Logo" class="auth-reset-logo">

                <h2 class="auth-reset-title">{{ __('Reset Password') }}</h2>
                <p class="auth-reset-subtitle">Enter your email and set a new secure password.</p>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group mb-3">
                        <label for="email" class="auth-reset-label">{{ __('Email Address') }}</label>
                        <input
                            id="email"
                            type="email"
                            class="form-control auth-reset-input @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ $email ?? old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
                            placeholder="Enter your email address"
                        >

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="auth-reset-label">{{ __('Password') }}</label>
                        <div class="auth-password-wrap">
                            <input
                                id="password"
                                type="password"
                                class="form-control auth-reset-input @error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Enter your new password"
                            >
                            <button type="button" class="auth-password-toggle password-toggle" data-target="password" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="password-confirm" class="auth-reset-label">{{ __('Confirm Password') }}</label>
                        <div class="auth-password-wrap">
                            <input
                                id="password-confirm"
                                type="password"
                                class="form-control auth-reset-input"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Confirm your new password"
                            >
                            <button type="button" class="auth-password-toggle password-toggle" data-target="password-confirm" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn auth-reset-btn">
                        <i class="fas fa-lock"></i>
                        {{ __('Reset Password') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.password-toggle').forEach(function(button) {
        button.addEventListener('click', function() {
            var input = document.getElementById(button.getAttribute('data-target'));
            var icon = button.querySelector('i');
            var shouldShow = input.type === 'password';

            input.type = shouldShow ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !shouldShow);
            icon.classList.toggle('fa-eye-slash', shouldShow);
            button.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
        });
    });
</script>
@endsection
