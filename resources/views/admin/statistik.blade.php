@extends('admin.layouts.app')

@section('page-title', 'Statistik')

@section('subnav')
    <a href="{{ route('admin.dashboard') }}" class="subnav__item">
        <i class="ti ti-home"></i> Ringkasan
    </a>
    <a href="{{ route('admin.dashboard.statistik') }}" class="subnav__item subnav__item--active">
        <i class="ti ti-chart-bar"></i> Statistik
    </a>
    <a href="{{ route('admin.quiz.monitoring') }}" class="subnav__item">
        <i class="ti ti-activity"></i> Aktivitas
    </a>
@endsection

@section('content')

{{-- ── Page Header ──────────────────────────────────────────── --}}
<div class="page-header">
    <div class="page-header__left">
        <h1>Statistik Platform</h1>
        <div class="page-header__breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <i class="ti ti-chevron-right"></i>
            <span>Statistik</span>
        </div>
    </div>
    <div class="page-header__actions">
        <span style="font-size:11px; color:var(--text-muted); padding:6px 12px; background:var(--bg-hover); border:1px solid var(--border); border-radius:var(--radius-md);">
            <i class="ti ti-clock"></i> Data real-time
        </span>
    </div>
</div>

{{-- ── Stats Overview ──────────────────────────────────────────── --}}
<div class="stats-grid" style="grid-template-columns: repeat(4,1fr);">
    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--teal"><i class="ti ti-book"></i></div>
        <div>
            <div class="stat-card__label">Materi Aktif</div>
            <div class="stat-card__value">{{ $stats['total_active_lessons'] }}</div>
            <div class="stat-card__sub">dari {{ $stats['total_lessons'] }} total</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--green"><i class="ti ti-circle-check"></i></div>
        <div>
            <div class="stat-card__label">Total Penyelesaian</div>
            <div class="stat-card__value">{{ number_format($stats['total_completions']) }}</div>
            <div class="stat-card__sub">lesson selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--blue"><i class="ti ti-target"></i></div>
        <div>
            <div class="stat-card__label">Tingkat Keberhasilan</div>
            <div class="stat-card__value">{{ $successRate }}%</div>
            <div class="stat-card__sub">dari {{ number_format($stats['total_quiz_results']) }} jawaban</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--orange"><i class="ti ti-users"></i></div>
        <div>
            <div class="stat-card__label">Total Anak</div>
            <div class="stat-card__value">{{ $stats['total_children'] }}</div>
            <div class="stat-card__sub">{{ $stats['total_tunanetra'] }} tunanetra · {{ $stats['total_tunarungu'] }} tunarungu</div>
        </div>
    </div>
</div>

{{-- ── Row 1: Chart 30 Hari ─────────────────────────────────────── --}}
<div class="card" style="margin-bottom:14px;">
    <div class="card__header">
        <h3 class="card__title">
            <i class="ti ti-chart-line"></i> Tren Aktivitas Belajar — 30 Hari Terakhir
        </h3>
        <div class="chart-legend">
            <span class="chart-legend__item">
                <span class="chart-legend__dot" style="background:var(--color-500)"></span>
                Lesson selesai
            </span>
            <span class="chart-legend__item">
                <span class="chart-legend__dot" style="background:var(--color-300)"></span>
                Quiz dijawab
            </span>
        </div>
    </div>
    <div class="card__body">
        <canvas id="trendChart" style="max-height:220px;"></canvas>
    </div>
</div>

