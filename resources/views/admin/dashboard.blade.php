@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('subnav')
    <a href="{{ route('admin.dashboard') }}" class="subnav__item subnav__item--active">
        <i class="ti ti-home"></i> Ringkasan
    </a>
    <a href="{{ route('admin.dashboard.statistik') }}" class="subnav__item">
        <i class="ti ti-chart-bar"></i> Statistik
    </a>
    <a href="{{ route('admin.quiz.monitoring') }}" class="subnav__item">
        <i class="ti ti-activity"></i> Aktivitas
    </a>
@endsection

@section('content')

{{-- ── Welcome Banner ──────────────────────────────────────── --}}
<div class="welcome-banner">
    <div class="welcome-banner__text">
        <h2>Selamat datang, {{ auth()->user()->name }} 👋</h2>
        <p>Pantau dan kelola platform pembelajaran Pinteria untuk anak berkebutuhan khusus</p>
    </div>
    <div class="welcome-banner__actions">
        <a href="{{ route('admin.lessons.create') }}" class="btn btn--white">
            <i class="ti ti-plus"></i> Tambah Materi
        </a>
        <a href="{{ route('admin.quiz.monitoring') }}" class="btn btn--ghost">
            <i class="ti ti-chart-line"></i> Lihat Laporan
        </a>
    </div>
</div>

{{-- ── Stats Grid ───────────────────────────────────────────── --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--teal">
            <i class="ti ti-users"></i>
        </div>
        <div>
            <div class="stat-card__label">Total Pengguna</div>
            <div class="stat-card__value">{{ $stats['total_users'] }}</div>
            <div class="stat-card__sub">Terdaftar di platform</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--blue">
            <i class="ti ti-user-heart"></i>
        </div>
        <div>
            <div class="stat-card__label">Total Anak</div>
            <div class="stat-card__value">{{ $stats['total_children'] }}</div>
            <div class="stat-card__sub">Aktif belajar</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--orange">
            <i class="ti ti-book"></i>
        </div>
        <div>
            <div class="stat-card__label">Total Materi</div>
            <div class="stat-card__value">{{ $stats['total_lessons'] }}</div>
            <div class="stat-card__sub">Tersedia di platform</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--green">
            <i class="ti ti-circle-check"></i>
        </div>
        <div>
            <div class="stat-card__label">Penyelesaian</div>
            <div class="stat-card__value">{{ number_format($stats['total_completions']) }}</div>
            <div class="stat-card__sub">Lesson selesai</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--red">
            <i class="ti ti-question-mark"></i>
        </div>
        <div>
            <div class="stat-card__label">Soal Quiz</div>
            <div class="stat-card__value">{{ $stats['total_quizzes'] }}</div>
            <div class="stat-card__sub">Di semua materi</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--teal">
            <i class="ti ti-layout-grid"></i>
        </div>
        <div>
            <div class="stat-card__label">Kategori</div>
            <div class="stat-card__value">{{ $stats['total_categories'] }}</div>
            <div class="stat-card__sub">Topik belajar</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--purple">
            <i class="ti ti-photo"></i>
        </div>
        <div>
            <div class="stat-card__label">Aset Media</div>
            <div class="stat-card__value">{{ $stats['total_media'] }}</div>
            <div class="stat-card__sub">Gambar &amp; audio</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card__icon stat-card__icon--blue">
            <i class="ti ti-users-group"></i>
        </div>
        <div>
            <div class="stat-card__label">Orang Tua</div>
            <div class="stat-card__value">{{ $stats['total_parents'] }}</div>
            <div class="stat-card__sub">Akun aktif</div>
        </div>
    </div>
</div>

{{-- ── Row 1: Chart + Distribusi ───────────────────────────── --}}
<div class="grid-2-1">

    {{-- Chart Aktivitas --}}
    <div class="card">
        <div class="card__header">
            <h3 class="card__title">
                <i class="ti ti-chart-bar"></i> Aktivitas Belajar — 7 Hari Terakhir
            </h3>
            <div class="chart-legend">
                <span class="chart-legend__item">
                    <span class="chart-legend__dot" style="background:var(--color-500)"></span>
                    Lesson selesai
                </span>
                <span class="chart-legend__item">
                    <span class="chart-legend__dot" style="background:var(--color-200)"></span>
                    Quiz dijawab
                </span>
            </div>
        </div>
        <div class="card__body">
            <canvas id="activityChart" style="max-height:200px"></canvas>
        </div>
    </div>

    {{-- Distribusi Anak --}}
    <div class="card">
        <div class="card__header">
            <h3 class="card__title">
                <i class="ti ti-users"></i> Distribusi Anak
            </h3>
        </div>
        <div class="card__body">
            @php
                $total = $stats['total_children'] > 0 ? $stats['total_children'] : 1;
                $pctTunanetra  = round($stats['total_tunanetra']  / $total * 100);
                $pctTunarungu  = round($stats['total_tunarungu']  / $total * 100);
            @endphp

            <div class="dist-row">
                <div class="dist-row__header">
                    <span class="dist-row__label">Tunanetra</span>
                    <span class="dist-row__value">{{ $stats['total_tunanetra'] }}</span>
                </div>
                <div class="dist-row__track">
                    <div class="dist-row__fill" style="width:{{ $pctTunanetra }}%; background:var(--color-500)"></div>
                </div>
            </div>

            <div class="dist-row">
                <div class="dist-row__header">
                    <span class="dist-row__label">Tunarungu</span>
                    <span class="dist-row__value">{{ $stats['total_tunarungu'] }}</span>
                </div>
                <div class="dist-row__track">
                    <div class="dist-row__fill" style="width:{{ $pctTunarungu }}%; background:var(--color-200)"></div>
                </div>
            </div>

            <div class="age-grid">
                <div class="age-pill">
                    <div class="age-pill__number" style="color:var(--teal)">
                        {{ $lessonsByAge->get('5-7', 0) }}
                    </div>
                    <div class="age-pill__label">Materi usia 5–7 th</div>
                </div>
                <div class="age-pill">
                    <div class="age-pill__number" style="color:var(--blue)">
                        {{ $lessonsByAge->get('8-10', 0) }}
                    </div>
                    <div class="age-pill__label">Materi usia 8–10 th</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Row 2: Quiz Analitik + Aksi Cepat ──────────────────── --}}
