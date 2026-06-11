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

        {{-- Kanan: Notif + User --}}
        <div class="topnav__right">
            <button class="topnav__icon-btn topnav__icon-btn--notif" title="Notifikasi">
                <i class="ti ti-bell"></i>
            </button>
            <button class="topnav__icon-btn" title="Pengaturan">
                <i class="ti ti-settings"></i>
            </button>

            {{-- User Dropdown --}}
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
                    <a href="#" class="topnav__dropdown-item">
                        <i class="ti ti-user"></i> Profil Saya
                    </a>
                    <a href="#" class="topnav__dropdown-item">
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

{{-- Alpine.js untuk dropdown --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@stack('scripts')
</body>
</html>
