<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Dashboard Progress Orang Tua - Skill Bridge. Pantau kemajuan belajar anak Anda.">
    <title>Dashboard Orang Tua — Skill Bridge</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <style>
        /* ─── Design System ─────────────────────────────────────────── */
        :root {
            --bg-primary: #0f1117;
            --bg-card: #1a1d28;
            --bg-card-hover: #222636;
            --border-subtle: rgba(255,255,255,0.06);
            --border-accent: rgba(99,102,241,0.3);
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --accent-indigo: #818cf8;
            --accent-violet: #a78bfa;
            --accent-emerald: #34d399;
            --accent-amber: #fbbf24;
            --accent-rose: #fb7185;
            --gradient-primary: linear-gradient(135deg, #818cf8, #a78bfa);
            --gradient-emerald: linear-gradient(135deg, #34d399, #6ee7b7);
            --gradient-amber: linear-gradient(135deg, #fbbf24, #fcd34d);
            --gradient-rose: linear-gradient(135deg, #fb7185, #fda4af);
            --radius: 16px;
            --radius-sm: 12px;
            --shadow-card: 0 4px 24px rgba(0,0,0,0.25), 0 0 0 1px var(--border-subtle);
            --shadow-glow: 0 0 40px rgba(129,140,248,0.08);
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, system-ui, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── Animated BG ───────────────────────────────────────────── */
        .bg-gradient {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }
        .bg-gradient::before,
        .bg-gradient::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.12;
            animation: float 20s infinite ease-in-out;
        }
        .bg-gradient::before {
            width: 600px; height: 600px;
            background: var(--accent-indigo);
            top: -200px; left: -100px;
        }
        .bg-gradient::after {
            width: 500px; height: 500px;
            background: var(--accent-violet);
            bottom: -200px; right: -100px;
            animation-delay: -10s;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40px, -30px) scale(1.05); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }

        /* ─── Layout ────────────────────────────────────────────────── */
        .container {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* ─── Header ────────────────────────────────────────────────── */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .header-left h1 {
            font-size: 1.75rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }
        .header-left p {
            color: var(--text-secondary);
            margin-top: 0.25rem;
            font-size: 0.95rem;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1rem;
            color: #fff;
            box-shadow: 0 0 0 3px var(--bg-primary), 0 0 0 5px rgba(129,140,248,0.3);
        }
        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
        }
        .logout-btn {
            background: rgba(251, 113, 133, 0.12);
            border: 1px solid rgba(251, 113, 133, 0.2);
            color: var(--accent-rose);
            padding: 0.4rem 0.75rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-family: inherit;
            margin-left: 0.5rem;
            text-decoration: none;
            outline: none;
        }
        .logout-btn:hover {
            background: var(--accent-rose);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(251, 113, 133, 0.25);
        }

        /* ─── Summary Cards ─────────────────────────────────────────── */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2.5rem;
        }
        .summary-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-card);
            transition: transform var(--transition), box-shadow var(--transition);
            position: relative;
            overflow: hidden;
        }
        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-card), var(--shadow-glow);
        }
        .summary-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .summary-card:nth-child(1)::before { background: var(--gradient-primary); }
        .summary-card:nth-child(2)::before { background: var(--gradient-emerald); }
        .summary-card:nth-child(3)::before { background: var(--gradient-amber); }
        .summary-card .icon {
            width: 48px; height: 48px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .summary-card:nth-child(1) .icon { background: rgba(129,140,248,0.15); }
        .summary-card:nth-child(2) .icon { background: rgba(52,211,153,0.15); }
        .summary-card:nth-child(3) .icon { background: rgba(251,191,36,0.15); }
        .summary-card .label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }
        .summary-card .value {
            font-size: 2rem;
            font-weight: 800;
            margin-top: 0.25rem;
            letter-spacing: -0.03em;
        }
        .summary-card:nth-child(1) .value { color: var(--accent-indigo); }
        .summary-card:nth-child(2) .value { color: var(--accent-emerald); }
        .summary-card:nth-child(3) .value { color: var(--accent-amber); }

        /* ─── Child Selector Tabs ───────────────────────────────────── */
        .child-tabs {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 2rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            scrollbar-width: thin;
            scrollbar-color: var(--border-subtle) transparent;
        }
        .child-tab {
            min-width: 80px;
            min-height: 80px;
            padding: 1rem 1.5rem;
            background: var(--bg-card);
            border: 2px solid var(--border-subtle);
            border-radius: var(--radius);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all var(--transition);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            white-space: nowrap;
            font-family: inherit;
        }
        .child-tab:hover {
            border-color: var(--accent-indigo);
            color: var(--text-primary);
            background: var(--bg-card-hover);
        }
        .child-tab.active {
            border-color: var(--accent-indigo);
            background: rgba(129,140,248,0.08);
            color: var(--accent-indigo);
            box-shadow: 0 0 20px rgba(129,140,248,0.1);
        }
        .child-tab .tab-icon {
            font-size: 1.5rem;
        }
        .child-tab .tab-badge {
            font-size: 0.7rem;
            padding: 0.15rem 0.5rem;
            border-radius: 20px;
            background: rgba(129,140,248,0.15);
            color: var(--accent-indigo);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ─── Chart Section ─────────────────────────────────────────── */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .charts-grid { grid-template-columns: 1fr 1fr; }
            .charts-grid .chart-card:last-child {
                grid-column: 1 / -1;
            }
        }
        .chart-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-card);
            transition: transform var(--transition);
        }
        .chart-card:hover {
            transform: translateY(-2px);
        }
        .chart-card h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .chart-card h3 .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        .chart-card canvas {
            max-height: 280px;
        }

        /* ─── Empty State ───────────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }
        .empty-state .emoji {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .empty-state h2 {
            color: var(--text-secondary);
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        /* ─── Responsive ────────────────────────────────────────────── */
        @media (max-width: 640px) {
            .container { padding: 1rem; }
            .header-left h1 { font-size: 1.35rem; }
            .summary-grid { grid-template-columns: 1fr; }
            .charts-grid { grid-template-columns: 1fr; }
        }

        /* ─── Animations ────────────────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
    </style>
</head>
<body>
    <div class="bg-gradient"></div>

    <div class="container">
        <!-- Header -->
        <header class="header animate-in">
            <div class="header-left">
                <h1>📊 Dashboard Progress</h1>
                <p>Pantau kemajuan belajar anak Anda</p>
            </div>
            <div class="header-right">
                <div class="avatar" aria-label="Avatar pengguna">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <span class="user-name">{{ $user->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn" title="Keluar">
                        <span>Keluar</span> 🚪
                    </button>
                </form>
            </div>
        </header>

        @if($children->isEmpty())
            <div class="empty-state animate-in delay-1">
                <div class="emoji">👶</div>
                <h2>Belum ada data anak</h2>
                <p>Tambahkan anak Anda melalui API untuk mulai memantau progress.</p>
            </div>
        @else
            <!-- Summary Cards (aggregate for all children) -->
            @php
                $totalLessons = $children->sum(fn($c) => $c->lessonCompletions->count());
                $avgScore = $children->flatMap(fn($c) => $c->quizResults)->avg('skor') ?? 0;
                $totalMinutes = round($children->flatMap(fn($c) => $c->studySessions)->sum('durasi_detik') / 60, 1);
            @endphp

            <div class="summary-grid">
                <div class="summary-card animate-in delay-1" id="card-lessons">
                    <div class="icon">📚</div>
                    <div class="label">Total Lesson Selesai</div>
                    <div class="value">{{ $totalLessons }}</div>
                </div>
                <div class="summary-card animate-in delay-2" id="card-score">
                    <div class="icon">🏆</div>
                    <div class="label">Rata-rata Skor Kuis</div>
                    <div class="value">{{ round($avgScore, 1) }}</div>
                </div>
                <div class="summary-card animate-in delay-3" id="card-time">
                    <div class="icon">⏱️</div>
                    <div class="label">Waktu Belajar (menit)</div>
                    <div class="value">{{ $totalMinutes }}</div>
                </div>
            </div>

            <!-- Child Selector Tabs -->
            <div class="child-tabs animate-in delay-3" role="tablist" aria-label="Pilih anak">
                @foreach($children as $index => $child)
                    <button
                        class="child-tab {{ $index === 0 ? 'active' : '' }}"
                        role="tab"
                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                        aria-controls="chart-panel-{{ $child->id }}"
                        id="tab-child-{{ $child->id }}"
                        data-child-id="{{ $child->id }}"
                        data-child-index="{{ $index }}"
                        onclick="selectChild({{ $index }})"
                    >
                        <span class="tab-icon">{{ $child->isAudioWorld() ? '🔊' : '👁️' }}</span>
                        <span>{{ $child->nama_panggilan ?? 'Anak '.($index+1) }}</span>
                        <span class="tab-badge">{{ $child->jenis_disabilitas === 'tunanetra' ? 'Audio' : 'Visual' }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Charts -->
            @foreach($children as $index => $child)
                @php
                    $startDate = now()->subDays(29)->startOfDay();

                    // Lessons per day
                    $lessonsRaw = $child->lessonCompletions
                        ->filter(fn($lc) => $lc->completed_at >= $startDate)
                        ->groupBy(fn($lc) => $lc->completed_at->format('Y-m-d'));

                    // Quiz scores per day
                    $quizRaw = $child->quizResults
                        ->filter(fn($qr) => $qr->created_at >= $startDate)
                        ->groupBy(fn($qr) => $qr->created_at->format('Y-m-d'));

                    // Study time per day
                    $sessionRaw = $child->studySessions
                        ->filter(fn($ss) => $ss->started_at >= $startDate)
                        ->groupBy(fn($ss) => $ss->started_at->format('Y-m-d'));

                    $labels = [];
                    $lessonsData = [];
                    $scoresData = [];
                    $studyData = [];

                    for ($i = 0; $i < 30; $i++) {
                        $date = now()->subDays(29 - $i)->format('Y-m-d');
                        $labels[] = \Carbon\Carbon::parse($date)->format('d M');
                        $lessonsData[] = isset($lessonsRaw[$date]) ? $lessonsRaw[$date]->count() : 0;
                        $scoresData[] = isset($quizRaw[$date]) ? round($quizRaw[$date]->avg('skor'), 1) : 0;
                        $studyData[] = isset($sessionRaw[$date]) ? round($sessionRaw[$date]->sum('durasi_detik') / 60, 1) : 0;
                    }
                @endphp

                <div class="charts-grid {{ $index === 0 ? '' : 'hidden' }}" id="chart-panel-{{ $child->id }}" role="tabpanel" aria-labelledby="tab-child-{{ $child->id }}">
                    <!-- Lessons Completed -->
                    <div class="chart-card animate-in delay-4">
                        <h3><span class="dot" style="background:var(--accent-indigo)"></span> Lesson Selesai per Hari</h3>
                        <canvas id="lessonsChart-{{ $child->id }}" aria-label="Grafik jumlah lesson selesai per hari untuk {{ $child->nama_panggilan ?? 'Anak' }}"></canvas>
                    </div>

                    <!-- Quiz Scores -->
                    <div class="chart-card animate-in delay-4">
                        <h3><span class="dot" style="background:var(--accent-emerald)"></span> Rata-rata Skor Kuis</h3>
                        <canvas id="scoresChart-{{ $child->id }}" aria-label="Grafik rata-rata skor kuis per hari untuk {{ $child->nama_panggilan ?? 'Anak' }}"></canvas>
                    </div>

                    <!-- Study Time -->
                    <div class="chart-card animate-in delay-5">
                        <h3><span class="dot" style="background:var(--accent-amber)"></span> Waktu Belajar per Hari (menit)</h3>
                        <canvas id="studyChart-{{ $child->id }}" aria-label="Grafik waktu belajar per hari untuk {{ $child->nama_panggilan ?? 'Anak' }}"></canvas>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @if(!$children->isEmpty())
    <script>
        // ─── Chart.js Global Config ───────────────────────────────────
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = 'rgba(255,255,255,0.06)';
        Chart.defaults.font.family = "'Inter', sans-serif";

        const chartInstances = {};

        // ─── Build Charts Per Child ───────────────────────────────────
        @foreach($children as $index => $child)
        (function() {
            const labels = @json($labels);
            const lessonsData = @json($lessonsData);
            const scoresData = @json($scoresData);
            const studyData = @json($studyData);

            // Lessons bar chart
            const lessonsCtx = document.getElementById('lessonsChart-{{ $child->id }}');
            if (lessonsCtx) {
                chartInstances['lessons-{{ $child->id }}'] = new Chart(lessonsCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Lesson Selesai',
                            data: lessonsData,
                            backgroundColor: 'rgba(129,140,248,0.6)',
                            borderColor: 'rgba(129,140,248,1)',
                            borderWidth: 1,
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, precision: 0 },
                                grid: { color: 'rgba(255,255,255,0.04)' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { maxTicksLimit: 10 }
                            }
                        }
                    }
                });
            }

            // Scores line chart
            const scoresCtx = document.getElementById('scoresChart-{{ $child->id }}');
            if (scoresCtx) {
                chartInstances['scores-{{ $child->id }}'] = new Chart(scoresCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Rata-rata Skor',
                            data: scoresData,
                            borderColor: '#34d399',
                            backgroundColor: 'rgba(52,211,153,0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#34d399',
                            pointBorderColor: '#1a1d28',
                            pointBorderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: { color: 'rgba(255,255,255,0.04)' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { maxTicksLimit: 10 }
                            }
                        }
                    }
                });
            }

            // Study time area chart
            const studyCtx = document.getElementById('studyChart-{{ $child->id }}');
            if (studyCtx) {
                chartInstances['study-{{ $child->id }}'] = new Chart(studyCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Waktu Belajar (menit)',
                            data: studyData,
                            borderColor: '#fbbf24',
                            backgroundColor: 'rgba(251,191,36,0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#fbbf24',
                            pointBorderColor: '#1a1d28',
                            pointBorderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(255,255,255,0.04)' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { maxTicksLimit: 10 }
                            }
                        }
                    }
                });
            }
        })();
        @endforeach

        // ─── Tab Switching ────────────────────────────────────────────
        function selectChild(index) {
            // Hide all panels
            document.querySelectorAll('.charts-grid').forEach(el => {
                el.classList.add('hidden');
            });

            // Deactivate all tabs
            document.querySelectorAll('.child-tab').forEach(tab => {
                tab.classList.remove('active');
                tab.setAttribute('aria-selected', 'false');
            });

            // Show selected panel and activate tab
            const tabs = document.querySelectorAll('.child-tab');
            const panels = document.querySelectorAll('.charts-grid');

            if (tabs[index]) {
                tabs[index].classList.add('active');
                tabs[index].setAttribute('aria-selected', 'true');
            }
            if (panels[index]) {
                panels[index].classList.remove('hidden');
                // Animate in
                panels[index].querySelectorAll('.chart-card').forEach(card => {
                    card.style.animation = 'none';
                    card.offsetHeight; // force reflow
                    card.style.animation = '';
                });
            }
        }

        // ─── Utility: hidden class ────────────────────────────────────
        document.head.insertAdjacentHTML('beforeend', '<style>.hidden{display:none!important}</style>');
    </script>
    @endif
</body>
</html>
