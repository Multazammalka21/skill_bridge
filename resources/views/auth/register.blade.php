<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Daftar ke Skill Bridge - Dunia Petualangan Belajar">
    <title>Daftar - Skill Bridge</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: var(--font-body);
            background: linear-gradient(180deg, #5ba8d8 0%, #87CEEB 30%, #b8e8f7 55%, #c8eea0 75%, #7ec850 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Sun */
        .sun {
            position: absolute;
            top: 40px;
            right: 60px;
            width: 80px;
            height: 80px;
            background: #FFD700;
            border-radius: 50%;
            z-index: 1;
            animation: sun-pulse 3s infinite;
        }

        /* Clouds */
        .cloud {
            position: absolute;
            background: #fff;
            border-radius: 50px;
            z-index: 2;
            animation: drift 20s infinite linear alternate;
        }
        .cloud::before, .cloud::after {
            content: '';
            position: absolute;
            background: #fff;
            border-radius: 50%;
        }
        .cloud-1 { top: 80px; left: 10%; width: 120px; height: 40px; animation-duration: 25s; }
        .cloud-1::before { width: 60px; height: 60px; top: -30px; left: 15px; }
        .cloud-1::after { width: 40px; height: 40px; top: -20px; left: 60px; }

        .cloud-2 { top: 120px; right: 20%; width: 150px; height: 50px; animation-duration: 35s; animation-direction: alternate-reverse; }
        .cloud-2::before { width: 70px; height: 70px; top: -35px; left: 20px; }
        .cloud-2::after { width: 50px; height: 50px; top: -25px; left: 80px; }

        .cloud-3 { top: 50px; left: 50%; width: 100px; height: 35px; animation-duration: 18s; }
        .cloud-3::before { width: 50px; height: 50px; top: -25px; left: 15px; }
        .cloud-3::after { width: 35px; height: 35px; top: -15px; left: 55px; }

        /* Mountain */
        .mountain {
            position: absolute;
            bottom: 44px;
            left: 0;
            width: 100%;
            height: 300px;
            z-index: 3;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1000 300" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><polygon fill="%232e7d32" points="0,300 200,100 450,300 700,50 1000,300"/></svg>') no-repeat bottom;
            background-size: cover;
        }

        /* Ground */
        .ground {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 52px;
            background: #5aad2a;
            z-index: 5;
        }

        /* Trees */
        .tree {
            position: absolute;
            bottom: 48px;
            z-index: 4;
        }
        .tree-trunk {
            width: 20px;
            height: 40px;
            background: #795548;
            margin: 0 auto;
        }
        .tree-leaves {
            width: 60px;
            height: 80px;
            background: #43a047;
            border-radius: 50px 50px 10px 10px;
            margin-bottom: -10px;
        }
        .tree-1 { left: 5%; transform: scale(1.2); }
        .tree-2 { left: 15%; transform: scale(0.8); }
        .tree-3 { right: 10%; transform: scale(1.1); }
        .tree-4 { right: 20%; transform: scale(0.9); }

        /* Flowers */
        .flower {
            position: absolute;
            bottom: 50px;
            font-size: 24px;
            z-index: 6;
            animation: sway 3s infinite ease-in-out;
            transform-origin: bottom center;
        }
        .f-1 { left: 10%; animation-delay: 0s; }
        .f-2 { left: 25%; animation-delay: 0.5s; }
        .f-3 { right: 15%; animation-delay: 1s; }
        .f-4 { right: 30%; animation-delay: 0.2s; }

        /* Mascots */
        .mascots {
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
            z-index: 6;
            animation: float 3s infinite ease-in-out;
        }
        .mascot-emoji {
            font-size: 60px;
            filter: drop-shadow(0 10px 10px rgba(0,0,0,0.2));
        }

        /* Login/Register Card */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 400px;
            padding: 20px;
            box-sizing: border-box;
            /* margin removed for better center alignment */
        }

        .login-card {
            background: #ffffff;
            border-top: 4px solid #43a047;
            border-radius: var(--card-radius, 22px);
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            max-height: 90vh;
            overflow-y: auto;
        }
        
        /* Customize scrollbar for card */
        .login-card::-webkit-scrollbar {
            width: 8px;
        }
        .login-card::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .login-card::-webkit-scrollbar-thumb {
            background: #c8eea0;
            border-radius: 10px;
        }

        .title {
            font-family: var(--font-display);
            font-size: 24px;
            color: #2e7d32;
            margin: 0 0 5px 0;
            font-weight: 600;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-label {
            display: block;
            font-weight: 700;
            margin-bottom: 6px;
            color: #333;
            font-size: 16px;
        }
        .form-input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px 16px;
            border-radius: 16px;
            border: 2px solid #e0e0e0;
            background: #f9f9f9;
            color: #333;
            font-family: var(--font-body);
            font-size: 16px;
            outline: none;
            transition: all 0.2s;
        }
        .form-input:focus {
            border-color: #43a047;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(67, 160, 71, 0.15);
        }

        /* Buttons */
        .btn {
            display: flex;
            width: 100%;
            border-radius: var(--btn-radius, 50px);
            padding: 12px 15px;
            font-size: 18px;
            font-weight: 700;
            font-family: var(--font-display);
            border: none;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            min-height: 60px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .btn-child {
            background: linear-gradient(135deg, #43a047, #5aad2a);
            color: white;
            box-shadow: 0 8px 20px rgba(67, 160, 71, 0.45);
        }
        .btn-child:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(67, 160, 71, 0.6);
        }

        .btn-parent {
            background: transparent;
            color: #43a047;
            border: 3px solid #81c784;
            box-shadow: none;
        }
        .btn-parent:hover {
            background: #f1f8e9;
            transform: translateY(-2px);
        }

        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 15px;
            border: 1px solid #ffcdd2;
        }

    </style>