{{-- ── Row 2: Distribusi Disabilitas + Usia ─────────────────────── --}}
<div class="grid-1-1">

    {{-- Distribusi Disabilitas --}}
    <div class="card">
        <div class="card__header">
            <h3 class="card__title"><i class="ti ti-users"></i> Distribusi Pengguna & Penyelesaian</h3>
        </div>
        <div class="card__body">
            @php
                $totalChildren = $stats['total_children'] ?: 1;
                $pctTunanetra  = round($stats['total_tunanetra'] / $totalChildren * 100);
                $pctTunarungu  = round($stats['total_tunarungu'] / $totalChildren * 100);
                $totalComp     = $stats['total_completions'] ?: 1;
                $compNeta  = $completionsByDisability->get('tunanetra', 0);
                $compRungu = $completionsByDisability->get('tunarungu', 0);
                $pctCompNeta  = round($compNeta / $totalComp * 100);
                $pctCompRungu = round($compRungu / $totalComp * 100);
            @endphp

            <div style="margin-bottom:20px;">
                <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:12px;">Profil Anak</div>
                <div class="dist-row">
                    <div class="dist-row__header">
                        <span class="dist-row__label">🎧 Tunanetra</span>
                        <span class="dist-row__value">{{ $stats['total_tunanetra'] }} <small style="font-size:12px; font-weight:400; color:var(--text-muted)">({{ $pctTunanetra }}%)</small></span>
                    </div>
                    <div class="dist-row__track">
                        <div class="dist-row__fill" style="width:{{ $pctTunanetra }}%; background:var(--color-500)"></div>
                    </div>
                </div>
                <div class="dist-row" style="margin-bottom:0;">
                    <div class="dist-row__header">
                        <span class="dist-row__label">👁️ Tunarungu</span>
                        <span class="dist-row__value">{{ $stats['total_tunarungu'] }} <small style="font-size:12px; font-weight:400; color:var(--text-muted)">({{ $pctTunarungu }}%)</small></span>
                    </div>
                    <div class="dist-row__track">
                        <div class="dist-row__fill" style="width:{{ $pctTunarungu }}%; background:var(--color-300)"></div>
                    </div>
                </div>
            </div>

            <div style="border-top:1px solid var(--border); padding-top:16px;">
                <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:12px;">Penyelesaian Materi</div>
                <div class="dist-row">
                    <div class="dist-row__header">
                        <span class="dist-row__label">🎧 Tunanetra</span>
                        <span class="dist-row__value">{{ number_format($compNeta) }} <small style="font-size:12px; font-weight:400; color:var(--text-muted)">({{ $pctCompNeta }}%)</small></span>
                    </div>
                    <div class="dist-row__track">
                        <div class="dist-row__fill" style="width:{{ $pctCompNeta }}%; background:var(--color-500)"></div>
                    </div>
                </div>
                <div class="dist-row" style="margin-bottom:0;">
                    <div class="dist-row__header">
                        <span class="dist-row__label">👁️ Tunarungu</span>
                        <span class="dist-row__value">{{ number_format($compRungu) }} <small style="font-size:12px; font-weight:400; color:var(--text-muted)">({{ $pctCompRungu }}%)</small></span>
                    </div>
                    <div class="dist-row__track">
                        <div class="dist-row__fill" style="width:{{ $pctCompRungu }}%; background:var(--color-300)"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Distribusi Usia & Tipe Materi --}}
    <div class="card">
        <div class="card__header">
            <h3 class="card__title"><i class="ti ti-chart-donut"></i> Distribusi Materi & Usia</h3>
        </div>
        <div class="card__body">
            @php
                $totalLessons = $stats['total_lessons'] ?: 1;
                $audio  = $lessonsByWorld->get('audio', 0);
                $visual = $lessonsByWorld->get('visual', 0);
                $age57  = $lessonsByAge->get('5-7', 0);
                $age810 = $lessonsByAge->get('8-10', 0);
                $compAge57  = $completionsByAge->get('5-7', 0);
                $compAge810 = $completionsByAge->get('8-10', 0);
                $totalCompAge = ($compAge57 + $compAge810) ?: 1;
            @endphp

            <div style="margin-bottom:20px;">
                <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:12px;">Tipe Dunia</div>
                <div class="dist-row">
                    <div class="dist-row__header">
                        <span class="dist-row__label">🎧 Audio (Tunanetra)</span>
                        <span class="dist-row__value">{{ $audio }}</span>
                    </div>
                    <div class="dist-row__track">
                        <div class="dist-row__fill" style="width:{{ round($audio/$totalLessons*100) }}%; background:var(--color-500)"></div>
                    </div>
                </div>
                <div class="dist-row" style="margin-bottom:0;">
                    <div class="dist-row__header">
                        <span class="dist-row__label">👁️ Visual (Tunarungu)</span>
                        <span class="dist-row__value">{{ $visual }}</span>
                    </div>
                    <div class="dist-row__track">
                        <div class="dist-row__fill" style="width:{{ round($visual/$totalLessons*100) }}%; background:var(--color-200)"></div>
                    </div>
                </div>
            </div>

            <div style="border-top:1px solid var(--border); padding-top:16px;">
                <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:12px;">Kelompok Usia</div>
                <div class="age-grid">
                    <div class="age-pill">
                        <div class="age-pill__number" style="color:var(--color-600)">{{ $age57 }}</div>
                        <div class="age-pill__label">Materi 5–7 Thn</div>
                    </div>
                    <div class="age-pill">
                        <div class="age-pill__number" style="color:var(--blue)">{{ $age810 }}</div>
                        <div class="age-pill__label">Materi 8–10 Thn</div>
                    </div>
                    <div class="age-pill">
                        <div class="age-pill__number" style="color:var(--color-500)">{{ number_format($compAge57) }}</div>
                        <div class="age-pill__label">Selesai 5–7 Thn</div>
                    </div>
                    <div class="age-pill">
                        <div class="age-pill__number" style="color:var(--blue)">{{ number_format($compAge810) }}</div>
                        <div class="age-pill__label">Selesai 8–10 Thn</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Row 3: Quiz per Kategori ─────────────────────────────────── --}}
