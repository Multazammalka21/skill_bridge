@extends('admin.layouts.app')

@section('page-title', 'Kuis Monitoring & Analytics')

@section('content')

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 28px;">
        <!-- Card 1 -->
        <div class="admin-card" style="padding: 20px; display: flex; align-items: center; gap: 16px;">
            <div style="font-size: 32px; background: rgba(41, 182, 246, 0.1); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 12px; color: #29b6f6;">
                🧒
            </div>
            <div>
                <h4 style="margin: 0; color: #888; font-size: 14px; text-transform: uppercase;">Total Anak Terdaftar</h4>
                <h2 style="margin: 4px 0 0 0; font-size: 28px;">{{ $totalChildren }}</h2>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="admin-card" style="padding: 20px; display: flex; align-items: center; gap: 16px;">
            <div style="font-size: 32px; background: rgba(67, 160, 71, 0.1); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 12px; color: #43a047;">
                🎮
            </div>
            <div>
                <h4 style="margin: 0; color: #888; font-size: 14px; text-transform: uppercase;">Kuis Dikerjakan</h4>
                <h2 style="margin: 4px 0 0 0; font-size: 28px;">{{ $totalQuizAttempts }}</h2>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="admin-card" style="padding: 20px; display: flex; align-items: center; gap: 16px;">
            <div style="font-size: 32px; background: rgba(255, 112, 67, 0.1); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 12px; color: #ff7043;">
                🎯
            </div>
            <div>
                <h4 style="margin: 0; color: #888; font-size: 14px; text-transform: uppercase;">Tingkat Keberhasilan</h4>
                <h2 style="margin: 4px 0 0 0; font-size: 28px;">{{ $successRate }}%</h2>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 28px;">
        <!-- Left Side: Recent Activities -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">🕒 Aktivitas Kuis Terbaru</h3>
            </div>
            <div class="admin-card__body" style="padding: 0;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Anak</th>
                            <th>Pelajaran</th>
                            <th>Hasil</th>
                            <th>Skor</th>
                            <th>Bintang</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $activity)
                            <tr>
                                <td>
                                    <strong>{{ $activity->child->nama_panggilan }}</strong>
                                    <span style="display: block; font-size: 11px; color: #888;">
                                        {{ $activity->child->jenis_disabilitas === 'tunanetra' ? 'Audio' : 'Visual' }}
                                    </span>
                                </td>
                                <td>{{ $activity->lesson->judul }}</td>
                                <td>
                                    @if($activity->benar)
                                        <span class="admin-badge admin-badge--active">✅ Benar</span>
                                    @else
                                        <span class="admin-badge admin-badge--inactive">❌ Salah</span>
                                    @endif
                                </td>
                                <td><strong>{{ $activity->skor }}</strong></td>
                                <td>
                                    <span style="color: #ffca28; letter-spacing: 2px;">
                                        {{ str_repeat('⭐', $activity->bintang) }}
                                    </span>
                                </td>
                                <td>{{ $activity->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: #888; padding: 20px;">Belum ada aktivitas kuis terdeteksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Side: Star Breakdown -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">⭐ Distribusi Bintang</h3>
            </div>
            <div class="admin-card__body">
                <div style="display: flex; flex-direction: column; gap: 16px; margin-top: 10px;">
                    @foreach($starBreakdown as $star => $count)
                        <div>
                            <div style="display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 4px;">
                                <span>{{ $star }} Bintang</span>
                                <strong>{{ $count }} kali</strong>
                            </div>
                            @php
                                $percent = $totalQuizAttempts > 0 ? ($count / $totalQuizAttempts) * 100 : 0;
                            @endphp
                            <div style="height: 12px; background: #eee; border-radius: 6px; overflow: hidden;">
                                <div style="height: 100%; width: {{ $percent }}%; background: #ffca28; border-radius: 6px;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Hardest Questions Section -->
    <div class="admin-card">
        <div class="admin-card__header">
            <h3 class="admin-card__title">⚠️ 5 Soal Kuis Paling Banyak Salah</h3>
        </div>
        <div class="admin-card__body" style="padding: 0;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pelajaran</th>
                        <th>Tipe</th>
                        <th>Pertanyaan</th>
                        <th>Total Percobaan</th>
                        <th>Jawaban Salah</th>
                        <th>Rasio Salah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hardestQuestions as $item)
                        @if($item->quizQuestion)
                            <tr>
                                <td>{{ $item->quizQuestion->lesson->judul }}</td>
                                <td>
                                    @if($item->quizQuestion->tipe === 'voice')
                                        <span class="admin-badge admin-badge--audio">🎧 Audio</span>
                                    @else
                                        <span class="admin-badge admin-badge--visual">👁️ Visual</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($item->quizQuestion->pertanyaan, 50) }}</td>
                                <td>{{ $item->total_attempts }} kali</td>
                                <td style="color: #ef5350; font-weight: bold;">{{ $item->wrong_attempts }} kali</td>
                                <td>
                                    @php
                                        $ratio = $item->total_attempts > 0 ? round(($item->wrong_attempts / $item->total_attempts) * 100, 1) : 0;
                                    @endphp
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="flex: 1; height: 8px; background: #eee; border-radius: 4px; overflow: hidden; min-width: 80px;">
                                            <div style="height: 100%; width: {{ $ratio }}%; background: #ef5350; border-radius: 4px;"></div>
                                        </div>
                                        <span>{{ $ratio }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #888; padding: 20px;">Semua kuis diselesaikan dengan sangat baik!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
