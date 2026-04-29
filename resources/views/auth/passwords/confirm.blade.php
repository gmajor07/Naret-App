@extends('layouts.app')

@section('content')
@include('auth.passwords._auth_styles')

<div class="auth-reset-shell">
    <div class="auth-reset-card">
        <div class="auth-reset-visual">
            <div class="auth-reset-visual__content">
                <span class="auth-reset-kicker">
                    <i class="fas fa-user-shield"></i>
                    Confirm identity
                </span>
                <h1>Confirm your password before continuing.</h1>
                <p>This extra step helps keep sensitive account actions protected.</p>
            </div>
        </div>

        <div class="auth-reset-panel">
            <div class="auth-reset-form">
                <img src="{{ asset('assets/dist/img/naret.jpg') }}" alt="Naret Logo" class="auth-reset-logo">

                <h2 class="auth-reset-title">{{ __('Confirm Password') }}</h2>
                <p class="auth-reset-subtitle">{{ __('Please confirm your password before continuing.') }}</p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="password" class="auth-reset-label">{{ __('Password') }}</label>
                        <div class="auth-password-wrap">
                            <input
                                id="password"
                                type="password"
                                class="form-control auth-reset-input @error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password"
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

                    <button type="submit" class="btn auth-reset-btn">
                        <i class="fas fa-check"></i>
                        {{ __('Confirm Password') }}
                    </button>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-reset-link">
                            <i class="fas fa-key"></i>
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
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
