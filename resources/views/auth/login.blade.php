@include('includes.head')
<style>
    :root {
        --login-primary: #0f5bd8;
        --login-primary-dark: #0a3ea9;
        --login-accent: #3aa0ff;
        --login-surface: rgba(255, 255, 255, 0.94);
        --login-border: rgba(15, 91, 216, 0.14);
        --login-text: #10233f;
        --login-muted: #6b7a90;
        --login-shadow: 0 24px 60px rgba(10, 62, 169, 0.20);
    }

    body.login-modern-page {
        min-height: 100vh;
        margin: 0;
        font-family: 'Source Sans Pro', sans-serif;
        background:
            radial-gradient(circle at top left, rgba(58, 160, 255, 0.28), transparent 34%),
            radial-gradient(circle at bottom right, rgba(15, 91, 216, 0.24), transparent 28%),
            linear-gradient(135deg, #eaf3ff 0%, #d6e9ff 42%, #f6faff 100%);
        color: var(--login-text);
    }

    .login-modern-shell {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px 16px;
    }

    .login-modern-card {
        width: 100%;
        max-width: 1140px;
        border: 1px solid var(--login-border);
        border-radius: 28px;
        overflow: hidden;
        background: var(--login-surface);
        box-shadow: var(--login-shadow);
        backdrop-filter: blur(14px);
    }

    .login-modern-grid {
        display: flex;
        flex-wrap: wrap;
        min-height: 680px;
    }

    .login-modern-branding {
        position: relative;
        flex: 1 1 56%;
        min-height: 680px;
        background:
            linear-gradient(160deg, rgba(5, 24, 64, 0.16), rgba(15, 91, 216, 0.40)),
            url("{{ asset('assets/dist/img/1.jpg') }}") center center / cover no-repeat;
        color: #ffffff;
        display: flex;
        align-items: flex-end;
    }

    .login-modern-branding::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(8, 30, 76, 0.10) 0%, rgba(8, 30, 76, 0.75) 100%);
    }

    .login-modern-branding-content {
        position: relative;
        z-index: 1;
        padding: 48px;
        max-width: 520px;
    }

    .login-modern-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.24);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 18px;
    }

    .login-modern-branding h1 {
        font-size: 40px;
        line-height: 1.1;
        font-weight: 700;
        margin: 0 0 16px;
    }

    .login-modern-branding p {
        font-size: 17px;
        line-height: 1.7;
        margin: 0;
        color: rgba(255, 255, 255, 0.88);
    }

    .login-modern-form-panel {
        flex: 1 1 44%;
        background: rgba(255, 255, 255, 0.96);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 42px 34px;
    }

    .login-modern-form-wrap {
        width: 100%;
        max-width: 390px;
    }

    .login-modern-logo {
        width: 104px;
        height: 104px;
        object-fit: contain;
        border-radius: 24px;
        background: linear-gradient(145deg, #f4f9ff, #dcecff);
        padding: 14px;
        box-shadow: inset 0 0 0 1px rgba(15, 91, 216, 0.08), 0 14px 34px rgba(15, 91, 216, 0.14);
        margin-bottom: 20px;
    }

    .login-modern-title {
        font-size: 31px;
        line-height: 1.2;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--login-text);
    }

    .login-modern-subtitle {
        color: var(--login-muted);
        font-size: 15px;
        margin-bottom: 28px;
    }

    .login-modern-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #365073;
        letter-spacing: 0.02em;
    }

    .login-modern-input {
        height: 54px;
        border-radius: 16px;
        border: 1px solid rgba(15, 91, 216, 0.14);
        background: #f9fbff;
        color: var(--login-text);
        padding: 0 16px;
        font-size: 15px;
        transition: all 0.2s ease;
        box-shadow: none !important;
    }

    .login-modern-input:focus {
        border-color: rgba(15, 91, 216, 0.42);
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(58, 160, 255, 0.16) !important;
    }

    .login-modern-input::placeholder {
        color: #91a0b6;
    }

    .login-modern-check {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--login-muted);
        font-size: 14px;
        margin-bottom: 22px;
    }

    .login-modern-check .form-check-input {
        margin-top: 0;
        width: 18px;
        height: 18px;
        accent-color: var(--login-primary);
    }

    .login-modern-btn {
        height: 54px;
        border: 0;
        border-radius: 16px;
        width: 100%;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.02em;
        background: linear-gradient(135deg, var(--login-accent), var(--login-primary));
        box-shadow: 0 16px 30px rgba(15, 91, 216, 0.24);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .login-modern-btn:hover,
    .login-modern-btn:focus {
        transform: translateY(-1px);
        box-shadow: 0 20px 34px rgba(15, 91, 216, 0.28);
        background: linear-gradient(135deg, var(--login-accent), var(--login-primary-dark));
    }

    .login-modern-contact {
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid rgba(15, 91, 216, 0.10);
    }

    .login-modern-contact p,
    .login-modern-contact li {
        font-size: 14px;
        color: var(--login-muted);
        margin-bottom: 6px;
    }

    .login-modern-contact ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .login-modern-contact strong,
    .login-modern-contact a {
        color: var(--login-primary);
        font-weight: 700;
        text-decoration: none;
    }

    .invalid-feedback {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #dc3545;
        margin-top: 6px;
        padding-left: 4px;
    }

    @media (max-width: 991.98px) {
        .login-modern-branding {
            min-height: 320px;
        }

        .login-modern-grid {
            min-height: auto;
        }

        .login-modern-branding-content,
        .login-modern-form-panel {
            padding: 30px 24px;
        }
    }

    @media (max-width: 767.98px) {
        .login-modern-card {
            border-radius: 22px;
        }

        .login-modern-branding h1 {
            font-size: 32px;
        }

        .login-modern-form-panel {
            padding: 28px 20px;
        }

        .login-modern-logo {
            width: 88px;
            height: 88px;
        }

        .login-modern-title {
            font-size: 26px;
        }
    }
</style>

<body class="login-modern-page">
    <div class="login-modern-shell">
        <div class="login-modern-card">
            <div class="login-modern-grid">
                <div class="login-modern-branding">
                    <div class="login-modern-branding-content">
                        <span class="login-modern-kicker">
                            <i class="fas fa-shield-alt"></i>
                            Naret Company
                        </span>
                        <h1>Modern, clean and secure access to your system.</h1>
                        <p>
                            We Provide Best In Miscellaneous Port Services
                            We store to offer our customers the lowest possible price the best available selection and all most conveniencevailable selection and all most convenience.
                        </p>
                    </div>
                </div>

                <div class="login-modern-form-panel">
                    <div class="login-modern-form-wrap">
                        <img src="{{ asset('assets/dist/img/naret.jpg') }}" alt="Naret Logo" class="login-modern-logo">

                        <div class="login-modern-title">Sign in</div>
                        <div class="login-modern-subtitle">
                            Ingia kwenye akaunti yako kuendelea kutumia mfumo wa Naret Company.
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="email" class="login-modern-label">Email Address</label>
                                <input
                                    id="email"
                                    type="email"
                                    class="form-control login-modern-input @error('email') is-invalid @enderror"
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

                            <div class="form-group mb-3">
                                <label for="password" class="login-modern-label">Password</label>
                                <input
                                    id="password"
                                    type="password"
                                    class="form-control login-modern-input @error('password') is-invalid @enderror"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                >

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-check login-modern-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary login-modern-btn">
                                <i class="fas fa-arrow-right mr-2"></i>{{ __('Login') }}
                            </button>

                            <div class="login-modern-contact">
                                <p>For any inquiry please contact: <a href="mailto:naret@naret.co.tz">naret@naret.co.tz</a></p>
                                <ul>
                                    <li><strong>Phone 24 hours:</strong> (+255) 753995084</li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
