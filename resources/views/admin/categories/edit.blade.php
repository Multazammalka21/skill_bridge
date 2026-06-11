@extends('admin.layouts.app')

@section('page-title', 'Edit Kategori')

@section('content')

<div style="max-width:680px;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('admin.categories.index') }}" style="color:var(--text-muted); text-decoration:none; font-size:0.85rem;">← Kembali ke Daftar Kategori</a>
        <h2 style="font-size:1.4rem; font-weight:800; margin-top:8px;">Edit Kategori: {{ $category->nama }}</h2>
    </div>

    <div class="card">
        <div class="card__header">
            <h3 class="card__title">🗂️ Informasi Kategori</h3>
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:0.8rem; color:var(--text-muted);">ID #{{ $category->id }}</span>
                @if($category->aktif)
                    <span class="badge" style="background:rgba(16,185,129,0.1); color:#059669;">✅ Aktif</span>
                @else
                    <span class="badge" style="background:rgba(239,68,68,0.1); color:#dc2626;">⛔ Nonaktif</span>
                @endif
            </div>
        </div>
        <div class="card__body">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama Kategori <span style="color:var(--red);">*</span></label>
                    <input type="text" name="nama" class="form-input"
                           value="{{ old('nama', $category->nama) }}" required maxlength="100">
                    @error('nama') <div style="color:var(--red); font-size:0.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-textarea" maxlength="500">{{ old('deskripsi', $category->deskripsi) }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Ikon (Emoji) <span style="color:var(--red);">*</span></label>
                        <input type="text" name="ikon" class="form-input"
                               value="{{ old('ikon', $category->ikon) }}" required maxlength="10"
                               style="font-size:1.5rem; text-align:center;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Warna Tema <span style="color:var(--red);">*</span></label>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <input type="color" name="warna" id="colorPicker"
                                   value="{{ old('warna', $category->warna) }}"
                                   style="width:50px; height:42px; border:1px solid var(--border); border-radius:8px; cursor:pointer; padding:2px;">
                            <input type="text" id="colorText" class="form-input"
                                   value="{{ old('warna', $category->warna) }}" placeholder="#3b82f6"
                                   style="font-family:monospace;" maxlength="20"
                                   oninput="document.getElementById('colorPicker').value=this.value">
                        </div>
                    </div>
                </div>

                {{-- Quick color presets --}}
                <div style="margin-bottom:20px;">
                    <div style="font-size:0.78rem; font-weight:600; color:var(--text-muted); margin-bottom:8px;">Warna Preset:</div>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        @foreach(['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4','#84cc16'] as $c)
                        <button type="button" onclick="setColor('{{ $c }}')"
                                style="width:28px; height:28px; background:{{ $c }}; border-radius:6px; border:2px solid transparent; cursor:pointer;"
                                title="{{ $c }}"></button>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Urutan Tampil <span style="color:var(--red);">*</span></label>
                    <input type="number" name="urutan" class="form-input"
                           value="{{ old('urutan', $category->urutan) }}" min="0" required>
                </div>

                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="checkbox" name="aktif" value="1" {{ old('aktif', $category->aktif) ? 'checked' : '' }}
                               style="width:18px; height:18px; cursor:pointer;">
                        <span style="font-weight:600; font-size:0.88rem;">Kategori aktif</span>
                    </label>
                </div>

                <div style="display:flex; gap:12px; padding-top:8px;">
                    <button type="submit" class="btn btn--primary">💾 Simpan Perubahan</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn--neutral">Batal</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Danger zone --}}
    @if($category->lessons_count ?? $category->lessons()->count() === 0)
    <div class="card" style="margin-top:20px; border-color:rgba(239,68,68,0.2);">
        <div class="card__header" style="background:rgba(239,68,68,0.03);">
            <h3 class="card__title" style="color:var(--red);">⚠️ Zona Berbahaya</h3>
        </div>
        <div class="card__body">
            <p style="font-size:0.85rem; color:var(--text-secondary); margin-bottom:16px;">
                Menghapus kategori akan menghilangkan asosiasi dengan semua materi. Aksi ini tidak dapat dibatalkan.
            </p>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus kategori {{ addslashes($category->nama) }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn--danger">🗑️ Hapus Kategori</button>
            </form>
        </div>
    </div>
    @endif
</div>

<script>
function setColor(hex) {
    document.getElementById('colorPicker').value = hex;
    document.getElementById('colorText').value = hex;
}
document.getElementById('colorPicker').addEventListener('input', function() {
    document.getElementById('colorText').value = this.value;
});
</script>

@endsection
