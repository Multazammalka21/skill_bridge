@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card stat-card--blue">
            <div class="stat-icon">👥</div>
            <div class="stat-label">Total Pengguna</div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
        </div>

        <div class="stat-card stat-card--green">
            <div class="stat-icon">👨‍👩‍👧</div>
            <div class="stat-label">Orang Tua</div>
            <div class="stat-value">{{ $stats['total_parents'] }}</div>
        </div>

        <div class="stat-card stat-card--orange">
            <div class="stat-icon">👶</div>
            <div class="stat-label">Total Anak</div>
            <div class="stat-value">{{ $stats['total_children'] }}</div>
        </div>

        <div class="stat-card stat-card--cyan">
            <div class="stat-icon">📚</div>
            <div class="stat-label">Total Materi</div>
            <div class="stat-value">{{ $stats['total_lessons'] }}</div>
        </div>

        <div class="stat-card stat-card--red">
            <div class="stat-icon">❓</div>
            <div class="stat-label">Total Soal Quiz</div>
            <div class="stat-value">{{ $stats['total_quizzes'] }}</div>
        </div>
    </div>

    <!-- Quick Info Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">

        <!-- Disability Breakdown -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">📋 Distribusi Anak</h3>
            </div>
            <div class="admin-card__body">
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span class="admin-badge admin-badge--audio">🎧 Tunanetra</span>
                        </div>
                        <span style="font-weight: 700; font-size: 1.3rem;">{{ $stats['total_tunanetra'] }}</span>
                    </div>
                    <div style="height: 8px; background: #f1f5f9; border-radius: 20px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $stats['total_children'] > 0 ? round($stats['total_tunanetra'] / $stats['total_children'] * 100) : 0 }}%; background: linear-gradient(90deg, #7c3aed, #a78bfa); border-radius: 20px; transition: width 0.8s ease;"></div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span class="admin-badge admin-badge--visual">👁️ Tunarungu</span>
                        </div>
                        <span style="font-weight: 700; font-size: 1.3rem;">{{ $stats['total_tunarungu'] }}</span>
                    </div>
                    <div style="height: 8px; background: #f1f5f9; border-radius: 20px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $stats['total_children'] > 0 ? round($stats['total_tunarungu'] / $stats['total_children'] * 100) : 0 }}%; background: linear-gradient(90deg, #ea580c, #fb923c); border-radius: 20px; transition: width 0.8s ease;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">⚡ Aksi Cepat</h3>
            </div>
            <div class="admin-card__body">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="{{ route('admin.quiz.create') }}" class="admin-btn admin-btn--primary" style="justify-content: center;">
                        ➕ Tambah Soal Quiz Baru
                    </a>
                    <a href="{{ route('admin.quiz.index') }}" class="admin-btn admin-btn--ghost" style="justify-content: center;">
                        📋 Kelola Semua Quiz
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection
