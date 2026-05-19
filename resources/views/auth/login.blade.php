<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Masuk ke Skill Bridge - Dunia Petualangan Belajar">
    <title>Masuk - Skill Bridge</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
    <!-- AlpineJS for state management (role selection) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            /* General text colors */
            --text-light: #ffffff;
            --text-dark: #1a1a2e;
            
            /* Login specific colors */
            --login-bg-1: #1a0b2e; /* Ungu gelap */
            --login-bg-2: #0f172a; /* Biru tua */
            --login-card-bg: rgba(255, 255, 255, 0.05);
            --login-card-border: rgba(255, 255, 255, 0.1);
            
            /* Buttons */
            --btn-child: #ff6b35;
            --btn-child-shadow: rgba(255, 107, 53, 0.5);
            --btn-child-hover: #ff8c5a;
            
            --btn-parent: #9b72f7;
            --btn-parent-shadow: rgba(155, 114, 247, 0.5);
            --btn-parent-hover: #b48fff;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Fredoka', sans-serif;
            background: radial-gradient(circle at top left, var(--login-bg-1), transparent 70%),
                        radial-gradient(circle at bottom right, var(--login-bg-2), transparent 70%);
            background-color: #0b071a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            overflow: hidden;
            position: relative;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Nunito', sans-serif;
        }

        /* Animated Mesh Background */
        .bg-mesh {
            position: absolute;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }
        .bg-mesh::before, .bg-mesh::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.5;
            animation: float 20s infinite alternate ease-in-out;
        }
        .bg-mesh::before {
            width: 50vw;
            height: 50vw;
            background: rgba(155, 114, 247, 0.2);
            top: -10%;
            left: -10%;
        }
        .bg-mesh::after {
            width: 60vw;
            height: 60vw;
            background: rgba(45, 111, 255, 0.2);
            bottom: -20%;
            right: -10%;
            animation-delay: -10s;
        }

        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(10%, 10%); }
        }

        /* Card Container */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 500px;
            padding: 20px;
            box-sizing: border-box;
        }

        .login-card {
            background: var(--login-card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--login-card-border);
            border-radius: 32px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            animation: slide-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slide-up {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Mascot Placeholder */
        .mascot-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .mascot {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 3px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .mascot.wulan { background: linear-gradient(135deg, #ff6b35, #ff8c5a); }
        .mascot.sinar { background: linear-gradient(135deg, #9b72f7, #b48fff); }

        .title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 10px;
            background: linear-gradient(to right, #fff, #a7a9be);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            font-size: 20px;
            color: #a7a9be;
            margin-bottom: 40px;
        }

        /* Buttons */
        .btn {
            display: block;
            width: 100%;
            border-radius: 50px;
            padding: 18px 36px;
            font-size: 22px;
            font-weight: 700;
            font-family: 'Fredoka', sans-serif;
            border: none;
            cursor: pointer;
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
            min-height: 80px;
            transition: all 0.2s ease;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-child {
            background: var(--btn-child);
            box-shadow: 0 6px 24px var(--btn-child-shadow);
        }
        .btn-child:hover {
            background: var(--btn-child-hover);
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(255, 107, 53, 0.65);
        }

        .btn-parent {
            background: var(--btn-parent);
            box-shadow: 0 6px 24px var(--btn-parent-shadow);
        }
        .btn-parent:hover {
            background: var(--btn-parent-hover);
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(155, 114, 247, 0.65);
        }

        .btn-back {
            background: transparent;
            border: 2px solid rgba(255,255,255,0.2);
            font-size: 20px;
            min-height: 60px;
            box-shadow: none;
            margin-top: 20px;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-label {
            display: block;
            font-family: 'Nunito', sans-serif;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #fff;
        }
        .form-input {
            width: 100%;
            box-sizing: border-box;
            padding: 16px 24px;
            border-radius: 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.2);
            color: #fff;
            font-family: 'Fredoka', sans-serif;
            font-size: 20px;
            outline: none;
            transition: all 0.2s;
        }
        .form-input:focus {
            border-color: #9b72f7;
            background: rgba(0, 0, 0, 0.4);
            box-shadow: 0 0 15px rgba(155, 114, 247, 0.3);
        }
        
        .error-message {
            background: rgba(239, 35, 60, 0.2);
            border: 1px solid rgba(239, 35, 60, 0.4);
            color: #ffb3b8;
            padding: 12px;
            border-radius: 16px;
            font-size: 18px;
            margin-bottom: 20px;
            animation: slide-up 0.3s ease;
        }

    </style>
</head>
<body>

    <div class="bg-mesh"></div>

    <div class="login-wrapper" x-data="{ view: 'role' }">
        <div class="login-card">
            
            <div class="mascot-container">
                <div class="mascot wulan" title="Wulan - Visual World">👁️</div>
                <div class="mascot sinar" title="Sinar - Audio World">🎧</div>
            </div>

            <!-- Role Selection View -->
            <div x-show="view === 'role'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <h1 class="title">Selamat Datang!</h1>
                <p class="subtitle">Siapa yang mau masuk hari ini?</p>

                <button @click="window.location.href='/play'" class="btn btn-child">
                    Masuk sebagai Anak
                </button>
                
                <button @click="view = 'form'" class="btn btn-parent">
                    Orang Tua / Guru
                </button>
            </div>

            <!-- Form View (Parent/Teacher) -->
            <div x-show="view === 'form'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <h2 class="title">Masuk Portal</h2>
                <p class="subtitle">Untuk Orang Tua dan Guru</p>

                @if ($errors->any())
                    <div class="error-message">
                        Oh tidak! {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="ketik email di sini..." class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="ketik rahasia di sini..." class="form-input">
                    </div>

                    <!-- Input tersembunyi untuk remember -->
                    <input type="hidden" name="remember" value="on">

                    <button type="submit" class="btn btn-parent" style="margin-top: 30px;">
                        Ayo Masuk!
                    </button>
                </form>

                <button @click="view = 'role'" class="btn btn-back">
                    Kembali
                </button>
            </div>

        </div>
    </div>

</body>
</html>
