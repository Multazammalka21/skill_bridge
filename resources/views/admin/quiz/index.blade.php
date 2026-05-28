@extends('admin.layouts.app')

@section('page-title', 'Kelola Quiz')

@section('content')

    <div class="admin-card" style="margin-bottom: 24px;">
        <div class="admin-card__header">
            <h3 class="admin-card__title">🔍 Filter</h3>
            <a href="{{ route('admin.quiz.create') }}" class="admin-btn admin-btn--primary admin-btn--sm">
                ➕ Tambah Soal
            </a>
        </div>
        <div class="admin-card__body">
            <form method="GET" action="{{ route('admin.quiz.index') }}" class="admin-filters">
                <div class="admin-form-group">
                    <label class="admin-form-label">Lesson</label>
                    <select name="lesson_id" class="admin-form-select" style="min-width: 180px;">
                        <option value="">Semua Lesson</option>
                        @foreach($lessons as $lesson)
                            <option value="{{ $lesson->id }}" {{ ($filters['lesson_id'] ?? '') == $lesson->id ? 'selected' : '' }}>
                                {{ $lesson->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Tipe Dunia</label>
                    <select name="tipe_dunia" class="admin-form-select" style="min-width: 150px;">
                        <option value="">Semua</option>
                        <option value="voice" {{ ($filters['tipe_dunia'] ?? '') === 'voice' ? 'selected' : '' }}>🎧 Audio (Tunanetra)</option>
                        <option value="image" {{ ($filters['tipe_dunia'] ?? '') === 'image' ? 'selected' : '' }}>👁️ Visual (Tunarungu)</option>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Kategori Usia</label>
                    <select name="kategori_usia" class="admin-form-select" style="min-width: 130px;">
                        <option value="">Semua</option>
                        <option value="5-7" {{ ($filters['kategori_usia'] ?? '') === '5-7' ? 'selected' : '' }}>5–7 Tahun</option>
                        <option value="8-10" {{ ($filters['kategori_usia'] ?? '') === '8-10' ? 'selected' : '' }}>8–10 Tahun</option>
                    </select>
                </div>
                <div class="admin-form-group">
                    <button type="submit" class="admin-btn admin-btn--primary admin-btn--sm">Filter</button>
                    <a href="{{ route('admin.quiz.index') }}" class="admin-btn admin-btn--ghost admin-btn--sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card__body" style="padding: 0; overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pertanyaan</th>
                        <th>Lesson</th>
                        <th>Tipe</th>
                        <th>Usia</th>
                        <th>Jawaban Benar</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes as $quiz)
                        <tr>
                            <td>{{ $quiz->id }}</td>
                            <td style="max-width: 250px;">
                                {{ Str::limit($quiz->pertanyaan, 60) }}
                            </td>
                            <td>
                                <span style="font-size: 0.82rem; color: var(--admin-text-secondary);">
                                    {{ $quiz->lesson->judul ?? '—' }}
                                </span>
                            </td>
                            <td>
                                @if($quiz->tipe === 'voice')
                                    <span class="admin-badge admin-badge--audio">🎧 Audio</span>
                                @else
                                    <span class="admin-badge admin-badge--visual">👁️ Visual</span>
                                @endif
                            </td>
                            <td>
                                @if($quiz->lesson)
                                    <span class="admin-badge {{ $quiz->lesson->kategori_usia === '5-7' ? 'admin-badge--age-57' : 'admin-badge--age-810' }}">
                                        {{ $quiz->lesson->kategori_usia }} th
                                    </span>
                                @endif
                            </td>
                            <td>
                                <code style="background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">
                                    {{ Str::limit($quiz->jawaban_benar, 30) }}
                                </code>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="{{ route('admin.quiz.edit', $quiz->id) }}" class="admin-btn admin-btn--ghost admin-btn--sm">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('admin.quiz.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--admin-text-muted);">
                                Belum ada soal quiz. <a href="{{ route('admin.quiz.create') }}" style="color: var(--admin-primary);">Tambah sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($quizzes->hasPages())
        <div class="admin-pagination">
            {{ $quizzes->appends($filters)->links('pagination::simple-bootstrap-5') }}
        </div>
    @endif

@endsection
