@extends('admin.layouts.app')

@section('page-title', 'Library Media')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:1.4rem; font-weight:800; color:var(--admin-text-primary); margin-bottom:4px;">Library Media Aset</h2>
        <p style="color:var(--admin-text-muted); font-size:0.85rem;">Upload dan kelola file MP3, gambar, GIF, dan animasi Lottie JSON</p>
    </div>
    <button onclick="document.getElementById('uploadModal').style.display='flex'" class="admin-btn admin-btn--primary">
        ⬆️ Upload Aset Baru
    </button>
</div>

{{-- Upload Modal --}}
<div id="uploadModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; padding:32px; width:100%; max-width:500px; box-shadow:0 20px 60px rgba(0,0,0,0.3); margin:20px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
            <h3 style="font-size:1.1rem; font-weight:700;">⬆️ Upload Aset Media</h3>
            <button onclick="document.getElementById('uploadModal').style.display='none'"
                    style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:var(--admin-text-muted);">×</button>
        </div>

        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="admin-form-group">
                <label class="admin-form-label">Nama Aset <span style="color:var(--admin-danger);">*</span></label>
                <input type="text" name="nama" class="admin-form-input" placeholder="Nama deskriptif untuk aset ini" required maxlength="200">
            </div>

            {{-- Drag & drop zone --}}
            <div class="admin-form-group">
                <label class="admin-form-label">File <span style="color:var(--admin-danger);">*</span></label>
                <div id="fileDropzone"
                     style="border:2px dashed var(--admin-border); border-radius:12px; padding:32px 20px; text-align:center; cursor:pointer; transition:all 0.2s; background:#fafafa;"
                     onclick="document.getElementById('mediaFile').click()"
                     ondragover="event.preventDefault(); this.style.borderColor='var(--admin-primary)'; this.style.background='rgba(59,130,246,0.04)'"
                     ondragleave="this.style.borderColor='var(--admin-border)'; this.style.background='#fafafa'"
                     ondrop="handleFileDrop(event)">
                    <div id="dropzoneContent">
                        <div style="font-size:2.5rem; margin-bottom:10px;">📁</div>
                        <div style="font-weight:600; font-size:0.92rem; color:var(--admin-text-primary); margin-bottom:4px;">Klik atau drag file ke sini</div>
                        <div style="font-size:0.78rem; color:var(--admin-text-muted);">MP3/WAV/OGG · JPG/PNG/WebP · GIF · JSON (Lottie)</div>
                        <div style="font-size:0.72rem; color:var(--admin-text-muted); margin-top:4px;">Maksimum 20MB per file</div>
                    </div>
                    <div id="fileSelectedInfo" style="display:none;">
                        <div style="font-size:2rem; margin-bottom:8px;" id="fileTypeEmoji">📎</div>
                        <div style="font-weight:600; font-size:0.88rem;" id="fileName"></div>
                        <div style="font-size:0.75rem; color:var(--admin-text-muted);" id="fileSize"></div>
                    </div>
                </div>
                <input type="file" id="mediaFile" name="file" style="display:none" required
                       accept=".mp3,.wav,.ogg,.m4a,.aac,.jpg,.jpeg,.png,.webp,.svg,.gif,.json"
                       onchange="showFileInfo(this)">
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label">Keterangan (opsional)</label>
                <textarea name="keterangan" class="admin-form-textarea" rows="2" maxlength="500"
                          placeholder="Catatan singkat tentang aset ini..."></textarea>
            </div>

            <div style="display:flex; gap:12px;">
                <button type="submit" class="admin-btn admin-btn--primary" style="flex:1; justify-content:center;">⬆️ Upload Sekarang</button>
                <button type="button" onclick="document.getElementById('uploadModal').style.display='none'" class="admin-btn admin-btn--ghost">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- Stats + Type Filter tabs --}}
<div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
    <a href="{{ route('admin.media.index') }}" class="admin-btn {{ !$tipe ? 'admin-btn--primary' : 'admin-btn--ghost' }}">
        📁 Semua <span style="margin-left:4px; opacity:0.7;">({{ $counts['all'] }})</span>
    </a>
    <a href="{{ route('admin.media.index', ['tipe'=>'audio']) }}" class="admin-btn {{ $tipe=='audio' ? 'admin-btn--primary' : 'admin-btn--ghost' }}">
        🎵 Audio <span style="margin-left:4px; opacity:0.7;">({{ $counts['audio'] }})</span>
    </a>
    <a href="{{ route('admin.media.index', ['tipe'=>'image']) }}" class="admin-btn {{ $tipe=='image' ? 'admin-btn--primary' : 'admin-btn--ghost' }}">
        🖼️ Gambar <span style="margin-left:4px; opacity:0.7;">({{ $counts['image'] }})</span>
    </a>
    <a href="{{ route('admin.media.index', ['tipe'=>'gif']) }}" class="admin-btn {{ $tipe=='gif' ? 'admin-btn--primary' : 'admin-btn--ghost' }}">
        🎞️ GIF <span style="margin-left:4px; opacity:0.7;">({{ $counts['gif'] }})</span>
    </a>
    <a href="{{ route('admin.media.index', ['tipe'=>'lottie']) }}" class="admin-btn {{ $tipe=='lottie' ? 'admin-btn--primary' : 'admin-btn--ghost' }}">
        ✨ Lottie <span style="margin-left:4px; opacity:0.7;">({{ $counts['lottie'] }})</span>
    </a>
