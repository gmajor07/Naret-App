<style>
    :root {
        --auth-primary: #0f5bd8;
        --auth-primary-dark: #0a3ea9;
        --auth-accent: #3aa0ff;
        --auth-surface: rgba(255, 255, 255, 0.96);
        --auth-border: rgba(15, 91, 216, 0.14);
        --auth-text: #10233f;
        --auth-muted: #6b7a90;
        --auth-shadow: 0 24px 60px rgba(10, 62, 169, 0.20);
    }

    body {
        min-height: 100vh;
        background:
            radial-gradient(circle at top left, rgba(58, 160, 255, 0.28), transparent 34%),
            radial-gradient(circle at bottom right, rgba(15, 91, 216, 0.22), transparent 28%),
            linear-gradient(135deg, #eaf3ff 0%, #d6e9ff 42%, #f6faff 100%);
        font-family: 'Source Sans Pro', sans-serif;
        color: var(--auth-text);
    }

    .auth-reset-shell {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px 16px;
    }

    .auth-reset-card {
        width: 100%;
        max-width: 1040px;
        display: grid;
        grid-template-columns: minmax(0, 0.95fr) minmax(360px, 0.75fr);
        overflow: hidden;
        border: 1px solid var(--auth-border);
        border-radius: 28px;
        background: var(--auth-surface);
        box-shadow: var(--auth-shadow);
        backdrop-filter: blur(14px);
    }

    .auth-reset-visual {
        position: relative;
        min-height: 620px;
        display: flex;
        align-items: flex-end;
        padding: 44px;
        color: #ffffff;
        background:
            linear-gradient(160deg, rgba(5, 24, 64, 0.18), rgba(15, 91, 216, 0.42)),
            url("{{ asset('assets/dist/img/1.jpg') }}") center center / cover no-repeat;
    }

    .auth-reset-visual::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(8, 30, 76, 0.10) 0%, rgba(8, 30, 76, 0.76) 100%);
    }

    .auth-reset-visual__content {
        position: relative;
        z-index: 1;
        max-width: 520px;
    }

    .auth-reset-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border: 1px solid rgba(255, 255, 255, 0.24);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 18px;
    }

    .auth-reset-visual h1 {
        margin: 0 0 14px;
        font-size: 38px;
        line-height: 1.12;
        font-weight: 700;
    }

    .auth-reset-visual p {
        margin: 0;
        color: rgba(255, 255, 255, 0.88);
        font-size: 16px;
        line-height: 1.7;
    }

    .auth-reset-panel {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 42px 34px;
        background: rgba(255, 255, 255, 0.98);
    }

    .auth-reset-form {
        width: 100%;
        max-width: 390px;
    }

    .auth-reset-logo {
        width: 94px;
        height: 94px;
        object-fit: contain;
        margin-bottom: 20px;
        padding: 12px;
        border-radius: 24px;
        background: linear-gradient(145deg, #f4f9ff, #dcecff);
        box-shadow: inset 0 0 0 1px rgba(15, 91, 216, 0.08), 0 14px 34px rgba(15, 91, 216, 0.14);
    }

    .auth-reset-title {
        margin: 0 0 8px;
        color: var(--auth-text);
        font-size: 31px;
        line-height: 1.2;
        font-weight: 700;
    }

    .auth-reset-subtitle {
        margin: 0 0 28px;
        color: var(--auth-muted);
        font-size: 15px;
        line-height: 1.6;
    }

    .auth-reset-label {
        display: block;
        margin-bottom: 8px;
        color: #365073;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .auth-reset-input {
        height: 54px;
        border: 1px solid rgba(15, 91, 216, 0.14);
        border-radius: 16px;
        background: #f9fbff;
        color: var(--auth-text);
        padding: 0 16px;
        font-size: 15px;
        box-shadow: none !important;
        transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
    }

    .auth-reset-input:focus {
        border-color: rgba(15, 91, 216, 0.42);
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(58, 160, 255, 0.16) !important;
    }

    .auth-reset-input::placeholder {
        color: #91a0b6;
    }

    .auth-password-wrap {
        position: relative;
    }

    .auth-password-wrap .auth-reset-input {
        padding-right: 52px;
    }

    .auth-password-toggle {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: var(--auth-muted);
        cursor: pointer;
    }

    .auth-password-toggle:hover,
    .auth-password-toggle:focus {
        color: var(--auth-primary);
        background: rgba(15, 91, 216, 0.08);
        outline: none;
    }

    .auth-reset-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        min-height: 54px;
        border: 0;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--auth-accent), var(--auth-primary));
        color: #ffffff;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.02em;
        box-shadow: 0 16px 30px rgba(15, 91, 216, 0.24);
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .auth-reset-btn:hover,
    .auth-reset-btn:focus {
        transform: translateY(-1px);
        color: #ffffff;
        background: linear-gradient(135deg, var(--auth-accent), var(--auth-primary-dark));
        box-shadow: 0 20px 34px rgba(15, 91, 216, 0.28);
    }

    .auth-reset-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 18px;
        color: var(--auth-primary);
        font-weight: 700;
        text-decoration: none;
    }

    .auth-reset-link:hover {
        color: var(--auth-primary-dark);
        text-decoration: none;
    }

    .auth-reset-alert {
        border: 1px solid rgba(31, 157, 103, 0.16);
        border-radius: 16px;
        background: #d9f7ea;
        color: #16794f;
        font-weight: 700;
    }

    .invalid-feedback {
        display: block;
        margin-top: 6px;
        padding-left: 4px;
        color: #dc3545;
        font-size: 13px;
        font-weight: 600;
    }

    @media (max-width: 991.98px) {
        .auth-reset-card {
            grid-template-columns: 1fr;
        }

        .auth-reset-visual {
            min-height: 300px;
            padding: 30px 24px;
        }

        .auth-reset-panel {
            padding: 34px 24px;
        }
    }

    @media (max-width: 767.98px) {
        .auth-reset-shell {
            padding: 18px 12px;
        }

        .auth-reset-card {
            border-radius: 22px;
        }

        .auth-reset-visual h1 {
            font-size: 30px;
        }

        .auth-reset-logo {
            width: 84px;
            height: 84px;
        }

        .auth-reset-title {
            font-size: 26px;
        }
    }
</style>
