<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Dunia Petualangan - Skill Bridge</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #1a0b2e;
            --text-light: #ffffff;
            
            /* Audio World Colors */
            --audio-bg: #1a1830;
            --audio-accent: #9b72f7;
            
            /* Visual World Colors */
            --visual-bg: #fff9f0;
            --visual-accent: #ff6b35;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Fredoka', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-light);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Stars Background */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 2px; height: 2px;
            background: transparent;
            box-shadow: 
                10vw 20vh #fff, 30vw 10vh #fff, 50vw 40vh #fff, 70vw 20vh #fff, 90vw 15vh #fff,
                15vw 60vh #fff, 40vw 80vh #fff, 60vw 70vh #fff, 85vw 90vh #fff, 20vw 90vh #fff;
            opacity: 0.5;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 10;
            text-align: center;
            width: 100%;
            max-width: 900px;
            padding: 20px;
            box-sizing: border-box;
            animation: slide-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        h1 {
            font-family: 'Nunito', sans-serif;
            font-size: 40px;
            font-weight: 800;
            margin-bottom: 10px;
            background: linear-gradient(to right, #fff, #ffd166);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p.subtitle {
            font-size: 24px;
            color: #a7a9be;
            margin-bottom: 50px;
        }

        .worlds-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .world-card {
            border-radius: 32px;
            padding: 40px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            border: 4px solid transparent;
        }

        .world-audio {
            background: var(--audio-bg);
            box-shadow: 0 10px 30px rgba(155, 114, 247, 0.3);
        }
        .world-audio:hover {
            transform: translateY(-10px);
            border-color: var(--audio-accent);
            box-shadow: 0 20px 50px rgba(155, 114, 247, 0.5);
        }

        .world-visual {
            background: var(--visual-bg);
            color: #1a1a2e;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
        }
        .world-visual:hover {
            transform: translateY(-10px);
            border-color: var(--visual-accent);
            box-shadow: 0 20px 50px rgba(255, 107, 53, 0.5);
        }

        .mascot {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .world-audio .mascot {
            background: linear-gradient(135deg, #9b72f7, #b48fff);
            border: 4px solid rgba(255,255,255,0.2);
        }
        .world-visual .mascot {
            background: linear-gradient(135deg, #ff6b35, #ff8c5a);
            border: 4px solid rgba(255,255,255,0.5);
        }

        .world-title {
            font-family: 'Nunito', sans-serif;
            font-size: 32px;
            font-weight: 800;
            margin: 0;
        }
        .world-audio .world-title { color: #fff; }
        .world-visual .world-title { color: #1a1a2e; }

        .world-desc {
            font-size: 20px;
            margin: 0;
            opacity: 0.8;
            font-weight: 600;
        }

        .btn-back {
            display: inline-block;
            margin-top: 40px;
            background: transparent;
            border: 2px solid rgba(255,255,255,0.3);
            color: #fff;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 20px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        @keyframes slide-up {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Mau Bermain di Mana Hari Ini?</h1>
        <p class="subtitle">Pilih dunia petualanganmu!</p>

        <div class="worlds-grid">
            
            <a href="{{ route('play.tunanetra') }}" class="world-card world-audio">
                <div class="mascot" title="Sinar">🎧</div>
                <div>
                    <h2 class="world-title">Dunia Suara</h2>
                    <p class="world-desc">Bermain dengan Sinar</p>
                </div>
            </a>

            <a href="{{ route('play.tunarungu') }}" class="world-card world-visual">
                <div class="mascot" title="Wulan">👁️</div>
                <div>
                    <h2 class="world-title">Dunia Gambar</h2>
                    <p class="world-desc">Bermain dengan Wulan</p>
                </div>
            </a>

        </div>

        <a href="{{ route('login') }}" class="btn-back">Kembali ke Beranda</a>
    </div>

</body>
</html>
