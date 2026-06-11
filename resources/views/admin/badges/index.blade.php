@extends('admin.layouts.app')

@section('page-title', 'Kelola Badge Pencapaian')

@section('content')

    <div class="card">
        <div class="card__header">
            <h3 class="card__title">🏆 Daftar Badge Pencapaian</h3>
            <a href="{{ route('admin.badges.create') }}" class="btn btn--primary btn--sm">➕ Tambah Badge</a>
        </div>
        <div class="card__body" style="padding: 0;">
            
            @if(session('success'))
                <div class="alert--success" style="margin: 16px;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Ikon</th>
                        <th>Nama Badge</th>
                        <th>Deskripsi</th>
                        <th>Kategori Syarat</th>
                        <th>Nilai Minimal</th>
                        <th style="width: 180px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($badges as $badge)
                        <tr>
                            <td style="font-size: 32px; text-align: center;">{{ $badge->ikon }}</td>
                            <td><strong>{{ $badge->nama }}</strong></td>
                            <td>{{ $badge->deskripsi }}</td>
                            <td>
                                @if($badge->syarat_tipe === 'quiz_count')
                                    📚 Jumlah Kuis Selesai
                                @elseif($badge->syarat_tipe === 'perfect_score')
                                    💯 Jumlah Kuis Nilai Sempurna
                                @else
                                    🔥 Hari Beruntun (Streak)
                                @endif
                            </td>
                            <td>{{ $badge->syarat_nilai }}</td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <a href="{{ route('admin.badges.edit', $badge->id) }}" class="btn btn--neutral btn--sm">Edit ✏️</a>
                                    <form action="{{ route('admin.badges.destroy', $badge->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus badge ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--danger btn--sm">Hapus 🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #888; padding: 30px;">Belum ada badge. Klik 'Tambah Badge' untuk menambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
