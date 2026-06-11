@extends('admin.layouts.app')

@section('page-title', 'Tambah Materi Pembelajaran')

@section('content')

<div style="max-width:860px;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('admin.lessons.index') }}" style="color:var(--text-muted); text-decoration:none; font-size:0.85rem;">← Kembali ke Daftar Materi</a>
        <h2 style="font-size:1.4rem; font-weight:800; margin-top:8px;">Tambah Materi Pembelajaran</h2>
        <p style="color:var(--text-muted); font-size:0.85rem;">Buat lesson dengan konten audio storytelling, gambar interaktif, animasi Lottie, atau teks sederhana</p>
    </div>

    <form action="{{ route('admin.lessons.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ── Blok 1: Informasi Dasar ──────────────────────────────── --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card__header">
                <h3 class="card__title">📋 Informasi Dasar</h3>
            </div>
            <div class="card__body">
                <div class="form-group">
                    <label class="form-label">Judul Materi <span style="color:var(--red);">*</span></label>
                    <input type="text" name="judul" class="form-input" placeholder="Contoh: Mengenal Huruf A"
                           value="{{ old('judul') }}" required maxlength="200">
                    @error('judul') <div style="color:var(--red); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-textarea" placeholder="Deskripsi singkat tentang materi ini..." rows="3" maxlength="1000">{{ old('deskripsi') }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Kategori Pembelajaran</label>
                        <select name="category_id" class="form-select">
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->ikon }} {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div style="color:var(--red); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipe Dunia <span style="color:var(--red);">*</span></label>
                        <select name="tipe_dunia" class="form-select" required>
                            <option value="">— Pilih Tipe —</option>
                            <option value="audio" {{ old('tipe_dunia') == 'audio' ? 'selected' : '' }}>🎧 Audio (Tunanetra)</option>
                            <option value="visual" {{ old('tipe_dunia') == 'visual' ? 'selected' : '' }}>👁️ Visual (Tunarungu)</option>
                        </select>
                        @error('tipe_dunia') <div style="color:var(--red); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori Usia <span style="color:var(--red);">*</span></label>
                        <select name="kategori_usia" class="form-select" required>
                            <option value="">— Pilih Usia —</option>
                            <option value="5-7" {{ old('kategori_usia') == '5-7' ? 'selected' : '' }}>👶 5–7 Tahun</option>
                            <option value="8-10" {{ old('kategori_usia') == '8-10' ? 'selected' : '' }}>🧒 8–10 Tahun</option>
                        </select>
                        @error('kategori_usia') <div style="color:var(--red); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Urutan Tampil <span style="color:var(--red);">*</span></label>
                        <input type="number" name="urutan" class="form-input" value="{{ old('urutan', 0) }}" min="0" required>
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:4px;">Angka kecil tampil lebih dahulu</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Durasi (Menit) <span style="color:var(--red);">*</span></label>
                        <input type="number" name="durasi_menit" class="form-input" value="{{ old('durasi_menit', 5) }}" min="1" max="120" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Blok 2: Learning Path ─────────────────────────────────── --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card__header">
                <h3 class="card__title">🗺️ Learning Path (Prasyarat)</h3>
            </div>
            <div class="card__body">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Prasyarat Lesson (opsional)</label>
                    <select name="prerequisite_lesson_id" class="form-select">
                        <option value="">— Tidak ada prasyarat (lesson bebas dibuka) —</option>
                        @foreach($allLessons as $l)
                            <option value="{{ $l->id }}" {{ old('prerequisite_lesson_id') == $l->id ? 'selected' : '' }}>
                                [#{{ $l->id }}] {{ $l->judul }}
                                ({{ $l->tipe_dunia === 'audio' ? 'Tunanetra' : 'Tunarungu' }}, Usia {{ $l->kategori_usia }})
                            </option>
                        @endforeach
                    </select>
                    <div style="font-size:0.78rem; color:var(--text-muted); margin-top:6px;">
                        💡 Jika dipilih, anak harus menyelesaikan lesson tersebut sebelum bisa mengakses materi ini.
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Blok 3: Konten Pembelajaran ──────────────────────────── --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card__header">
                <h3 class="card__title">🎨 Konten Pembelajaran</h3>
            </div>
            <div class="card__body">
                <div class="form-group">
                    <label class="form-label">Tipe Konten Utama</label>
                    <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:10px; margin-top:4px;">
                        @foreach([
                            ['audio_story','🎙️','Audio Story'],
                            ['gambar_interaktif','🖼️','Gambar Interaktif'],
                            ['animasi_lottie','✨','Animasi Lottie'],
                            ['teks','📝','Teks'],
                            ['campuran','🎨','Campuran'],
                        ] as [$val, $ico, $lbl])
                        <label style="border:2px solid var(--border); border-radius:10px; padding:12px 8px; text-align:center; cursor:pointer; transition:all 0.15s;"
                               class="konten-type-label">
                            <input type="radio" name="konten_tipe" value="{{ $val }}" {{ old('konten_tipe') == $val ? 'checked' : '' }}
                                   style="display:none;" onchange="updateKontenType(this)">
                            <div style="font-size:1.4rem;">{{ $ico }}</div>
                            <div style="font-size:0.72rem; font-weight:600; margin-top:4px; color:var(--text-secondary);">{{ $lbl }}</div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Teks Narasi --}}
                <div class="form-group">
                    <label class="form-label">📝 Teks Narasi</label>
                    <textarea name="teks_narasi" class="form-textarea" rows="4"
                              placeholder="Teks narasi yang dibacakan atau ditampilkan kepada anak...">{{ old('teks_narasi') }}</textarea>
                    <div style="font-size:0.75rem; color:var(--text-muted); margin-top:4px;">Untuk tunanetra: teks ini akan dikonversi ke suara (TTS). Untuk tunarungu: teks ini ditampilkan di layar.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">📄 Teks Keterangan</label>
                    <textarea name="teks_keterangan" class="form-textarea" rows="3"
                              placeholder="Keterangan tambahan, petunjuk belajar, atau catatan untuk anak...">{{ old('teks_keterangan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── Blok 4: Media Upload ──────────────────────────────────── --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card__header">
                <h3 class="card__title">🎵 Media Pembelajaran</h3>
            </div>
            <div class="card__body">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                    {{-- Gambar --}}
                    <div class="form-group">
                        <label class="form-label">🖼️ Gambar Utama</label>
                        <div id="imageDropzone" style="border:2px dashed var(--border); border-radius:10px; padding:24px; text-align:center; cursor:pointer; transition:all 0.2s; background:#fafafa;"
                             onclick="document.getElementById('gambar_file').click()"
                             ondragover="event.preventDefault(); this.style.borderColor='var(--teal)'"
                             ondragleave="this.style.borderColor='var(--border)'"
                             ondrop="handleDrop(event,'gambar_file')">
                            <div id="imagePreview" style="display:none; margin-bottom:8px;">
                                <img id="imagePreviewImg" style="max-height:120px; border-radius:8px; max-width:100%;">
                            </div>
                            <div id="imageDropText">
                                <div style="font-size:1.8rem;">🖼️</div>
                                <div style="font-size:0.82rem; color:var(--text-muted); margin-top:6px;">Klik atau drag gambar ke sini</div>
                                <div style="font-size:0.72rem; color:var(--text-muted);">JPG, PNG, WebP • Maks 3MB</div>
                            </div>
                        </div>
                        <input type="file" id="gambar_file" name="gambar_file" accept="image/*" style="display:none"
                               onchange="previewImage(this,'imagePreviewImg','imagePreview','imageDropText')">
                        @error('gambar_file') <div style="color:var(--red); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                    </div>

                    {{-- Audio Efek Suara --}}
                    <div class="form-group">
                        <label class="form-label">🎵 Efek Suara / Audio</label>
                        <div id="audioDropzone" style="border:2px dashed var(--border); border-radius:10px; padding:24px; text-align:center; cursor:pointer; transition:all 0.2s; background:#fafafa;"
                             onclick="document.getElementById('efek_suara_file').click()">
                            <div id="audioInfo" style="display:none; margin-bottom:8px; padding:8px; background:rgba(124,58,237,0.08); border-radius:8px;">
                                <div style="font-size:0.85rem; font-weight:600; color:#7c3aed;" id="audioName"></div>
                            </div>
                            <div id="audioDropText">
                                <div style="font-size:1.8rem;">🎵</div>
                                <div style="font-size:0.82rem; color:var(--text-muted); margin-top:6px;">Klik untuk pilih audio</div>
                                <div style="font-size:0.72rem; color:var(--text-muted);">MP3, WAV, OGG • Maks 10MB</div>
                            </div>
                        </div>
                        <input type="file" id="efek_suara_file" name="efek_suara_file" accept=".mp3,.wav,.ogg,.m4a" style="display:none"
                               onchange="previewAudio(this)">
                        @error('efek_suara_file') <div style="color:var(--red); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Animasi Lottie URL --}}
                <div class="form-group">
                    <label class="form-label">✨ URL Animasi Lottie (JSON)</label>
                    <input type="text" name="animasi_lottie" class="form-input"
                           placeholder="https://assets.lottiefiles.com/... atau /storage/media/lottie/file.json"
                           value="{{ old('animasi_lottie') }}">
                    <div style="font-size:0.75rem; color:var(--text-muted); margin-top:4px;">
                        💡 Upload file JSON Lottie terlebih dahulu di <a href="{{ route('admin.media.index') }}" style="color:var(--teal);">Library Media</a>, lalu salin URL-nya ke sini.
                    </div>
                </div>

                {{-- Audio Story --}}
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">🎙️ Audio Cerita (Storytelling)</label>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div>
                            <label style="font-size:0.78rem; color:var(--text-muted); display:block; margin-bottom:4px;">Upload file audio (MP3/WAV):</label>
                            <input type="file" name="audio_story_file" class="form-input" accept=".mp3,.wav,.ogg,.m4a"
                                   style="padding:6px; font-size:0.85rem;">
                            <div style="font-size:0.72rem; color:var(--text-muted); margin-top:4px;">MP3, WAV, OGG • Maks 20MB</div>
                            @error('audio_story_file') <div style="color:var(--red); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label style="font-size:0.78rem; color:var(--text-muted); display:block; margin-bottom:4px;">Atau masukkan URL manual:</label>
                            <input type="text" name="audio_story_url" class="form-input"
                                   value="{{ old('audio_story_url') }}"
                                   placeholder="/audio/nama-cerita.mp3 atau https://...">
                            <div style="font-size:0.72rem; color:var(--text-muted); margin-top:4px;">URL manual akan dipakai jika tidak ada file yang diupload.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Blok 5: Pengaturan ────────────────────────────────────── --}}
        <div class="card" style="margin-bottom:24px;">
            <div class="card__header">
                <h3 class="card__title">⚙️ Pengaturan</h3>
            </div>
            <div class="card__body">
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }}
                           style="width:18px; height:18px; cursor:pointer;">
                    <div>
                        <div style="font-weight:600; font-size:0.88rem;">Materi aktif</div>
                        <div style="font-size:0.75rem; color:var(--text-muted);">Jika dicentang, materi ini akan tersedia untuk anak</div>
                    </div>
                </label>
            </div>
        </div>

        <div style="display:flex; gap:12px;">
            <button type="submit" class="btn btn--primary" style="padding:10px 28px;">✅ Simpan Materi</button>
            <a href="{{ route('admin.lessons.index') }}" class="btn btn--neutral">Batal</a>
        </div>

    </form>
