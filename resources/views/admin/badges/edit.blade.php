@extends('admin.layouts.app')

@section('page-title', 'Edit Badge Pencapaian')

@section('content')

    <div style="max-width: 600px;">
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">🏆 Edit Badge Pencapaian</h3>
                <a href="{{ route('admin.badges.index') }}" class="admin-btn admin-btn--ghost admin-btn--sm">← Kembali</a>
            </div>
            <div class="admin-card__body">

                @if($errors->any())
                    <div class="admin-flash admin-flash--error">
                        ❌ {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.badges.update', $badge->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="admin-form-group">
                        <label class="admin-form-label">Nama Badge *</label>
                        <input type="text" name="nama" class="admin-form-input" required value="{{ old('nama', $badge->nama) }}">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Ikon Badge (Emoji) *</label>
                        <input type="text" name="ikon" class="admin-form-input" required value="{{ old('ikon', $badge->ikon) }}">
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px;">Gunakan emoji atau teks pendek untuk merepresentasikan badge ini.</small>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Deskripsi Badge *</label>
                        <input type="text" name="deskripsi" class="admin-form-input" required value="{{ old('deskripsi', $badge->deskripsi) }}">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Tipe Syarat *</label>
                        <select name="syarat_tipe" class="admin-form-select" required>
                            <option value="">Pilih Syarat...</option>
                            <option value="quiz_count" {{ old('syarat_tipe', $badge->syarat_tipe) === 'quiz_count' ? 'selected' : '' }}>📚 Jumlah Kuis Selesai</option>
                            <option value="perfect_score" {{ old('syarat_tipe', $badge->syarat_tipe) === 'perfect_score' ? 'selected' : '' }}>💯 Kuis Nilai Sempurna (Skor 100)</option>
                            <option value="streak" {{ old('syarat_tipe', $badge->syarat_tipe) === 'streak' ? 'selected' : '' }}>🔥 Hari Beruntun (Streak Belajar)</option>
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Nilai Syarat Minimal *</label>
                        <input type="number" name="syarat_nilai" class="admin-form-input" required value="{{ old('syarat_nilai', $badge->syarat_nilai) }}" min="1">
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px;">Target nilai / jumlah kuis / hari yang harus dicapai anak.</small>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 28px;">
                        <button type="submit" class="admin-btn admin-btn--primary">
                            💾 Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.badges.index') }}" class="admin-btn admin-btn--ghost">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