<div class="card" style="margin-bottom:14px;">
    <div class="card__header">
        <h3 class="card__title"><i class="ti ti-target"></i> Tingkat Keberhasilan Quiz per Kategori</h3>
        <a href="{{ route('admin.categories.index') }}" class="card__link">Kelola Kategori →</a>
    </div>
    <div class="card__body--flush">
        @forelse($categoryStats as $cat)
        @if($cat->quiz_total > 0)
        <div style="display:flex; align-items:center; gap:14px; padding:12px 16px; border-bottom:1px solid #f9fafb;">
            <div style="width:36px; height:36px; border-radius:var(--radius-md); background:{{ $cat->warna }}20; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0;">
                {{ $cat->ikon }}
            </div>
            <div style="flex:1; min-width:0;">
                <div style="font-size:12px; font-weight:600; color:var(--text-primary); margin-bottom:5px;">{{ $cat->nama }}</div>
                <div style="height:5px; background:var(--bg-page); border-radius:20px; overflow:hidden;">
                    <div style="height:100%; width:{{ $cat->quiz_rate }}%; border-radius:20px;
                        background:{{ $cat->quiz_rate >= 70 ? 'var(--color-500)' : ($cat->quiz_rate >= 40 ? 'var(--yellow)' : 'var(--red)') }};
                        transition:width 0.6s ease;"></div>
                </div>
            </div>
            <div style="text-align:right; flex-shrink:0; min-width:80px;">
                <div style="font-size:16px; font-weight:700; color:{{ $cat->quiz_rate >= 70 ? 'var(--color-600)' : ($cat->quiz_rate >= 40 ? '#d97706' : 'var(--red)') }}">
                    {{ $cat->quiz_rate }}%
                </div>
                <div style="font-size:10px; color:var(--text-muted);">{{ $cat->quiz_correct }}/{{ $cat->quiz_total }} benar</div>
            </div>
        </div>
        @endif
        @empty
        <div class="empty-state" style="padding:30px;">
            <i class="ti ti-chart-off"></i>
            <div class="empty-state__title">Belum ada data quiz</div>
        </div>
        @endforelse

        @if($categoryStats->where('quiz_total', 0)->count() > 0)
        <div style="padding:10px 16px; background:var(--bg-hover); border-top:1px solid var(--border);">
            <span style="font-size:11px; color:var(--text-muted);">
                <i class="ti ti-info-circle"></i>
                {{ $categoryStats->where('quiz_total', 0)->count() }} kategori belum memiliki data quiz
            </span>
        </div>
        @endif
    </div>
