@extends('admin.layouts.app')

@section('page-title', 'Pengaturan')

@section('content')

<div class="page-header">
    <div class="page-header__left">
        <h1>Pengaturan Akun</h1>
        <div class="page-header__breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <i class="ti ti-chevron-right"></i>
            <span>Pengaturan</span>
        </div>
    </div>
</div>

<div class="grid-1-1" style="align-items:start;">

    {{-- Kolom Kiri: Profil + Password --}}
    <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- ── Profil Admin ──────────────────────────────── --}}
        <div class="card">
            <div class="card__header">
                <h3 class="card__title"><i class="ti ti-user-circle"></i> Profil Admin</h3>
            </div>
            <div class="card__body">
                {{-- Avatar --}}
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border);">
                    <div style="width:60px;height:60px;border-radius:50%;background:var(--teal);color:#fff;font-size:22px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:var(--text-primary);">{{ $user->name }}</div>
                        <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">{{ $user->email }}</div>
                        <span class="badge badge--teal" style="margin-top:6px;">Super Admin</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.settings.profile') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="name" class="form-input @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Email <span class="required">*</span></label>
                        <input type="email" name="email" class="form-input @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn--primary">
                        <i class="ti ti-check"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Ganti Password ─────────────────────────────── --}}
        <div class="card">
            <div class="card__header">
                <h3 class="card__title"><i class="ti ti-lock"></i> Ganti Password</h3>
            </div>
            <div class="card__body">
                <form method="POST" action="{{ route('admin.settings.password') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Password Lama <span class="required">*</span></label>
                        <input type="password" name="current_password" class="form-input" placeholder="Masukkan password lama" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru <span class="required">*</span></label>
                        <input type="password" name="password" class="form-input" placeholder="Minimal 8 karakter" required>
                        <div class="form-hint">Gunakan kombinasi huruf, angka, dan simbol untuk keamanan lebih baik.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password baru" required>
                    </div>
                    <button type="submit" class="btn btn--primary">
                        <i class="ti ti-lock-check"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- Kolom Kanan: Tampilan + Info --}}
    <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- ── Tampilan / Dark Mode ───────────────────────── --}}
        <div class="card" x-data="appearanceSettings()">
            <div class="card__header">
                <h3 class="card__title"><i class="ti ti-palette"></i> Tampilan</h3>
            </div>
            <div class="card__body">

                {{-- Mode Gelap toggle --}}
                <div class="toggle-row" style="padding:12px 0;border-bottom:1px solid var(--border);">
                    <div>
                        <div class="toggle-row__label">
                            <i class="ti ti-moon" style="margin-right:6px;color:var(--text-muted);"></i>Mode Gelap
                        </div>
                        <div class="toggle-row__sub">Panel admin menjadi tampilan gelap</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" :checked="isDark" @change="toggleDark($event.target.checked)">
                        <span class="toggle-switch__track"></span>
                    </label>
                </div>

                {{-- Mode preview indicator --}}
                <div style="margin-top:14px;padding:12px;border-radius:var(--radius-md);background:var(--bg-page);border:1px solid var(--border);font-size:12px;color:var(--text-muted);text-align:center;">
                    <i class="ti ti-eye" style="margin-right:4px;"></i>
                    <span x-text="isDark ? '🌙 Mode Gelap aktif' : '☀️ Mode Terang aktif'"></span>
                </div>

                <div style="margin-top:12px;font-size:11px;color:var(--text-muted);">
                    <i class="ti ti-info-circle"></i>
                    Preferensi tampilan tersimpan di browser dan berlaku permanen.
                </div>
            </div>
        </div>

        {{-- ── Info Akun ──────────────────────────────────── --}}
        <div class="card">
            <div class="card__header">
                <h3 class="card__title"><i class="ti ti-info-circle"></i> Informasi Akun</h3>
            </div>
            <div class="card__body">
                <table style="width:100%;font-size:12px;border-collapse:collapse;">
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:8px 0;color:var(--text-muted);width:40%;">ID Pengguna</td>
                        <td style="padding:8px 0;font-weight:600;color:var(--text-primary);">#{{ $user->id }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:8px 0;color:var(--text-muted);">Role</td>
                        <td style="padding:8px 0;"><span class="badge badge--teal">Super Admin</span></td>
                    </tr>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:8px 0;color:var(--text-muted);">Terdaftar sejak</td>
                        <td style="padding:8px 0;font-weight:600;color:var(--text-primary);">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;color:var(--text-muted);">Update terakhir</td>
                        <td style="padding:8px 0;font-weight:600;color:var(--text-primary);">{{ $user->updated_at->diffForHumans() }}</td>
                    </tr>
                </table>

                <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn--danger btn--sm" style="width:100%;justify-content:center;">
                            <i class="ti ti-logout"></i> Keluar dari Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
function appearanceSettings() {
    return {
        isDark: localStorage.getItem('pinteria-theme') === 'dark',
        toggleDark(val) {
            this.isDark = val;
            const theme = val ? 'dark' : 'light';
            localStorage.setItem('pinteria-theme', theme);
            document.documentElement.setAttribute('data-theme', theme);
        }
    };
}
</script>
@endpush
