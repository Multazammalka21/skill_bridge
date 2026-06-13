<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Dashboard') — Pinteria Admin</title>

    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    {{-- Dark mode: apply before paint to avoid flash --}}
    <script>
        (function() {
            var theme = localStorage.getItem('pinteria-theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>

{{-- ═══════════════════════════════════════════
     TOPBAR NAVIGASI UTAMA
════════════════════════════════════════════ --}}
<header class="topnav">
    <div class="topnav__inner">

        {{-- Logo --}}
        <a href="{{ route('admin.dashboard') }}" class="topnav__logo" style="display: flex; align-items: center; gap: 8px;">
            <img src="{{ asset('images/Logo_pinteria (1).png') }}" alt="Pinteria Logo" style="height: 34px; width: auto; max-width: 140px; object-fit: contain;">
            <div class="topnav__logo-text" style="display: flex; flex-direction: column; justify-content: center;">
                <span class="topnav__logo-name">Pinteria</span>
                <span class="topnav__logo-sub">Platform Inklusif</span>
            </div>
        </a>

        <div class="topnav__divider"></div>

        {{-- Menu Navigasi --}}
        <nav class="topnav__menu">
            <a href="{{ route('admin.dashboard') }}"
               class="topnav__item {{ request()->routeIs('admin.dashboard') ? 'topnav__item--active' : '' }}">
                <i class="ti ti-layout-dashboard"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="topnav__item {{ request()->routeIs('admin.categories.*') ? 'topnav__item--active' : '' }}">
                <i class="ti ti-layout-grid"></i>
                Kategori
            </a>

            <a href="{{ route('admin.lessons.index') }}"
               class="topnav__item {{ request()->routeIs('admin.lessons.*') ? 'topnav__item--active' : '' }}">
                <i class="ti ti-book"></i>
                Materi
            </a>

            <a href="{{ route('admin.quiz.index') }}"
               class="topnav__item {{ request()->routeIs('admin.quiz.*') ? 'topnav__item--active' : '' }}">
                <i class="ti ti-question-mark"></i>
                Quiz
            </a>

            <a href="{{ route('admin.learning-path.index') }}"
               class="topnav__item {{ request()->routeIs('admin.learning-path.*') ? 'topnav__item--active' : '' }}">
                <i class="ti ti-map"></i>
                Learning Path
            </a>

            <a href="{{ route('admin.media.index') }}"
               class="topnav__item {{ request()->routeIs('admin.media.*') ? 'topnav__item--active' : '' }}">
                <i class="ti ti-photo"></i>
                Media
            </a>

            <a href="{{ route('admin.badges.index') }}"
               class="topnav__item {{ request()->routeIs('admin.badges.*') ? 'topnav__item--active' : '' }}">
                <i class="ti ti-trophy"></i>
                Badge
            </a>
        </nav>

        {{-- Kanan: Notif + Settings + User --}}
        <div class="topnav__right">

            {{-- 🔔 Notification Bell --}}
            <div class="notif-wrap" x-data="notifPanel()" x-init="init()" @click.outside="open = false">
                <button class="topnav__icon-btn notif-btn" @click="toggle()" title="Notifikasi">
                    <i class="ti ti-bell"></i>
                    <span class="notif-count" x-show="unread > 0" x-text="unread > 9 ? '9+' : unread" style="display:none;"></span>
                </button>

                <div class="notif-panel" x-show="open" x-transition style="display:none;">
                    <div class="notif-panel__header">
                        <div class="notif-panel__title"><i class="ti ti-bell"></i> Notifikasi</div>
                        <button class="notif-panel__action" @click="markRead()" x-show="unread > 0">Tandai dibaca</button>
                    </div>
                    <div class="notif-panel__body">
                        <template x-if="notifications.length === 0">
                            <div class="notif-panel__empty">
                                <i class="ti ti-bell-off"></i>
                                Belum ada notifikasi baru
                            </div>
                        </template>
                        <template x-for="n in notifications" :key="n.id">
                            <div class="notif-item" :class="{ 'notif-item--unread': n.is_new }">
                                <div class="notif-item__avatar" x-text="n.name.slice(0,2).toUpperCase()"></div>
                                <div class="notif-item__body">
                                    <div class="notif-item__name" x-text="n.name"></div>
                                    <div class="notif-item__desc" x-text="n.email"></div>
                                    <div class="notif-item__time"><i class="ti ti-clock" style="font-size:10px"></i> <span x-text="n.time"></span></div>
                                </div>
                                <div class="notif-dot" x-show="n.is_new"></div>
                            </div>
                        </template>
                    </div>
                    <div class="notif-panel__footer">
                        <a href="{{ route('admin.settings') }}">Lihat semua pengguna →</a>
                    </div>
                </div>
            </div>



            {{-- 👤 User Dropdown --}}
            <div class="topnav__user" x-data="{ open: false }" @click.outside="open = false">
                <button class="topnav__user-btn" @click="open = !open">
                    <div class="topnav__avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <span class="topnav__user-name">{{ auth()->user()->name }}</span>
                    <i class="ti ti-chevron-down topnav__chevron"></i>
                </button>

                <div class="topnav__dropdown" x-show="open" x-transition style="display: none;">
                    <div class="topnav__dropdown-header">
                        <div class="topnav__dropdown-name">{{ auth()->user()->name }}</div>
                        <div class="topnav__dropdown-role">Super Admin</div>
                    </div>
                    <div class="topnav__dropdown-divider"></div>
                    <a href="{{ route('admin.settings') }}" class="topnav__dropdown-item">
                        <i class="ti ti-user"></i> Profil Saya
                    </a>
                    <a href="{{ route('admin.settings') }}" class="topnav__dropdown-item">
                        <i class="ti ti-settings"></i> Pengaturan
                    </a>
                    <div class="topnav__dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="topnav__dropdown-item topnav__dropdown-item--danger">
                            <i class="ti ti-logout"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>

{{-- ═══════════════════════════════════════════
     SUB-NAVIGASI (opsional per halaman)
════════════════════════════════════════════ --}}
@hasSection('subnav')
<div class="subnav">
    <div class="subnav__inner">
        @yield('subnav')
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════
     AREA KONTEN UTAMA
════════════════════════════════════════════ --}}
<main class="main-content">
    <div class="page-content">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert--success">
            <i class="ti ti-circle-check"></i>
            {{ session('success') }}
            <button class="alert__close" onclick="this.parentElement.remove()">
                <i class="ti ti-x"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert--danger">
            <i class="ti ti-alert-circle"></i>
            {{ session('error') }}
            <button class="alert__close" onclick="this.parentElement.remove()">
                <i class="ti ti-x"></i>
            </button>
        </div>
        @endif

        @yield('content')
    </div>
</main>



{{-- Alpine.js --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
function notifPanel() {
    return {
        open: false,
        notifications: [],
        unread: 0,
        async toggle() {
            this.open = !this.open;
            if (this.open) await this.fetchNotifs();
        },
        async fetchNotifs() {
            try {
                const res = await fetch('{{ route("admin.notifications") }}');
                const data = await res.json();
                this.notifications = data.notifications;
                this.unread = data.unread_count;
            } catch(e) {}
        },
        async markRead() {
            try {
                await fetch('{{ route("admin.notifications.read") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                this.notifications = this.notifications.map(n => ({...n, is_new: false}));
                this.unread = 0;
            } catch(e) {}
        },
        init() { this.fetchNotifs(); }
    };
}


</script>

@stack('scripts')
</body>
</html>