</div>

{{-- Media Grid --}}
@if($assets->isEmpty())
    <div style="text-align:center; padding:80px 20px; background:#fff; border-radius:12px; border:1px solid var(--admin-border);">
        <div style="font-size:4rem; margin-bottom:16px;">📁</div>
        <h3 style="font-weight:700; margin-bottom:8px;">Library kosong</h3>
        <p style="color:var(--admin-text-muted); font-size:0.88rem; margin-bottom:20px;">Upload aset media pertama untuk mulai mengisi library pembelajaran</p>
        <button onclick="document.getElementById('uploadModal').style.display='flex'" class="admin-btn admin-btn--primary">⬆️ Upload Aset Pertama</button>
    </div>
@else
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:16px;">
        @foreach($assets as $asset)
        <div style="background:#fff; border:1px solid var(--admin-border); border-radius:12px; overflow:hidden; transition:all 0.2s; position:relative;"
             onmouseenter="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.10)'; this.style.transform='translateY(-2px)'"
             onmouseleave="this.style.boxShadow='none'; this.style.transform='none'">

            {{-- Preview area --}}
            <div style="height:130px; background:#f8fafc; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden;">
                @if($asset->tipe === 'image' || $asset->tipe === 'gif')
                    <img src="{{ asset($asset->url) }}" alt="{{ $asset->nama }}"
                         style="max-width:100%; max-height:130px; object-fit:cover;">
                @elseif($asset->tipe === 'audio')
                    <div style="text-align:center; padding:16px;">
                        <div style="font-size:2.5rem; margin-bottom:6px;">🎵</div>
                        <audio controls style="width:100%; height:28px;" preload="none">
                            <source src="{{ asset($asset->url) }}">
                        </audio>
                    </div>
                @else
                    <div style="text-align:center;">
                        <div style="font-size:3rem;">✨</div>
                        <div style="font-size:0.72rem; color:var(--admin-text-muted);">JSON Lottie</div>
                    </div>
                @endif

                {{-- Type badge --}}
                <div style="position:absolute; top:8px; left:8px;">
                    <span style="background:rgba(0,0,0,0.6); color:#fff; font-size:0.68rem; font-weight:700; padding:2px 8px; border-radius:20px; text-transform:uppercase;">
                        {{ $asset->tipe_ikon }} {{ $asset->tipe }}
                    </span>
                </div>
            </div>

            {{-- Info --}}
            <div style="padding:12px;">
                <div style="font-weight:700; font-size:0.85rem; color:var(--admin-text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:2px;" title="{{ $asset->nama }}">
                    {{ $asset->nama }}
                </div>
                <div style="font-size:0.72rem; color:var(--admin-text-muted);">{{ $asset->readable_size }}</div>

                {{-- URL copy box --}}
                <div style="margin-top:10px; background:#f1f5f9; border-radius:6px; padding:6px 8px; display:flex; align-items:center; gap:6px;">
                    <code style="font-size:0.65rem; color:var(--admin-text-secondary); flex:1; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;">{{ asset($asset->url) }}</code>
                    <button onclick="copyUrl('{{ asset($asset->url) }}', this)"
                            style="background:none; border:none; cursor:pointer; font-size:0.9rem; padding:0; flex-shrink:0;"
                            title="Salin URL">📋</button>
                </div>

                {{-- Delete action --}}
                <form action="{{ route('admin.media.destroy', $asset) }}" method="POST"
                      onsubmit="return confirm('Hapus aset {{ addslashes($asset->nama) }}?')"
                      style="margin-top:8px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn admin-btn--danger admin-btn--sm" style="width:100%; justify-content:center;">
                        🗑️ Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($assets->hasPages())
        <div class="admin-pagination" style="margin-top:24px;">
            {{ $assets->links('pagination::simple-bootstrap-4') }}
        </div>
    @endif
@endif

<script>
function showFileInfo(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const ext = file.name.split('.').pop().toLowerCase();
    const emojiMap = {mp3:'🎵', wav:'🎵', ogg:'🎵', m4a:'🎵', aac:'🎵', jpg:'🖼️', jpeg:'🖼️', png:'🖼️', webp:'🖼️', svg:'🖼️', gif:'🎞️', json:'✨'};
    const size = file.size > 1048576 ? (file.size/1048576).toFixed(1)+' MB' : (file.size/1024).toFixed(0)+' KB';

    document.getElementById('fileTypeEmoji').textContent = emojiMap[ext] || '📎';
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = size;
    document.getElementById('dropzoneContent').style.display = 'none';
    document.getElementById('fileSelectedInfo').style.display = 'block';
}

function handleFileDrop(event) {
    event.preventDefault();
    const input = document.getElementById('mediaFile');
    input.files = event.dataTransfer.files;
    showFileInfo(input);
    event.target.style.borderColor = 'var(--admin-border)';
    event.target.style.background = '#fafafa';
}

function copyUrl(url, btn) {
    navigator.clipboard.writeText(url).then(() => {
        btn.textContent = '✅';
        setTimeout(() => btn.textContent = '📋', 1500);
    });
}

// Close modal on backdrop click
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});

// Auto-open modal if there was a validation error
@if(session('error') || $errors->has('file') || $errors->has('nama'))
    document.getElementById('uploadModal').style.display = 'flex';
@endif
</script>

@endsection
