@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')

{{-- ── Welcome banner ──────────────────────────────────────────────────── --}}
<div style="background:linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #818cf8 100%); border-radius:16px; padding:28px 32px; margin-bottom:28px; color:#fff; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; position:relative; overflow:hidden;">
    <div style="position:absolute; right:-20px; top:-20px; width:200px; height:200px; border-radius:50%; background:rgba(255,255,255,0.06);"></div>
    <div style="position:absolute; right:80px; bottom:-40px; width:140px; height:140px; border-radius:50%; background:rgba(255,255,255,0.04);"></div>
    <div style="position:relative; z-index:1;">
        <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:6px;">Selamat datang, {{ auth()->user()->name }}! 👋</h2>
        <p style="opacity:0.85; font-size:0.9rem;">Kelola platform pembelajaran Pinteria untuk anak berkebutuhan khusus</p>
    </div>
    <div style="position:relative; z-index:1; display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('admin.lessons.create') }}" style="background:rgba(255,255,255,0.2); color:#fff; border:1px solid rgba(255,255,255,0.3); backdrop-filter:blur(4px);" class="admin-btn">
            ➕ Tambah Materi
        </a>
        <a href="{{ route('admin.categories.create') }}" style="background:rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.2);" class="admin-btn">
            🗂️ Tambah Kategori
        </a>
    </div>
</div>

{{-- ── Stats Grid ───────────────────────────────────────────────────────── --}}
<div class="stats-grid" style="margin-bottom:28px;">
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
    <div class="stat-card stat-card--green">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Penyelesaian Lesson</div>
        <div class="stat-value">{{ $stats['total_completions'] }}</div>
    </div>
    <div class="stat-card stat-card--blue">
        <div class="stat-icon">🗂️</div>
        <div class="stat-label">Kategori</div>
        <div class="stat-value">{{ $stats['total_categories'] }}</div>
    </div>
    <div class="stat-card stat-card--orange">
        <div class="stat-icon">🖼️</div>
        <div class="stat-label">Aset Media</div>
        <div class="stat-value">{{ $stats['total_media'] }}</div>
    </div>
</div>

