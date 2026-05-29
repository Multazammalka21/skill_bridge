<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio World — Pinteria</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --bg: #0f0e17;
            --surface: #1a1830;
            --accent: #9b72f7;
            --accent-glow: rgba(155, 114, 247, 0.35);
            --text: #fffffe;
            --text-muted: #a7a9be;
            --btn-primary: #9b72f7;
            --btn-hover: #b48fff;
            
            --font-display: 'Fredoka', sans-serif;
            --font-body: 'Nunito', sans-serif;
            
            --audio-bg: #0f0e17;
            --audio-surface: #1a1830;
            --audio-border: rgba(155,114,247,0.22);
            --audio-text: #fffffe;
            --audio-text-muted: #a7a9be;
            --audio-accent: #9b72f7;
            --audio-glow: rgba(155, 114, 247, 0.35);
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--audio-bg);
            background: radial-gradient(ellipse at 20% 50%, #2d1b6b 0%, #0f0e17 60%),
                        radial-gradient(ellipse at 80% 20%, #1a0a4a 0%, transparent 50%);
            color: var(--audio-text);
            font-family: var(--font-display);
            font-size: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        /* Stars Background */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 2px; height: 2px;
            background: transparent;
            box-shadow: 
                10vw 20vh #fff, 30vw 10vh #fff, 50vw 40vh #fff, 70vw 20vh #fff, 90vw 15vh #fff,
                15vw 60vh #fff, 40vw 80vh #fff, 60vw 70vh #fff, 85vw 90vh #fff, 20vw 90vh #fff,
                25vw 30vh #fff, 65vw 10vh #fff, 80vw 45vh #fff, 10vw 75vh #fff, 95vw 65vh #fff;
            opacity: 0.5;
            z-index: -1;
            pointer-events: none;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-body);
            margin-top: 0;
            color: var(--text);
        }

        /* Topbar */
        .topbar {
            background-color: var(--audio-surface);
            border-bottom: 1px solid var(--audio-border);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-title {
            font-family: var(--font-display);
            font-size: 24px;
            color: var(--audio-text);
            margin: 0;
        }

        /* Owl Mascot Container */
        .owl-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 40px auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .owl-mascot {
            width: 90px;
            height: 90px;
            background: radial-gradient(circle, #2a2150 0%, #1a1830 100%);
            border: 3px solid var(--audio-accent);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 54px;
            z-index: 10;
            animation: owl-float 3s infinite ease-in-out;
        }

        .owl-ring {
            position: absolute;
            top: 50%; left: 50%;
            width: 90px; height: 90px;
            margin-top: -45px; margin-left: -45px;
            border-radius: 50%;
            border: 2px solid var(--audio-accent);
            animation: ring-pulse 2s infinite ease-out;
        }
        .owl-ring:nth-child(2) { animation-delay: 0.6s; }
        .owl-ring:nth-child(3) { animation-delay: 1.2s; }

        @keyframes owl-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes ring-pulse {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.8); opacity: 0; }
        }

        /* Content Area */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            box-sizing: border-box;
            position: relative;
            z-index: 1;
        }

        /* Story Box */
        .story-box {
            background: rgba(155,114,247,0.08);
            border: 1px solid var(--audio-border);
            border-radius: 18px;
            padding: 30px;
            text-align: center;
            width: 100%;
            margin-bottom: 30px;
            font-size: 24px;
            line-height: 1.6;
        }

        /* Mic Indicator */
        .mic-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
            padding: 15px 30px;
            background: var(--audio-surface);
            border-radius: 50px;
            border: 1px solid var(--audio-border);
        }
        
        .mic-icon {
            width: 40px;
            height: 40px;
            background: var(--audio-accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            box-shadow: 0 0 15px var(--audio-glow);
            position: relative;
        }

        .mic-dots {
            display: flex;
            gap: 6px;
        }
        .mic-dot {
            width: 8px;
            height: 8px;
            background: var(--audio-accent);
            border-radius: 50%;
            animation: dot-bounce 1.5s infinite ease-in-out;
        }
        .mic-dot:nth-child(2) { animation-delay: 0.2s; }
        .mic-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes dot-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        /* Buttons */
        .action-buttons {
            display: flex;
            gap: 20px;
            width: 100%;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        button, .btn {
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 700;
            border-radius: 50px;
            min-height: 80px;
            padding: 18px 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            border: none;
            color: #ffffff;
            box-shadow: 0 6px 24px rgba(155, 114, 247, 0.4);
        }

        button:hover, .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(155, 114, 247, 0.6);
        }

        .btn-repeat {
            background: rgba(155,114,247,0.14);
            border: 2px solid var(--audio-accent);
            color: #d8c4ff;
            box-shadow: none;
        }
        .btn-repeat:hover {
            background: rgba(155,114,247,0.25);
            box-shadow: none;
        }

        .btn-next {
            background: var(--audio-accent);
            color: #ffffff;
            box-shadow: 0 8px 24px var(--audio-glow);
        }
        .btn-next:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 32px rgba(155,114,247,0.6);
        }

        /* Mic pulse animation */
        @keyframes mic-pulse {
            0%   { transform: scale(1);   opacity: 1; }
            100% { transform: scale(1.8); opacity: 0; }
        }
        .mic-ring {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            animation: mic-pulse 1.2s ease-out infinite;
            background: rgba(155, 114, 247, 0.4);
            border-radius: 50%;
            z-index: -1;
        }

        /* Card / Surface */
        .surface-card {
            background: var(--surface);
            border-radius: 24px;
            padding: 28px;
            border: 2px solid rgba(155, 114, 247, 0.2);
            text-align: center;
        }

        /* Animations */
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        
        .animate-slide-up {
            animation: slide-up 0.4s ease forwards;
            opacity: 0;
        }
    </style>
</head>
<body>

    <header class="topbar">
        <h2 class="topbar-title">Audio World</h2>
        <a href="/dashboard" style="color: var(--audio-text); text-decoration: none; font-weight: bold; padding: 8px 16px; border: 2px solid var(--audio-accent); border-radius: 20px; font-size: 16px; transition: background 0.3s;" onmouseover="this.style.background='rgba(155,114,247,0.2)'" onmouseout="this.style.background='transparent'">Kembali</a>
    </header>

    <div class="owl-container">
        <div class="owl-ring"></div>
        <div class="owl-ring"></div>
        <div class="owl-ring"></div>
        <div class="owl-mascot">🦉</div>
    </div>

    <main>
        @yield('content')
    </main>

</body>
</html>
