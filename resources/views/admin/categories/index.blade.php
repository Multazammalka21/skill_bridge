@extends('admin.layouts.app')

@section('page-title', 'Kategori Pembelajaran')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
    <div>
        <h2 style="font-size:1.4rem; font-weight:800; color:var(--admin-text-primary); margin-bottom:4px;">Kategori Pembelajaran</h2>
        <p style="color:var(--admin-text-muted); font-size:0.85rem;">Kelola kategori materi seperti Literasi, Numerasi, Pengenalan Lingkungan, dll.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn--primary">
        <span>➕</span> Tambah Kategori
    </a>
</div>

{{-- Stats row --}}
<div class="stats-grid" style="margin-bottom:24px;">
    <div class="stat-card stat-card--blue">
        <div class="stat-icon">🗂️</div>
        <div class="stat-label">Total Kategori</div>
        <div class="stat-value">{{ $categories->count() }}</div>
    </div>
    <div class="stat-card stat-card--green">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Kategori Aktif</div>
        <div class="stat-value">{{ $categories->where('aktif', true)->count() }}</div>
    </div>
    <div class="stat-card stat-card--orange">
        <div class="stat-icon">📚</div>
        <div class="stat-label">Total Materi</div>
        <div class="stat-value">{{ $categories->sum('lessons_count') }}</div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__header">
        <h3 class="admin-card__title">🗂️ Daftar Kategori</h3>
        <span style="font-size:0.82rem; color:var(--admin-text-muted);">{{ $categories->count() }} kategori</span>
    </div>
    <div class="admin-card__body" style="padding:0;">
        @if($categories->isEmpty())
            <div style="text-align:center; padding:60px 20px; color:var(--admin-text-muted);">
                <div style="font-size:3rem; margin-bottom:12px;">🗂️</div>
                <p style="font-size:1rem; font-weight:600; margin-bottom:8px;">Belum ada kategori</p>
                <p style="font-size:0.85rem;">Mulai dengan membuat kategori pembelajaran pertama.</p>
                <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn--primary" style="margin-top:16px;">➕ Tambah Kategori Pertama</a>
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:50px;">Urutan</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Materi</th>
                        <th>Status</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td style="text-align:center; font-weight:700; color:var(--admin-text-muted);">{{ $category->urutan }}</td>
                        <td>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:42px; height:42px; border-radius:10px; background:{{ $category->warna }}20; border:2px solid {{ $category->warna }}40; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                                    {{ $category->ikon }}
                                </div>
                                <div>
                                    <div style="font-weight:700; color:var(--admin-text-primary);">{{ $category->nama }}</div>
                                    <div style="font-size:0.75rem; color:{{ $category->warna }}; font-weight:600;">{{ $category->warna }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="max-width:280px;">
                            <span style="color:var(--admin-text-secondary); font-size:0.85rem;">
                                {{ Str::limit($category->deskripsi, 80) ?: '—' }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight:700; font-size:1.1rem; color:var(--admin-text-primary);">{{ $category->lessons_count }}</span>
                            <span style="font-size:0.78rem; color:var(--admin-text-muted); margin-left:4px;">materi</span>
                        </td>
                        <td>
                            @if($category->aktif)
                                <span class="admin-badge" style="background:rgba(16,185,129,0.1); color:#059669;">✅ Aktif</span>
                            @else
                                <span class="admin-badge" style="background:rgba(239,68,68,0.1); color:#dc2626;">⛔ Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="admin-btn admin-btn--ghost admin-btn--sm">✏️ Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Hapus kategori {{ addslashes($category->nama) }}? Pastikan tidak ada materi yang terhubung.')">
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
        @endif
    </div>
</div>

@endsection
