@extends('admin.layouts.app')

@section('page-title', 'Edit Soal Quiz')

@section('content')

    <div style="max-width: 700px;">
        <div class="admin-card">
            <div class="admin-card__header">
                <h3 class="admin-card__title">✏️ Edit Soal #{{ $quiz->id }}</h3>
                <a href="{{ route('admin.quiz.index') }}" class="admin-btn admin-btn--ghost admin-btn--sm">← Kembali</a>
            </div>
            <div class="admin-card__body">

                @if($errors->any())
                    <div class="admin-flash admin-flash--error">
                        ❌ {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.quiz.update', $quiz->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="admin-form-group">
                        <label class="admin-form-label">Lesson *</label>
                        <select name="lesson_id" class="admin-form-select" required>
                            <option value="">Pilih Lesson...</option>
                            @foreach($lessons as $lesson)
                                <option value="{{ $lesson->id }}" {{ (old('lesson_id', $quiz->lesson_id)) == $lesson->id ? 'selected' : '' }}>
                                    {{ $lesson->judul }} ({{ $lesson->tipe_dunia }} — {{ $lesson->kategori_usia ?? 'N/A' }} th)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Tipe Soal *</label>
                        <select name="tipe" class="admin-form-select" required>
                            <option value="">Pilih Tipe...</option>
                            <option value="voice" {{ old('tipe', $quiz->tipe) === 'voice' ? 'selected' : '' }}>🎧 Audio (Tunanetra)</option>
                            <option value="image" {{ old('tipe', $quiz->tipe) === 'image' ? 'selected' : '' }}>👁️ Visual (Tunarungu)</option>
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Pertanyaan *</label>
                        <textarea name="pertanyaan" class="admin-form-textarea" required>{{ old('pertanyaan', $quiz->pertanyaan) }}</textarea>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Jawaban Benar *</label>
                        <input type="text" name="jawaban_benar" class="admin-form-input" required value="{{ old('jawaban_benar', $quiz->jawaban_benar) }}">
                    </div>

                    <div class="admin-form-group" id="pilihan-container">
                        <label class="admin-form-label">Pilihan Jawaban *</label>
                        <div style="display: flex; flex-direction: column; gap: 8px;" id="pilihan-list">
                            @php
                                $pilihan = old('pilihan', $quiz->pilihan ?? []);
                            @endphp
                            @foreach($pilihan as $i => $p)
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="text" name="pilihan[]" class="admin-form-input" required value="{{ $p }}" placeholder="Pilihan {{ $i + 1 }}">
                                    @if($i > 1)
                                        <button type="button" onclick="this.parentElement.remove()" class="admin-btn admin-btn--danger admin-btn--sm">✕</button>
                                    @endif
                                </div>
                            @endforeach
                            @if(count($pilihan) === 0)
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="text" name="pilihan[]" class="admin-form-input" required placeholder="Pilihan 1">
                                </div>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="text" name="pilihan[]" class="admin-form-input" required placeholder="Pilihan 2">
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addPilihan()" class="admin-btn admin-btn--ghost admin-btn--sm" style="margin-top: 8px;">
                            ➕ Tambah Pilihan
                        </button>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 28px;">
                        <button type="submit" class="admin-btn admin-btn--primary">
                            💾 Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.quiz.index') }}" class="admin-btn admin-btn--ghost">Batal</a>
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
                <input type="text" name="pilihan[]" class="admin-form-input" required placeholder="Pilihan ${pilihanCount}">
                <button type="button" onclick="this.parentElement.remove()" class="admin-btn admin-btn--danger admin-btn--sm">✕</button>
            `;
            container.appendChild(div);
        }
    </script>

@endsection
