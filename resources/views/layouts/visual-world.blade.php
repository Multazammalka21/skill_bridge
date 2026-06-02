<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual World — Pinteria</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            
            --font-display: 'Fredoka', sans-serif;
            --font-body: 'Nunito', sans-serif;
            
            --visual-primary: #ff6b35;
            --visual-yellow: #ffd166;
            --visual-success: #06d6a0;
            --visual-error: #ef4444;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg);
            color: var(--text);
            font-family: var(--font-display);
            font-size: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        /* SVG Background Blobs */
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
            width: 50%;
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
            border-radius: 22px;
            min-height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-family: var(--font-display);
            font-weight: 600;
            cursor: pointer;
            border: 4px solid transparent;
            box-shadow: var(--card-shadow);
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
        button, .btn {
            font-family: var(--font-display);
            border-radius: 50px;
            padding: 15px 30px;
            font-size: 22px;
            font-weight: 700;
            min-height: 80px;
            background: var(--btn-primary);
            color: #ffffff;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            text-decoration: none;
        }

        button:hover, .btn:hover {
            background: var(--btn-hover);
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(255,107,53,0.5);
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

        @keyframes border-pulse {
            0%   { box-shadow: 0 0 0 0 rgba(6, 214, 160, 0.6); }
            70%  { box-shadow: 0 0 0 16px rgba(6, 214, 160, 0); }
            100% { box-shadow: 0 0 0 0 rgba(6, 214, 160, 0); }
        }
        .card-benar {
            animation: border-pulse 0.6s ease;
            border-color: var(--accent-2);
        }

        @media (max-width: 600px) {
            .quiz-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <h2 class="topbar-title"><span>👁️</span> Visual World</h2>
        <a href="/dashboard" class="btn btn-outline" style="min-height: 50px; font-size: 18px; padding: 10px 20px;">Kembali</a>
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

    <!-- Parent Password Verification Modal (Kids Mode Lock) -->
    <div id="parentUnlockModal" class="parent-unlock-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(8px);">
        <div class="parent-unlock-content" style="background: white; color: #333; border-radius: 28px; padding: 35px 30px; width: 90%; max-width: 440px; text-align: center; border: 4px solid var(--accent); box-shadow: 0 15px 40px rgba(0,0,0,0.3); animation: zoomIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); position: relative;">
            <button onclick="closeParentUnlockModal()" style="position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 28px; cursor: pointer; color: #888; font-family: sans-serif;">&times;</button>
            
            <div style="font-size: 3.5rem; margin-bottom: 15px;">🔒</div>
            <h2 style="font-family: var(--font-display); font-size: 1.6rem; margin-bottom: 10px; color: #1a1a2e;">Kunci Orang Tua</h2>
            <p style="font-family: var(--font-body); font-size: 0.95rem; color: #666; margin-bottom: 24px; font-weight: bold; line-height: 1.4;">Masukkan kata sandi akun Orang Tua Anda untuk menonaktifkan Kids Mode dan kembali ke Dashboard.</p>
            
            <div style="margin-bottom: 20px; text-align: left;">
                <input type="password" id="parentPasswordInput" placeholder="Masukkan kata sandi..." style="width: 100%; padding: 14px 18px; border-radius: 14px; border: 2px solid rgba(0,0,0,0.1); outline: none; font-size: 16px; font-family: var(--font-body); box-sizing: border-box; text-align: center;">
                <div id="unlockErrorMessage" style="color: #ef4444; font-size: 0.9rem; margin-top: 8px; text-align: center; display: none; font-weight: bold;"></div>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button onclick="closeParentUnlockModal()" style="flex: 1; min-height: 55px; font-size: 16px; padding: 10px; background: #eceff1; color: #455a64; border-radius: 50px; border: none; cursor: pointer; font-weight: 700; box-shadow: none;">Batal</button>
                <button id="btnSubmitUnlock" onclick="submitParentUnlock()" style="flex: 1; min-height: 55px; font-size: 16px; padding: 10px; background: var(--btn-primary); color: white; border-radius: 50px; border: none; cursor: pointer; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                    Verifikasi 🔓
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .parent-unlock-overlay {
            display: none;
        }
        .parent-unlock-overlay.active {
            display: flex !important;
        }
    </style>

    <script>
        let pendingRedirectUrl = '/dashboard';

        window.openParentUnlockModal = function(event, redirectUrl = '/dashboard') {
            if (event) {
                event.preventDefault();
            }
            pendingRedirectUrl = redirectUrl;
            document.getElementById('parentPasswordInput').value = '';
            document.getElementById('unlockErrorMessage').style.display = 'none';
            document.getElementById('parentUnlockModal').classList.add('active');
            
            setTimeout(() => {
                const input = document.getElementById('parentPasswordInput');
                if (input) input.focus();
            }, 100);
        };

        window.closeParentUnlockModal = function() {
            document.getElementById('parentUnlockModal').classList.remove('active');
        };

        window.submitParentUnlock = function() {
            const password = document.getElementById('parentPasswordInput').value;
            const errMsg = document.getElementById('unlockErrorMessage');
            const btn = document.getElementById('btnSubmitUnlock');

            if (!password) {
                errMsg.textContent = 'Kata sandi tidak boleh kosong!';
                errMsg.style.display = 'block';
                return;
            }

            errMsg.style.display = 'none';
            btn.disabled = true;
            btn.textContent = 'Memverifikasi...';

            fetch('/play/verify-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ password: password })
            })
            .then(async (res) => {
                const data = await res.json();
                if (res.ok && data.success) {
                    window.location.href = pendingRedirectUrl;
                } else {
                    throw new Error(data.message || 'Verifikasi gagal');
                }
            })
            .catch(err => {
                errMsg.textContent = err.message;
                errMsg.style.display = 'block';
                btn.disabled = false;
                btn.textContent = 'Verifikasi 🔓';
            });
        };

        document.addEventListener('DOMContentLoaded', () => {
            const pwdInput = document.getElementById('parentPasswordInput');
            if (pwdInput) {
                pwdInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        submitParentUnlock();
                    }
                });
            }

            // Intercept all topbar dashboard/back links/buttons
            const kembaliBtns = document.querySelectorAll('.topbar a[href="/dashboard"], .topbar a[href*="dashboard"]');
            kembaliBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    window.openParentUnlockModal(e, btn.getAttribute('href'));
                });
            });
        });
    </script>
</body>
</html>