</div>

{{-- ── Row 4: Top & Bottom Lessons ─────────────────────────────── --}}
<div class="grid-1-1">

    {{-- Top 5 Materi --}}
    <div class="card">
        <div class="card__header">
            <h3 class="card__title"><i class="ti ti-trending-up"></i> Materi Paling Sering Diselesaikan</h3>
            <a href="{{ route('admin.lessons.index') }}" class="card__link">Lihat semua →</a>
        </div>
        @forelse($topLessons as $idx => $lesson)
        <div class="lesson-list-row">
            <div class="lesson-list-row__rank {{ $idx === 0 ? 'lesson-list-row__rank--top' : '' }}">{{ $idx + 1 }}</div>
            <div class="lesson-list-row__info">
                <div class="lesson-list-row__title">{{ $lesson->judul }}</div>
                <div class="lesson-list-row__meta">
                    @if($lesson->category) {{ $lesson->category->ikon }} {{ $lesson->category->nama }} · @endif
                    {{ $lesson->tipe_dunia === 'audio' ? '🎧 Audio' : '👁️ Visual' }}
                </div>
            </div>
            <div class="lesson-list-row__count">
                <div class="lesson-list-row__number">{{ $lesson->completions_count }}</div>
                <div class="lesson-list-row__number-label">selesai</div>
            </div>
        </div>
        @empty
        <div class="empty-state"><i class="ti ti-book-off"></i><div class="empty-state__title">Belum ada data</div></div>
        @endforelse
    </div>

    {{-- Bottom 5 Materi --}}
    <div class="card">
        <div class="card__header">
            <h3 class="card__title" style="color:var(--red)"><i class="ti ti-trending-down"></i> Materi Paling Sedikit Diselesaikan</h3>
            <span style="font-size:11px; background:var(--red-light); color:var(--red); padding:3px 8px; border-radius:20px; font-weight:600;">Perlu perhatian</span>
        </div>
        @forelse($bottomLessons as $idx => $lesson)
        <div class="lesson-list-row">
            <div class="lesson-list-row__rank">{{ $idx + 1 }}</div>
            <div class="lesson-list-row__info">
                <div class="lesson-list-row__title">{{ $lesson->judul }}</div>
                <div class="lesson-list-row__meta">
                    @if($lesson->category) {{ $lesson->category->ikon }} {{ $lesson->category->nama }} · @endif
                    {{ $lesson->tipe_dunia === 'audio' ? '🎧 Audio' : '👁️ Visual' }}
                </div>
            </div>
            <div class="lesson-list-row__count">
                <div class="lesson-list-row__number" style="color:var(--red)">{{ $lesson->completions_count }}</div>
                <div class="lesson-list-row__number-label">selesai</div>
            </div>
        </div>
        @empty
        <div class="empty-state"><i class="ti ti-book-off"></i><div class="empty-state__title">Belum ada data</div></div>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('trendChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chart30Labels),
            datasets: [
                {
                    label: 'Lesson Selesai',
                    data: @json($chart30Completions),
                    borderColor: '#65C837',
                    backgroundColor: 'rgba(101,200,55,0.08)',
                    borderWidth: 2,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Quiz Dijawab',
                    data: @json($chart30Quizzes),
                    borderColor: '#9CDC7F',
                    backgroundColor: 'rgba(156,220,127,0.05)',
                    borderWidth: 2,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label}: ${ctx.raw}`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { family: 'Inter', size: 11 } },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Inter', size: 10 },
                        maxTicksLimit: 10,
                        maxRotation: 0,
                    }
                }
            }
        }
    });
}
</script>
@endpush
