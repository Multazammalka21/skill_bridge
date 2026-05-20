<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual World - Skill Bridge Kids</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: var(--visual-bg, #fff9f0);
            color: #333;
            font-family: var(--font-body);
            font-size: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            margin-top: 0;
            color: var(--visual-primary);
        }

        /* Topbar */
        .topbar {
            background-color: transparent;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .topbar-title {
            font-family: var(--font-display);
            font-size: 28px;
            color: var(--visual-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Organic Blobs */
        .blob-1 {
            position: absolute;
            top: -20px;
            left: -40px;
            width: 180px; height: 180px;
            border-radius: 60% 40% 70% 30% / 50% 60% 40% 50%;
            background: var(--visual-blob-1, rgba(255,209,102,0.25));
            animation: blob-drift 6s ease-in-out infinite;
            z-index: -1;
        }
        .blob-2 {
            position: absolute;
            bottom: -30px;
            right: -30px;
            width: 140px; height: 140px;
            border-radius: 40% 60% 30% 70% / 60% 40% 60% 40%;
            background: var(--visual-blob-2, rgba(6,214,160,0.18));
            animation: blob-drift 8s ease-in-out infinite reverse;
            z-index: -1;
        }

        /* Progress Bar */
        .progress-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto 30px;
            text-align: center;
        }
        .progress-bar-bg {
            height: 20px;
            background: #ffe3d8;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--visual-primary), var(--visual-yellow));
            border-radius: 20px;
            width: 50%; /* Example width */
            transition: width 0.4s ease;
        }
        .progress-star {
            color: var(--visual-yellow);
            font-size: 24px;
            margin-top: 5px;
            display: inline-block;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        main {
            flex: 1;
            padding: 2rem;
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            position: relative;
            z-index: 1;
        }

        /* Quiz Cards Grid */
        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }

        .quiz-card {
            background: #ffffff;
            border-radius: var(--card-radius, 22px);
            min-height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-family: var(--font-display);
            font-weight: 600;
            cursor: pointer;
            border: 4px solid transparent;
            box-shadow: 0 8px 24px var(--visual-shadow, rgba(255,107,53,0.15));
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            padding: 20px;
            text-align: center;
        }

        .quiz-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 32px rgba(255,107,53,0.25);
            border-color: #ffe3d8;
        }

        .quiz-card.correct {
            border-color: var(--visual-success);
            background: #e6f9f4;
            animation: pop-correct 0.4s ease-out forwards;
            pointer-events: none;
        }

        .quiz-card.wrong {
            border-color: var(--visual-error);
            background: #fde8ea;
            animation: shake-wrong 0.5s ease-in-out forwards;
        }

        /* Global Button */
        .btn {
            font-family: var(--font-display);
            border-radius: var(--btn-radius, 50px);
            padding: 15px 30px;
            font-size: 22px;
            font-weight: 700;
            min-height: var(--btn-min-h, 80px);
            background: var(--visual-primary);
            color: #ffffff;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 24px var(--visual-shadow);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            text-decoration: none;
        }

        .btn:hover {
            background: #ff8c5a;
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(255,107,53,0.3);
        }
        
        .btn-outline {
            background: transparent;
            color: var(--visual-primary);
            border: 3px solid var(--visual-primary);
            box-shadow: none;
        }
        .btn-outline:hover {
            background: #fff0eb;
            box-shadow: none;
        }

        /* Responsiveness */
        @media (max-width: 600px) {
            .quiz-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="blob-1"></div>
    <div class="blob-2"></div>

    <header class="topbar">
        <h2 class="topbar-title"><span>👁️</span> Visual World</h2>
        <a href="#" class="btn btn-outline" style="min-height: 50px; font-size: 18px; padding: 10px 20px;">Kembali</a>
    </header>

    <main>
        <div class="progress-container">
            <div class="progress-bar-bg">
                <div class="progress-bar-fill"></div>
            </div>
            <div class="progress-star">★ ★ ★</div>
        </div>

        @yield('content')
    </main>

</body>
</html>    
    <style>
        :root {
            --bg: #fff9f0;
            --surface: #ffffff;
            --accent: #ff6b35;
            --accent-2: #06d6a0;
            --accent-3: #ffd166;
            --text: #1a1a2e;
            --card-shadow: 0 8px 32px rgba(255, 107, 53, 0.15);
            --btn-primary: #ff6b35;
            --btn-hover: #ff8c5a;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Fredoka', sans-serif;
            font-size: 20px; /* Minimum konten 20px */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        /* SVG Background Blobs via CSS Background Image */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: -1;
            pointer-events: none;
            opacity: 0.15;
            background-image: 
                url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='%23ff6b35' d='M45.7,-76.1C58.9,-69.3,69.1,-55.3,77.2,-40.8C85.3,-26.3,91.3,-11.3,90.4,3.3C89.5,17.9,81.7,32.2,71.5,43.7C61.3,55.2,48.7,63.9,34.8,70.8C20.9,77.7,5.7,82.8,-8.7,81.8C-23.1,80.8,-36.7,73.7,-48.9,64.3C-61.1,54.9,-71.9,43.2,-78.9,29.1C-85.9,15,-89.1,-1.5,-85.4,-16.5C-81.7,-31.5,-71.1,-45,-58.5,-55.8C-45.9,-66.6,-31.3,-74.7,-16.3,-78.5C-1.3,-82.3,13.7,-81.8,28.7,-78.7L45.7,-76.1Z' transform='translate(100 100)' /%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='%2306d6a0' d='M39.9,-66.5C52.4,-58.5,63.6,-48.1,71.1,-35.1C78.6,-22.1,82.4,-6.5,80.1,8.3C77.8,23.1,69.4,37.1,58.3,48.1C47.2,59.1,33.4,67.1,18.4,72.4C3.4,77.7,-12.8,80.3,-27.2,76.1C-41.6,71.9,-54.2,60.9,-64.1,47.8C-74,34.7,-81.2,19.5,-82.1,4.1C-83,-11.3,-77.6,-26.9,-68.2,-39.8C-58.8,-52.7,-45.4,-62.9,-31.6,-69.9C-17.8,-76.9,-3.6,-80.7,10.1,-80.5C23.8,-80.3,37.6,-76.1,48.4,-69.6L39.9,-66.5Z' transform='translate(100 100)' /%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='%23ffd166' d='M47.8,-75C60.6,-66.5,68.6,-50.2,74.9,-34.2C81.2,-18.2,85.8,-2.5,82.8,11.8C79.8,26.1,69.2,39,56.6,48.8C44,58.6,29.4,65.3,13.8,70.2C-1.8,75.1,-18.4,78.2,-32.7,73.4C-47,68.6,-59,55.9,-67.2,41.4C-75.4,26.9,-79.8,10.6,-78.3,-4.9C-76.8,-20.4,-69.4,-35.1,-58.5,-45.7C-47.6,-56.3,-33.2,-62.8,-19.1,-67C-5,-71.2,8.8,-73.1,23.1,-75.4C37.4,-77.7,52.2,-80.4,47.8,-75Z' transform='translate(100 100)' /%3E%3C/svg%3E");
            background-position: -10% -10%, 110% 50%, 20% 110%;
            background-size: 50vw, 60vw, 40vw;
            background-repeat: no-repeat;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Nunito', sans-serif;
            margin-top: 0;
            color: var(--text);
        }

        main {
            flex: 1;
            padding: 2rem;
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            position: relative;
            z-index: 1;
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
            box-shadow: 0 6px 24px rgba(255, 107, 53, 0.4);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        button:hover, .btn:hover {
            background: var(--btn-hover);
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(255, 107, 53, 0.65);
        }

        /* Lesson Card */
        .lesson-card {
            border-radius: 24px;
            padding: 28px;
            background: var(--surface);
            box-shadow: var(--card-shadow);
            border: 3px solid transparent;
            transition: all 0.25s ease;
        }

        .lesson-card:hover {
            border-color: var(--accent);
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(255, 107, 53, 0.22);
        }

        /* Animations */
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .lesson-card, .animate-slide-up {
            animation: slide-up 0.4s ease forwards;
            opacity: 0;
        }
        
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }

        /* Pulse Benar */
        @keyframes border-pulse {
            0%   { box-shadow: 0 0 0 0 rgba(6, 214, 160, 0.6); }
            70%  { box-shadow: 0 0 0 16px rgba(6, 214, 160, 0); }
            100% { box-shadow: 0 0 0 0 rgba(6, 214, 160, 0); }
        }
        .card-benar {
            animation: border-pulse 0.6s ease;
            border-color: var(--accent-2);
        }

    </style>
</head>
<body>
    <main>
        @yield('content')
    </main>
</body>
</html>
