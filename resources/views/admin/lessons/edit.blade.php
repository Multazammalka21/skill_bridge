@extends('admin.layouts.app')

@section('page-title', 'Edit Materi')

@section('content')

<div style="max-width:860px;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('admin.lessons.index') }}" style="color:var(--admin-text-muted); text-decoration:none; font-size:0.85rem;">← Kembali ke Daftar Materi</a>
        <h2 style="font-size:1.4rem; font-weight:800; margin-top:8px;">Edit Materi: {{ Str::limit($lesson->judul, 50) }}</h2>
    </div>

    <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Blok 1: Informasi Dasar --}}
        <div class="admin-card" style="margin-bottom:20px;">
            <div class="admin-card__header">
                <h3 class="admin-card__title">📋 Informasi Dasar</h3>
                <span style="font-size:0.78rem; color:var(--admin-text-muted);">ID #{{ $lesson->id }}</span>
            </div>
            <div class="admin-card__body">
                <div class="admin-form-group">
                    <label class="admin-form-label">Judul Materi <span style="color:var(--admin-danger);">*</span></label>
                    <input type="text" name="judul" class="admin-form-input"
                           value="{{ old('judul', $lesson->judul) }}" required maxlength="200">
                    @error('judul') <div style="color:var(--admin-danger); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="admin-form-textarea" rows="3" maxlength="1000">{{ old('deskripsi', $lesson->deskripsi) }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Kategori Pembelajaran</label>
                        <select name="category_id" class="admin-form-select">
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $lesson->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->ikon }} {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Tipe Dunia <span style="color:var(--admin-danger);">*</span></label>
                        <select name="tipe_dunia" class="admin-form-select" required>
                            <option value="audio" {{ old('tipe_dunia', $lesson->tipe_dunia) == 'audio' ? 'selected' : '' }}>🎧 Audio (Tunanetra)</option>
                            <option value="visual" {{ old('tipe_dunia', $lesson->tipe_dunia) == 'visual' ? 'selected' : '' }}>👁️ Visual (Tunarungu)</option>
                        </select>
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Kategori Usia <span style="color:var(--admin-danger);">*</span></label>
                        <select name="kategori_usia" class="admin-form-select" required>
                            <option value="5-7" {{ old('kategori_usia', $lesson->kategori_usia) == '5-7' ? 'selected' : '' }}>👶 5–7 Tahun</option>
                            <option value="8-10" {{ old('kategori_usia', $lesson->kategori_usia) == '8-10' ? 'selected' : '' }}>🧒 8–10 Tahun</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Urutan Tampil <span style="color:var(--admin-danger);">*</span></label>
                        <input type="number" name="urutan" class="admin-form-input" value="{{ old('urutan', $lesson->urutan) }}" min="0" required>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Durasi (Menit) <span style="color:var(--admin-danger);">*</span></label>
                        <input type="number" name="durasi_menit" class="admin-form-input" value="{{ old('durasi_menit', $lesson->durasi_menit) }}" min="1" max="120" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Blok 2: Learning Path --}}
        <div class="admin-card" style="margin-bottom:20px;">
            <div class="admin-card__header">
                <h3 class="admin-card__title">🗺️ Learning Path (Prasyarat)</h3>
            </div>
            <div class="admin-card__body">
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">Prasyarat Lesson (opsional)</label>
                    <select name="prerequisite_lesson_id" class="admin-form-select">
                        <option value="">— Tidak ada prasyarat —</option>
                        @foreach($allLessons as $l)
                            <option value="{{ $l->id }}" {{ old('prerequisite_lesson_id', $lesson->prerequisite_lesson_id) == $l->id ? 'selected' : '' }}>
                                [#{{ $l->id }}] {{ $l->judul }}
                                ({{ $l->tipe_dunia === 'audio' ? 'Tunanetra' : 'Tunarungu' }}, Usia {{ $l->kategori_usia }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Blok 3: Konten --}}
        <div class="admin-card" style="margin-bottom:20px;">
            <div class="admin-card__header">
                <h3 class="admin-card__title">🎨 Konten Pembelajaran</h3>
            </div>
            <div class="admin-card__body">
                <div class="admin-form-group">
                    <label class="admin-form-label">Tipe Konten Utama</label>
                    <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:10px; margin-top:4px;">
                        @foreach([
                            ['audio_story','🎙️','Audio Story'],
                            ['gambar_interaktif','🖼️','Gambar Interaktif'],
                            ['animasi_lottie','✨','Animasi Lottie'],
                            ['teks','📝','Teks'],
                            ['campuran','🎨','Campuran'],
                        ] as [$val, $ico, $lbl])
                        <label style="border:2px solid {{ old('konten_tipe', $lesson->konten_tipe) == $val ? 'var(--admin-primary)' : 'var(--admin-border)' }};
                                      background:{{ old('konten_tipe', $lesson->konten_tipe) == $val ? 'rgba(59,130,246,0.06)' : '#fafafa' }};
                                      border-radius:10px; padding:12px 8px; text-align:center; cursor:pointer;" class="konten-type-label">
                            <input type="radio" name="konten_tipe" value="{{ $val }}"
                                   {{ old('konten_tipe', $lesson->konten_tipe) == $val ? 'checked' : '' }}
                                   style="display:none;">
                            <div style="font-size:1.4rem;">{{ $ico }}</div>
                            <div style="font-size:0.72rem; font-weight:600; margin-top:4px; color:var(--admin-text-secondary);">{{ $lbl }}</div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">📝 Teks Narasi</label>
                    <textarea name="teks_narasi" class="admin-form-textarea" rows="4">{{ old('teks_narasi', $lesson->teks_narasi) }}</textarea>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">📄 Teks Keterangan</label>
                    <textarea name="teks_keterangan" class="admin-form-textarea" rows="3">{{ old('teks_keterangan', $lesson->teks_keterangan) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Blok 4: Media --}}
        <div class="admin-card" style="margin-bottom:20px;">
            <div class="admin-card__header">
                <h3 class="admin-card__title">🎵 Media Pembelajaran</h3>
            </div>
            <div class="admin-card__body">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    {{-- Gambar --}}
                    <div class="admin-form-group">
                        <label class="admin-form-label">🖼️ Gambar Utama</label>
                        @if($lesson->gambar)
                            <div style="margin-bottom:10px; padding:10px; background:#f8fafc; border-radius:8px; border:1px solid var(--admin-border);">
                                <img src="{{ $lesson->gambar }}" style="max-height:80px; border-radius:6px; max-width:100%; display:block; margin-bottom:8px;">
                                <label style="display:flex; align-items:center; gap:6px; font-size:0.78rem; cursor:pointer; color:var(--admin-danger);">
                                    <input type="checkbox" name="hapus_gambar" value="1"> Hapus gambar saat ini
                                </label>
                            </div>
                        @endif
                        <input type="file" name="gambar_file" class="admin-form-input" accept="image/*"
                               style="padding:6px; font-size:0.85rem;">
                        <div style="font-size:0.72rem; color:var(--admin-text-muted); margin-top:4px;">JPG, PNG, WebP • Maks 3MB. Kosongkan jika tidak ganti.</div>
                        @error('gambar_file') <div style="color:var(--admin-danger); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                    </div>

                    {{-- Audio --}}
                    <div class="admin-form-group">
                        <label class="admin-form-label">🎵 Efek Suara / Audio</label>
                        @if($lesson->efek_suara)
                            <div style="margin-bottom:10px; padding:10px; background:#f8fafc; border-radius:8px; border:1px solid var(--admin-border);">
                                <audio controls style="width:100%; height:32px; margin-bottom:8px;">
                                    <source src="{{ $lesson->efek_suara }}">
                                </audio>
                                <label style="display:flex; align-items:center; gap:6px; font-size:0.78rem; cursor:pointer; color:var(--admin-danger);">
                                    <input type="checkbox" name="hapus_audio" value="1"> Hapus audio saat ini
                                </label>
                            </div>
                        @endif
                        <input type="file" name="efek_suara_file" class="admin-form-input" accept=".mp3,.wav,.ogg,.m4a"
                               style="padding:6px; font-size:0.85rem;">
                        <div style="font-size:0.72rem; color:var(--admin-text-muted); margin-top:4px;">MP3, WAV, OGG • Maks 10MB. Kosongkan jika tidak ganti.</div>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">✨ URL Animasi Lottie (JSON)</label>
                    <input type="text" name="animasi_lottie" class="admin-form-input"
                           value="{{ old('animasi_lottie', $lesson->animasi_lottie) }}"
                           placeholder="https://... atau /storage/media/lottie/file.json">
                </div>

                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">🎙️ Audio Cerita (Storytelling)</label>

                    @if($lesson->audio_story_url)
                        <div style="margin-bottom:10px; padding:12px; background:#f0fdf4; border-radius:8px; border:1px solid #bbf7d0;">
                            <div style="font-size:0.78rem; color:#166534; font-weight:600; margin-bottom:6px;">✅ Audio storytelling terpasang:</div>
                            <audio controls style="width:100%; height:36px; margin-bottom:8px;">
                                <source src="{{ $lesson->audio_story_url }}">
                                Browser Anda tidak mendukung audio player.
                            </audio>
                            <div style="font-size:0.72rem; color:#166534;">{{ $lesson->audio_story_url }}</div>
                        </div>
                    @else
                        <div style="margin-bottom:10px; padding:10px; background:#fef9c3; border-radius:8px; border:1px solid #fde68a;">
                            <div style="font-size:0.78rem; color:#92400e;">⚠️ Belum ada audio storytelling. Mode Tunanetra akan menggunakan Text-to-Speech sebagai fallback.</div>
                        </div>
                    @endif

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div>
                            <label style="font-size:0.78rem; color:var(--admin-text-muted); display:block; margin-bottom:4px;">Upload file audio (MP3/WAV):</label>
                            <input type="file" name="audio_story_file" class="admin-form-input" accept=".mp3,.wav,.ogg,.m4a"
                                   style="padding:6px; font-size:0.85rem;">
                            <div style="font-size:0.72rem; color:var(--admin-text-muted); margin-top:4px;">MP3, WAV, OGG • Maks 20MB</div>
                            @error('audio_story_file') <div style="color:var(--admin-danger); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label style="font-size:0.78rem; color:var(--admin-text-muted); display:block; margin-bottom:4px;">Atau masukkan URL manual:</label>
                            <input type="text" name="audio_story_url" class="admin-form-input"
                                   value="{{ old('audio_story_url', $lesson->audio_story_url) }}"
                                   placeholder="/audio/nama-cerita.mp3 atau https://...">
                            <div style="font-size:0.72rem; color:var(--admin-text-muted); margin-top:4px;">URL manual akan dipakai jika tidak ada file yang diupload.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pengaturan --}}
        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card__header"><h3 class="admin-card__title">⚙️ Pengaturan</h3></div>
            <div class="admin-card__body">
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="aktif" value="1" {{ old('aktif', $lesson->aktif) ? 'checked' : '' }}
                           style="width:18px; height:18px; cursor:pointer;">
                    <span style="font-weight:600; font-size:0.88rem;">Materi aktif</span>
                </label>
            </div>
        </div>

        <div style="display:flex; gap:12px;">
            <button type="submit" class="admin-btn admin-btn--primary" style="padding:10px 28px;">💾 Simpan Perubahan</button>
            <a href="{{ route('admin.lessons.index') }}" class="admin-btn admin-btn--ghost">Batal</a>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('.konten-type-label input').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.konten-type-label').forEach(l => {
            l.style.borderColor = 'var(--admin-border)';
            l.style.background = '#fafafa';
        });
        if (this.checked) {
            this.closest('.konten-type-label').style.borderColor = 'var(--admin-primary)';
            this.closest('.konten-type-label').style.background = 'rgba(59,130,246,0.06)';
        }
    });
});
</script>

@endsection
