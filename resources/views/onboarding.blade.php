<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Selamat datang di Pinteria - Dunia Petualangan Belajar">
    <title>Selamat Datang - Pinteria</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-display: 'Fredoka', sans-serif;
            --font-body: 'Nunito', sans-serif;
            --primary: #43a047;
            --secondary: #5aad2a;
            --accent-audio: #9b72f7;
            --accent-visual: #ff6b35;
            --bg-color: #f1f8e9;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: var(--font-body);
            background: linear-gradient(180deg, #b8e8f7 0%, #d8f3dc 60%, #b7e4c7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Ambient elements */
        .ambient-cloud {
            position: absolute;
            background: #fff;
            border-radius: 50px;
            opacity: 0.6;
            z-index: 1;
            animation: float-cloud 20s infinite linear alternate;
        }
        .ambient-cloud::before, .ambient-cloud::after {
            content: '';
            position: absolute;
            background: #fff;
            border-radius: 50%;
        }
        .cloud-1 { top: 8%; left: 5%; width: 140px; height: 45px; animation-duration: 25s; }
        .cloud-1::before { width: 70px; height: 70px; top: -35px; left: 20px; }
        .cloud-1::after { width: 50px; height: 50px; top: -25px; left: 70px; }

        .cloud-2 { top: 15%; right: 8%; width: 160px; height: 50px; animation-duration: 35s; animation-direction: alternate-reverse; }
        .cloud-2::before { width: 80px; height: 80px; top: -40px; left: 25px; }
        .cloud-2::after { width: 55px; height: 55px; top: -27px; left: 85px; }

        @keyframes float-cloud {
            from { transform: translateX(0); }
            to { transform: translateX(50px); }
        }

        /* Onboarding Wrapper */
        .onboarding-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 500px;
            padding: 20px;
            box-sizing: border-box;
        }

        .onboarding-card {
            background: #ffffff;
            border-radius: 30px;
            padding: 40px 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 580px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 4px solid #fff;
        }

        /* Slides */
        .slides-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .slide {
            display: none;
            width: 100%;
            animation: fadeIn 0.5s ease-out forwards;
        }

        .slide.active {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Illustrations */
        .illus-wrapper {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            position: relative;
            width: 100%;
        }

        /* Mascot waving */
        .mascot-wave {
            font-size: 110px;
            animation: wave 2.5s infinite ease-in-out;
            transform-origin: bottom center;
            filter: drop-shadow(0 10px 10px rgba(0,0,0,0.15));
        }

        @keyframes wave {
            0%, 100% { transform: rotate(0deg) scale(1); }
            25% { transform: rotate(-8deg) scale(1.05); }
            75% { transform: rotate(8deg) scale(1.05); }
        }

        /* Audio World illustration */
        .audio-illus {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .headphone-glow {
            font-size: 100px;
            animation: pulse-audio 2s infinite ease-in-out;
            filter: drop-shadow(0 10px 15px rgba(155, 114, 247, 0.4));
        }
        .mic-illus {
            font-size: 50px;
            position: absolute;
            bottom: -10px;
            right: 20px;
            animation: float-mic 3s infinite ease-in-out;
        }
        .wave-illus {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: space-around;
            pointer-events: none;
        }
        .wave-bar {
            width: 6px;
            background: var(--accent-audio);
            border-radius: 3px;
            animation: bounce-wave 1.2s infinite ease-in-out alternate;
            opacity: 0.3;
        }
        .wave-bar:nth-child(1) { height: 40px; animation-delay: 0.1s; }
        .wave-bar:nth-child(2) { height: 70px; animation-delay: 0.3s; }
        .wave-bar:nth-child(3) { height: 50px; animation-delay: 0.5s; }
        .wave-bar:nth-child(4) { height: 80px; animation-delay: 0.2s; }

        @keyframes pulse-audio {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); filter: drop-shadow(0 15px 25px rgba(155, 114, 247, 0.6)); }
        }
        @keyframes float-mic {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes bounce-wave {
            0% { transform: scaleY(0.3); }
            100% { transform: scaleY(1); }
        }

        /* Visual World illustration */
        .visual-illus {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .flashcard {
            background: #fff9f0;
            border: 3px solid var(--accent-visual);
            border-radius: 18px;
            width: 90px;
            height: 130px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            font-family: var(--font-display);
            animation: bounce-card 3s infinite ease-in-out;
            position: relative;
        }
        .flashcard:nth-child(1) { animation-delay: 0.1s; transform: rotate(-8deg); }
        .flashcard:nth-child(2) { animation-delay: 0.4s; transform: rotate(0deg); }
        .flashcard:nth-child(3) { animation-delay: 0.7s; transform: rotate(8deg); }

        @keyframes bounce-card {
            0%, 100% { transform: translateY(0) rotate(var(--rot, 0deg)); }
            50% { transform: translateY(-15px) rotate(calc(var(--rot, 0deg) + 2deg)); }
        }

        /* Mascot jumping */
        .mascot-jump {
            font-size: 110px;
            animation: jump 1.8s infinite ease-in-out;
            filter: drop-shadow(0 15px 15px rgba(0,0,0,0.15));
        }
        .confetti {
            position: absolute;
            font-size: 24px;
            animation: spin-confetti 4s infinite linear;
        }
        .c-1 { top: 20px; left: 40px; }
        .c-2 { top: 60px; right: 50px; }
        .c-3 { bottom: 30px; left: 80px; }
        .c-4 { bottom: 50px; right: 70px; }

        @keyframes jump {
            0%, 100% { transform: translateY(0) scale(1); }
            40% { transform: translateY(-30px) scale(1.05); }
            50% { transform: translateY(-30px) scale(1.05) rotate(5deg); }
            80% { transform: translateY(0) scale(0.95); }
        }
        @keyframes spin-confetti {
            0% { transform: rotate(0deg) translateY(0); }
            50% { transform: rotate(180deg) translateY(-5px); }
            100% { transform: rotate(360deg) translateY(0); }
        }

        /* Typography */
        .title {
            font-family: var(--font-display);
            font-size: 28px;
            color: #2e7d32;
            margin: 0 0 10px 0;
            font-weight: 700;
            line-height: 1.2;
        }
        .title--audio { color: var(--accent-audio); }
        .title--visual { color: var(--accent-visual); }

        .subtitle {
            font-size: 16px;
            color: #555;
            margin: 0;
            line-height: 1.5;
            padding: 0 10px;
        }

        /* Navigation */
        .nav-container {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .dots {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #cbd5e1;
            transition: all 0.3s;
            cursor: pointer;
        }
        .dot.active {
            background: var(--primary);
            width: 28px;
            border-radius: 6px;
        }

        .btn-action {
            width: 100%;
            border-radius: 50px;
            padding: 15px 25px;
            font-size: 18px;
            font-weight: 700;
            font-family: var(--font-display);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-sizing: border-box;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 8px 20px rgba(67, 160, 71, 0.35);
        }
        .btn-action:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(67, 160, 71, 0.5);
        }
        
        .btn-action--audio {
            background: linear-gradient(135deg, var(--accent-audio), #b48fff);
            box-shadow: 0 8px 20px rgba(155, 114, 247, 0.35);
        }
        .btn-action--audio:hover {
            box-shadow: 0 12px 25px rgba(155, 114, 247, 0.5);
        }

        .btn-action--visual {
            background: linear-gradient(135deg, var(--accent-visual), #ff8c5a);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.35);
        }
        .btn-action--visual:hover {
            box-shadow: 0 12px 25px rgba(255, 107, 53, 0.5);
        }

        .skip-btn {
            background: transparent;
            border: none;
            color: #64748b;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: color 0.2s;
        }
        .skip-btn:hover {
            color: #334155;
        }
    </style>
</head>
<body>

    <!-- Background clouds -->
    <div class="ambient-cloud cloud-1"></div>
    <div class="ambient-cloud cloud-2"></div>

    <div class="onboarding-container">
        <div class="onboarding-card">
            
            <div class="slides-container">
                <!-- Slide 1: Welcome -->
                <div class="slide slide-1 active">
                    <div class="illus-wrapper">
                        <div class="mascot-wave">🦉</div>
                    </div>
                    <h1 class="title">Selamat Datang di Pinteria</h1>
                    <p class="subtitle">Belajar sambil bermain dengan seru!</p>
                </div>

                <!-- Slide 2: Audio Learning -->
                <div class="slide slide-2">
                    <div class="illus-wrapper">
                        <div class="wave-illus">
                            <div class="wave-bar"></div>
                            <div class="wave-bar"></div>
                            <div class="wave-bar"></div>
                            <div class="wave-bar"></div>
                        </div>
                        <div class="audio-illus">
                            <div class="headphone-glow">🎧</div>
                            <div class="mic-illus">🎤</div>
                        </div>
                    </div>
                    <h1 class="title title--audio">Dengarkan dan Jawab dengan Suara</h1>
                    <p class="subtitle">Belajar jadi lebih mudah dan menyenangkan</p>
                </div>

                <!-- Slide 3: Visual Learning -->
                <div class="slide slide-3">
                    <div class="illus-wrapper">
                        <div class="visual-illus">
                            <div class="flashcard" style="--rot: -8deg">🦁</div>
                            <div class="flashcard" style="--rot: 0deg">🅰️</div>
                            <div class="flashcard" style="--rot: 8deg">🔢</div>
                        </div>
                    </div>
                    <h1 class="title title--visual">Belajar dengan Gambar dan Animasi</h1>
                    <p class="subtitle">Klik, lihat, dan bermain sambil belajar!</p>
                </div>

                <!-- Slide 4: Ready -->
                <div class="slide slide-4">
                    <div class="illus-wrapper">
                        <div class="confetti c-1">🎉</div>
                        <div class="confetti c-2">⭐</div>
                        <div class="confetti c-3">✨</div>
                        <div class="confetti c-4">🎈</div>
                        <div class="mascot-jump">🦜</div>
                    </div>
                    <h1 class="title">Siap Memulai Petualangan?</h1>
                    <p class="subtitle">Tumbuh dan belajar bersama Pinteria!</p>
                </div>
            </div>

            <!-- Navigation Controls -->
            <div class="nav-container">
                <div class="dots">
                    <div class="dot active" onclick="goToSlide(1)"></div>
                    <div class="dot" onclick="goToSlide(2)"></div>
                    <div class="dot" onclick="goToSlide(3)"></div>
                    <div class="dot" onclick="goToSlide(4)"></div>
                </div>

                <button id="btnNext" onclick="nextSlide()" class="btn-action">
                    Lanjut ➔
                </button>

                <button id="btnSkip" onclick="finishOnboarding()" class="skip-btn">
                    Lewati
                </button>
            </div>

        </div>
    </div>

    <script>
        let currentSlide = 1;
        const totalSlides = 4;

        function goToSlide(n) {
            // Hide current slide
            document.querySelector(`.slide-${currentSlide}`).classList.remove('active');
            document.querySelectorAll('.dot')[currentSlide - 1].classList.remove('active');

            // Show new slide
            currentSlide = n;
            document.querySelector(`.slide-${currentSlide}`).classList.add('active');
            document.querySelectorAll('.dot')[currentSlide - 1].classList.add('active');

            updateControls();
        }

        function nextSlide() {
            if (currentSlide < totalSlides) {
                goToSlide(currentSlide + 1);
            } else {
                finishOnboarding();
            }
        }

        function updateControls() {
            const btnNext = document.getElementById('btnNext');
            const btnSkip = document.getElementById('btnSkip');

            // Remove specific accent classes
            btnNext.classList.remove('btn-action--audio', 'btn-action--visual');

            if (currentSlide === 2) {
                btnNext.classList.add('btn-action--audio');
            } else if (currentSlide === 3) {
                btnNext.classList.add('btn-action--visual');
            }

            if (currentSlide === totalSlides) {
                btnNext.innerHTML = 'Mulai Sekarang 🚀';
                btnSkip.style.display = 'none';
            } else {
                btnNext.innerHTML = 'Lanjut ➔';
                btnSkip.style.display = 'block';
            }
        }

        function finishOnboarding() {
            // Mark onboarding as seen in localStorage
            localStorage.setItem('pinteria_onboarding_seen', 'true');
            // Redirect to login screen
            window.location.href = "{{ route('login') }}";
        }

        // Check if user has already seen onboarding (optional routing could handle this, but client-side check is robust)
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('pinteria_onboarding_seen') === 'true') {
                // If query param 'force' is not present, we can redirect or let the route redirect
                const urlParams = new URLSearchParams(window.location.search);
                if (!urlParams.has('force')) {
                    // Let the welcome page handle direct session auth, or go straight to login
                }
            }
            updateControls();
        });
    </script>
</body>
</html>
