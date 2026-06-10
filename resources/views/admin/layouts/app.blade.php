<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Pinteria</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        /* ─── Admin Design Tokens ────────────────────────────────── */
        :root {
            --admin-font: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --admin-bg: #f0f2f5;
            --admin-sidebar-bg: #0f172a;
            --admin-sidebar-hover: #1e293b;
            --admin-sidebar-active: #334155;
            --admin-sidebar-text: #94a3b8;
            --admin-sidebar-text-active: #ffffff;
            --admin-sidebar-width: 260px;

            --admin-primary: #3b82f6;
            --admin-primary-dark: #2563eb;
            --admin-success: #10b981;
            --admin-warning: #f59e0b;
            --admin-danger: #ef4444;
            --admin-info: #06b6d4;

            --admin-card-bg: #ffffff;
            --admin-card-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --admin-card-radius: 12px;

            --admin-text-primary: #0f172a;
            --admin-text-secondary: #475569;
            --admin-text-muted: #94a3b8;
            --admin-border: #e2e8f0;

            --admin-transition: 0.2s ease;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--admin-font);
            background: var(--admin-bg);
            color: var(--admin-text-primary);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── Sidebar ────────────────────────────────────────────── */
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background: var(--admin-sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform var(--admin-transition);
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--admin-primary), #818cf8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            font-weight: 800;
        }
        .sidebar-brand-text {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .sidebar-brand-badge {
            font-size: 0.6rem;
            font-weight: 600;
            color: var(--admin-primary);
            background: rgba(59,130,246,0.15);
            padding: 2px 8px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .sidebar-section-title {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--admin-text-muted);
            padding: 16px 12px 8px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: var(--admin-sidebar-text);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all var(--admin-transition);
        }
        .sidebar-link:hover {
            background: var(--admin-sidebar-hover);
            color: #e2e8f0;
        }
        .sidebar-link.active {
            background: var(--admin-sidebar-active);
            color: var(--admin-sidebar-text-active);
        }
        .sidebar-link .link-icon {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        /* ─── Main Content ───────────────────────────────────────── */
        .admin-main {
            margin-left: var(--admin-sidebar-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-topbar {
            background: var(--admin-card-bg);
            border-bottom: 1px solid var(--admin-border);
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .admin-topbar h1 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--admin-text-primary);
        }
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--admin-primary), #818cf8);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
        }
        .topbar-user-name {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--admin-text-primary);
        }

        .admin-content {
            flex: 1;
            padding: 28px 32px;
        }

        /* ─── Stats Grid ─────────────────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--admin-card-bg);
            border-radius: var(--admin-card-radius);
            padding: 22px 24px;
            box-shadow: var(--admin-card-shadow);
            border: 1px solid var(--admin-border);
            transition: transform var(--admin-transition), box-shadow var(--admin-transition);
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .stat-card--blue::before   { background: var(--admin-primary); }
        .stat-card--green::before  { background: var(--admin-success); }
        .stat-card--orange::before { background: var(--admin-warning); }
        .stat-card--cyan::before   { background: var(--admin-info); }
        .stat-card--red::before    { background: var(--admin-danger); }

        .stat-card .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .stat-card .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--admin-text-muted);
            margin-bottom: 4px;
        }
        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--admin-text-primary);
            line-height: 1.1;
        }

        /* ─── Admin Card & Aliases ───────────────────────────────── */
        .admin-card, .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid var(--admin-border);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            margin-bottom: 24px;
            transition: all 0.2s ease;
        }
        .admin-card__header, .card__header {
            padding: 16px 24px;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            gap: 12px;
        }
        .admin-card__title, .card__title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--admin-text-primary);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }
        .admin-card__body, .card__body {
            padding: 24px;
            flex: 1;
        }

        /* ─── Admin Table ────────────────────────────────────────── */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }
        .admin-table th {
            background: #f8fafc;
            padding: 14px 20px;
            font-weight: 700;
            text-align: left;
            color: var(--admin-text-secondary);
            border-bottom: 1px solid var(--admin-border);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        .admin-table td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--admin-border);
            color: var(--admin-text-primary);
            vertical-align: middle;
        }
        .admin-table tr:last-child td {
            border-bottom: none;
        }
        .admin-table tr:hover td {
            background: #f8fafc;
        }

        /* ─── Admin Buttons & Aliases ────────────────────────────── */
        .admin-btn, .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            font-family: inherit;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all var(--admin-transition);
        }
        .admin-btn--primary, .btn--primary {
            background: var(--admin-primary);
            color: #ffffff;
        }
        .admin-btn--primary:hover, .btn--primary:hover {
            background: var(--admin-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }
        .admin-btn--success, .btn--success {
            background: var(--admin-success);
            color: #ffffff;
        }
        .admin-btn--success:hover, .btn--success:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
        .admin-btn--danger, .btn--danger {
            background: var(--admin-danger);
            color: #ffffff;
        }
        .admin-btn--danger:hover, .btn--danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }
        .admin-btn--ghost, .btn--ghost {
            background: #f1f5f9;
            color: var(--admin-text-secondary);
            border: 1px solid var(--admin-border);
        }
        .admin-btn--ghost:hover, .btn--ghost:hover {
            background: #e2e8f0;
            color: var(--admin-text-primary);
            transform: translateY(-1px);
        }
        .admin-btn--sm, .btn--sm {
            padding: 6px 12px;
            font-size: 0.8rem;
            border-radius: 6px;
        }

        /* ─── Admin Form & Aliases ───────────────────────────────── */
        .admin-form-group, .form-group {
            margin-bottom: 20px;
        }
        .admin-form-label, .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--admin-text-secondary);
            margin-bottom: 8px;
        }
        .admin-form-input, .admin-form-select, .admin-form-textarea,
        .form-input, .form-select, .form-textarea {
            width: 100%;
            background: #ffffff;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.88rem;
            font-family: inherit;
            color: var(--admin-text-primary);
            transition: all var(--admin-transition);
            box-sizing: border-box;
        }
        .admin-form-input:focus, .admin-form-select:focus, .admin-form-textarea:focus,
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            background: #ffffff;
        }
        .admin-form-input::placeholder, .admin-form-textarea::placeholder,
        .form-input::placeholder, .form-textarea::placeholder {
            color: var(--admin-text-muted);
            opacity: 0.8;
        }
        .admin-form-textarea, .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* ─── Badge & Aliases ────────────────────────────────────── */
        .admin-badge, .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border: 1px solid transparent;
        }
        .admin-badge--audio, .badge--teal {
            background: rgba(13, 148, 136, 0.1);
            color: #0d9488;
            border-color: rgba(13, 148, 136, 0.2);
        }
        .admin-badge--visual, .badge--orange {
            background: rgba(249, 115, 22, 0.1);
            color: #f97316;
            border-color: rgba(249, 115, 22, 0.2);
        }
        .admin-badge--age-57, .badge--blue {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-color: rgba(59, 130, 246, 0.2);
        }
        .admin-badge--age-810, .badge--purple {
            background: rgba(168, 85, 247, 0.1);
            color: #a855f7;
            border-color: rgba(168, 85, 247, 0.2);
        }

        /* ─── Flash Messages ─────────────────────────────────────── */
        .admin-flash {
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .admin-flash--success {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #065f46;
        }
        .admin-flash--error {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #991b1b;
        }

        /* ─── Pagination ─────────────────────────────────────────── */
        .admin-pagination {
            display: flex;
            justify-content: center;
            gap: 4px;
            margin-top: 20px;
        }
        .admin-pagination a,
        .admin-pagination span {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--admin-text-secondary);
            border: 1px solid var(--admin-border);
            transition: all var(--admin-transition);
        }
        .admin-pagination a:hover {
            background: var(--admin-primary);
            color: #fff;
            border-color: var(--admin-primary);
        }
        .admin-pagination .active span {
            background: var(--admin-primary);
            color: #fff;
            border-color: var(--admin-primary);
        }

        /* ─── Filters ────────────────────────────────────────────── */
        .admin-filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: end;
        }
        .admin-filters .admin-form-group { margin-bottom: 0; }

        /* ─── Responsive ─────────────────────────────────────────── */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
            .admin-content {
                padding: 20px 16px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ─── Subnav Styles ──────────────────────────────────────── */
        .admin-subnav {
            background: #ffffff;
            border-bottom: 1px solid var(--admin-border);
            padding: 0 32px;
            display: flex;
            gap: 24px;
        }
        .subnav__item {
            padding: 16px 0;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--admin-text-secondary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
            transition: color 0.2s ease;
        }
        .subnav__item i {
            font-size: 1.1rem;
        }
        .subnav__item:hover {
            color: var(--admin-primary);
        }
        .subnav__item--active {
            color: var(--admin-primary);
        }
        .subnav__item--active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--admin-primary);
            border-radius: 2px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand" style="display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 10px; padding: 20px;">
            <img src="{{ asset('images/Logo_pinteria (1).png') }}" alt="Pinteria Logo" style="height: 38px; width: auto; max-width: 140px; object-fit: contain; filter: brightness(0) invert(1);">
            <span class="sidebar-brand-badge">Admin</span>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-title">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="link-icon">📊</span>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section-title">Manajemen Konten</div>

            <a href="{{ route('admin.categories.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="link-icon">🗂️</span>
                <span>Kategori Pembelajaran</span>
            </a>

            <a href="{{ route('admin.lessons.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.lessons.*') ? 'active' : '' }}">
                <span class="link-icon">📚</span>
                <span>Materi Pembelajaran</span>
            </a>

            <a href="{{ route('admin.media.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                <span class="link-icon">🖼️</span>
                <span>Library Media</span>
            </a>

            <a href="{{ route('admin.learning-path.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.learning-path.*') ? 'active' : '' }}">
                <span class="link-icon">🗺️</span>
                <span>Learning Path</span>
            </a>

            <div class="sidebar-section-title">Quiz & Evaluasi</div>

            <a href="{{ route('admin.quiz.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.quiz.index') || request()->routeIs('admin.quiz.create') || request()->routeIs('admin.quiz.edit') ? 'active' : '' }}">
                <span class="link-icon">❓</span>
                <span>Kelola Quiz</span>
            </a>

            <a href="{{ route('admin.quiz.monitoring') }}"
               class="sidebar-link {{ request()->routeIs('admin.quiz.monitoring') ? 'active' : '' }}">
                <span class="link-icon">📈</span>
                <span>Monitoring Quiz</span>
            </a>

            <div class="sidebar-section-title">Gamifikasi</div>

            <a href="{{ route('admin.badges.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.badges.*') ? 'active' : '' }}">
                <span class="link-icon">🏆</span>
                <span>Badge Pencapaian</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link" style="width:100%; border:none; background:none; cursor:pointer; text-align:left;">
                    <span class="link-icon">🚪</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="admin-main">
        <header class="admin-topbar">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <div class="topbar-user" style="display: flex; align-items: center; gap: 16px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span class="topbar-user-name">{{ auth()->user()->name }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline;">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn--danger admin-btn--sm" style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; background: var(--admin-danger); color: #fff; border: none; cursor: pointer;">
                        🚪 Keluar
                    </button>
                </form>
            </div>
        </header>

        @if (trim($__env->yieldContent('subnav')))
            <div class="admin-subnav">
                @yield('subnav')
            </div>
        @endif

        <main class="admin-content">
            @if(session('success'))
                <div class="admin-flash admin-flash--success">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="admin-flash admin-flash--error">
                    ❌ {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