{{-- ── Row 1: Chart + Disability breakdown ─────────────────────────────── --}}
<div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:20px;">

    {{-- Activity Chart --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <h3 class="admin-card__title">📈 Aktivitas Belajar — 7 Hari Terakhir</h3>
        </div>
        <div class="admin-card__body">
            <canvas id="activityChart" style="max-height:220px;"></canvas>
        </div>
    </div>

    {{-- Disability & Age --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <h3 class="admin-card__title">📋 Distribusi Anak</h3>
        </div>
        <div class="admin-card__body">
            <div style="margin-bottom:20px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <span class="admin-badge admin-badge--audio">🎧 Tunanetra</span>
                    <span style="font-weight:800; font-size:1.3rem;">{{ $stats['total_tunanetra'] }}</span>
                </div>
                <div style="height:8px; background:#f1f5f9; border-radius:20px; overflow:hidden;">
                    <div style="height:100%; width:{{ $stats['total_children'] > 0 ? round($stats['total_tunanetra'] / $stats['total_children'] * 100) : 0 }}%; background:linear-gradient(90deg, #7c3aed, #a78bfa); border-radius:20px; transition:width 0.8s ease;"></div>
                </div>
            </div>
            <div style="margin-bottom:24px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <span class="admin-badge admin-badge--visual">👁️ Tunarungu</span>
                    <span style="font-weight:800; font-size:1.3rem;">{{ $stats['total_tunarungu'] }}</span>
                </div>
                <div style="height:8px; background:#f1f5f9; border-radius:20px; overflow:hidden;">
                    <div style="height:100%; width:{{ $stats['total_children'] > 0 ? round($stats['total_tunarungu'] / $stats['total_children'] * 100) : 0 }}%; background:linear-gradient(90deg, #ea580c, #fb923c); border-radius:20px; transition:width 0.8s ease;"></div>
                </div>
            </div>

            <div style="border-top:1px solid var(--admin-border); padding-top:16px;">
                <div style="font-size:0.78rem; font-weight:600; color:var(--admin-text-muted); margin-bottom:10px; text-transform:uppercase; letter-spacing:0.06em;">Distribusi Usia Materi</div>
                <div style="display:flex; gap:10px;">
                    <div style="flex:1; text-align:center; background:rgba(16,185,129,0.08); border-radius:10px; padding:12px 8px;">
                        <div style="font-weight:800; font-size:1.4rem; color:#059669;">{{ $lessonsByAge->get('5-7', 0) }}</div>
                        <div style="font-size:0.72rem; color:var(--admin-text-muted);">5–7 Tahun</div>
                    </div>
                    <div style="flex:1; text-align:center; background:rgba(59,130,246,0.08); border-radius:10px; padding:12px 8px;">
                        <div style="font-weight:800; font-size:1.4rem; color:#2563eb;">{{ $lessonsByAge->get('8-10', 0) }}</div>
                        <div style="font-size:0.72rem; color:var(--admin-text-muted);">8–10 Tahun</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Row 2: Quiz Analytics + Quick Actions ────────────────────────────── --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">

    {{-- Quiz analytics --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <h3 class="admin-card__title">🎯 Analitik Quiz</h3>
        </div>
        <div class="admin-card__body">
            <div style="text-align:center; margin-bottom:20px;">
                <div style="font-size:2.8rem; font-weight:900; color:{{ $successRate >= 70 ? '#059669' : ($successRate >= 40 ? '#d97706' : '#dc2626') }};">
                    {{ $successRate }}%
                </div>
                <div style="font-size:0.85rem; color:var(--admin-text-muted);">Tingkat Keberhasilan Quiz</div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div style="text-align:center; background:rgba(16,185,129,0.08); border-radius:10px; padding:14px;">
                    <div style="font-size:1.6rem; font-weight:800; color:#059669;">{{ $correctAnswers }}</div>
                    <div style="font-size:0.72rem; color:var(--admin-text-muted);">Jawaban Benar</div>
                </div>
                <div style="text-align:center; background:rgba(239,68,68,0.08); border-radius:10px; padding:14px;">
                    <div style="font-size:1.6rem; font-weight:800; color:#dc2626;">{{ $incorrectAnswers }}</div>
                    <div style="font-size:0.72rem; color:var(--admin-text-muted);">Jawaban Salah</div>
                </div>
            </div>
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--admin-border);">
                <div style="height:10px; background:#f1f5f9; border-radius:20px; overflow:hidden;">
                    <div style="height:100%; width:{{ $successRate }}%; background:linear-gradient(90deg, #10b981, #34d399); border-radius:20px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <h3 class="admin-card__title">⚡ Aksi Cepat</h3>
        </div>
        <div class="admin-card__body" style="display:flex; flex-direction:column; gap:10px;">
            <a href="{{ route('admin.lessons.create') }}" class="admin-btn admin-btn--primary" style="justify-content:center; padding:12px;">
                📚 Tambah Materi Baru
            </a>
            <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn--ghost" style="justify-content:center; padding:12px;">
                🗂️ Tambah Kategori
            </a>
            <a href="{{ route('admin.quiz.create') }}" class="admin-btn admin-btn--ghost" style="justify-content:center; padding:12px;">
                ❓ Tambah Soal Quiz
            </a>
            <a href="{{ route('admin.media.index') }}" class="admin-btn admin-btn--ghost" style="justify-content:center; padding:12px;">
                🖼️ Upload Media Aset
            </a>
            <a href="{{ route('admin.learning-path.index') }}" class="admin-btn admin-btn--ghost" style="justify-content:center; padding:12px;">
                🗺️ Atur Learning Path
            </a>
            <a href="{{ route('admin.quiz.monitoring') }}" class="admin-btn admin-btn--ghost" style="justify-content:center; padding:12px;">
                📈 Monitoring Quiz
            </a>
        </div>
    </div>
</div>

{{-- ── Row 3: Popular Lessons + Category Stats ──────────────────────────── --}}
<div style="display:grid; grid-template-columns:3fr 2fr; gap:20px; margin-bottom:20px;">

    {{-- Popular lessons --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <h3 class="admin-card__title">🔥 Materi Terpopuler</h3>
            <a href="{{ route('admin.lessons.index') }}" style="font-size:0.82rem; color:var(--admin-primary); text-decoration:none;">Lihat Semua →</a>
        </div>
        <div class="admin-card__body" style="padding:0;">
            @forelse($popularLessons as $idx => $lesson)
            <div style="display:flex; align-items:center; gap:14px; padding:14px 20px; border-bottom:1px solid var(--admin-border);">
                <div style="width:32px; height:32px; border-radius:50%; background:{{ $idx === 0 ? '#fef9c3' : ($idx === 1 ? '#f1f5f9' : '#fff7ed') }}; border:2px solid {{ $idx === 0 ? '#fbbf24' : ($idx === 1 ? '#94a3b8' : '#fb923c') }}; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.82rem; color:{{ $idx === 0 ? '#d97706' : ($idx === 1 ? '#64748b' : '#ea580c') }}; flex-shrink:0;">
                    {{ $idx + 1 }}
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:700; font-size:0.88rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $lesson->judul }}</div>
                    <div style="font-size:0.72rem; color:var(--admin-text-muted);">
                        @if($lesson->category) {{ $lesson->category->ikon }} {{ $lesson->category->nama }} · @endif
                        {{ $lesson->tipe_dunia === 'audio' ? '🎧 Tunanetra' : '👁️ Tunarungu' }}
                    </div>
                </div>
                <div style="flex-shrink:0; text-align:right;">
                    <div style="font-weight:800; font-size:1rem; color:var(--admin-text-primary);">{{ $lesson->completions_count }}</div>
                    <div style="font-size:0.7rem; color:var(--admin-text-muted);">selesai</div>
                </div>
            </div>
            @empty
            <div style="padding:32px; text-align:center; color:var(--admin-text-muted); font-size:0.85rem;">
                Belum ada data penyelesaian materi
            </div>
            @endforelse
        </div>
    </div>

    {{-- Category stats --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <h3 class="admin-card__title">🗂️ Materi per Kategori</h3>
            <a href="{{ route('admin.categories.index') }}" style="font-size:0.82rem; color:var(--admin-primary); text-decoration:none;">Kelola →</a>
        </div>
        <div class="admin-card__body" style="padding:0;">
            @forelse($categoryStats as $cat)
            <div style="padding:12px 20px; border-bottom:1px solid var(--admin-border); display:flex; align-items:center; gap:12px;">
                <div style="width:36px; height:36px; border-radius:8px; background:{{ $cat->warna }}20; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0;">{{ $cat->ikon }}</div>
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:600; font-size:0.85rem;">{{ $cat->nama }}</div>
                    <div style="height:4px; background:#f1f5f9; border-radius:20px; margin-top:6px; overflow:hidden;">
                        <div style="height:100%; width:{{ $stats['total_lessons'] > 0 ? round($cat->lessons_count / $stats['total_lessons'] * 100) : 0 }}%; background:{{ $cat->warna }}; border-radius:20px;"></div>
                    </div>
                </div>
                <div style="font-weight:800; font-size:0.95rem; flex-shrink:0;">{{ $cat->lessons_count }}</div>
            </div>
            @empty
            <div style="padding:32px; text-align:center; color:var(--admin-text-muted); font-size:0.85rem;">
                Belum ada kategori. <a href="{{ route('admin.categories.create') }}" style="color:var(--admin-primary);">Buat sekarang →</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── Row 4: Recent Activities ─────────────────────────────────────────── --}}
@if($recentActivities->isNotEmpty())
<div class="admin-card">
    <div class="admin-card__header">
        <h3 class="admin-card__title">🕐 Aktivitas Belajar Terkini</h3>
    </div>
    <div class="admin-card__body" style="padding:0;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Anak</th>
                    <th>Materi</th>
                    <th>Disabilitas</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentActivities as $act)
                <tr>
                    <td style="font-weight:600;">{{ $act->child->nama_panggilan ?? 'Anak' }}</td>
                    <td>
                        <div style="font-weight:600; font-size:0.85rem;">{{ $act->lesson->judul ?? '—' }}</div>
                    </td>
                    <td>
                        @if($act->child && $act->child->jenis_disabilitas === 'tunanetra')
                            <span class="admin-badge admin-badge--audio">🎧 Tunanetra</span>
                        @elseif($act->child)
                            <span class="admin-badge admin-badge--visual">👁️ Tunarungu</span>
                        @else
                            <span style="color:var(--admin-text-muted);">—</span>
                        @endif
                    </td>
                    <td style="font-size:0.82rem; color:var(--admin-text-muted);">{{ $act->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ── Chart.js ─────────────────────────────────────────────────────────── --}}
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
                    backgroundColor: 'rgba(59,130,246,0.7)',
                    borderColor: '#3b82f6',
                    borderWidth: 2,
                    borderRadius: 6,
                },
                {
                    label: 'Quiz Dijawab',
                    data: @json($chartQuizzes),
                    backgroundColor: 'rgba(16,185,129,0.6)',
                    borderColor: '#10b981',
                    borderWidth: 2,
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top', labels: { font: { family: 'Inter', size: 11 }, boxWidth: 12 } },
                tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.raw}` } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { size: 10 } },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
}
</script>

@endsection
