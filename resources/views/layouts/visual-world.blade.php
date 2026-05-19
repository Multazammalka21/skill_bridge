<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual World - Skill Bridge Kids</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    
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