<div class="grid-1-1">

    {{-- Quiz Analytics --}}
    <div class="card">
        <div class="card__header">
            <h3 class="card__title">
                <i class="ti ti-target"></i> Analitik Quiz
            </h3>
            <a href="{{ route('admin.quiz.monitoring') }}" class="card__link">Monitoring →</a>
        </div>
        <div class="card__body">
            <div class="quiz-rate">
                <div class="quiz-rate__number"
                     style="color:{{ $successRate >= 70 ? 'var(--green)' : ($successRate >= 40 ? 'var(--orange)' : 'var(--red)') }}">
                    {{ $successRate }}%
                </div>
                <div class="quiz-rate__label">Tingkat keberhasilan quiz</div>
            </div>

            <div class="quiz-stats">
                <div class="quiz-stat-box">
                    <div class="quiz-stat-box__number" style="color:var(--green)">
                        {{ number_format($correctAnswers) }}
                    </div>
                    <div class="quiz-stat-box__label">Jawaban Benar</div>
                </div>
                <div class="quiz-stat-box">
                    <div class="quiz-stat-box__number" style="color:var(--red)">
                        {{ number_format($incorrectAnswers) }}
                    </div>
                    <div class="quiz-stat-box__label">Jawaban Salah</div>
                </div>
            </div>

            <div class="progress-bar">
                <div class="progress-bar__fill"
                     style="width:{{ $successRate }}%;
                            background:{{ $successRate >= 70 ? 'var(--green)' : ($successRate >= 40 ? 'var(--orange)' : 'var(--red)') }}">
                </div>
            </div>
        </div>
    </div>

    {{-- Aksi Cepat --}}
    <div class="card">
        <div class="card__header">
            <h3 class="card__title">
                <i class="ti ti-bolt"></i> Aksi Cepat
            </h3>
        </div>
        <div class="action-list">
            <a href="{{ route('admin.lessons.create') }}" class="action-item">
                <span class="action-item__icon" style="background:var(--teal-light);color:var(--teal)">
                    <i class="ti ti-book"></i>
                </span>
                <span class="action-item__label">Tambah materi baru</span>
                <i class="ti ti-chevron-right action-item__arrow"></i>
            </a>
            <a href="{{ route('admin.categories.create') }}" class="action-item">
                <span class="action-item__icon" style="background:var(--blue-light);color:var(--blue)">
                    <i class="ti ti-layout-grid"></i>
                </span>
                <span class="action-item__label">Tambah kategori</span>
                <i class="ti ti-chevron-right action-item__arrow"></i>
            </a>
            <a href="{{ route('admin.quiz.create') }}" class="action-item">
                <span class="action-item__icon" style="background:var(--orange-light);color:var(--orange)">
                    <i class="ti ti-question-mark"></i>
                </span>
                <span class="action-item__label">Tambah soal quiz</span>
                <i class="ti ti-chevron-right action-item__arrow"></i>
            </a>
            <a href="{{ route('admin.media.index') }}" class="action-item">
                <span class="action-item__icon" style="background:var(--red-light);color:var(--red)">
                    <i class="ti ti-photo"></i>
                </span>
                <span class="action-item__label">Upload media aset</span>
                <i class="ti ti-chevron-right action-item__arrow"></i>
            </a>
            <a href="{{ route('admin.learning-path.index') }}" class="action-item">
                <span class="action-item__icon" style="background:var(--purple-light);color:var(--purple)">
                    <i class="ti ti-map"></i>
                </span>
                <span class="action-item__label">Atur learning path</span>
                <i class="ti ti-chevron-right action-item__arrow"></i>
            </a>
        </div>
    </div>