</div>

<style>
.konten-type-label input:checked ~ div,
.konten-type-label:has(input:checked) { border-color: var(--teal) !important; background: rgba(59,130,246,0.06) !important; }
</style>

<script>
function previewImage(input, imgId, previewId, textId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById(imgId).src = e.target.result;
            document.getElementById(previewId).style.display = 'block';
            document.getElementById(textId).style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewAudio(input) {
    if (input.files && input.files[0]) {
        document.getElementById('audioName').textContent = '🎵 ' + input.files[0].name;
        document.getElementById('audioInfo').style.display = 'block';
        document.getElementById('audioDropText').style.display = 'none';
    }
}

function handleDrop(event, inputId) {
    event.preventDefault();
    const input = document.getElementById(inputId);
    input.files = event.dataTransfer.files;
    input.dispatchEvent(new Event('change'));
    event.target.style.borderColor = 'var(--border)';
}

// Highlight selected konten type
document.querySelectorAll('.konten-type-label input').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.konten-type-label').forEach(l => {
            l.style.borderColor = 'var(--border)';
            l.style.background = '#fafafa';
        });
        if (this.checked) {
            this.closest('.konten-type-label').style.borderColor = 'var(--teal)';
            this.closest('.konten-type-label').style.background = 'rgba(59,130,246,0.06)';
        }
    });
});
</script>

@endsection
