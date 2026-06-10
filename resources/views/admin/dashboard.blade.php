@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@push('styles')
<style>
    :root {
        --teal: #0d9488;
        --teal-light: rgba(13, 148, 136, 0.1);
        --yellow: #eab308;
        --yellow-light: rgba(234, 179, 8, 0.1);
        --blue: #3b82f6;
        --blue-light: rgba(59, 130, 246, 0.1);
        --orange: #f97316;
        --orange-light: rgba(249, 115, 22, 0.1);
        --green: #22c55e;
        --green-light: rgba(34, 197, 94, 0.1);
        --red: #ef4444;
        --red-light: rgba(239, 68, 68, 0.1);
        --purple: #a855f7;
        --purple-light: rgba(168, 85, 247, 0.1);
    }

    .welcome-banner {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #6366f1 100%);
        border-radius: 16px;
        padding: 32px;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 24px;
        margin-bottom: 32px;
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.3);
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.07);
        pointer-events: none;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30px;
        right: 120px;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.04);
        pointer-events: none;
    }
    .welcome-banner__text h2 {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.02em;
    }
    .welcome-banner__text p {
        font-size: 0.95rem;
        opacity: 0.9;
        font-weight: 400;
    }
    .welcome-banner__actions {
        display: flex;
        gap: 12px;
        z-index: 1;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn--white {
        background: #ffffff;
        color: #1e3a8a;
        border: 1px solid #ffffff;
    }
    .btn--white:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.25);
    }
    .btn--ghost {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(4px);
    }
    .btn--ghost:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: #ffffff;
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .stat-card__icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .stat-card__icon--teal { background: rgba(13, 148, 136, 0.1); color: #0d9488; }
    .stat-card__icon--blue { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-card__icon--orange { background: rgba(249, 115, 22, 0.1); color: #f97316; }
    .stat-card__icon--green { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .stat-card__icon--red { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .stat-card__icon--purple { background: rgba(168, 85, 247, 0.1); color: #a855f7; }

    .stat-card__label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--admin-text-muted);
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }
    .stat-card__value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--admin-text-primary);
        line-height: 1.2;
    }
    .stat-card__sub {
        font-size: 0.72rem;
        color: var(--admin-text-muted);
        margin-top: 2px;
    }

    .grid-2-1 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    .grid-1-1 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    .grid-3-2 {
        display: grid;
        grid-template-columns: 3fr 2fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    @media (max-width: 1024px) {
        .grid-2-1, .grid-1-1, .grid-3-2 {
            grid-template-columns: 1fr;
        }
    }

    .card {
        background: #ffffff;
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .card__header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--admin-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }
    .card__title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--admin-text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card__title i {
        font-size: 1.2rem;
    }
    .card__body {
        padding: 24px;
        flex: 1;
    }
    .card__link {
        font-size: 0.82rem;
        color: var(--admin-primary);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }
    .card__link:hover {
        color: var(--admin-primary-dark);
    }

    .chart-legend {
        display: flex;
        gap: 16px;
    }
    .chart-legend__item {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--admin-text-secondary);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .chart-legend__dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .dist-row {
        margin-bottom: 16px;
    }
    .dist-row__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }
    .dist-row__label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--admin-text-secondary);
    }
    .dist-row__value {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--admin-text-primary);
    }
    .dist-row__track {
        height: 8px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
    }
    .dist-row__fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease-in-out;
    }

    .age-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 24px;
        border-top: 1px solid var(--admin-border);
        padding-top: 20px;
    }
    .age-pill {
        background: #f8fafc;
        border: 1px solid var(--admin-border);
        border-radius: 10px;
        padding: 12px 8px;
        text-align: center;
    }
    .age-pill__number {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 4px;
    }
    .age-pill__label {
        font-size: 0.72rem;
        color: var(--admin-text-muted);
        font-weight: 600;
    }

    .quiz-rate {
        text-align: center;
        margin-bottom: 20px;
    }
    .quiz-rate__number {
        font-size: 2.5rem;
        font-weight: 900;
        line-height: 1;
    }
    .quiz-rate__label {
        font-size: 0.82rem;
        color: var(--admin-text-muted);
        font-weight: 500;
        margin-top: 4px;
    }
    .quiz-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }
    .quiz-stat-box {
        background: #f8fafc;
        border: 1px solid var(--admin-border);
        border-radius: 10px;
        padding: 12px;
        text-align: center;
    }
    .quiz-stat-box__number {
        font-size: 1.35rem;
        font-weight: 800;
        margin-bottom: 2px;
    }
    .quiz-stat-box__label {
        font-size: 0.72rem;
        color: var(--admin-text-muted);
        font-weight: 600;
    }
    .progress-bar {
        height: 8px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
    }
    .progress-bar__fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease-in-out;
    }

    .action-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .action-item {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid var(--admin-border);
        text-decoration: none;
        color: var(--admin-text-secondary);
        transition: all 0.2s ease;
    }
    .action-item:hover {
        background: #f8fafc;
        transform: translateX(4px);
        border-color: var(--admin-primary);
    }
    .action-item__icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        margin-right: 12px;
        flex-shrink: 0;
    }
    .action-item__label {
        font-size: 0.85rem;
        font-weight: 600;
        flex: 1;
    }
    .action-item__arrow {
        font-size: 0.85rem;
        color: var(--admin-text-muted);
        transition: transform 0.2s ease;
    }
    .action-item:hover .action-item__arrow {
        transform: translateX(2px);
        color: var(--admin-primary);
    }

    .lesson-list-row {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        border-bottom: 1px solid var(--admin-border);
    }
    .lesson-list-row:last-child {
        border-bottom: none;
    }
    .lesson-list-row__rank {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #f1f5f9;
        border: 1px solid var(--admin-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--admin-text-secondary);
        margin-right: 12px;
        flex-shrink: 0;
    }
    .lesson-list-row__rank--top {
        background: #fef9c3;
        border-color: #fbbf24;
        color: #d97706;
    }
    .lesson-list-row__info {
        flex: 1;
        min-width: 0;
    }
    .lesson-list-row__title {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--admin-text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .lesson-list-row__meta {
        font-size: 0.72rem;
        color: var(--admin-text-muted);
        margin-top: 2px;
    }
    .lesson-list-row__count {
        text-align: right;
        margin-left: 12px;
        flex-shrink: 0;
    }
    .lesson-list-row__number {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--admin-text-primary);
    }
    .lesson-list-row__number-label {
        font-size: 0.68rem;
        color: var(--admin-text-muted);
    }

    .category-list-row {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        border-bottom: 1px solid var(--admin-border);
    }
    .category-list-row:last-child {
        border-bottom: none;
    }
    .category-list-row__icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-right: 12px;
        flex-shrink: 0;
    }
    .category-list-row__info {
        flex: 1;
        min-width: 0;
    }
    .category-list-row__name {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--admin-text-primary);
    }
    .category-list-row__track {
        height: 4px;
        background: #f1f5f9;
        border-radius: 10px;
        margin-top: 4px;
        overflow: hidden;
    }
    .category-list-row__fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease;
    }
    .category-list-row__count {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--admin-text-primary);
        margin-left: 12px;
        flex-shrink: 0;
    }

    .empty-state {
        text-align: center;
        padding: 32px;
        color: var(--admin-text-muted);
    }
    .empty-state i {
        font-size: 2rem;
        margin-bottom: 8px;
        display: block;
    }
    .empty-state__title {
        font-size: 0.88rem;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .empty-state__desc {
        font-size: 0.78rem;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .badge--teal {
        background: rgba(13, 148, 136, 0.1);
        color: #0d9488;
    }
    .badge--orange {
        background: rgba(249, 115, 22, 0.1);
        color: #f97316;
    }
    .font-medium {
        font-weight: 500;
    }
</style>
@endpush

@section('subnav')
    <a href="{{ route('admin.dashboard') }}" class="subnav__item subnav__item--active">
        <i class="ti ti-home"></i> Ringkasan
    </a>
    <a href="#" class="subnav__item">
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
                    <span class="chart-legend__dot" style="background:var(--teal)"></span>
                    Lesson selesai
                </span>
                <span class="chart-legend__item">
                    <span class="chart-legend__dot" style="background:var(--yellow)"></span>
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
                    <div class="dist-row__fill" style="width:{{ $pctTunanetra }}%; background:var(--teal)"></div>
                </div>
            </div>

            <div class="dist-row">
                <div class="dist-row__header">
                    <span class="dist-row__label">Tunarungu</span>
                    <span class="dist-row__value">{{ $stats['total_tunarungu'] }}</span>
                </div>
                <div class="dist-row__track">
                    <div class="dist-row__fill" style="width:{{ $pctTunarungu }}%; background:var(--yellow)"></div>
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
                     style="color:{{ $successRate >= 70 ? 'var(--teal)' : ($successRate >= 40 ? 'var(--yellow)' : 'var(--red)') }}">
                    {{ $successRate }}%
                </div>
                <div class="quiz-rate__label">Tingkat keberhasilan quiz</div>
            </div>

            <div class="quiz-stats">
                <div class="quiz-stat-box">
                    <div class="quiz-stat-box__number" style="color:var(--teal)">
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
                            background:{{ $successRate >= 70 ? 'var(--teal)' : ($successRate >= 40 ? 'var(--yellow)' : 'var(--red)') }}">
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
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Lesson Selesai',
                    data: @json($chartCompletions),
                    backgroundColor: 'rgba(0,169,157,0.75)',
                    borderColor: '#00A99D',
                    borderWidth: 2,
                    borderRadius: 5,
                },
                {
                    label: 'Quiz Dijawab',
                    data: @json($chartQuizzes),
                    backgroundColor: 'rgba(251,191,36,0.7)',
                    borderColor: '#fbbf24',
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
                    ticks: { font: { family: 'Inter', size: 11 } }
                }
            }
        }
    });
}
</script>
@endpush
