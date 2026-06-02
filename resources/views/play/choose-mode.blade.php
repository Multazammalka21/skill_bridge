<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Pilih Dunia Petualangan Belajar untuk Anak Anda.">
    <title>Pilih Mode Petualangan — Pinteria</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&family=Fredoka+One&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-display: 'Fredoka One', cursive;
            --font-body:    'Nunito', sans-serif;

            --sky-top:      #5dc8f0;
            --sky-bottom:   #a8e6cf;
            --grass:        #4caf50;
            --grass-dark:   #2e7d32;
            --sun:          #ffd600;
            --cloud:        #ffffff;

            --text-dark:    #1b5e20;
            --text-mid:     #388e3c;
            --text-muted:   #81c784;
            --text-body:    #3e3e3e;

            --accent-audio:  #9b72f7;
            --accent-visual: #ff7043;

            --transition: 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background: linear-gradient(180deg, var(--sky-top) 0%, var(--sky-bottom) 60%, #c8e6c9 100%);
            color: var(--text-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        h1, h2, h3, h4 { font-family: var(--font-display); }

        /* Sky Scene decoration */
        .sky-scene {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .sky-scene::before {
            content: '';
            position: absolute;
            top: 40px; right: 80px;
            width: 90px; height: 90px;
            background: var(--sun);
            border-radius: 50%;
            box-shadow: 0 0 0 15px rgba(255,214,0,0.2);
            animation: sunPulse 4s ease-in-out infinite;
        }
        @keyframes sunPulse {
            0%,100% { box-shadow: 0 0 0 15px rgba(255,214,0,0.2); }
            50%      { box-shadow: 0 0 0 25px rgba(255,214,0,0.1); }
        }

        .cloud {
            position: absolute;
            background: white;
            border-radius: 50px;
            opacity: 0.9;
        }
        .cloud::before, .cloud::after {
            content: '';
            position: absolute;
            background: white;
            border-radius: 50%;
        }
        .cloud-1 { width:120px; height:40px; top:60px; left:10%; animation: float 25s linear infinite; }
        .cloud-1::before { width:60px; height:60px; top:-30px; left:20px; }
        .cloud-1::after  { width:40px; height:40px; top:-20px; left:65px; }

        .cloud-2 { width:100px; height:32px; top:120px; right:15%; animation: float 30s linear infinite reverse; }
        .cloud-2::before { width:50px; height:50px; top:-25px; left:15px; }
        .cloud-2::after  { width:35px; height:35px; top:-15px; left:50px; }

        @keyframes float {
            from { transform: translateX(-150px); }
            to   { transform: translateX(calc(100vw + 150px)); }
        }

        /* Container */
        .page-wrap {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 860px;
            padding: 24px;
            text-align: center;
        }

        .header-section {
            margin-bottom: 40px;
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .header-section h1 {
            font-size: 2.2rem;
            color: var(--text-dark);
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .header-section p {
            font-size: 1.1rem;
            color: var(--text-mid);
            font-weight: 700;
        }

        /* Mode Selection Cards */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards;
            opacity: 0;
        }

        .mode-card {
            background: white;
            border-radius: 28px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(76,175,80,0.15);
            border: 4px solid white;
            cursor: pointer;
            transition: all var(--transition);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        .mode-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .mode-card.audio-world {
            border-color: rgba(155, 114, 247, 0.2);
        }
        .mode-card.audio-world:hover {
            border-color: var(--accent-audio);
        }
        .mode-card.visual-world {
            border-color: rgba(255, 112, 67, 0.2);
        }
        .mode-card.visual-world:hover {
            border-color: var(--accent-visual);
        }

        .mascot-avatar {
            margin-bottom: 24px;
            transition: transform 0.3s ease;
        }
        .mode-card:hover .mascot-avatar {
            transform: scale(1.1) rotate(3deg);
        }

        .mode-card h2 {
            font-size: 1.6rem;
            margin-bottom: 12px;
        }
        .audio-world h2 { color: var(--accent-audio); }
        .visual-world h2 { color: var(--accent-visual); }

        .mode-desc {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.5;
            margin-bottom: 24px;
            font-weight: 600;
        }

        .btn-start {
            font-family: var(--font-display);
            font-size: 1.1rem;
            padding: 12px 32px;
            border-radius: 50px;
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
            transition: all var(--transition);
        }
        .audio-world .btn-start {
            background: linear-gradient(135deg, var(--accent-audio), #b48fff);
        }
        .visual-world .btn-start {
            background: linear-gradient(135deg, var(--accent-visual), #ff8c5a);
        }
        .mode-card:hover .btn-start {
            transform: scale(1.05);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 900;
            font-size: 1rem;
            margin-top: 20px;
            font-family: var(--font-display);
            transition: var(--transition);
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
            opacity: 0;
        }
        .back-link:hover {
            color: var(--grass-dark);
            transform: translateX(-4px);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 600px) {
            .header-section h1 { font-size: 1.8rem; }
            .cards-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="sky-scene" aria-hidden="true">
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
    </div>

    <div class="page-wrap">
        <div class="header-section">
            <img src="{{ asset('images/Logo_pinteria (1).png') }}" alt="Pinteria Logo" style="height: 70px; width: auto; object-fit: contain; margin-bottom: 15px;">
            <h1>Pilih Mode Petualangan</h1>
            <p>
                Halo <strong>{{ $child->nama_panggilan ?? 'Petualang' }}</strong>! 
                @if($child->isAudioWorld())
                    Mode <span style="color:#9b72f7; font-weight:900;">🎧 Audio</span> direkomendasikan untukmu.
                @else
                    Mode <span style="color:#ff7043; font-weight:900;">👁️ Visual</span> direkomendasikan untukmu.
                @endif
            </p>
        </div>

        <div class="cards-grid">
            @php $isAudio = $child->isAudioWorld(); @endphp

            {{-- Recommended world always first --}}
            @if($isAudio)
                {{-- Audio World (Recommended) --}}
                <a href="{{ route('play.tunanetra', $child->id) }}" class="mode-card audio-world" style="position: relative; border-color: var(--accent-audio);">
                    <div style="position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: var(--accent-audio); color: white; font-size: 0.8rem; font-weight: 900; padding: 4px 16px; border-radius: 20px; white-space: nowrap; box-shadow: 0 4px 12px rgba(155,114,247,0.4);">
                        ⭐ Direkomendasikan
                    </div>
                    <div class="mascot-avatar">
                        <svg width="90" height="90" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="32" r="30" fill="#E3F2FD" />
                            <ellipse cx="32" cy="34" rx="16" ry="18" fill="#FFB74D" />
                            <ellipse cx="32" cy="37" rx="11" ry="12" fill="#FFE082" />
                            <polygon points="18,22 25,18 24,26" fill="#F57C00" />
                            <polygon points="46,22 39,18 40,26" fill="#F57C00" />
                            <path d="M22,30 Q26,34 30,30" stroke="#E65100" stroke-width="3" stroke-linecap="round" fill="none" />
                            <path d="M34,30 Q38,34 42,30" stroke="#E65100" stroke-width="3" stroke-linecap="round" fill="none" />
                            <polygon points="32,31 29,35 35,35" fill="#FF8F00" />
                            <path d="M16,34 Q10,38 15,44 Q18,44 17,37" fill="#E65100" />
                            <path d="M48,34 Q54,38 49,44 Q46,44 47,37" fill="#E65100" />
                            <path d="M17,32 A16,16 0 0,1 47,32" stroke="#1E88E5" stroke-width="5" stroke-linecap="round" fill="none" />
                            <rect x="12" y="28" width="7" height="12" rx="3" fill="#1E88E5" />
                            <rect x="45" y="28" width="7" height="12" rx="3" fill="#1E88E5" />
                        </svg>
                    </div>
                    <h2>Dunia Audio</h2>
                    <p class="mode-desc">Mode Tunanetra: Belajar menggunakan cerita audio, narasi suara, dan menjawab kuis dengan ucapan (mikrofon).</p>
                    <div class="btn-start">Mulai Main 🎧</div>
                </a>

                {{-- Visual World (Alternative) --}}
                <a href="{{ route('play.tunarungu', $child->id) }}" class="mode-card visual-world" style="opacity: 0.75;">
                    <div class="mascot-avatar">
                        <svg width="90" height="90" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="32" r="30" fill="#E8F5E9" />
                            <ellipse cx="32" cy="36" rx="15" ry="17" fill="#4CAF50" />
                            <ellipse cx="32" cy="39" rx="10" ry="11" fill="#FFEB3B" />
                            <ellipse cx="26" cy="28" rx="7" ry="7" fill="#FFFFFF" />
                            <ellipse cx="38" cy="28" rx="7" ry="7" fill="#FFFFFF" />
                            <circle cx="26" cy="28" r="4.5" fill="#263238" />
                            <circle cx="38" cy="28" r="4.5" fill="#263238" />
                            <circle cx="24.5" cy="26.5" r="1.5" fill="#FFFFFF" />
                            <circle cx="36.5" cy="26.5" r="1.5" fill="#FFFFFF" />
                            <path d="M32,31 C35,31 35,37 32,41 C29,37 29,31 32,31 Z" fill="#FF9100" />
                            <path d="M17,35 Q12,38 14,44 Q17,45 18,39" fill="#29B6F6" />
                            <path d="M47,35 Q52,38 50,44 Q47,45 46,39" fill="#29B6F6" />
                        </svg>
                    </div>
                    <h2>Dunia Visual</h2>
                    <p class="mode-desc">Mode Tunarungu: Belajar dengan gambar interaktif, animasi visual, dan kuis pilihan bergambar.</p>
                    <div class="btn-start">Coba Mode Ini 🦜</div>
                </a>

            @else
                {{-- Visual World (Recommended) --}}
                <a href="{{ route('play.tunarungu', $child->id) }}" class="mode-card visual-world" style="position: relative; border-color: var(--accent-visual);">
                    <div style="position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: var(--accent-visual); color: white; font-size: 0.8rem; font-weight: 900; padding: 4px 16px; border-radius: 20px; white-space: nowrap; box-shadow: 0 4px 12px rgba(255,112,67,0.4);">
                        ⭐ Direkomendasikan
                    </div>
                    <div class="mascot-avatar">
                        <svg width="90" height="90" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="32" r="30" fill="#E8F5E9" />
                            <ellipse cx="32" cy="36" rx="15" ry="17" fill="#4CAF50" />
                            <ellipse cx="32" cy="39" rx="10" ry="11" fill="#FFEB3B" />
                            <ellipse cx="26" cy="28" rx="7" ry="7" fill="#FFFFFF" />
                            <ellipse cx="38" cy="28" rx="7" ry="7" fill="#FFFFFF" />
                            <circle cx="26" cy="28" r="4.5" fill="#263238" />
                            <circle cx="38" cy="28" r="4.5" fill="#263238" />
                            <circle cx="24.5" cy="26.5" r="1.5" fill="#FFFFFF" />
                            <circle cx="36.5" cy="26.5" r="1.5" fill="#FFFFFF" />
                            <path d="M32,31 C35,31 35,37 32,41 C29,37 29,31 32,31 Z" fill="#FF9100" />
                            <path d="M17,35 Q12,38 14,44 Q17,45 18,39" fill="#29B6F6" />
                            <path d="M47,35 Q52,38 50,44 Q47,45 46,39" fill="#29B6F6" />
                            <path d="M32,19 C31,14 34,13 34,13 C34,13 36,15 34,19 Z" fill="#4CAF50" />
                        </svg>
                    </div>
                    <h2>Dunia Visual</h2>
                    <p class="mode-desc">Mode Tunarungu: Belajar dengan gambar interaktif, animasi cerita, dan kuis pilihan bergambar yang seru!</p>
                    <div class="btn-start">Mulai Main 🦜</div>
                </a>

                {{-- Audio World (Alternative) --}}
                <a href="{{ route('play.tunanetra', $child->id) }}" class="mode-card audio-world" style="opacity: 0.75;">
                    <div class="mascot-avatar">
                        <svg width="90" height="90" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="32" r="30" fill="#E3F2FD" />
                            <ellipse cx="32" cy="34" rx="16" ry="18" fill="#FFB74D" />
                            <ellipse cx="32" cy="37" rx="11" ry="12" fill="#FFE082" />
                            <polygon points="18,22 25,18 24,26" fill="#F57C00" />
                            <polygon points="46,22 39,18 40,26" fill="#F57C00" />
                            <path d="M22,30 Q26,34 30,30" stroke="#E65100" stroke-width="3" stroke-linecap="round" fill="none" />
                            <path d="M34,30 Q38,34 42,30" stroke="#E65100" stroke-width="3" stroke-linecap="round" fill="none" />
                            <polygon points="32,31 29,35 35,35" fill="#FF8F00" />
                            <path d="M16,34 Q10,38 15,44 Q18,44 17,37" fill="#E65100" />
                            <path d="M48,34 Q54,38 49,44 Q46,44 47,37" fill="#E65100" />
                            <path d="M17,32 A16,16 0 0,1 47,32" stroke="#1E88E5" stroke-width="5" stroke-linecap="round" fill="none" />
                            <rect x="12" y="28" width="7" height="12" rx="3" fill="#1E88E5" />
                            <rect x="45" y="28" width="7" height="12" rx="3" fill="#1E88E5" />
                        </svg>
                    </div>
                    <h2>Dunia Audio</h2>
                    <p class="mode-desc">Mode Tunanetra: Belajar menggunakan cerita audio dan menjawab kuis dengan ucapan suara (mikrofon).</p>
                    <div class="btn-start">Coba Mode Ini 🎧</div>
                </a>
            @endif
        </div>

        <a href="{{ route('dashboard') }}" class="back-link">
            ⬅️ Kembali ke Dashboard
        </a>
    </div>

</body>
</html>