</div>

{{-- ── Row 3: Materi Terpopuler + Kategori ─────────────────── --}}
<div class="grid-3-2">

    {{-- Materi Terpopuler --}}
    <div class="card">
        <div class="card__header">
            <h3 class="card__title">
                <i class="ti ti-trending-up"></i> Materi Terpopuler
            </h3>
            <a href="{{ route('admin.lessons.index') }}" class="card__link">Lihat semua →</a>
        </div>

        @forelse($popularLessons as $idx => $lesson)
        <div class="lesson-list-row">
            <div class="lesson-list-row__rank {{ $idx === 0 ? 'lesson-list-row__rank--top' : '' }}">
                {{ $idx + 1 }}
            </div>
            <div class="lesson-list-row__info">
                <div class="lesson-list-row__title">{{ $lesson->judul }}</div>
                <div class="lesson-list-row__meta">
                    @if($lesson->category)
                        {{ $lesson->category->ikon }} {{ $lesson->category->nama }} ·
                    @endif
                    {{ $lesson->tipe_dunia === 'audio' ? 'Tunanetra' : 'Tunarungu' }}
                </div>
            </div>
            <div class="lesson-list-row__count">
                <div class="lesson-list-row__number">{{ $lesson->completions_count }}</div>
                <div class="lesson-list-row__number-label">selesai</div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="ti ti-book-off"></i>
            <div class="empty-state__title">Belum ada data</div>
            <div class="empty-state__desc">Belum ada penyelesaian materi</div>
        </div>
        @endforelse
    </div>

    {{-- Materi per Kategori --}}
    <div class="card">
        <div class="card__header">
            <h3 class="card__title">
                <i class="ti ti-folder"></i> Materi per Kategori
            </h3>
            <a href="{{ route('admin.categories.index') }}" class="card__link">Kelola →</a>
        </div>

        @forelse($categoryStats as $cat)
        <div class="category-list-row">
            <div class="category-list-row__icon" style="background:{{ $cat->warna }}20">
                {{ $cat->ikon }}
            </div>
            <div class="category-list-row__info">
                <div class="category-list-row__name">{{ $cat->nama }}</div>
                <div class="category-list-row__track">
                    <div class="category-list-row__fill"
                         style="width:{{ $stats['total_lessons'] > 0 ? round($cat->lessons_count / $stats['total_lessons'] * 100) : 0 }}%;
                                background:{{ $cat->warna }}">
                    </div>
                </div>
            </div>
            <div class="category-list-row__count">{{ $cat->lessons_count }}</div>
        </div>
        @empty
        <div class="empty-state">
            <i class="ti ti-folder-off"></i>
            <div class="empty-state__title">Belum ada kategori</div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn--primary btn--sm" style="margin-top:10px">
                Buat sekarang
            </a>
        </div>
        @endforelse
    </div>
