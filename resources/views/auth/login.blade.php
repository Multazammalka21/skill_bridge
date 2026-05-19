<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Masuk ke Dashboard Orang Tua - Skill Bridge. Pantau kemajuan belajar anak Anda.">
    <title>Masuk - Skill Bridge Parent Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ─── Design System ─────────────────────────────────────────── */
        :root {
            --bg-primary: #0f1117;
            --bg-card: rgba(26, 29, 40, 0.75);
            --border-subtle: rgba(255, 255, 255, 0.06);
            --border-accent: rgba(99, 102, 241, 0.4);
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --accent-indigo: #818cf8;
            --accent-violet: #a78bfa;
            --accent-emerald: #34d399;
            --gradient-primary: linear-gradient(135deg, #818cf8, #a78bfa);
            --radius: 20px;
            --shadow-card: 0 10px 40px rgba(0, 0, 0, 0.35), 0 0 0 1px var(--border-subtle);
            --shadow-focus: 0 0 20px rgba(129, 140, 248, 0.25);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, system-ui, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── Animated BG ───────────────────────────────────────────── */
        .bg-gradient {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }
        .bg-gradient::before,
        .bg-gradient::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(130px);
            opacity: 0.15;
            animation: float 25s infinite ease-in-out;
        }
        .bg-gradient::before {
            width: 600px;
            height: 600px;
            background: var(--accent-indigo);
            top: -150px;
            left: -100px;
        }
        .bg-gradient::after {
            width: 500px;
            height: 500px;
            background: var(--accent-violet);
            bottom: -150px;
            right: -100px;
            animation-delay: -12s;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -40px) scale(1.08); }
            66% { transform: translate(-30px, 30px) scale(0.92); }
        }

        /* ─── Login Container ───────────────────────────────────────── */
        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 1.5rem;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .login-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--radius);
            padding: 2.75rem 2.25rem;
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border-subtle);
            position: relative;
            overflow: hidden;
        }

        .login-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        /* ─── Header ────────────────────────────────────────────────── */
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: var(--gradient-primary);
            font-size: 1.75rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 24px rgba(129, 140, 248, 0.3);
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .brand-subtitle {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* ─── Form Elements ─────────────────────────────────────────── */
        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--text-muted);
            font-size: 1.1rem;
            pointer-events: none;
            transition: var(--transition);
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: rgba(15, 17, 23, 0.6);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.95rem;
            transition: var(--transition);
            outline: none;
        }

        .form-input:focus {
            border-color: var(--accent-indigo);
            box-shadow: var(--shadow-focus);
            background: rgba(15, 17, 23, 0.85);
        }

        .form-input:focus + .input-icon {
            color: var(--accent-indigo);
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--text-primary);
        }

        /* Remember & Forget */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1.25rem 0 1.75rem 0;
            font-size: 0.85rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: var(--text-secondary);
            user-select: none;
        }

        .remember-checkbox {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 1px solid var(--border-subtle);
            border-radius: 4px;
            background: rgba(15, 17, 23, 0.6);
            cursor: pointer;
            position: relative;
            transition: var(--transition);
            outline: none;
        }

        .remember-checkbox:checked {
            background: var(--accent-indigo);
            border-color: var(--accent-indigo);
        }

        .remember-checkbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -52%);
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: bold;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--gradient-primary);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(129, 140, 248, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(129, 140, 248, 0.35);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* ─── Alert / Errors ────────────────────────────────────────── */
        .error-alert {
            background: rgba(251, 113, 133, 0.1);
            border: 1px solid rgba(251, 113, 133, 0.25);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: #fda4af;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: shake 0.4s ease-in-out;
        }

        /* ─── Demo Account Box ──────────────────────────────────────── */
        .demo-box {
            margin-top: 2rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px dashed rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1rem 1.25rem;
        }

        .demo-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--accent-violet);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .demo-credential {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .demo-credential code {
            font-family: 'Courier New', Courier, monospace;
            background: rgba(0, 0, 0, 0.25);
            padding: 2px 6px;
            border-radius: 4px;
            color: var(--text-primary);
            font-size: 0.85rem;
        }

        /* ─── Animations ────────────────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <div class="bg-gradient"></div>

    <div class="login-container">
        <div class="login-card">
            <!-- Brand -->
            <div class="brand-header">
                <div class="brand-logo" aria-hidden="true">🌉</div>
                <h1 class="brand-title">Skill Bridge</h1>
                <p class="brand-subtitle">Portal Monitoring Orang Tua</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="error-alert" role="alert">
                    <span>⚠️</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Surel (Email)</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✉️</span>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="nama@email.com"
                            class="form-input"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi"
                            class="form-input"
                        >
                        <button
                            type="button"
                            id="togglePassword"
                            class="password-toggle"
                            aria-label="Tampilkan kata sandi"
                        >
                            👁️
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-options">
                    <label for="remember_me" class="remember-me">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="remember-checkbox"
                        >
                        <span>Ingat Saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    Masuk ke Dashboard
                </button>
            </form>

            <!-- Seeded Demo Accounts -->
            <div class="demo-box">
                <div class="demo-title">🔑 Akun Demo Percobaan</div>
                <div class="demo-credential">
                    Email: <code>parent@skillbridge.test</code>
                </div>
                <div class="demo-credential">
                    Sandi: <code>password</code>
                </div>
            </div>
        </div>

        <p class="footer-text">&copy; {{ date('Y') }} Skill Bridge. Hak Cipta Dilindungi.</p>
    </div>

    <script>
        // Password visibility toggle
        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePasswordBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle emoji
            this.textContent = type === 'password' ? '👁️' : '🙈';
            this.setAttribute('aria-label', type === 'password' ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
        });
    </script>
</body>
</html>
