<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Dashboard Progress Orang Tua - Pinteria. Pantau kemajuan belajar anak Anda.">
    <title>Dashboard Orang Tua — Pinteria</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&family=Fredoka+One&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ─── Design Tokens ──────────────────────────────────────── */
        :root {
            --font-display: 'Fredoka One', cursive;
            --font-body:    'Nunito', sans-serif;

            /* Sky-to-meadow palette — matches login screen */
            --sky-top:      #5dc8f0;
            --sky-bottom:   #a8e6cf;
            --grass:        #4caf50;
            --grass-dark:   #2e7d32;
            --sun:          #ffd600;
            --cloud:        #ffffff;

            --card-bg:      #ffffff;
            --card-shadow:  0 6px 24px rgba(76,175,80,0.15), 0 2px 8px rgba(0,0,0,0.06);
            --card-radius:  20px;
            --card-border:  2px solid rgba(76,175,80,0.15);

            --accent-green:  #43a047;
            --accent-green2: #66bb6a;
            --accent-yellow: #ffd600;
            --accent-sky:    #29b6f6;
            --accent-orange: #ff7043;

            --text-dark:    #1b5e20;
            --text-mid:     #388e3c;
            --text-muted:   #81c784;
            --text-body:    #3e3e3e;

            --transition: 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background: linear-gradient(180deg, var(--sky-top) 0%, var(--sky-bottom) 60%, #c8e6c9 100%);
            color: var(--text-body);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            position: relative;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 { font-family: var(--font-display); }

        /* ─── Decorative Sky Scene ───────────────────────────────── */
        .sky-scene {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        /* Sun */
        .sky-scene::before {
            content: '';
            position: absolute;
            top: 20px; right: 60px;
            width: 80px; height: 80px;
            background: var(--sun);
            border-radius: 50%;
            box-shadow: 0 0 0 12px rgba(255,214,0,0.25);
            animation: sunPulse 4s ease-in-out infinite;
        }
        @keyframes sunPulse {
            0%,100% { box-shadow: 0 0 0 12px rgba(255,214,0,0.25); }
            50%      { box-shadow: 0 0 0 22px rgba(255,214,0,0.12); }
        }

        /* Ground strip at bottom */
        .sky-scene::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 60px;
            background: var(--grass);
            border-radius: 60% 60% 0 0 / 20px 20px 0 0;
        }

        /* Clouds */
        .cloud {
            position: absolute;
            background: white;
            border-radius: 50px;
            opacity: 0.92;
        }
        .cloud::before, .cloud::after {
            content: '';
            position: absolute;
            background: white;
            border-radius: 50%;
        }
        .cloud-1 { width:110px; height:36px; top:55px; left:8%; animation: drift 18s linear infinite; }
        .cloud-1::before { width:56px; height:56px; top:-28px; left:18px; }
        .cloud-1::after  { width:38px; height:38px; top:-18px; left:58px; }

        .cloud-2 { width:80px;  height:26px; top:90px; left:42%; animation: drift 26s linear infinite 6s; }
        .cloud-2::before { width:40px; height:40px; top:-20px; left:10px; }
        .cloud-2::after  { width:28px; height:28px; top:-12px; left:44px; }

        .cloud-3 { width:90px;  height:30px; top:40px; left:68%; animation: drift 22s linear infinite 12s; }
        .cloud-3::before { width:48px; height:48px; top:-24px; left:14px; }
        .cloud-3::after  { width:32px; height:32px; top:-15px; left:56px; }

        @keyframes drift {
            from { transform: translateX(-220px); }
            to   { transform: translateX(calc(100vw + 220px)); }
        }

        /* Mountains */
        .mountains {
            position: fixed;
            bottom: 55px;
            left: 0; right: 0;
            z-index: 0;
            pointer-events: none;
        }
        .mountains svg { display: block; width: 100%; height: 160px; }

        /* Trees */
        .trees {
            position: fixed;
            bottom: 52px;
            left: 0; right: 0;
            z-index: 0;
            pointer-events: none;
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
        }
        .tree {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .tree-top {
            background: var(--grass-dark);
            border-radius: 50% 50% 40% 40%;
        }
        .tree-trunk {
            background: #5d4037;
            border-radius: 3px;
        }

        /* ─── Layout ─────────────────────────────────────────────── */
        .page-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            padding-bottom: 120px; /* room for ground */
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px 40px;
        }

        /* ─── Topbar ─────────────────────────────────────────────── */
        .topbar {
            background: rgba(255,255,255,0.72);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 2px solid rgba(76,175,80,0.18);
            padding: 14px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar-brand h1 {
            font-size: 1.6rem;
            color: var(--text-dark);
            letter-spacing: 0.01em;
        }
        .topbar-brand .brand-badge {
            background: var(--accent-green);
            color: white;
            font-size: 0.7rem;
            font-family: var(--font-body);
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .avatar {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-green), var(--accent-green2));
            color: white;
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-display);
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(76,175,80,0.35);
            flex-shrink: 0;
        }
        .user-name {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 0.95rem;
        }
        .logout-btn {
            background: rgba(255,112,67,0.1);
            border: 2px solid rgba(255,112,67,0.25);
            color: var(--accent-orange);
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
            font-family: var(--font-body);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .logout-btn:hover {
            background: var(--accent-orange);
            color: white;
            transform: translateY(-2px);
        }

        /* ─── Summary Cards ──────────────────────────────────────── */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin: 30px 0;
        }
        .summary-card {
            background: var(--card-bg);
            border-radius: var(--card-radius);
            border: var(--card-border);
            padding: 22px 24px;
            box-shadow: var(--card-shadow);
            transition: transform var(--transition), box-shadow var(--transition);
            position: relative;
            overflow: hidden;
        }
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(76,175,80,0.2), 0 4px 12px rgba(0,0,0,0.08);
        }
        .summary-card::after {
            content: attr(data-emoji);
            position: absolute;
            right: 16px; bottom: 10px;
            font-size: 3rem;
            opacity: 0.12;
            pointer-events: none;
        }
        .summary-card .s-icon {
            font-size: 1.8rem;
            margin-bottom: 10px;
            display: block;
        }
        .summary-card .s-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: var(--text-muted);
            margin-bottom: 4px;
        }
        .summary-card .s-value {
            font-family: var(--font-display);
            font-size: 2.4rem;
            color: var(--text-dark);
            line-height: 1;
        }
        .summary-card:nth-child(1) .s-value { color: var(--accent-sky); }
        .summary-card:nth-child(2) .s-value { color: var(--accent-green); }
        .summary-card:nth-child(3) .s-value { color: var(--accent-orange); }
        .summary-card:nth-child(1) { border-color: rgba(41,182,246,0.25); }
        .summary-card:nth-child(2) { border-color: rgba(76,175,80,0.25); }
        .summary-card:nth-child(3) { border-color: rgba(255,112,67,0.25); }

        /* ─── Section heading ────────────────────────────────────── */
        .section-heading {
            font-family: var(--font-display);
            font-size: 1.25rem;
            color: var(--text-dark);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ─── Child Tabs ─────────────────────────────────────────── */
        .child-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            overflow-x: auto;
            padding-bottom: 6px;
            scrollbar-width: thin;
            scrollbar-color: rgba(76,175,80,0.2) transparent;
        }
        .child-tab {
            min-width: 100px;
            padding: 14px 20px;
            background: rgba(255,255,255,0.75);
            border: 2px solid rgba(76,175,80,0.2);
            border-radius: 18px;
            color: var(--text-mid);
            font-family: var(--font-display);
            font-size: 1rem;
            cursor: pointer;
            transition: all var(--transition);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            backdrop-filter: blur(8px);
        }
        .child-tab:hover {
            border-color: var(--accent-green);
            transform: translateY(-3px);
            background: white;
        }
        .child-tab.active {
            background: white;
            border-color: var(--accent-green);
            color: var(--text-dark);
            box-shadow: 0 4px 16px rgba(76,175,80,0.25);
            transform: translateY(-3px);
        }
        .tab-icon { font-size: 1.6rem; }
        .tab-badge {
            font-size: 0.62rem;
            font-family: var(--font-body);
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            background: rgba(76,175,80,0.12);
            color: var(--accent-green);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* ─── Chart Cards ────────────────────────────────────────── */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        @media (min-width: 720px) {
            .charts-grid { grid-template-columns: 1fr 1fr; }
            .chart-card--full { grid-column: 1 / -1; }
        }

        .chart-card {
            background: var(--card-bg);
            border-radius: var(--card-radius);
            border: var(--card-border);
            padding: 22px 24px;
            box-shadow: var(--card-shadow);
            transition: transform var(--transition);
        }
        .chart-card:hover { transform: translateY(-3px); }

        .chart-card h3 {
            font-size: 1rem;
            font-family: var(--font-display);
            color: var(--text-dark);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .chart-card canvas { max-height: 260px; }

        /* Start Banner */
        .banner-card {
            background: linear-gradient(135deg, var(--accent-green) 0%, #66bb6a 100%);
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .banner-card h3 { color: white; font-size: 1.25rem; margin-bottom: 4px; }
        .banner-card p  { color: rgba(255,255,255,0.88); font-size: 0.92rem; margin: 0; }
        .btn-play {
            background: white;
            color: var(--accent-green);
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 50px;
            font-family: var(--font-display);
            font-size: 1.05rem;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
            transition: transform 0.2s, box-shadow 0.2s;
            white-space: nowrap;
            display: inline-block;
        }
        .btn-play:hover {
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 6px 20px rgba(0,0,0,0.18);
        }

        /* Progress bars */
        .progress-list { display: flex; flex-direction: column; gap: 14px; }
        .progress-label {
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--text-mid);
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }
        .progress-track {
            background: #e8f5e9;
            height: 14px;
            border-radius: 20px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 20px;
            transition: width 0.9s cubic-bezier(0.16,1,0.3,1);
        }
        .pf-1 { background: linear-gradient(90deg, #29b6f6, #4fc3f7); }
        .pf-2 { background: linear-gradient(90deg, #43a047, #66bb6a); }
        .pf-3 { background: linear-gradient(90deg, #ff7043, #ffa726); }

        /* dot accent */
        .dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        /* ─── Empty State ────────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            color: var(--text-mid);
        }
        .empty-state .emoji { font-size: 4.5rem; margin-bottom: 1rem; display: block; }
        .empty-state h2 {
            font-family: var(--font-display);
            color: var(--text-dark);
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        /* ─── Animations ─────────────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp 0.55s cubic-bezier(0.16,1,0.3,1) forwards;
            opacity: 0;
        }
        .d1 { animation-delay: 0.08s; }
        .d2 { animation-delay: 0.16s; }
        .d3 { animation-delay: 0.24s; }
        .d4 { animation-delay: 0.32s; }
        .d5 { animation-delay: 0.40s; }

        .hidden { display: none !important; }

        /* ─── Responsive ─────────────────────────────────────────── */
        @media (max-width: 600px) {
            .container { padding: 0 14px 40px; }
            .topbar     { padding: 12px 16px; }
            .topbar-brand h1 { font-size: 1.25rem; }
            .summary-grid { grid-template-columns: 1fr; }
            .banner-card  { flex-direction: column; align-items: flex-start; }
            .sky-scene::before { width: 55px; height: 55px; right: 16px; top: 14px; }
        }
    </style>
</head>
<body>

    {{-- ── Decorative sky scene ──────────────────────────────────── --}}
    <div class="sky-scene" aria-hidden="true">
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
    </div>

    <div class="mountains" aria-hidden="true">
        <svg viewBox="0 0 1440 160" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <polygon points="0,160 200,30 400,160" fill="#2e7d32" opacity="0.85"/>
            <polygon points="180,160 430,15 680,160" fill="#388e3c"/>
            <polygon points="600,160 820,40 1040,160" fill="#2e7d32" opacity="0.85"/>
            <polygon points="900,160 1140,25 1380,160" fill="#388e3c"/>
            <polygon points="1200,160 1350,55 1500,160" fill="#2e7d32" opacity="0.75"/>
        </svg>
    </div>

    <div class="trees" aria-hidden="true">
        @foreach([
            ['h'=>52,'w'=>38,'th'=>6,'tw'=>8],
            ['h'=>38,'w'=>28,'th'=>5,'tw'=>6],
            ['h'=>60,'w'=>44,'th'=>7,'tw'=>10],
            ['h'=>44,'w'=>32,'th'=>5,'tw'=>8],
            ['h'=>50,'w'=>36,'th'=>6,'tw'=>8],
            ['h'=>36,'w'=>26,'th'=>4,'tw'=>6],
            ['h'=>55,'w'=>40,'th'=>7,'tw'=>9],
        ] as $t)
        <div class="tree">
            <div class="tree-top" style="width:{{$t['w']}}px;height:{{$t['h']}}px;"></div>
            <div class="tree-trunk" style="width:{{$t['tw']}}px;height:{{$t['th']}}px;"></div>
        </div>
        @endforeach
    </div>

    {{-- ── Page Wrapper ──────────────────────────────────────────── --}}
    <div class="page-wrap">

        {{-- Topbar --}}
        <header class="topbar">
            <div class="topbar-brand" style="display: flex; align-items: center; gap: 12px;">
                <img src="{{ asset('images/Logo_pinteria (1).png') }}" alt="Pinteria Logo" style="height: 48px; width: auto; object-fit: contain;">
                <h1 style="font-size: 1.4rem; color: var(--text-dark); margin: 0;">Dashboard Orang Tua</h1>
            </div>
            <div class="topbar-right">
                <div class="avatar" aria-label="Avatar {{ $user->name }}">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <span class="user-name">{{ $user->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Keluar 🚪</button>
                </form>
            </div>
        </header>

        <div class="container">

            @if($children->isEmpty())
                {{-- Empty state --}}
                <div class="empty-state animate-in d1">
                    <span class="emoji">👶</span>
                    <h2>Belum ada data anak</h2>
                    <p>Tambahkan anak Anda untuk mulai memantau progress petualangan belajar mereka.</p>
                </div>

            @else
                {{-- Aggregate stats --}}
                @php
                    $totalLessons = $children->sum(fn($c) => $c->lessonCompletions->count());
                    $avgScore     = $children->flatMap(fn($c) => $c->quizResults)->avg('skor') ?? 0;
                    $totalMinutes = round($children->flatMap(fn($c) => $c->studySessions)->sum('durasi_detik') / 60, 1);
                @endphp

                {{-- Summary cards --}}
                <div class="summary-grid">
                    <div class="summary-card animate-in d1" data-emoji="📚">
                        <span class="s-icon">📚</span>
                        <div class="s-label">Total Lesson Selesai</div>
                        <div class="s-value">{{ $totalLessons }}</div>
                    </div>
                    <div class="summary-card animate-in d2" data-emoji="🏆">
                        <span class="s-icon">🏆</span>
                        <div class="s-label">Rata-rata Skor Kuis</div>
                        <div class="s-value">{{ round($avgScore, 1) }}</div>
                    </div>
                    <div class="summary-card animate-in d3" data-emoji="⏱️">
                        <span class="s-icon">⏱️</span>
                        <div class="s-label">Waktu Belajar (menit)</div>
                        <div class="s-value">{{ $totalMinutes }}</div>
                    </div>
                </div>

                {{-- Child selector tabs --}}
                <p class="section-heading animate-in d3">🧒 Pilih Petualang</p>
                <div class="child-tabs animate-in d3" role="tablist" aria-label="Pilih anak">
                    @foreach($children as $index => $child)
                        <button
                            class="child-tab {{ $index === 0 ? 'active' : '' }}"
                            role="tab"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="chart-panel-{{ $child->id }}"
                            id="tab-child-{{ $child->id }}"
                            onclick="selectChild({{ $index }})"
                        >
                            <span class="tab-icon" style="display: inline-flex; align-items: center; justify-content: center; margin-bottom: 6px;">
                                @if($child->isAudioWorld())
                                    <!-- Cute Owl with Headphones SVG -->
                                    <svg width="42" height="42" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                        <path d="M8,26 Q5,23 9,20" stroke="#4FC3F7" stroke-width="2" stroke-linecap="round" fill="none" />
                                        <path d="M56,26 Q59,23 55,20" stroke="#4FC3F7" stroke-width="2" stroke-linecap="round" fill="none" />
                                    </svg>
                                @else
                                    <!-- Cute Parrot Mascot SVG (Sinar the Parrot) -->
                                    <svg width="42" height="42" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                @endif
                            </span>
                            <span>{{ $child->nama_panggilan ?? 'Anak '.($index + 1) }}</span>
                            <span class="tab-badge">{{ $child->jenis_disabilitas === 'tunanetra' ? 'Audio' : 'Visual' }}</span>
                        </button>
                    @endforeach
                </div>

                {{-- Per-child chart panels --}}
                @foreach($children as $index => $child)
                    @php
                        $startDate = now()->subDays(29)->startOfDay();

                        $lessonsRaw = $child->lessonCompletions
                            ->filter(fn($lc) => $lc->completed_at >= $startDate)
                            ->groupBy(fn($lc) => $lc->completed_at->format('Y-m-d'));

                        $quizRaw = $child->quizResults
                            ->filter(fn($qr) => $qr->created_at >= $startDate)
                            ->groupBy(fn($qr) => $qr->created_at->format('Y-m-d'));

                        $sessionRaw = $child->studySessions
                            ->filter(fn($ss) => $ss->started_at >= $startDate)
                            ->groupBy(fn($ss) => $ss->started_at->format('Y-m-d'));

                        $chartLabels = [];
                        $lessonsData = [];
                        $scoresData  = [];
                        $studyData   = [];

                        for ($i = 0; $i < 30; $i++) {
                            $date           = now()->subDays(29 - $i)->format('Y-m-d');
                            $chartLabels[]  = \Carbon\Carbon::parse($date)->translatedFormat('d M');
                            $lessonsData[]  = isset($lessonsRaw[$date]) ? $lessonsRaw[$date]->count() : 0;
                            $scoresData[]   = isset($quizRaw[$date])    ? round($quizRaw[$date]->avg('skor'), 1) : 0;
                            $studyData[]    = isset($sessionRaw[$date]) ? round($sessionRaw[$date]->sum('durasi_detik') / 60, 1) : 0;
                        }
                    @endphp

                    <div
                        class="charts-grid animate-in d4 {{ $index > 0 ? 'hidden' : '' }}"
                        id="chart-panel-{{ $child->id }}"
                        role="tabpanel"
                        aria-labelledby="tab-child-{{ $child->id }}"
                    >
                        {{-- Start banner --}}
                        <div class="chart-card chart-card--full banner-card">
                            <div>
                                <h3>🎮 Mulai Petualangan!</h3>
                                <p>Temani {{ $child->nama_panggilan ?? 'Anak' }} belajar hari ini</p>
                            </div>
                            <a
                                href="{{ $child->isAudioWorld() ? route('play.tunanetra', $child->id) : route('play.tunarungu', $child->id) }}"
                                class="btn-play"
                            >
                                Main Sekarang 🚀
                            </a>
                        </div>

                        {{-- Gamification Badges --}}
                        <div class="chart-card animate-in d4">
                            <h3>🏆 Badge Pencapaian</h3>
                            <div class="badges-row" style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 12px;">
                                @forelse($child->badges as $badge)
                                    <div class="badge-item" title="{{ $badge->deskripsi }}" style="text-align: center; background: rgba(76,175,80,0.06); padding: 12px; border-radius: 12px; border: 1px solid rgba(76,175,80,0.15); min-width: 90px; flex: 1;">
                                        <div style="font-size: 32px; margin-bottom: 4px;">{{ $badge->ikon }}</div>
                                        <div style="font-size: 13px; font-weight: bold; color: var(--text-dark);">{{ $badge->nama }}</div>
                                    </div>
                                @empty
                                    <p style="color: #666; font-size: 14px; margin-top: 8px;">Belum ada badge yang diraih. Selesaikan kuis untuk meraih lencana pertama!</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Rekomendasi Belajar --}}
                        <div class="chart-card animate-in d4">
                            <h3>🚀 Rekomendasi Belajar</h3>
                            <div class="rec-list" style="display: flex; flex-direction: column; gap: 10px; margin-top: 12px;">
                                @forelse($child->rekomendasi as $rec)
                                    <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(41,182,246,0.06); padding: 12px; border-radius: 12px; border: 1px solid rgba(41,182,246,0.15);">
                                        <div>
                                            <strong style="color: var(--text-dark); display: block;">{{ $rec->judul }}</strong>
                                            <span style="font-size: 12px; color: #666;">Kategori: {{ $rec->kategori ?? 'Literasi' }} (Usia {{ $rec->kategori_usia }} th)</span>
                                        </div>
                                        <span style="display: inline-flex; align-items: center; justify-content: center;">
                                            @if($rec->tipe_dunia === 'audio')
                                                <!-- Cute Owl SVG for recommendation -->
                                                <svg width="32" height="32" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                                    <path d="M8,26 Q5,23 9,20" stroke="#4FC3F7" stroke-width="2" stroke-linecap="round" fill="none" />
                                                    <path d="M56,26 Q59,23 55,20" stroke="#4FC3F7" stroke-width="2" stroke-linecap="round" fill="none" />
                                                </svg>
                                            @else
                                                <!-- Cute Parrot Mascot SVG for recommendation -->
                                                <svg width="32" height="32" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                            @endif
                                        </span>
                                    </div>
                                @empty
                                    <div style="background: rgba(76,175,80,0.06); padding: 12px; border-radius: 12px; border: 1px solid rgba(76,175,80,0.15); text-align: center; padding: 20px 10px;">
                                        🎉 <strong>Hebat! Semua materi sudah selesai diselesaikan!</strong>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Hasil Kuis Terbaru --}}
                        <div class="chart-card chart-card--full animate-in d4">
                            <h3>📝 Hasil Kuis Terbaru</h3>
                            <div style="margin-top: 12px; overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid rgba(76,175,80,0.15); font-size: 14px; color: var(--text-mid);">
                                            <th style="padding: 8px;">Pelajaran</th>
                                            <th style="padding: 8px;">Pertanyaan</th>
                                            <th style="padding: 8px;">Hasil</th>
                                            <th style="padding: 8px;">Skor</th>
                                            <th style="padding: 8px;">Bintang</th>
                                            <th style="padding: 8px;">Durasi</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 14px;">
                                        @forelse($child->recentQuizzes as $result)
                                            <tr style="border-bottom: 1px solid rgba(76,175,80,0.08);">
                                                <td style="padding: 10px 8px; font-weight: bold; color: var(--text-dark);">{{ $result->lesson->judul }}</td>
                                                <td style="padding: 10px 8px;">{{ Str::limit($result->quizQuestion->pertanyaan, 40) }}</td>
                                                <td style="padding: 10px 8px;">
                                                    @if($result->benar)
                                                        <span style="color: #2e7d32; font-weight: bold;">✅ Benar</span>
                                                    @else
                                                        <span style="color: #ef5350; font-weight: bold;">❌ Salah</span>
                                                    @endif
                                                </td>
                                                <td style="padding: 10px 8px; font-weight: bold;">{{ $result->skor }}</td>
                                                <td style="padding: 10px 8px; color: #ffca28; font-size: 16px;">
                                                    {{ str_repeat('⭐', $result->bintang) }}
                                                </td>
                                                <td style="padding: 10px 8px; color: #666;">{{ $result->waktu_detik }} detik</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" style="padding: 20px; text-align: center; color: #888;">Belum ada hasil kuis yang tercatat. Selesaikan kuis pertama sekarang!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Progress per kategori (DINAMIS dari DB) --}}
                        <div class="chart-card animate-in d4">
                            <h3><span class="dot" style="background:#29b6f6"></span> Progress Kategori</h3>
                            <div class="progress-list">
                                @php $pfColors = ['pf-1','pf-2','pf-3','pf-1','pf-2']; @endphp
                                @forelse($child->progressKategori as $idx => $katData)
                                    <div>
                                        <div class="progress-label">
                                            <span>{{ $katData['nama'] }}</span>
                                            <span>{{ $katData['persen'] }}%</span>
                                        </div>
                                        <div class="progress-track">
                                            <div class="progress-fill {{ $pfColors[$idx % count($pfColors)] }}"
                                                 style="width:{{ $katData['persen'] }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <p style="color:#888;font-size:14px;text-align:center;padding:16px 0;">
                                        Belum ada kategori pembelajaran yang tersedia.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Lessons bar chart --}}
                        <div class="chart-card animate-in d4">
                            <h3><span class="dot" style="background:#29b6f6"></span> Lesson Selesai per Hari</h3>
                            <canvas id="lessonsChart-{{ $child->id }}" aria-label="Grafik lesson selesai untuk {{ $child->nama_panggilan ?? 'Anak' }}"></canvas>
                        </div>

                        {{-- Quiz scores line chart --}}
                        <div class="chart-card animate-in d5">
                            <h3><span class="dot" style="background:#43a047"></span> Rata-rata Skor Kuis</h3>
                            <canvas id="scoresChart-{{ $child->id }}" aria-label="Grafik skor kuis untuk {{ $child->nama_panggilan ?? 'Anak' }}"></canvas>
                        </div>

                        {{-- Study time area chart --}}
                        <div class="chart-card chart-card--full animate-in d5">
                            <h3><span class="dot" style="background:#ff7043"></span> Waktu Belajar per Hari (menit)</h3>
                            <canvas id="studyChart-{{ $child->id }}" aria-label="Grafik waktu belajar untuk {{ $child->nama_panggilan ?? 'Anak' }}"></canvas>
                        </div>

                    </div>
                @endforeach

            @endif
        </div>{{-- /.container --}}
    </div>{{-- /.page-wrap --}}

    @if(!$children->isEmpty())
    <script>
        // ── Chart.js Global Defaults ──────────────────────────────────
        Chart.defaults.color       = '#81c784';
        Chart.defaults.borderColor = 'rgba(76,175,80,0.1)';
        Chart.defaults.font.family = "'Nunito', sans-serif";
        Chart.defaults.font.weight = '700';

        const GRID = { color: 'rgba(76,175,80,0.1)' };
        const X_OPTS = { grid: { display: false }, ticks: { maxTicksLimit: 10, color: '#81c784' } };
        const Y_OPTS = (extra = {}) => ({ beginAtZero: true, grid: GRID, ticks: { color: '#81c784' }, ...extra });

        @foreach($children as $child)
        (function () {
            const labels      = @json($chartLabels);
            const lessonsData = @json($lessonsData);
            const scoresData  = @json($scoresData);
            const studyData   = @json($studyData);

            // Lessons — bar
            const lessonsEl = document.getElementById('lessonsChart-{{ $child->id }}');
            if (lessonsEl) {
                new Chart(lessonsEl, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Lesson Selesai',
                            data: lessonsData,
                            backgroundColor: 'rgba(41,182,246,0.55)',
                            borderColor: '#29b6f6',
                            borderWidth: 1,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: Y_OPTS({ ticks: { stepSize: 1, precision: 0, color: '#81c784' } }),
                            x: X_OPTS
                        }
                    }
                });
            }

            // Scores — line
            const scoresEl = document.getElementById('scoresChart-{{ $child->id }}');
            if (scoresEl) {
                new Chart(scoresEl, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Rata-rata Skor',
                            data: scoresData,
                            borderColor: '#43a047',
                            backgroundColor: 'rgba(67,160,71,0.12)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#43a047',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: Y_OPTS({ max: 100 }),
                            x: X_OPTS
                        }
                    }
                });
            }

            // Study time — area
            const studyEl = document.getElementById('studyChart-{{ $child->id }}');
            if (studyEl) {
                new Chart(studyEl, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Waktu Belajar (menit)',
                            data: studyData,
                            borderColor: '#ff7043',
                            backgroundColor: 'rgba(255,112,67,0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#ff7043',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: Y_OPTS(), x: X_OPTS }
                    }
                });
            }
        })();
        @endforeach

        // ── Tab switching ─────────────────────────────────────────────
        function selectChild(index) {
            const tabs   = document.querySelectorAll('.child-tab');
            const panels = document.querySelectorAll('.charts-grid');

            panels.forEach(el => el.classList.add('hidden'));
            tabs.forEach(tab => { tab.classList.remove('active'); tab.setAttribute('aria-selected', 'false'); });

            if (tabs[index])   { tabs[index].classList.add('active');   tabs[index].setAttribute('aria-selected', 'true'); }
            if (panels[index]) { panels[index].classList.remove('hidden'); }
        }
    </script>
    @endif

</body>
</html>