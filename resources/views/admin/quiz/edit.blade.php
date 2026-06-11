@extends('admin.layouts.app')

@section('page-title', 'Edit Soal Quiz')

@section('content')

    <div style="max-width: 700px;">
        <div class="card">
            <div class="card__header">
                <h3 class="card__title">✏️ Edit Soal #{{ $quiz->id }}</h3>
                <a href="{{ route('admin.quiz.index') }}" class="btn btn--neutral btn--sm">← Kembali</a>
            </div>
            <div class="card__body">

                @if($errors->any())
                    <div class="alert--danger">
                        ❌ {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.quiz.update', $quiz->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Lesson *</label>
                        <select name="lesson_id" class="form-select" required>
                            <option value="">Pilih Lesson...</option>
                            @foreach($lessons as $lesson)
                                <option value="{{ $lesson->id }}" {{ (old('lesson_id', $quiz->lesson_id)) == $lesson->id ? 'selected' : '' }}>
                                    {{ $lesson->judul }} ({{ $lesson->tipe_dunia }} — {{ $lesson->kategori_usia ?? 'N/A' }} th)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipe Soal *</label>
                        <select name="tipe" class="form-select" required>
                            <option value="">Pilih Tipe...</option>
                            <option value="voice" {{ old('tipe', $quiz->tipe) === 'voice' ? 'selected' : '' }}>🎧 Audio (Tunanetra)</option>
                            <option value="image" {{ old('tipe', $quiz->tipe) === 'image' ? 'selected' : '' }}>👁️ Visual (Tunarungu)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pertanyaan *</label>
                        <textarea name="pertanyaan" class="form-textarea" required>{{ old('pertanyaan', $quiz->pertanyaan) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jawaban Benar *</label>
                        <input type="text" name="jawaban_benar" class="form-input" required value="{{ old('jawaban_benar', $quiz->jawaban_benar) }}">
                    </div>

                    <div class="form-group" id="pilihan-container">
                        <label class="form-label">Pilihan Jawaban *</label>
                        <div style="display: flex; flex-direction: column; gap: 8px;" id="pilihan-list">
                            @php
                                $pilihan = old('pilihan', $quiz->pilihan ?? []);
                            @endphp
                            @foreach($pilihan as $i => $p)
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="text" name="pilihan[]" class="form-input" required value="{{ $p }}" placeholder="Pilihan {{ $i + 1 }}">
                                    @if($i > 1)
                                        <button type="button" onclick="this.parentElement.remove()" class="btn btn--danger btn--sm">✕</button>
                                    @endif
                                </div>
                            @endforeach
                            @if(count($pilihan) === 0)
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="text" name="pilihan[]" class="form-input" required placeholder="Pilihan 1">
                                </div>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="text" name="pilihan[]" class="form-input" required placeholder="Pilihan 2">
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addPilihan()" class="btn btn--neutral btn--sm" style="margin-top: 8px;">
                            ➕ Tambah Pilihan
                        </button>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Gambar Soal (Opsional)</label>
                        @if($quiz->gambar)
                            <div style="margin-bottom: 8px;">
                                <img src="{{ asset($quiz->gambar) }}" alt="Gambar kuis" style="max-height: 100px; border-radius: 8px; border: 1px solid #ddd;">
                            </div>
                        @endif
                        <input type="file" name="gambar_file" class="form-input" accept="image/*">
                        <small class="admin-form-help" style="display: block; margin-top: 4px; color: #666;">Format JPG, PNG, WEBP. Maks 2MB. Untuk quiz visual tunarungu.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Audio Narasi Soal (Opsional)</label>
                        @if($quiz->audio_url)
                            <div style="margin-bottom: 8px;">
                                <audio controls src="{{ asset($quiz->audio_url) }}" style="height: 36px;"></audio>
                            </div>
                        @endif
                        <input type="file" name="audio_file" class="form-input" accept="audio/*">
                        <small class="admin-form-help" style="display: block; margin-top: 4px; color: #666;">Format MP3, WAV. Maks 5MB. Berguna untuk deskripsi suara kuis tunanetra.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Animasi Lottie URL (Opsional)</label>
                        <input type="text" name="animasi_url" class="form-input" value="{{ old('animasi_url', $quiz->animasi_url) }}" placeholder="https://assets.lottiefiles.com/...json">
                        <small class="admin-form-help" style="display: block; margin-top: 4px; color: #666;">Gunakan link animasi json. Bagus untuk quiz visual tunarungu.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Efek Suara Khas (Opsional)</label>
                        <input type="text" name="efek_suara_url" class="form-input" value="{{ old('efek_suara_url', $quiz->efek_suara_url) }}" placeholder="https://example.com/sound.mp3">
                        <small class="admin-form-help" style="display: block; margin-top: 4px; color: #666;">URL efek suara saat kuis dimuat.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Poin Soal *</label>
                        <input type="number" name="poin" class="form-input" required value="{{ old('poin', $quiz->poin ?? 10) }}" min="1">
                        <small class="admin-form-help" style="display: block; margin-top: 4px; color: #666;">Jumlah poin reward gamifikasi jika dijawab dengan benar.</small>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 28px;">
                        <button type="submit" class="btn btn--primary">
                            💾 Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.quiz.index') }}" class="btn btn--neutral">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        let pilihanCount = document.querySelectorAll('#pilihan-list input').length;

        function addPilihan() {
            pilihanCount++;
            const container = document.getElementById('pilihan-list');
            const div = document.createElement('div');
            div.style.display = 'flex';
            div.style.gap = '8px';
            div.style.alignItems = 'center';
            div.innerHTML = `
                <input type="text" name="pilihan[]" class="form-input" required placeholder="Pilihan ${pilihanCount}">
                <button type="button" onclick="this.parentElement.remove()" class="btn btn--danger btn--sm">✕</button>
            `;
            container.appendChild(div);
        }
    </script>

@endsection