</div>

{{-- ── Row 4: Aktivitas Terkini ─────────────────────────────── --}}
@if($recentActivities->isNotEmpty())
<div class="card">
    <div class="card__header">
        <h3 class="card__title">
            <i class="ti ti-clock"></i> Aktivitas Belajar Terkini
        </h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Anak</th>
                <th>Materi</th>
                <th>Tipe Disabilitas</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentActivities as $act)
            <tr>
                <td><span class="font-medium">{{ $act->child->nama_panggilan ?? 'Anak' }}</span></td>
                <td>{{ $act->lesson->judul ?? '—' }}</td>
                <td>
                    @if($act->child && $act->child->jenis_disabilitas === 'tunanetra')
                        <span class="badge badge--teal">Tunanetra</span>
                    @elseif($act->child)
                        <span class="badge badge--orange">Tunarungu</span>
                    @else
                        <span style="color:var(--text-muted)">—</span>
                    @endif
                </td>
                <td style="color:var(--text-muted); font-size:12px">
                    {{ $act->created_at->diffForHumans() }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('activityChart');
if (ctx) {
    const getThemeColors = () => {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        return {
            text: isDark ? '#94a3b8' : '#6b7280', // --text-secondary
            grid: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.04)',
            tooltipBg: isDark ? '#1e293b' : '#ffffff', // --bg-card
            tooltipText: isDark ? '#f1f5f9' : '#1a1a2e', // --text-primary
            tooltipBorder: isDark ? '#334155' : '#e8eaed', // --border
        };
    };

    let colors = getThemeColors();

    const activityChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Lesson Selesai',
                    data: @json($chartCompletions),
                    backgroundColor: 'rgba(101, 200, 55, 0.75)',
                    borderColor: '#65C837',
                    borderWidth: 2,
                    borderRadius: 5,
                },
                {
                    label: 'Quiz Dijawab',
                    data: @json($chartQuizzes),
                    backgroundColor: 'rgba(184, 230, 163, 0.75)',
                    borderColor: '#B8E6A3',
                    borderWidth: 2,
                    borderRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: colors.tooltipBg,
                    titleColor: colors.tooltipText,
                    bodyColor: colors.tooltipText,
                    borderColor: colors.tooltipBorder,
                    borderWidth: 1,
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label}: ${ctx.raw}`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        precision: 0, 
                        color: colors.text,
                        font: { family: 'Inter', size: 11 } 
                    },
                    grid: { color: colors.grid }
                },
                x: {
                    grid: { display: false },
                    ticks: { 
                        color: colors.text,
                        font: { family: 'Inter', size: 11 } 
                    }
                }
            }
        }
    });

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'data-theme') {
                const newColors = getThemeColors();
                activityChart.options.scales.y.ticks.color = newColors.text;
                activityChart.options.scales.y.grid.color = newColors.grid;
                activityChart.options.scales.x.ticks.color = newColors.text;
                
                activityChart.options.plugins.tooltip.backgroundColor = newColors.tooltipBg;
                activityChart.options.plugins.tooltip.titleColor = newColors.tooltipText;
                activityChart.options.plugins.tooltip.bodyColor = newColors.tooltipText;
                activityChart.options.plugins.tooltip.borderColor = newColors.tooltipBorder;
                
                activityChart.update();
            }
        });
    });
    observer.observe(document.documentElement, { attributes: true });
}
</script>
@endpush
