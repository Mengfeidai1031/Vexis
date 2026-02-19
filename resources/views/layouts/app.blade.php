<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VEXIS - Grupo ARI')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/vexis-favicon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* ============================================
           VEXIS Design System - CSS Variables
           ============================================ */
        :root {
            --vx-primary: #33AADD;
            --vx-primary-dark: #2890BB;
            --vx-primary-light: #5CBDE6;
            --vx-accent: #9BA4AE;
            --vx-accent-dark: #6B7580;
            --vx-white: #FFFFFF;
            --vx-gray-50: #F8F9FA;
            --vx-gray-100: #F1F3F5;
            --vx-gray-200: #E9ECEF;
            --vx-gray-300: #DEE2E6;
            --vx-gray-400: #CED4DA;
            --vx-gray-500: #ADB5BD;
            --vx-gray-600: #6C757D;
            --vx-gray-700: #495057;
            --vx-gray-800: #343A40;
            --vx-gray-900: #212529;
            --vx-success: #2ECC71;
            --vx-warning: #F39C12;
            --vx-danger: #E74C3C;
            --vx-info: #3498DB;
            --vx-bg: var(--vx-gray-50);
            --vx-surface: var(--vx-white);
            --vx-surface-hover: var(--vx-gray-100);
            --vx-border: var(--vx-gray-200);
            --vx-text: var(--vx-gray-900);
            --vx-text-secondary: var(--vx-gray-600);
            --vx-text-muted: var(--vx-gray-500);
            --vx-shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --vx-shadow: 0 2px 8px rgba(0,0,0,0.08);
            --vx-shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --vx-radius: 8px;
            --vx-radius-lg: 12px;
            --vx-navbar-height: 56px;
            --vx-font: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --vx-font-mono: 'JetBrains Mono', monospace;
        }

        [data-theme="dark"] {
            --vx-bg: #0F1117;
            --vx-surface: #1A1D27;
            --vx-surface-hover: #242736;
            --vx-border: #2A2D3A;
            --vx-text: #E8E9ED;
            --vx-text-secondary: #9CA3AF;
            --vx-text-muted: #6B7280;
            --vx-shadow-sm: 0 1px 3px rgba(0,0,0,0.2);
            --vx-shadow: 0 2px 8px rgba(0,0,0,0.3);
            --vx-shadow-lg: 0 8px 24px rgba(0,0,0,0.4);
            --vx-gray-50: #1A1D27;
            --vx-gray-100: #242736;
            --vx-gray-200: #2A2D3A;
            --vx-gray-300: #3A3D4A;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        body { font-family: var(--vx-font); font-size: 14px; line-height: 1.6; color: var(--vx-text); background: var(--vx-bg); min-height: 100vh; display: flex; flex-direction: column; transition: background-color 0.3s ease, color 0.3s ease; }
        a { color: var(--vx-primary); text-decoration: none; transition: color 0.2s; }
        a:hover { color: var(--vx-primary-dark); }

        /* Navbar */
        .vx-navbar { height: var(--vx-navbar-height); background: var(--vx-surface); border-bottom: 1px solid var(--vx-border); display: flex; align-items: center; padding: 0 24px; position: sticky; top: 0; z-index: 1000; box-shadow: var(--vx-shadow-sm); transition: background-color 0.3s, border-color 0.3s; }
        .vx-navbar-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; margin-right: 32px; flex-shrink: 0; }
        .vx-navbar-brand img { height: 28px; width: auto; }
        .vx-navbar-brand .vx-role-badge { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 2px 8px; border-radius: 4px; background: var(--vx-primary); color: white; }
        .vx-nav { display: flex; align-items: center; gap: 4px; list-style: none; flex: 1; }
        .vx-nav-item { position: relative; }
        .vx-nav-link { display: flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 500; color: var(--vx-text-secondary); text-decoration: none; transition: all 0.2s; white-space: nowrap; cursor: pointer; border: none; background: none; font-family: var(--vx-font); }
        .vx-nav-link:hover, .vx-nav-link.active { color: var(--vx-primary); background: rgba(51, 170, 221, 0.08); }
        .vx-nav-link i { font-size: 15px; }
        .vx-dropdown { position: absolute; top: calc(100% + 4px); left: 0; min-width: 220px; background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius); box-shadow: var(--vx-shadow-lg); padding: 6px; opacity: 0; visibility: hidden; transform: translateY(-8px); transition: all 0.2s ease; z-index: 1100; }
        .vx-nav-item:hover > .vx-dropdown, .vx-nav-item.open > .vx-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
        .vx-dropdown-item { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 6px; font-size: 13px; font-weight: 400; color: var(--vx-text); text-decoration: none; transition: all 0.15s; }
        .vx-dropdown-item:hover { background: var(--vx-surface-hover); color: var(--vx-primary); }
        .vx-dropdown-item i { font-size: 15px; color: var(--vx-text-muted); width: 20px; text-align: center; }
        .vx-dropdown-item:hover i { color: var(--vx-primary); }
        .vx-dropdown-divider { height: 1px; background: var(--vx-border); margin: 4px 8px; }
        .vx-dropdown-header { padding: 6px 12px 4px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--vx-text-muted); }
        .vx-nav-right { display: flex; align-items: center; gap: 4px; margin-left: auto; }
        .vx-icon-btn { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--vx-text-secondary); background: none; border: none; cursor: pointer; transition: all 0.2s; font-size: 17px; position: relative; }
        .vx-icon-btn:hover { background: var(--vx-surface-hover); color: var(--vx-primary); }
        .vx-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--vx-primary), var(--vx-primary-dark)); color: white; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; cursor: pointer; transition: box-shadow 0.2s; }
        .vx-avatar:hover { box-shadow: 0 0 0 3px rgba(51, 170, 221, 0.25); }
        .vx-user-dropdown { right: 0; left: auto; }

        /* Main Content */
        .vx-main { flex: 1; padding: 24px; max-width: 1400px; width: 100%; margin: 0 auto; }

        /* Cards */
        .vx-card { background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius-lg); box-shadow: var(--vx-shadow-sm); transition: all 0.3s ease; overflow: hidden; }
        .vx-card-header { padding: 16px 20px; border-bottom: 1px solid var(--vx-border); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .vx-card-header h2, .vx-card-header h3, .vx-card-header h4 { font-size: 16px; font-weight: 700; margin: 0; color: var(--vx-text); }
        .vx-card-body { padding: 20px; }

        /* Tables */
        .vx-table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .vx-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .vx-table thead th { padding: 10px 14px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--vx-text-muted); background: var(--vx-gray-50); border-bottom: 2px solid var(--vx-border); white-space: nowrap; text-align: left; }
        [data-theme="dark"] .vx-table thead th { background: var(--vx-gray-100); }
        .vx-table tbody td { padding: 12px 14px; font-size: 13px; border-bottom: 1px solid var(--vx-border); color: var(--vx-text); vertical-align: middle; }
        .vx-table tbody tr { transition: background 0.15s; }
        .vx-table tbody tr:hover { background: var(--vx-surface-hover); }
        .vx-table tbody tr:last-child td { border-bottom: none; }

        /* Buttons */
        .vx-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-family: var(--vx-font); font-size: 13px; font-weight: 600; border: 1px solid transparent; cursor: pointer; transition: all 0.2s; text-decoration: none; white-space: nowrap; line-height: 1.4; }
        .vx-btn i { font-size: 15px; }
        .vx-btn-primary { background: var(--vx-primary); color: white; border-color: var(--vx-primary); }
        .vx-btn-primary:hover { background: var(--vx-primary-dark); border-color: var(--vx-primary-dark); color: white; box-shadow: 0 4px 12px rgba(51, 170, 221, 0.3); }
        .vx-btn-secondary { background: var(--vx-surface); color: var(--vx-text); border-color: var(--vx-border); }
        .vx-btn-secondary:hover { background: var(--vx-surface-hover); border-color: var(--vx-gray-400); color: var(--vx-text); }
        .vx-btn-success { background: var(--vx-success); color: white; border-color: var(--vx-success); }
        .vx-btn-success:hover { background: #27AE60; border-color: #27AE60; color: white; }
        .vx-btn-warning { background: var(--vx-warning); color: white; border-color: var(--vx-warning); }
        .vx-btn-warning:hover { background: #E67E22; border-color: #E67E22; color: white; }
        .vx-btn-danger { background: var(--vx-danger); color: white; border-color: var(--vx-danger); }
        .vx-btn-danger:hover { background: #C0392B; border-color: #C0392B; color: white; }
        .vx-btn-info { background: var(--vx-info); color: white; border-color: var(--vx-info); }
        .vx-btn-info:hover { background: #2980B9; border-color: #2980B9; color: white; }
        .vx-btn-ghost { background: transparent; color: var(--vx-text-secondary); border-color: transparent; }
        .vx-btn-ghost:hover { background: var(--vx-surface-hover); color: var(--vx-text); }
        .vx-btn-sm { padding: 5px 10px; font-size: 12px; border-radius: 6px; }
        .vx-btn-sm i { font-size: 13px; }
        .vx-btn-lg { padding: 12px 24px; font-size: 15px; }
        .vx-btn-group { display: inline-flex; gap: 6px; }

        /* Forms */
        .vx-form-group { margin-bottom: 16px; }
        .vx-label { display: block; font-size: 13px; font-weight: 600; color: var(--vx-text); margin-bottom: 6px; }
        .vx-label .required { color: var(--vx-danger); margin-left: 2px; }
        .vx-input, .vx-select, .vx-textarea { width: 100%; padding: 9px 12px; border: 1px solid var(--vx-border); border-radius: var(--vx-radius); font-family: var(--vx-font); font-size: 13px; color: var(--vx-text); background: var(--vx-surface); transition: all 0.2s; outline: none; }
        .vx-input:focus, .vx-select:focus, .vx-textarea:focus { border-color: var(--vx-primary); box-shadow: 0 0 0 3px rgba(51, 170, 221, 0.15); }
        .vx-input.is-invalid, .vx-select.is-invalid { border-color: var(--vx-danger); }
        .vx-input.is-invalid:focus, .vx-select.is-invalid:focus { box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.15); }
        .vx-invalid-feedback { font-size: 12px; color: var(--vx-danger); margin-top: 4px; }
        .vx-form-hint { font-size: 12px; color: var(--vx-text-muted); margin-top: 4px; }
        .vx-checkbox { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; }
        .vx-checkbox input[type="checkbox"] { width: 16px; height: 16px; border-radius: 4px; accent-color: var(--vx-primary); cursor: pointer; }

        /* Badges */
        .vx-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 100px; font-size: 11px; font-weight: 600; letter-spacing: 0.2px; }
        .vx-badge-primary { background: rgba(51,170,221,0.12); color: var(--vx-primary); }
        .vx-badge-success { background: rgba(46,204,113,0.12); color: var(--vx-success); }
        .vx-badge-warning { background: rgba(243,156,18,0.12); color: var(--vx-warning); }
        .vx-badge-danger { background: rgba(231,76,60,0.12); color: var(--vx-danger); }
        .vx-badge-info { background: rgba(52,152,219,0.12); color: var(--vx-info); }
        .vx-badge-gray { background: var(--vx-gray-200); color: var(--vx-gray-700); }

        /* Alerts */
        .vx-alert { display: flex; align-items: flex-start; gap: 12px; padding: 12px 16px; border-radius: var(--vx-radius); font-size: 13px; margin-bottom: 16px; border: 1px solid; }
        .vx-alert i:first-child { font-size: 18px; margin-top: 1px; flex-shrink: 0; }
        .vx-alert-success { background: rgba(46,204,113,0.08); border-color: rgba(46,204,113,0.2); color: #1E8449; }
        [data-theme="dark"] .vx-alert-success { color: var(--vx-success); }
        .vx-alert-danger { background: rgba(231,76,60,0.08); border-color: rgba(231,76,60,0.2); color: #C0392B; }
        [data-theme="dark"] .vx-alert-danger { color: var(--vx-danger); }
        .vx-alert-warning { background: rgba(243,156,18,0.08); border-color: rgba(243,156,18,0.2); color: #D68910; }
        [data-theme="dark"] .vx-alert-warning { color: var(--vx-warning); }
        .vx-alert-info { background: rgba(52,152,219,0.08); border-color: rgba(52,152,219,0.2); color: #2471A3; }
        [data-theme="dark"] .vx-alert-info { color: var(--vx-info); }
        .vx-alert-close { margin-left: auto; background: none; border: none; color: inherit; cursor: pointer; opacity: 0.6; font-size: 16px; padding: 0; flex-shrink: 0; }
        .vx-alert-close:hover { opacity: 1; }

        /* Pagination */
        .pagination { display: flex; align-items: center; justify-content: center; gap: 4px; list-style: none; padding: 0; margin-top: 20px; }
        .pagination .page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 6px; font-size: 13px; font-weight: 500; color: var(--vx-text-secondary); background: var(--vx-surface); border: 1px solid var(--vx-border); text-decoration: none; transition: all 0.15s; font-family: var(--vx-font); }
        .pagination .page-item .page-link:hover { color: var(--vx-primary); border-color: var(--vx-primary); background: rgba(51,170,221,0.05); }
        .pagination .page-item.active .page-link { background: var(--vx-primary); color: white; border-color: var(--vx-primary); }
        .pagination .page-item.disabled .page-link { opacity: 0.4; pointer-events: none; }

        /* Page Header */
        .vx-page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; gap: 16px; flex-wrap: wrap; }
        .vx-page-title { font-size: 22px; font-weight: 800; color: var(--vx-text); letter-spacing: -0.3px; }
        .vx-page-actions { display: flex; gap: 8px; flex-wrap: wrap; }

        /* Search Box */
        .vx-search-box { display: flex; gap: 8px; margin-bottom: 20px; }
        .vx-search-box .vx-input { flex: 1; }

        /* Empty State */
        .vx-empty { text-align: center; padding: 48px 24px; }
        .vx-empty i { font-size: 48px; color: var(--vx-text-muted); margin-bottom: 12px; display: block; }
        .vx-empty p { font-size: 14px; color: var(--vx-text-secondary); }

        /* Footer */
        .vx-footer { padding: 16px 24px; text-align: center; font-size: 12px; color: var(--vx-text-muted); border-top: 1px solid var(--vx-border); background: var(--vx-surface); transition: all 0.3s; }
        .vx-footer a { color: var(--vx-primary); font-weight: 600; }

        /* Grid Utilities */
        .vx-grid { display: grid; gap: 16px; }
        .vx-grid-2 { grid-template-columns: repeat(2, 1fr); }
        .vx-grid-3 { grid-template-columns: repeat(3, 1fr); }
        .vx-grid-4 { grid-template-columns: repeat(4, 1fr); }

        /* Stat Card */
        .vx-stat-card { background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius-lg); padding: 20px; display: flex; align-items: flex-start; gap: 16px; transition: all 0.2s; text-decoration: none; }
        .vx-stat-card:hover { box-shadow: var(--vx-shadow); border-color: var(--vx-gray-300); transform: translateY(-1px); }
        .vx-stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .vx-stat-content h4 { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--vx-text-muted); margin-bottom: 4px; }
        .vx-stat-content .vx-stat-value { font-size: 22px; font-weight: 800; color: var(--vx-text); }

        /* Info Row (for show views) */
        .vx-info-row { display: flex; padding: 12px 0; border-bottom: 1px solid var(--vx-border); }
        .vx-info-row:last-child { border-bottom: none; }
        .vx-info-label { width: 180px; flex-shrink: 0; font-size: 13px; font-weight: 600; color: var(--vx-text-secondary); }
        .vx-info-value { font-size: 13px; color: var(--vx-text); flex: 1; }

        /* Section within form (for restriction blocks etc) */
        .vx-section { border: 1px solid var(--vx-border); border-radius: var(--vx-radius); overflow: hidden; margin-bottom: 12px; }
        .vx-section-header { padding: 10px 16px; background: var(--vx-gray-50); border-bottom: 1px solid var(--vx-border); font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        [data-theme="dark"] .vx-section-header { background: var(--vx-gray-100); }
        .vx-section-body { padding: 12px 16px; }

        /* Flex Utilities */
        .vx-flex { display: flex; }
        .vx-flex-center { display: flex; align-items: center; justify-content: center; }
        .vx-flex-between { display: flex; align-items: center; justify-content: space-between; }
        .vx-gap-sm { gap: 8px; }
        .vx-gap-md { gap: 16px; }

        /* Mobile */
        .vx-mobile-toggle { display: none; background: none; border: none; color: var(--vx-text); font-size: 20px; cursor: pointer; padding: 8px; }

        @media (max-width: 992px) {
            .vx-mobile-toggle { display: block; }
            .vx-nav { display: none; position: absolute; top: var(--vx-navbar-height); left: 0; right: 0; background: var(--vx-surface); border-bottom: 1px solid var(--vx-border); box-shadow: var(--vx-shadow-lg); flex-direction: column; padding: 8px; gap: 2px; }
            .vx-nav.open { display: flex; }
            .vx-nav-item:hover > .vx-dropdown { position: static; box-shadow: none; border: none; opacity: 1; visibility: visible; transform: none; padding-left: 20px; background: var(--vx-gray-50); border-radius: var(--vx-radius); }
            .vx-dropdown { min-width: 100%; }
        }

        @media (max-width: 768px) {
            .vx-grid-2, .vx-grid-3, .vx-grid-4 { grid-template-columns: 1fr; }
            .vx-main { padding: 16px; }
            .vx-page-header { flex-direction: column; align-items: flex-start; }
            .vx-navbar { padding: 0 12px; }
            .vx-info-row { flex-direction: column; gap: 4px; }
            .vx-info-label { width: auto; }
        }

        @media (max-width: 992px) and (min-width: 769px) {
            .vx-grid-3, .vx-grid-4 { grid-template-columns: repeat(2, 1fr); }
        }

        /* Animations */
        .vx-fade-in { animation: vxFadeIn 0.3s ease; }
        @keyframes vxFadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--vx-gray-400); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--vx-gray-500); }

        /* Collapsible filter */
        .vx-collapse { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        .vx-collapse.open { max-height: 2000px; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="vx-navbar">
        <a href="{{ route('home') }}" class="vx-navbar-brand">
            <img src="{{ asset('img/vexis-logo.png') }}" alt="VEXIS">
            @auth
                <span class="vx-role-badge">{{ Auth::user()->roles->first()->name ?? 'Usuario' }}</span>
            @endauth
        </a>

        <button class="vx-mobile-toggle" onclick="document.querySelector('.vx-nav').classList.toggle('open')">
            <i class="bi bi-list"></i>
        </button>

        @auth
        <ul class="vx-nav">
            <li class="vx-nav-item">
                <a href="{{ route('dashboard') }}" class="vx-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            </li>

            @canany(['ver usuarios', 'ver departamentos', 'ver centros', 'ver roles', 'ver restricciones', 'ver clientes'])
            <li class="vx-nav-item">
                <button class="vx-nav-link">
                    <i class="bi bi-building"></i> Gestión <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                </button>
                <div class="vx-dropdown">
                    @can('ver usuarios')
                    <a href="{{ route('users.index') }}" class="vx-dropdown-item"><i class="bi bi-people"></i> Usuarios</a>
                    @endcan
                    @can('ver clientes')
                    <a href="{{ route('clientes.index') }}" class="vx-dropdown-item"><i class="bi bi-person-lines-fill"></i> Clientes</a>
                    @endcan
                    <div class="vx-dropdown-divider"></div>
                    <div class="vx-dropdown-header">Mantenimiento</div>
                    @can('ver departamentos')
                    <a href="{{ route('departamentos.index') }}" class="vx-dropdown-item"><i class="bi bi-diagram-3"></i> Departamentos</a>
                    @endcan
                    @can('ver centros')
                    <a href="{{ route('centros.index') }}" class="vx-dropdown-item"><i class="bi bi-geo-alt"></i> Centros</a>
                    @endcan
                    <div class="vx-dropdown-divider"></div>
                    <div class="vx-dropdown-header">Seguridad</div>
                    @can('ver roles')
                    <a href="{{ route('roles.index') }}" class="vx-dropdown-item"><i class="bi bi-shield-lock"></i> Roles y Permisos</a>
                    @endcan
                    @can('ver restricciones')
                    <a href="{{ route('restricciones.index') }}" class="vx-dropdown-item"><i class="bi bi-lock"></i> Restricciones</a>
                    @endcan
                </div>
            </li>
            @endcanany

            @canany(['ver vehículos', 'ver ofertas'])
            <li class="vx-nav-item">
                <button class="vx-nav-link">
                    <i class="bi bi-car-front"></i> Comercial <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                </button>
                <div class="vx-dropdown">
                    @can('ver ofertas')
                    <a href="{{ route('ofertas.index') }}" class="vx-dropdown-item"><i class="bi bi-file-earmark-text"></i> Ofertas</a>
                    @endcan
                    @can('ver vehículos')
                    <a href="{{ route('vehiculos.index') }}" class="vx-dropdown-item"><i class="bi bi-truck"></i> Vehículos</a>
                    @endcan
                </div>
            </li>
            @endcanany
        </ul>
        @endauth

        <div class="vx-nav-right">
            <button class="vx-icon-btn" onclick="toggleTheme()" title="Cambiar tema" id="themeToggle">
                <i class="bi bi-moon"></i>
            </button>

            @auth
            <div class="vx-nav-item">
                <div class="vx-avatar" onclick="this.parentElement.classList.toggle('open')">
                    {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellidos, 0, 1)) }}
                </div>
                <div class="vx-dropdown vx-user-dropdown">
                    <div style="padding: 10px 12px; border-bottom: 1px solid var(--vx-border); margin-bottom: 4px;">
                        <div style="font-weight: 700; font-size: 13px; color: var(--vx-text);">{{ Auth::user()->nombre_completo }}</div>
                        <div style="font-size: 12px; color: var(--vx-text-muted);">{{ Auth::user()->email }}</div>
                    </div>
                    <a href="{{ route('dashboard') }}" class="vx-dropdown-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <div class="vx-dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="vx-dropdown-item" style="width:100%; border:none; background:none; cursor:pointer; text-align:left; font-family: var(--vx-font);">
                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="vx-btn vx-btn-primary vx-btn-sm">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
            </a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="vx-main vx-fade-in">
        @if(session('success'))
            <div class="vx-alert vx-alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button class="vx-alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="vx-alert vx-alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('error') }}</span>
                <button class="vx-alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="vx-footer">
        <span>&copy; {{ date('Y') }}, made by <a href="{{ route('home') }}">Grupo ARI</a></span>
    </footer>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('vexis-theme', next);
            document.querySelector('#themeToggle i').className = next === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
        }
        (function() {
            const saved = localStorage.getItem('vexis-theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
            const icon = document.querySelector('#themeToggle i');
            if (icon) icon.className = saved === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
        })();
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.vx-nav-item')) {
                document.querySelectorAll('.vx-nav-item.open').forEach(i => i.classList.remove('open'));
            }
        });
        document.querySelectorAll('.vx-alert').forEach(alert => {
            setTimeout(() => { alert.style.opacity = '0'; alert.style.transform = 'translateY(-8px)'; alert.style.transition = 'all 0.3s'; setTimeout(() => alert.remove(), 300); }, 5000);
        });
    </script>
    @stack('scripts')
</body>
</html>
