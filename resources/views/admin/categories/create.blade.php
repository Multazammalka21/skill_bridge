@extends('admin.layouts.app')

@section('page-title', 'Tambah Kategori')

@section('content')

<div style="max-width:680px;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('admin.categories.index') }}" style="color:var(--admin-text-muted); text-decoration:none; font-size:0.85rem;">← Kembali ke Daftar Kategori</a>
        <h2 style="font-size:1.4rem; font-weight:800; margin-top:8px;">Tambah Kategori Baru</h2>
        <p style="color:var(--admin-text-muted); font-size:0.85rem;">Contoh: Literasi, Numerasi, Pengenalan Lingkungan, Sains Dasar</p>
    </div>

    <div class="admin-card">
        <div class="admin-card__header">
            <h3 class="admin-card__title">🗂️ Informasi Kategori</h3>
        </div>
        <div class="admin-card__body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="admin-form-group">
                    <label class="admin-form-label">Nama Kategori <span style="color:var(--admin-danger);">*</span></label>
                    <input type="text" name="nama" class="admin-form-input" placeholder="Contoh: Literasi, Numerasi, Pengenalan Lingkungan"
                           value="{{ old('nama') }}" required maxlength="100">
                    @error('nama') <div style="color:var(--admin-danger); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="admin-form-textarea" placeholder="Deskripsi singkat kategori pembelajaran ini..." maxlength="500">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div style="color:var(--admin-danger); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Ikon (Emoji) <span style="color:var(--admin-danger);">*</span></label>
                        <input type="text" name="ikon" class="admin-form-input" placeholder="📚"
                               value="{{ old('ikon', '📚') }}" required maxlength="10"
                               style="font-size:1.5rem; text-align:center;">
                        <div style="font-size:0.75rem; color:var(--admin-text-muted); margin-top:4px;">Masukkan emoji yang mewakili kategori ini</div>
                        @error('ikon') <div style="color:var(--admin-danger); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-form-label">Warna Tema <span style="color:var(--admin-danger);">*</span></label>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <input type="color" name="warna" id="colorPicker"
                                   value="{{ old('warna', '#3b82f6') }}"
                                   style="width:50px; height:42px; border:1px solid var(--admin-border); border-radius:8px; cursor:pointer; padding:2px;">
                            <input type="text" id="colorText" class="admin-form-input"
                                   value="{{ old('warna', '#3b82f6') }}" placeholder="#3b82f6"
                                   style="font-family:monospace;" maxlength="20"
                                   oninput="document.getElementById('colorPicker').value=this.value">
                        </div>
                        <div style="font-size:0.75rem; color:var(--admin-text-muted); margin-top:4px;">Warna aksen untuk tampilan kategori di UI</div>
                        @error('warna') <div style="color:var(--admin-danger); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Quick color presets --}}
                <div style="margin-bottom:20px;">
                    <div style="font-size:0.78rem; font-weight:600; color:var(--admin-text-muted); margin-bottom:8px;">Warna Preset:</div>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        @foreach(['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4','#84cc16'] as $c)
                        <button type="button" onclick="setColor('{{ $c }}')"
                                style="width:28px; height:28px; background:{{ $c }}; border-radius:6px; border:2px solid transparent; cursor:pointer; transition:border-color 0.2s;"
                                title="{{ $c }}"></button>
                        @endforeach
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Urutan Tampil <span style="color:var(--admin-danger);">*</span></label>
                    <input type="number" name="urutan" class="admin-form-input" placeholder="0"
                           value="{{ old('urutan', 0) }}" min="0" required>
                    <div style="font-size:0.75rem; color:var(--admin-text-muted); margin-top:4px;">Angka kecil tampil lebih awal. Contoh: 0 = pertama, 1 = kedua, dst.</div>
                    @error('urutan') <div style="color:var(--admin-danger); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                {{-- Preview card --}}
                <div style="background:#f8fafc; border:1px solid var(--admin-border); border-radius:10px; padding:16px; margin-bottom:20px;">
                    <div style="font-size:0.78rem; font-weight:600; color:var(--admin-text-muted); margin-bottom:12px;">👁️ Preview Kartu Kategori:</div>
                    <div id="previewCard" style="display:inline-flex; align-items:center; gap:12px; background:#fff; border:2px solid #3b82f640; border-radius:12px; padding:12px 18px;">
                        <div id="previewIcon" style="width:44px; height:44px; border-radius:10px; background:#3b82f620; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">📚</div>
                        <div>
                            <div id="previewName" style="font-weight:700; color:#0f172a; font-size:0.95rem;">Nama Kategori</div>
                            <div id="previewColor" style="font-size:0.72rem; font-weight:600; color:#3b82f6;">#3b82f6</div>
                        </div>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="checkbox" name="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }}
                               style="width:18px; height:18px; cursor:pointer;">
                        <span style="font-weight:600; font-size:0.88rem;">Kategori aktif (tampil di sistem)</span>
                    </label>
                </div>

                <div style="display:flex; gap:12px; padding-top:8px;">
                    <button type="submit" class="admin-btn admin-btn--primary">✅ Simpan Kategori</button>
                    <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn--ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setColor(hex) {
    document.getElementById('colorPicker').value = hex;
    document.getElementById('colorText').value = hex;
    updatePreview();
}

document.getElementById('colorPicker').addEventListener('input', function() {
    document.getElementById('colorText').value = this.value;
    updatePreview();
});

document.querySelector('[name="nama"]').addEventListener('input', function() {
    document.getElementById('previewName').textContent = this.value || 'Nama Kategori';
});

document.querySelector('[name="ikon"]').addEventListener('input', function() {
    document.getElementById('previewIcon').textContent = this.value || '📚';
});

function updatePreview() {
    const color = document.getElementById('colorPicker').value;
    document.getElementById('previewColor').style.color = color;
    document.getElementById('previewColor').textContent = color;
    document.getElementById('previewIcon').style.background = color + '20';
    document.getElementById('previewCard').style.borderColor = color + '40';
}
</script>

@endsection
