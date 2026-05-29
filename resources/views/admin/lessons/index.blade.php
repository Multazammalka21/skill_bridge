@extends('admin.layouts.app')

@section('page-title', 'Materi Pembelajaran')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:1.4rem; font-weight:800; color:var(--admin-text-primary); margin-bottom:4px;">Materi Pembelajaran</h2>
        <p style="color:var(--admin-text-muted); font-size:0.85rem;">Kelola semua lesson dengan konten audio, gambar, animasi, dan teks</p>
    </div>
    <a href="{{ route('admin.lessons.create') }}" class="admin-btn admin-btn--primary">
        ➕ Tambah Materi
    </a>
</div>

{{-- Filters --}}
<div class="admin-card" style="margin-bottom:20px;">
    <div class="admin-card__body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('admin.lessons.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
            <div class="admin-form-group" style="margin:0; flex:1; min-width:160px;">
                <label class="admin-form-label">🔍 Cari Judul</label>
                <input type="text" name="search" class="admin-form-input" placeholder="Cari materi..." value="{{ $filters['search'] ?? '' }}">
            </div>
            <div class="admin-form-group" style="margin:0; min-width:150px;">
                <label class="admin-form-label">🗂️ Kategori</label>
                <select name="category_id" class="admin-form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->ikon }} {{ $cat->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="admin-form-group" style="margin:0; min-width:130px;">
                <label class="admin-form-label">👁️ Tipe Dunia</label>
                <select name="tipe_dunia" class="admin-form-select">
                    <option value="">Semua</option>
                    <option value="audio" {{ ($filters['tipe_dunia'] ?? '') == 'audio' ? 'selected' : '' }}>🎧 Audio (Tunanetra)</option>
                    <option value="visual" {{ ($filters['tipe_dunia'] ?? '') == 'visual' ? 'selected' : '' }}>👁️ Visual (Tunarungu)</option>
                </select>
            </div>
            <div class="admin-form-group" style="margin:0; min-width:120px;">
                <label class="admin-form-label">👶 Usia</label>
                <select name="kategori_usia" class="admin-form-select">
                    <option value="">Semua</option>
                    <option value="5-7" {{ ($filters['kategori_usia'] ?? '') == '5-7' ? 'selected' : '' }}>5–7 Tahun</option>
                    <option value="8-10" {{ ($filters['kategori_usia'] ?? '') == '8-10' ? 'selected' : '' }}>8–10 Tahun</option>
                </select>
            </div>
            <div class="admin-form-group" style="margin:0; min-width:110px;">
                <label class="admin-form-label">Status</label>
                <select name="aktif" class="admin-form-select">
                    <option value="">Semua</option>
                    <option value="1" {{ ($filters['aktif'] ?? '') === '1' ? 'selected' : '' }}>✅ Aktif</option>
                    <option value="0" {{ ($filters['aktif'] ?? '') === '0' ? 'selected' : '' }}>⛔ Nonaktif</option>
                </select>
            </div>
            <div style="display:flex; gap:8px;">
                <button type="submit" class="admin-btn admin-btn--primary">Filter</button>
                <a href="{{ route('admin.lessons.index') }}" class="admin-btn admin-btn--ghost">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__header">
        <h3 class="admin-card__title">📚 Daftar Materi</h3>
        <span style="font-size:0.82rem; color:var(--admin-text-muted);">{{ $lessons->total() }} materi ditemukan</span>
    </div>
    <div class="admin-card__body" style="padding:0;">
        @if($lessons->isEmpty())
            <div style="text-align:center; padding:60px 20px; color:var(--admin-text-muted);">
                <div style="font-size:3rem; margin-bottom:12px;">📚</div>
                <p style="font-size:1rem; font-weight:600; margin-bottom:8px;">Belum ada materi</p>
                <p style="font-size:0.85rem;">Mulai dengan membuat materi pembelajaran pertama.</p>
                <a href="{{ route('admin.lessons.create') }}" class="admin-btn admin-btn--primary" style="margin-top:16px;">➕ Tambah Materi Pertama</a>
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:44px;">#</th>
                        <th>Judul Materi</th>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th>Usia</th>
                        <th>Konten</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th style="width:130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lessons as $lesson)
                    <tr>
                        <td style="text-align:center; font-weight:700; color:var(--admin-text-muted); font-size:0.8rem;">{{ $lesson->urutan }}</td>
                        <td>
                            <div style="font-weight:700; color:var(--admin-text-primary); margin-bottom:2px;">{{ Str::limit($lesson->judul, 45) }}</div>
                            @if($lesson->prerequisite_lesson_id)
                                <div style="font-size:0.72rem; color:var(--admin-primary);">🔒 Perlu prasyarat</div>
                            @endif
                            @if($lesson->deskripsi)
                                <div style="font-size:0.75rem; color:var(--admin-text-muted);">{{ Str::limit($lesson->deskripsi, 55) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($lesson->category)
                                <span style="display:inline-flex; align-items:center; gap:4px; background:{{ $lesson->category->warna }}15; color:{{ $lesson->category->warna }}; border:1px solid {{ $lesson->category->warna }}30; padding:3px 8px; border-radius:20px; font-size:0.72rem; font-weight:600;">
                                    {{ $lesson->category->ikon }} {{ $lesson->category->nama }}
                                </span>
                            @else
                                <span style="color:var(--admin-text-muted); font-size:0.8rem;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($lesson->tipe_dunia === 'audio')
                                <span class="admin-badge admin-badge--audio">🎧 Audio</span>
                            @else
                                <span class="admin-badge admin-badge--visual">👁️ Visual</span>
                            @endif
                        </td>
                        <td>
                            @if($lesson->kategori_usia === '5-7')
                                <span class="admin-badge admin-badge--age-57">5–7 Thn</span>
                            @else
                                <span class="admin-badge admin-badge--age-810">8–10 Thn</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $kontenIkon = match($lesson->konten_tipe) {
                                    'audio_story' => '🎙️ Audio Story',
                                    'gambar_interaktif' => '🖼️ Gambar',
                                    'animasi_lottie' => '✨ Animasi',
                                    'teks' => '📝 Teks',
                                    'campuran' => '🎨 Campuran',
                                    default => '—',
                                };
                            @endphp
                            <span style="font-size:0.78rem; color:var(--admin-text-secondary);">{{ $kontenIkon }}</span>
                        </td>
                        <td style="font-size:0.85rem; color:var(--admin-text-secondary);">{{ $lesson->durasi_menit }} mnt</td>
                        <td>
                            @if($lesson->aktif)
                                <span class="admin-badge" style="background:rgba(16,185,129,0.1); color:#059669;">✅ Aktif</span>
                            @else
                                <span class="admin-badge" style="background:rgba(239,68,68,0.1); color:#dc2626;">⛔ Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex; gap:5px;">
                                <a href="{{ route('admin.lessons.edit', $lesson) }}" class="admin-btn admin-btn--ghost admin-btn--sm">✏️</a>
                                <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST"
                                      onsubmit="return confirm('Hapus materi ini? Aksi tidak bisa dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($lessons->hasPages())
                <div class="admin-pagination" style="padding:16px 20px;">
                    {{ $lessons->withQueryString()->links('pagination::simple-bootstrap-4') }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection
