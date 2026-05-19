<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio World - Skill Bridge Kids</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
    
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
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg);
            background: radial-gradient(ellipse at 20% 50%, #2d1b6b 0%, #0f0e17 60%),
                        radial-gradient(ellipse at 80% 20%, #1a0a4a 0%, transparent 50%);
            color: var(--text);
            font-family: 'Fredoka', sans-serif;
            font-size: 20px; /* Minimum konten 20px */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        /* Bintang-bintang menggunakan box-shadow */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 2px;
            height: 2px;
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
            font-family: 'Nunito', sans-serif;
            margin-top: 0;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            box-sizing: border-box;
        }

        /* Global Button */
        button, .btn {
            font-family: 'Fredoka', sans-serif;
            border-radius: 50px;
            padding: 18px 36px;
            font-size: 22px;
            font-weight: 700;
            min-height: 80px;
            background: var(--btn-primary);
            color: #ffffff;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 24px rgba(155, 114, 247, 0.5);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        button:hover, .btn:hover {
            background: var(--btn-hover);
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(155, 114, 247, 0.65);
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
    <main>
        @yield('content')
    </main>
</body>
</html>