</head>
<body>

    <!-- Nature Illustration Background -->
    <div class="sun"></div>
    
    <div class="cloud cloud-1"></div>
    <div class="cloud cloud-2"></div>
    <div class="cloud cloud-3"></div>
    
    <div class="mountain"></div>
    
    <div class="tree tree-1"><div class="tree-leaves"></div><div class="tree-trunk"></div></div>
    <div class="tree tree-2"><div class="tree-leaves"></div><div class="tree-trunk"></div></div>
    <div class="tree tree-3"><div class="tree-leaves"></div><div class="tree-trunk"></div></div>
    <div class="tree tree-4"><div class="tree-leaves"></div><div class="tree-trunk"></div></div>
    
    <div class="flower f-1">🌸</div>
    <div class="flower f-2">🌼</div>
    <div class="flower f-3">🌸</div>
    <div class="flower f-4">🌼</div>

    <div class="mascots">
        <div class="mascot-emoji" title="Wulan">🦉</div>
        <div class="mascot-emoji" title="Sinar">🦜</div>
    </div>
    
    <div class="ground"></div>

    <!-- Register Card -->
    <div class="login-wrapper">
        <div class="login-card">
            
            <h1 class="title">Halo, Teman Baru!</h1>
            <p class="subtitle">Buat akun untuk memulai petualanganmu</p>

            @if ($errors->any())
                <div class="error-message">
                    Ups! {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Ketik namamu..." class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Petualang</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Ketik emailmu..." class="form-input">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi Rahasia</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Buat kata sandi..." class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Ulangi Kata Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang kata sandi..." class="form-input">
                </div>

                <button type="submit" class="btn btn-child" style="margin-top: 20px;">
                    Daftar Sekarang!
                </button>
                
                <a href="{{ route('login') }}" class="btn btn-parent">
                    Sudah Punya Akun? Masuk
                </a>
            </form>

        </div>
    </div>

</body>
</html>
