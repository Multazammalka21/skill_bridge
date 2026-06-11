@extends('admin.layouts.app')

@section('page-title', 'Edit Badge Pencapaian')

@section('content')

    <div style="max-width: 600px;">
        <div class="card">
            <div class="card__header">
                <h3 class="card__title">🏆 Edit Badge Pencapaian</h3>
                <a href="{{ route('admin.badges.index') }}" class="btn btn--neutral btn--sm">← Kembali</a>
            </div>
            <div class="card__body">

                @if($errors->any())
                    <div class="alert--danger">
                        ❌ {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.badges.update', $badge->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Nama Badge *</label>
                        <input type="text" name="nama" class="form-input" required value="{{ old('nama', $badge->nama) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ikon Badge (Emoji) *</label>
                        <input type="text" name="ikon" class="form-input" required value="{{ old('ikon', $badge->ikon) }}">
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px;">Gunakan emoji atau teks pendek untuk merepresentasikan badge ini.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi Badge *</label>
                        <input type="text" name="deskripsi" class="form-input" required value="{{ old('deskripsi', $badge->deskripsi) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipe Syarat *</label>
                        <select name="syarat_tipe" class="form-select" required>
                            <option value="">Pilih Syarat...</option>
                            <option value="quiz_count" {{ old('syarat_tipe', $badge->syarat_tipe) === 'quiz_count' ? 'selected' : '' }}>📚 Jumlah Kuis Selesai</option>
                            <option value="perfect_score" {{ old('syarat_tipe', $badge->syarat_tipe) === 'perfect_score' ? 'selected' : '' }}>💯 Kuis Nilai Sempurna (Skor 100)</option>
                            <option value="streak" {{ old('syarat_tipe', $badge->syarat_tipe) === 'streak' ? 'selected' : '' }}>🔥 Hari Beruntun (Streak Belajar)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nilai Syarat Minimal *</label>
                        <input type="number" name="syarat_nilai" class="form-input" required value="{{ old('syarat_nilai', $badge->syarat_nilai) }}" min="1">
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 4px;">Target nilai / jumlah kuis / hari yang harus dicapai anak.</small>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 28px;">
                        <button type="submit" class="btn btn--primary">
                            💾 Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.badges.index') }}" class="btn btn--neutral">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
