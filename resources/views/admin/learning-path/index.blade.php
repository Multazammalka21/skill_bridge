@extends('admin.layouts.app')

@section('page-title', 'Learning Path')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:1.4rem; font-weight:800; color:var(--text-primary); margin-bottom:4px;">🗺️ Learning Path</h2>
        <p style="color:var(--text-muted); font-size:0.85rem;">Atur urutan lesson dan prasyarat — anak harus menyelesaikan materi sebelumnya untuk membuka level berikutnya</p>
    </div>
    <button id="saveBtn" class="btn btn--success" onclick="savePath()" style="display:none;">
        💾 Simpan Urutan
    </button>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card__body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('admin.learning-path.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
            <div class="form-group" style="margin:0; min-width:150px;">
                <label class="form-label">🗂️ Kategori</label>
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $selectedCategory == $cat->id ? 'selected' : '' }}>
                            {{ $cat->ikon }} {{ $cat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0; min-width:140px;">
                <label class="form-label">👁️ Tipe Dunia</label>
                <select name="tipe_dunia" class="form-select">
                    <option value="audio" {{ $selectedTipeDunia === 'audio' ? 'selected' : '' }}>🎧 Audio (Tunanetra)</option>
                    <option value="visual" {{ $selectedTipeDunia === 'visual' ? 'selected' : '' }}>👁️ Visual (Tunarungu)</option>
                </select>
            </div>
            <div class="form-group" style="margin:0; min-width:120px;">
                <label class="form-label">👶 Kategori Usia</label>
                <select name="kategori_usia" class="form-select">
                    <option value="">Semua Usia</option>
                    <option value="5-7" {{ $selectedUsia === '5-7' ? 'selected' : '' }}>5–7 Tahun</option>
                    <option value="8-10" {{ $selectedUsia === '8-10' ? 'selected' : '' }}>8–10 Tahun</option>
                </select>
            </div>
            <button type="submit" class="btn btn--primary">Filter</button>
        </form>
    </div>
</div>

@if($lessons->isEmpty())
    <div style="text-align:center; padding:80px 20px; background:#fff; border-radius:12px; border:1px solid var(--border);">
        <div style="font-size:3.5rem; margin-bottom:16px;">🗺️</div>
        <h3 style="font-weight:700; margin-bottom:8px;">Belum ada materi untuk path ini</h3>
        <p style="color:var(--text-muted); font-size:0.88rem; margin-bottom:20px;">Tambahkan materi pembelajaran terlebih dahulu atau ubah filter di atas.</p>
        <a href="{{ route('admin.lessons.create') }}" class="btn btn--primary">➕ Tambah Materi Baru</a>
    </div>
@else
    <div class="card">
        <div class="card__header">
            <h3 class="card__title">📋 Urutan Pembelajaran</h3>
            <div style="display:flex; align-items:center; gap:12px;">
                <span style="font-size:0.8rem; color:var(--text-muted);">{{ $lessons->count() }} materi</span>
                <span style="font-size:0.78rem; background:rgba(59,130,246,0.1); color:var(--teal); padding:3px 10px; border-radius:20px; font-weight:600;">
                    {{ $selectedTipeDunia === 'audio' ? '🎧 Tunanetra' : '👁️ Tunarungu' }}
                </span>
            </div>
        </div>
        <div class="card__body" style="padding:0;">
            <div style="padding:12px 20px; background:#fffbeb; border-bottom:1px solid #fde68a; font-size:0.82rem; color:#92400e; display:flex; align-items:center; gap:8px;">
                <span>💡</span>
                <span>Drag kartu untuk mengubah urutan. Pilih prasyarat pada setiap materi untuk sistem unlock otomatis. Klik <strong>Simpan Urutan</strong> setelah selesai.</span>
            </div>

            <div id="sortableList" style="padding:16px; display:flex; flex-direction:column; gap:10px;">
                @foreach($lessons as $idx => $lesson)
                <div class="path-item" data-id="{{ $lesson->id }}" data-urutan="{{ $idx }}"
                     style="background:#fff; border:2px solid var(--border); border-radius:12px; padding:16px 18px; cursor:grab; transition:all 0.15s; display:flex; align-items:center; gap:14px; user-select:none;">

                    {{-- Drag handle --}}
                    <div class="drag-handle" style="color:#cbd5e1; font-size:1.2rem; cursor:grab; flex-shrink:0;">⣿</div>

                    {{-- Step number --}}
                    <div style="width:34px; height:34px; border-radius:50%; background:var(--teal); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.88rem; flex-shrink:0;" class="step-num">
                        {{ $idx + 1 }}
                    </div>

                    {{-- Lesson info --}}
                    <div style="flex:1; min-width:0;">
                        <div style="font-weight:700; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $lesson->judul }}
                        </div>
                        <div style="display:flex; gap:8px; margin-top:4px; flex-wrap:wrap;">
                            @if($lesson->category)
                                <span style="font-size:0.7rem; background:{{ $lesson->category->warna }}15; color:{{ $lesson->category->warna }}; padding:1px 7px; border-radius:20px; font-weight:600;">
                                    {{ $lesson->category->ikon }} {{ $lesson->category->nama }}
                                </span>
                            @endif
                            <span style="font-size:0.7rem; color:var(--text-muted);">{{ $lesson->durasi_menit }} menit</span>
                            @if($lesson->aktif)
                                <span style="font-size:0.7rem; color:#059669; font-weight:600;">✅ Aktif</span>
                            @else
                                <span style="font-size:0.7rem; color:#dc2626; font-weight:600;">⛔ Nonaktif</span>
                            @endif
                        </div>
                    </div>

                    {{-- Prerequisite selector --}}
                    <div style="flex-shrink:0; min-width:220px;">
                        <label style="font-size:0.72rem; font-weight:600; color:var(--text-muted); display:block; margin-bottom:4px;">🔒 Prasyarat:</label>
                        <select class="form-select prereq-select" data-lesson-id="{{ $lesson->id }}"
                                style="font-size:0.78rem; padding:6px 10px;" onchange="markDirty()">
                            <option value="">— Bebas dibuka —</option>
                            @foreach($allLessons->where('id', '!=', $lesson->id) as $other)
                                <option value="{{ $other->id }}" {{ $lesson->prerequisite_lesson_id == $other->id ? 'selected' : '' }}>
                                    #{{ $other->id }} {{ Str::limit($other->judul, 30) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status icon --}}
                    @if($lesson->prerequisite_lesson_id)
                        <div style="flex-shrink:0; font-size:1.4rem;" title="Memerlukan prasyarat">🔒</div>
                    @else
                        <div style="flex-shrink:0; font-size:1.4rem;" title="Bebas dibuka">🔓</div>
                    @endif
                </div>
                @endforeach
            </div>

            <div style="padding:16px 20px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:12px;">
                <button class="btn btn--neutral" onclick="location.reload()">↺ Reset</button>
                <button class="btn btn--success" onclick="savePath()">💾 Simpan Urutan & Prasyarat</button>
            </div>
        </div>
    </div>
@endif

<style>
.path-item.dragging { opacity:0.4; border-color:var(--teal); }
.path-item.drag-over { border-color:var(--teal); background:rgba(59,130,246,0.04); }
</style>

<script>
let isDirty = false;

function markDirty() {
    isDirty = true;
    document.getElementById('saveBtn').style.display = 'inline-flex';
}

// ── Drag & drop sort ─────────────────────────────────────────────────
const list = document.getElementById('sortableList');
if (list) {
    let dragEl = null;
    let placeholder = null;

    list.addEventListener('dragstart', e => {
        dragEl = e.target.closest('.path-item');
        if (!dragEl) return;
        dragEl.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';

        placeholder = document.createElement('div');
        placeholder.style.cssText = `height:${dragEl.offsetHeight}px; border:2px dashed var(--teal); border-radius:12px; background:rgba(59,130,246,0.04); margin-bottom:0;`;
        dragEl.setAttribute('draggable', 'true');
    });

    list.addEventListener('dragover', e => {
        e.preventDefault();
        const target = e.target.closest('.path-item');
        if (!target || target === dragEl) return;

        const rect = target.getBoundingClientRect();
        const midY = rect.top + rect.height / 2;
        if (e.clientY < midY) {
            list.insertBefore(dragEl, target);
        } else {
            list.insertBefore(dragEl, target.nextSibling);
        }
        markDirty();
    });

    list.addEventListener('dragend', e => {
        if (dragEl) dragEl.classList.remove('dragging');
        dragEl = null;
        renumberSteps();
    });

    // Make items draggable
    document.querySelectorAll('.path-item').forEach(item => {
        item.setAttribute('draggable', 'true');
    });
}

function renumberSteps() {
    document.querySelectorAll('.path-item').forEach((item, idx) => {
        item.querySelector('.step-num').textContent = idx + 1;
        item.dataset.urutan = idx;
    });
}

async function savePath() {
    const items = [];
    document.querySelectorAll('.path-item').forEach((item, idx) => {
        const prereqSelect = item.querySelector('.prereq-select');
        items.push({
            id: parseInt(item.dataset.id),
            urutan: idx,
            prerequisite_lesson_id: prereqSelect.value ? parseInt(prereqSelect.value) : null,
        });
    });

    const btn = document.querySelector('.btn--success');
    btn.textContent = '⏳ Menyimpan...';
    btn.disabled = true;

    try {
        const res = await fetch('{{ route('admin.learning-path.update') }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ lessons: items }),
        });
        const data = await res.json();
        btn.textContent = '✅ Tersimpan!';
        isDirty = false;
        document.getElementById('saveBtn').style.display = 'none';
        setTimeout(() => { btn.textContent = '💾 Simpan Urutan & Prasyarat'; btn.disabled = false; }, 2000);
    } catch (err) {
        btn.textContent = '❌ Gagal — Coba Lagi';
        btn.disabled = false;
    }
}

window.addEventListener('beforeunload', e => {
    if (isDirty) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>

@endsection
