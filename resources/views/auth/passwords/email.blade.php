@extends('layouts.app')

@section('content')
@include('auth.passwords._auth_styles')

<div class="auth-reset-shell">
    <div class="auth-reset-card">
        <div class="auth-reset-visual">
            <div class="auth-reset-visual__content">
                <span class="auth-reset-kicker">
                    <i class="fas fa-key"></i>
                    Account recovery
                </span>
                <h1>Recover access to your Naret Company account.</h1>
                <p>Enter your email address and we will send you a secure password reset link.</p>
            </div>
        </div>

        <div class="auth-reset-panel">
            <div class="auth-reset-form">
                <img src="{{ asset('assets/dist/img/naret.jpg') }}" alt="Naret Logo" class="auth-reset-logo">

                <h2 class="auth-reset-title">Reset password</h2>
                <p class="auth-reset-subtitle">Use the email address connected to your account.</p>

                @if (session('status'))
                    <div class="alert auth-reset-alert" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="email" class="auth-reset-label">{{ __('Email Address') }}</label>
                        <input
                            id="email"
                            type="email"
                            class="form-control auth-reset-input @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
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

                    <button type="submit" class="btn auth-reset-btn">
                        <i class="fas fa-paper-plane"></i>
                        {{ __('Send Password Reset Link') }}
                    </button>

                    <a href="{{ route('login') }}" class="auth-reset-link">
                        <i class="fas fa-arrow-left"></i>
                        Back to login
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
