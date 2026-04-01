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
            --vx-text-muted: #8590A2;
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

        /* Mega dropdown for modules */
        .vx-dropdown-mega { display: flex; gap: 0; min-width: 420px; padding: 8px 0; }
        .vx-mega-col { flex: 1; padding: 0; min-width: 140px; }
        .vx-mega-col:not(:last-child) { border-right: 1px solid var(--vx-border); }
        .vx-mega-col .vx-dropdown-item { padding: 7px 16px; }
        .vx-mega-col .vx-dropdown-header { padding: 8px 16px 4px; }
        .vx-dropdown-sub { min-width: 200px; }
        .vx-submenu-parent { position: relative; }
        .vx-submenu-trigger { cursor: default; display: flex; align-items: center; }
        .vx-submenu-trigger:hover { background: var(--vx-surface-hover); }
        .vx-submenu { position: absolute; left: 100%; top: -4px; min-width: 190px; background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: 8px; box-shadow: var(--vx-shadow-lg); padding: 4px; display: none; z-index: 1200; }
        .vx-submenu-parent:hover > .vx-submenu { display: block; }
        .vx-icon-btn { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--vx-text-secondary); background: none; border: none; cursor: pointer; transition: all 0.2s; font-size: 17px; position: relative; }
        .vx-icon-btn:hover { background: var(--vx-surface-hover); color: var(--vx-primary); }
        .vx-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--vx-primary), var(--vx-primary-dark)); color: white; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; cursor: pointer; transition: box-shadow 0.2s; }
        .vx-avatar:hover { box-shadow: 0 0 0 3px rgba(51, 170, 221, 0.25); }
        .vx-user-dropdown { right: 0; left: auto; }
        .vx-navbar-modes { display: flex; align-items: center; gap: 10px; margin-left: 8px; }
        .vx-mode-toggle { display: inline-flex; align-items: center; gap: 6px; border: 1px solid var(--vx-border); background: var(--vx-surface); color: var(--vx-text-secondary); border-radius: 999px; padding: 3px; min-height: 34px; }
        .vx-mode-toggle button { border: none; background: transparent; color: inherit; font-size: 11px; font-weight: 700; border-radius: 999px; padding: 5px 10px; cursor: pointer; transition: all 0.15s; font-family: var(--vx-font); }
        .vx-mode-toggle button.active { background: var(--vx-primary); color: #fff; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08); }
        .vx-mode-toggle button:not(.active):hover { color: var(--vx-text); background: var(--vx-surface-hover); }
        .vx-navbar-mobile-tools { display: none; position: relative; margin-left: 8px; }
        .vx-mobile-tools-btn { height: 34px; border-radius: 8px; border: 1px solid var(--vx-border); background: var(--vx-surface); color: var(--vx-text-secondary); padding: 0 12px; font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
        .vx-mobile-tools-btn:hover { background: var(--vx-surface-hover); color: var(--vx-text); }
        .vx-mobile-tools-menu { position: absolute; top: calc(100% + 6px); right: 0; min-width: 220px; background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: 10px; box-shadow: var(--vx-shadow-lg); padding: 6px; display: none; z-index: 1300; }
        .vx-navbar-mobile-tools.open .vx-mobile-tools-menu { display: block; }
        .vx-mobile-tools-menu button, .vx-mobile-tools-menu a { width: 100%; border: none; background: none; color: var(--vx-text); border-radius: 7px; padding: 8px 10px; display: flex; align-items: center; gap: 9px; text-decoration: none; font-size: 12px; cursor: pointer; text-align: left; font-family: var(--vx-font); }
        .vx-mobile-tools-menu form { margin: 0; }
        .vx-mobile-tools-menu button:hover, .vx-mobile-tools-menu a:hover { background: var(--vx-surface-hover); }
        .vx-mobile-tools-menu .danger { color: var(--vx-danger); }
        .vx-modulebar { background: linear-gradient(90deg, #1F7FC6, #226FC2); border-bottom: 1px solid rgba(255,255,255,0.18); position: sticky; top: var(--vx-navbar-height); z-index: 990; box-shadow: 0 3px 10px rgba(18, 54, 97, 0.22); }
        .vx-modulebar-inner { max-width: 1200px; width: 100%; margin: 0 auto; padding: 8px 24px; }
        .vx-modules-toggle { display: none; align-items: center; gap: 8px; border: 1px solid rgba(255,255,255,0.28); color: #fff; background: rgba(255,255,255,0.12); border-radius: 8px; padding: 7px 12px; font-size: 12px; font-weight: 700; cursor: pointer; }
        .vx-modules-toggle:hover { background: rgba(255,255,255,0.2); }
        .vx-module-panel { margin-top: 8px; padding: 0; border-radius: 10px; background: transparent; border: none; display: block; }
        .vx-module-panel.open { display: block; }
        .vx-module-nav { display: flex; align-items: center; gap: 6px; list-style: none; width: 100%; margin: 0; padding: 0; overflow: visible; flex-wrap: wrap; }
        .vx-module-nav .vx-nav-link { color: rgba(255,255,255,0.92); background: transparent; }
        .vx-module-nav .vx-nav-link:hover, .vx-module-nav .vx-nav-link.active { color: #FFFFFF; background: rgba(255,255,255,0.18); }
        .vx-module-nav .vx-nav-link i { color: rgba(255,255,255,0.92); }
        .vx-module-nav .vx-dropdown { top: calc(100% + 8px); }
        .vx-module-label-client { display: none; }
        .vx-client-mode .vx-module-item-dev { display: none !important; }
        .vx-client-mode .vx-module-label-default { display: none; }
        .vx-client-mode .vx-module-label-client { display: inline; }
        .vx-preview-stage { width: 100%; }
        .vx-preview-device { width: 100%; }
        .vx-force-mobile { background: #10151f; }
        .vx-force-mobile .vx-preview-stage { min-height: 100vh; display: flex; justify-content: center; align-items: flex-start; padding: 14px; overflow: auto; }
        .vx-force-mobile .vx-preview-device { width: 390px; max-width: 390px; background: var(--vx-bg); border-radius: 22px; border: 8px solid #0b0f17; box-shadow: 0 20px 50px rgba(0,0,0,0.45); overflow: hidden; transform-origin: top center; transform: scale(var(--vx-mobile-preview-scale, 1)); }
        .vx-force-mobile .vx-navbar { padding: 8px 10px 4px; min-height: calc(var(--vx-navbar-height) + 12px); height: auto; align-items: center; }
        .vx-force-mobile .vx-navbar-modes { display: none; }
        .vx-force-mobile .vx-nav-right { display: none; }
        .vx-force-mobile .vx-navbar-mobile-tools { display: block; margin-left: auto; }
        .vx-force-mobile .vx-main { width: 100%; padding: 14px; max-width: none; }
        .vx-force-mobile .vx-modulebar-inner { padding: 8px 10px; }
        .vx-force-mobile .vx-modules-toggle { display: inline-flex; }
        .vx-force-mobile .vx-module-panel { margin-top: 6px; padding: 8px; border-radius: 10px; background: rgba(9, 39, 78, 0.22); border: 1px solid rgba(255,255,255,0.14); display: none; }
        .vx-force-mobile .vx-module-panel.open { display: block; }
        .vx-force-mobile .vx-module-nav { flex-direction: column; align-items: stretch; }
        .vx-force-mobile .vx-module-nav .vx-nav-item { width: 100%; }
        .vx-force-mobile .vx-module-nav .vx-nav-link { width: 100%; justify-content: space-between; }

        /* Main Content */
        .vx-main { flex: 1; padding: 24px; max-width: 1200px; width: 100%; margin: 0 auto; }

        /* Cards */
        .vx-card { background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius-lg); box-shadow: var(--vx-shadow-sm); transition: all 0.3s ease; overflow: visible; }
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
        .vx-actions { position: relative; display: inline-block; }
        .vx-actions-toggle { width: 30px; height: 30px; border-radius: 6px; border: 1px solid var(--vx-border); background: var(--vx-surface); color: var(--vx-text-muted); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s; font-size: 14px; }
        .vx-actions-toggle:hover { background: var(--vx-surface-hover); color: var(--vx-text); border-color: var(--vx-primary); }
        .vx-actions-menu { position: fixed; left: -9999px; top: -9999px; min-width: 170px; background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: 8px; box-shadow: var(--vx-shadow-lg); z-index: 99999; padding: 4px; display: none; }
        .vx-actions.open .vx-actions-menu { display: block; }
        .vx-actions-menu a, .vx-actions-menu button { display: flex; align-items: center; gap: 8px; width: 100%; padding: 8px 10px; border: none; background: none; border-radius: 5px; font-size: 12px; color: var(--vx-text); text-decoration: none; cursor: pointer; transition: background 0.1s; font-family: var(--vx-font); white-space: nowrap; text-align: left; }
        .vx-actions-menu i { width: 14px; flex-shrink: 0; text-align: center; }
        .vx-actions-menu a:hover, .vx-actions-menu button:hover { background: var(--vx-surface-hover); }
        .vx-actions-menu .vx-btn { justify-content: flex-start; font-weight: 500; border: none; background: transparent; color: var(--vx-text); width: 100%; padding: 8px 10px; }
        .vx-actions-menu .act-view i { color: var(--vx-info) !important; }
        .vx-actions-menu .act-edit i { color: var(--vx-warning) !important; }
        .vx-actions-menu .act-approve i { color: var(--vx-success) !important; }
        .vx-actions-menu .act-reject i { color: var(--vx-danger) !important; }
        .vx-actions-menu .act-delete, .vx-actions-menu .act-danger { color: var(--vx-danger); }
        .vx-actions-menu .act-delete i, .vx-actions-menu .act-danger i { color: var(--vx-danger) !important; }
        .vx-actions-menu .act-view:hover { background: rgba(52,152,219,0.12); }
        .vx-actions-menu .act-edit:hover { background: rgba(243,156,18,0.12); }
        .vx-actions-menu .act-approve:hover { background: rgba(46,204,113,0.12); }
        .vx-actions-menu .act-reject:hover, .vx-actions-menu .act-delete:hover, .vx-actions-menu .act-danger:hover { background: rgba(231,76,60,0.12); }
        .vx-actions-menu .act-danger { color: var(--vx-danger); }
        .vx-actions-menu .act-danger:hover { background: rgba(231,76,60,0.08); }
        .vx-actions-menu form { margin: 0; }

        /* Forms */
        .vx-form-group { margin-bottom: 16px; }
        .vx-label { display: block; font-size: 13px; font-weight: 600; color: var(--vx-text); margin-bottom: 6px; }
        .vx-label .required { color: var(--vx-danger); margin-left: 2px; }
        .vx-input, .vx-select, .vx-textarea { width: 100%; padding: 9px 12px; border: 1px solid var(--vx-border); border-radius: var(--vx-radius); font-family: var(--vx-font); font-size: 13px; color: var(--vx-text); background: var(--vx-surface); transition: all 0.2s; outline: none; }
        .vx-input:focus, .vx-select:focus, .vx-textarea:focus { border-color: var(--vx-primary); box-shadow: 0 0 0 3px rgba(51, 170, 221, 0.15); }
        .vx-input.is-invalid, .vx-select.is-invalid { border-color: var(--vx-danger); }
        .vx-input.is-invalid:focus, .vx-select.is-invalid:focus { box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.15); }
        .vx-select-create { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; color: var(--vx-primary); text-decoration: none; margin-top: 4px; padding: 2px 0; transition: color 0.15s; }
        .vx-select-create:hover { color: var(--vx-primary-dark); text-decoration: underline; }
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
        .vx-alert-gray { background: var(--vx-gray-50); border-color: var(--vx-border); color: var(--vx-text-secondary); }
        .vx-hidden { display: none !important; }
        .vx-alert-warning { background: rgba(243,156,18,0.08); border-color: rgba(243,156,18,0.2); color: #D68910; }
        [data-theme="dark"] .vx-alert-warning { color: var(--vx-warning); }
        .vx-alert-info { background: rgba(52,152,219,0.08); border-color: rgba(52,152,219,0.2); color: #2471A3; }
        [data-theme="dark"] .vx-alert-info { color: var(--vx-info); }
        .vx-alert-close { margin-left: auto; background: none; border: none; color: inherit; cursor: pointer; opacity: 0.6; font-size: 16px; padding: 0; flex-shrink: 0; }
        .vx-alert-close:hover { opacity: 1; }

        /* Pagination */
        .vx-pagination { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .vx-pagination-list { display: flex; align-items: center; gap: 4px; list-style: none; padding: 0; margin: 0; }
        .vx-page-link { display: flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 6px; font-size: 13px; font-weight: 500; color: var(--vx-text-secondary); background: var(--vx-surface); border: 1px solid var(--vx-border); text-decoration: none; transition: all 0.15s; font-family: var(--vx-font); cursor: pointer; }
        .vx-page-link:hover { color: var(--vx-primary); border-color: var(--vx-primary); background: rgba(51,170,221,0.05); }
        .vx-page-item.active .vx-page-link { background: var(--vx-primary); color: white; border-color: var(--vx-primary); }
        .vx-page-item.disabled .vx-page-link { opacity: 0.4; pointer-events: none; cursor: default; }
        .vx-pagination-info { font-size: 12px; color: var(--vx-text-muted); }
        /* Compat: old pagination from bootstrap-5 */
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
        .vx-info-label { width: 150px; flex-shrink: 0; font-size: 13px; font-weight: 600; color: var(--vx-text-secondary); }
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
            .vx-dropdown-mega { flex-direction: column; min-width: 100%; }
            .vx-mega-col:not(:last-child) { border-right: none; border-bottom: 1px solid var(--vx-border); padding-bottom: 4px; margin-bottom: 4px; }
            .vx-submenu { position: static; border: none; box-shadow: none; padding-left: 16px; display: none; }
            .vx-submenu-parent:hover > .vx-submenu { display: block; }
            .vx-submenu-trigger .bi-chevron-right { transform: rotate(90deg); }
        }

        @media (max-width: 768px) {
            .vx-grid-2, .vx-grid-3, .vx-grid-4 { grid-template-columns: 1fr; }
            .vx-main { padding: 16px; }
            .vx-page-header { flex-direction: column; align-items: flex-start; }
            .vx-navbar { padding: 0 12px; }
            .vx-navbar-modes { display: none; }
            .vx-modulebar-inner { padding: 8px 12px; }
            .vx-module-nav { flex-direction: column; align-items: stretch; }
            .vx-module-nav .vx-nav-item { width: 100%; }
            .vx-module-nav .vx-nav-link { width: 100%; justify-content: space-between; }
            .vx-info-row { flex-direction: column; gap: 4px; }
            .vx-info-label { width: auto; }
            [style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
            [style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
            [style*="grid-template-columns: repeat(auto-fill"] { grid-template-columns: 1fr !important; }
            [style*="grid-template-columns:repeat(auto-fill"] { grid-template-columns: 1fr !important; }
            .vx-search-box { flex-direction: column; }
            .vx-search-box select { width: 100% !important; }
            .vx-search-box input { width: 100% !important; }
            .vx-page-actions { flex-wrap: wrap; }
            .vx-btn-group { gap: 4px; }
        }

        @media (max-width: 480px) {
            .vx-main { padding: 8px; }
            .vx-page-title { font-size: 18px; }
            .vx-card-body { padding: 12px; }
            .vx-btn { font-size: 12px; padding: 6px 10px; }
            .vx-table th, .vx-table td { padding: 6px 8px; font-size: 12px; }
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

        /* Loading Screen */
        .vx-loading-screen { position: fixed; inset: 0; background: var(--vx-bg); display: flex; align-items: center; justify-content: center; z-index: 99999; transition: opacity 0.4s, visibility 0.4s; }
        .vx-loading-screen.hidden { opacity: 0; visibility: hidden; }
        .vx-loading-inner { text-align: center; }
        .vx-loading-logo { width: 120px; animation: vxPulse 1.8s infinite ease-in-out; }
        .vx-loading-bar { width: 160px; height: 3px; background: var(--vx-gray-200); border-radius: 2px; overflow: hidden; margin: 20px auto 0; }
        .vx-loading-bar-fill { height: 100%; width: 40%; background: linear-gradient(90deg, var(--vx-primary), var(--vx-primary-light)); border-radius: 2px; animation: vxLoadBar 1.2s infinite ease-in-out; }
        @keyframes vxPulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.6; transform: scale(0.95); } }
        @keyframes vxLoadBar { 0% { margin-left: 0; width: 30%; } 50% { width: 50%; } 100% { margin-left: 70%; width: 30%; } }

        /* Notifications Panel */
        .vx-notif-panel { position: absolute; top: calc(100% + 8px); right: 0; width: 360px; max-height: 420px; background: var(--vx-surface); border: 1px solid var(--vx-border); border-radius: var(--vx-radius-lg); box-shadow: var(--vx-shadow-lg); z-index: 1100; display: none; overflow: hidden; }
        .vx-notif-panel.open { display: block; }
        .vx-notif-header { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; border-bottom: 1px solid var(--vx-border); }
        .vx-notif-header h5 { font-size: 14px; font-weight: 700; margin: 0; }
        .vx-notif-actions { display: flex; gap: 8px; }
        .vx-notif-actions button { font-size: 11px; border: none; background: none; color: var(--vx-primary); cursor: pointer; font-family: var(--vx-font); padding: 2px 4px; }
        .vx-notif-actions button:hover { text-decoration: underline; }
        .vx-notif-list { max-height: 340px; overflow-y: auto; }
        .vx-notif-item { display: flex; gap: 10px; padding: 10px 16px; border-bottom: 1px solid var(--vx-border); transition: background 0.15s; cursor: default; }
        .vx-notif-item:hover { background: var(--vx-gray-50); }
        .vx-notif-item.unread { background: rgba(51,170,221,0.04); }
        .vx-notif-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
        .vx-notif-icon.success { background: rgba(46,204,113,0.12); color: var(--vx-success); }
        .vx-notif-icon.warning { background: rgba(243,156,18,0.12); color: var(--vx-warning); }
        .vx-notif-icon.danger { background: rgba(231,76,60,0.12); color: var(--vx-danger); }
        .vx-notif-icon.info { background: rgba(52,152,219,0.12); color: var(--vx-info); }
        .vx-notif-body { flex: 1; min-width: 0; }
        .vx-notif-close { width: 22px; height: 22px; border-radius: 4px; border: none; background: none; color: var(--vx-text-muted); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; transition: all 0.15s; opacity: 0; }
        .vx-notif-item:hover .vx-notif-close { opacity: 1; }
        .vx-notif-close:hover { background: rgba(231,76,60,0.1); color: var(--vx-danger); }
        .vx-notif-text { font-size: 12px; color: var(--vx-text); line-height: 1.4; }
        .vx-notif-time { font-size: 11px; color: var(--vx-text-muted); margin-top: 2px; }
        .vx-notif-empty { padding: 32px 16px; text-align: center; color: var(--vx-text-muted); font-size: 13px; }
        .vx-notif-badge { position: absolute; top: 2px; right: 2px; width: 8px; height: 8px; background: var(--vx-danger); border-radius: 50%; border: 2px solid var(--vx-surface); }

        /* Global Search */
        .vx-search-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 2000; display: none; align-items: flex-start; justify-content: center; padding-top: 15vh; }
        .vx-search-overlay.open { display: flex; }
        .vx-search-modal { width: 560px; max-width: 90vw; background: var(--vx-surface); border-radius: var(--vx-radius-lg); box-shadow: var(--vx-shadow-lg); overflow: hidden; }
        .vx-search-modal input { width: 100%; padding: 16px 20px; border: none; outline: none; font-size: 16px; font-family: var(--vx-font); background: transparent; color: var(--vx-text); }
        .vx-search-modal input::placeholder { color: var(--vx-text-muted); }
        .vx-search-hint { padding: 12px 20px; border-top: 1px solid var(--vx-border); font-size: 11px; color: var(--vx-text-muted); }
        .vx-search-hint kbd { background: var(--vx-gray-200); padding: 1px 6px; border-radius: 4px; font-size: 10px; font-family: var(--vx-font-mono); }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Loading Screen -->
    <div class="vx-loading-screen" id="vxLoader">
        <div class="vx-loading-inner">
            <img src="{{ asset('img/vexis-logo.png') }}" alt="VEXIS" class="vx-loading-logo">
            <div class="vx-loading-bar"><div class="vx-loading-bar-fill"></div></div>
        </div>
    </div>

    <!-- Global Search Overlay -->
    <div class="vx-search-overlay" id="vxSearch" onclick="if(event.target===this)closeSearch()">
        <div class="vx-search-modal">
            <input type="text" id="vxSearchInput" placeholder="Buscar en VEXIS..." autocomplete="off">
            <div class="vx-search-hint"><kbd>ESC</kbd> para cerrar · <kbd>↵</kbd> para buscar</div>
        </div>
    </div>

    <div class="vx-preview-stage" id="vxPreviewStage">
    <div class="vx-preview-device" id="vxPreviewDevice">
    <!-- Navbar -->
    <nav class="vx-navbar">
        <a href="{{ route('home') }}" class="vx-navbar-brand">
            <img src="{{ asset('img/vexis-logo.png') }}" alt="VEXIS">
            @auth
                <span class="vx-role-badge">{{ Auth::user()->roles->first()->name ?? 'Usuario' }}</span>
            @endauth
        </a>
        @auth
        <div class="vx-navbar-modes">
            <div class="vx-mode-toggle" role="group" aria-label="Modo de interfaz">
                <button type="button" id="uiModeDesktopBtn" class="active" onclick="setUiMode('desktop')">Ordenador</button>
                <button type="button" id="uiModeMobileBtn" onclick="setUiMode('mobile')">Móvil</button>
            </div>
            <div class="vx-mode-toggle" role="group" aria-label="Vista de usuario">
                <button type="button" id="viewModeDevBtn" class="active" onclick="setViewMode('dev')">Desarrollador</button>
                <button type="button" id="viewModeClientBtn" onclick="setViewMode('client')">Cliente</button>
            </div>
        </div>
        <div class="vx-navbar-mobile-tools" id="vxMobileTools">
            <button class="vx-mobile-tools-btn" type="button" onclick="toggleMobileToolsMenu()">
                <i class="bi bi-list"></i> Opciones
            </button>
            <div class="vx-mobile-tools-menu">
                <button type="button" onclick="openSearch(); closeMobileToolsMenu();"><i class="bi bi-search"></i> Buscar</button>
                <button type="button" onclick="toggleTheme(); closeMobileToolsMenu();"><i class="bi bi-moon-stars"></i> Cambiar tema</button>
                <button type="button" onclick="setUiMode('desktop'); closeMobileToolsMenu();"><i class="bi bi-display"></i> Vista ordenador</button>
                <button type="button" onclick="setUiMode('mobile'); closeMobileToolsMenu();"><i class="bi bi-phone"></i> Vista móvil</button>
                <button type="button" onclick="setViewMode('dev'); closeMobileToolsMenu();"><i class="bi bi-code-slash"></i> Vista desarrollador</button>
                <button type="button" onclick="setViewMode('client'); closeMobileToolsMenu();"><i class="bi bi-person"></i> Vista cliente</button>
                @auth
                    <button type="button" onclick="toggleNotifications(); closeMobileToolsMenu();"><i class="bi bi-bell"></i> Notificaciones</button>
                    <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="{{ route('profile.edit') }}"><i class="bi bi-person-gear"></i> Editar Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="danger"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</button>
                    </form>
                @endauth
            </div>
        </div>
        @endauth

        <div class="vx-nav-right">
            <!-- Global Search -->
            <button class="vx-icon-btn" onclick="openSearch()" title="Buscar (Ctrl+K)">
                <i class="bi bi-search"></i>
            </button>

            <!-- Theme Toggle -->
            <button class="vx-icon-btn" onclick="toggleTheme()" title="Cambiar tema" id="themeToggle">
                <i class="bi bi-moon"></i>
            </button>

            @auth
            <!-- Notifications -->
            <div class="vx-nav-item" style="position:relative;">
                <button class="vx-icon-btn" onclick="toggleNotifications()" title="Notificaciones" style="position:relative;">
                    <i class="bi bi-bell"></i>
                    <span class="vx-notif-badge" id="notifBadge" style="display:none;"></span>
                </button>
                <div class="vx-notif-panel" id="notifPanel">
                    <div class="vx-notif-header">
                        <h5>Notificaciones</h5>
                        <div class="vx-notif-actions">
                            <button onclick="markAllRead()">Marcar leídas</button>
                            <button onclick="clearAllNotifs()">Borrar todo</button>
                        </div>
                    </div>
                    <div class="vx-notif-list" id="notifList">
                        <div class="vx-notif-empty"><i class="bi bi-bell-slash" style="font-size:24px;display:block;margin-bottom:8px;"></i>Sin notificaciones</div>
                    </div>
                </div>
            </div>

            <!-- User Avatar -->
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
                    <a href="{{ route('profile.edit') }}" class="vx-dropdown-item"><i class="bi bi-person-gear"></i> Editar Perfil</a>
                    @role('Super Admin')
                    <a href="{{ route('settings.index') }}" class="vx-dropdown-item"><i class="bi bi-gear"></i> Configuración</a>
                    @endrole
                    <div class="vx-dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="vx-dropdown-item" style="width:100%;border:none;background:none;cursor:pointer;text-align:left;font-family:var(--vx-font);color:var(--vx-danger);">
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

    @auth
    <div class="vx-modulebar">
        <div class="vx-modulebar-inner">
        <button class="vx-modules-toggle" id="vxModulesToggle" type="button" onclick="toggleModulesPanel()">
            <i class="bi bi-grid-1x2"></i> Módulos <i class="bi bi-chevron-down" id="vxModulesToggleIcon" style="font-size:11px;"></i>
        </button>
        <div class="vx-module-panel open" id="vxModulesPanel">
        <ul class="vx-module-nav">
            {{-- Módulo GESTIÓN --}}
            @canany(['ver usuarios', 'ver departamentos', 'ver centros', 'ver roles', 'ver restricciones', 'ver clientes'])
            <li class="vx-nav-item vx-module-item-dev">
                <button class="vx-nav-link {{ request()->is('gestion*','users*','clientes*','departamentos*','centros*','roles*','restricciones*','empresas*','noticias*','campanias*','naming-pcs*','vacaciones*','festivos*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Gestión <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                </button>
                <div class="vx-dropdown vx-dropdown-sub">
                    <a href="{{ route('gestion.inicio') }}" class="vx-dropdown-item"><i class="bi bi-house-door"></i> Inicio</a>
                    @can('ver usuarios')
                    <a href="{{ route('users.index') }}" class="vx-dropdown-item"><i class="bi bi-people"></i> Usuarios</a>
                    @endcan
                    @can('ver clientes')
                    <a href="{{ route('clientes.index') }}" class="vx-dropdown-item"><i class="bi bi-person-lines-fill"></i> Clientes</a>
                    @endcan
                    <a href="{{ route('vacaciones.index') }}" class="vx-dropdown-item"><i class="bi bi-calendar-check"></i> Vacaciones</a>
                    {{-- Seguridad con submenú --}}
                    <div class="vx-submenu-parent">
                        <div class="vx-dropdown-item vx-submenu-trigger"><i class="bi bi-shield-lock"></i> Seguridad <i class="bi bi-chevron-right" style="margin-left:auto;font-size:10px;"></i></div>
                        <div class="vx-submenu">
                            @can('ver roles')
                            <a href="{{ route('roles.index') }}" class="vx-dropdown-item"><i class="bi bi-shield-lock"></i> Roles</a>
                            <a href="{{ route('gestion.permisos') }}" class="vx-dropdown-item"><i class="bi bi-key"></i> Permisos</a>
                            @endcan
                            @can('ver restricciones')
                            <a href="{{ route('restricciones.index') }}" class="vx-dropdown-item"><i class="bi bi-lock"></i> Restricciones</a>
                            @endcan
                            <a href="{{ route('gestion.politica') }}" class="vx-dropdown-item"><i class="bi bi-file-earmark-lock"></i> Política</a>
                        </div>
                    </div>
                    {{-- Marketing con submenú --}}
                    <div class="vx-submenu-parent">
                        <div class="vx-dropdown-item vx-submenu-trigger"><i class="bi bi-megaphone"></i> Marketing <i class="bi bi-chevron-right" style="margin-left:auto;font-size:10px;"></i></div>
                        <div class="vx-submenu">
                            @can('ver noticias')
                            <a href="{{ route('noticias.index') }}" class="vx-dropdown-item"><i class="bi bi-newspaper"></i> Noticias</a>
                            @endcan
                            @can('ver campanias')
                            <a href="{{ route('campanias.index') }}" class="vx-dropdown-item"><i class="bi bi-megaphone"></i> Campañas</a>
                            @endcan
                        </div>
                    </div>
                    {{-- Mantenimiento con submenú --}}
                    <div class="vx-submenu-parent">
                        <div class="vx-dropdown-item vx-submenu-trigger"><i class="bi bi-gear"></i> Mantenimiento <i class="bi bi-chevron-right" style="margin-left:auto;font-size:10px;"></i></div>
                        <div class="vx-submenu">
                            @can('ver empresas')
                            <a href="{{ route('empresas.index') }}" class="vx-dropdown-item"><i class="bi bi-building"></i> Empresas</a>
                            @endcan
                            @can('ver departamentos')
                            <a href="{{ route('departamentos.index') }}" class="vx-dropdown-item"><i class="bi bi-diagram-3"></i> Departamentos</a>
                            @endcan
                            @can('ver centros')
                            <a href="{{ route('centros.index') }}" class="vx-dropdown-item"><i class="bi bi-geo-alt"></i> Centros</a>
                            @endcan
                            <a href="{{ route('gestion.marcas') }}" class="vx-dropdown-item"><i class="bi bi-tags"></i> Marcas</a>
                            @can('ver naming-pcs')
                            <a href="{{ route('naming-pcs.index') }}" class="vx-dropdown-item"><i class="bi bi-pc-display"></i> Naming PCs</a>
                            @endcan
                            @can('ver festivos')
                            <a href="{{ route('festivos.index') }}" class="vx-dropdown-item"><i class="bi bi-calendar-event"></i> Festivos</a>
                            @endcan
                            @if(\App\Models\Setting::get('modulo_incidencias', true))
                            @can('ver incidencias')
                            <a href="{{ route('incidencias.index') }}" class="vx-dropdown-item"><i class="bi bi-exclamation-triangle"></i> Incidencias</a>
                            @endcan
                            @endif
                        </div>
                    </div>
                </div>
            </li>
            @endcanany

            {{-- Módulo RECAMBIOS --}}
            @canany(['ver almacenes', 'ver stocks', 'ver repartos'])
            <li class="vx-nav-item vx-module-item-dev">
                <button class="vx-nav-link {{ request()->is('recambios*','almacenes*','stocks*','repartos*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Recambios <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                </button>
                <div class="vx-dropdown">
                    <a href="{{ route('recambios.inicio') }}" class="vx-dropdown-item"><i class="bi bi-house-door"></i> Inicio</a>
                    @can('ver almacenes')
                    <a href="{{ route('almacenes.index') }}" class="vx-dropdown-item"><i class="bi bi-boxes"></i> Almacenes</a>
                    @endcan
                    @can('ver stocks')
                    <a href="{{ route('stocks.index') }}" class="vx-dropdown-item"><i class="bi bi-box2"></i> Stock</a>
                    @endcan
                    @can('ver repartos')
                    <a href="{{ route('repartos.index') }}" class="vx-dropdown-item"><i class="bi bi-truck"></i> Repartos</a>
                    @endcan
                </div>
            </li>
            @endcanany

            {{-- Módulo TALLERES --}}
            @canany(['ver talleres', 'ver mecanicos', 'ver citas', 'ver coches-sustitucion'])
            <li class="vx-nav-item vx-module-item-dev">
                <button class="vx-nav-link {{ request()->is('talleres*','mecanicos*','citas*','coches-sustitucion*') ? 'active' : '' }}">
                    <i class="bi bi-wrench-adjustable"></i> Talleres <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                </button>
                <div class="vx-dropdown">
                    <a href="{{ route('talleres.inicio') }}" class="vx-dropdown-item"><i class="bi bi-house-door"></i> Inicio</a>
                    @can('ver talleres')<a href="{{ route('talleres.index') }}" class="vx-dropdown-item"><i class="bi bi-tools"></i> Talleres</a>@endcan
                    @can('ver mecanicos')<a href="{{ route('mecanicos.index') }}" class="vx-dropdown-item"><i class="bi bi-person-gear"></i> Mecánicos</a>@endcan
                    @can('ver citas')<a href="{{ route('citas.index') }}" class="vx-dropdown-item"><i class="bi bi-calendar-check"></i> Citas</a>@endcan
                    @can('ver coches-sustitucion')<a href="{{ route('coches-sustitucion.index') }}" class="vx-dropdown-item"><i class="bi bi-car-front"></i> Coches Sustitución</a>@endcan
                </div>
            </li>
            @endcanany

            {{-- Módulo COMERCIAL --}}
            @canany(['ver vehículos', 'ver ofertas', 'ver ventas', 'ver tasaciones', 'ver catalogo-precios', 'ver facturas', 'ver verifactu'])
            <li class="vx-nav-item vx-module-item-dev">
                <button class="vx-nav-link {{ request()->is('comercial*','ofertas*','vehiculos*','ventas*','tasaciones*','catalogo-precios*') ? 'active' : '' }}">
                    <i class="bi bi-car-front"></i> Comercial <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                </button>
                <div class="vx-dropdown">
                    <a href="{{ route('comercial.inicio') }}" class="vx-dropdown-item"><i class="bi bi-house-door"></i> Inicio</a>
                    @can('ver ofertas')
                    <a href="{{ route('ofertas.index') }}" class="vx-dropdown-item"><i class="bi bi-file-earmark-text"></i> Ofertas</a>
                    @endcan
                    @can('ver vehículos')
                    <a href="{{ route('vehiculos.index') }}" class="vx-dropdown-item"><i class="bi bi-truck"></i> Vehículos</a>
                    @endcan
                    @can('ver ventas')
                    <a href="{{ route('ventas.index') }}" class="vx-dropdown-item"><i class="bi bi-cart-check"></i> Ventas</a>
                    @endcan
                    @can('ver tasaciones')
                    <a href="{{ route('tasaciones.index') }}" class="vx-dropdown-item"><i class="bi bi-calculator"></i> Tasaciones</a>
                    @endcan
                    @can('ver catalogo-precios')
                    <a href="{{ route('catalogo-precios.index') }}" class="vx-dropdown-item"><i class="bi bi-currency-euro"></i> Catálogo Precios</a>
                    @endcan
                    @if(\App\Models\Setting::get('modulo_facturas', true))
                    @can('ver facturas')
                    <a href="{{ route('facturas.index') }}" class="vx-dropdown-item"><i class="bi bi-receipt"></i> Facturas</a>
                    @endcan
                    @endif
                    @if(\App\Models\Setting::get('modulo_verifactu', true))
                    @can('ver verifactu')
                    <a href="{{ route('verifactu.index') }}" class="vx-dropdown-item"><i class="bi bi-shield-check"></i> Verifactu</a>
                    @endcan
                    @endif
                </div>
            </li>
            @endcanany

            {{-- Módulo DATAXIS --}}
            @canany(['ver usuarios', 'ver departamentos', 'ver centros', 'ver roles', 'ver restricciones', 'ver clientes'])
            <li class="vx-nav-item vx-module-item-dev">
                <button class="vx-nav-link {{ request()->is('dataxis*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i> Dataxis <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                </button>
                <div class="vx-dropdown">
                    <a href="{{ route('dataxis.inicio') }}" class="vx-dropdown-item"><i class="bi bi-house-door"></i> Inicio</a>
                    <a href="{{ route('dataxis.general') }}" class="vx-dropdown-item"><i class="bi bi-speedometer2"></i> General</a>
                    <a href="{{ route('dataxis.ventas') }}" class="vx-dropdown-item"><i class="bi bi-currency-euro"></i> Ventas</a>
                    <a href="{{ route('dataxis.stock') }}" class="vx-dropdown-item"><i class="bi bi-box-seam"></i> Stock</a>
                    <a href="{{ route('dataxis.taller') }}" class="vx-dropdown-item"><i class="bi bi-wrench-adjustable"></i> Taller</a>
                </div>
            </li>
            @endcanany

            {{-- Módulo CLIENTE --}}
            <li class="vx-nav-item vx-module-item-client">
                <button class="vx-nav-link {{ request()->is('cliente*') ? 'active' : '' }}">
                    <i class="bi bi-person-heart"></i>
                    <span class="vx-module-label-default">Cliente</span>
                    <span class="vx-module-label-client">Menú</span>
                    <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                </button>
                <div class="vx-dropdown">
                    <a href="{{ route('cliente.inicio') }}" class="vx-dropdown-item"><i class="bi bi-house-door"></i> Inicio</a>
                    <a href="{{ route('cliente.chatbot') }}" class="vx-dropdown-item"><i class="bi bi-robot"></i> Chatbot IA</a>
                    <a href="{{ route('cliente.pretasacion') }}" class="vx-dropdown-item"><i class="bi bi-calculator"></i> Pretasación IA</a>
                    <a href="{{ route('cliente.tasacion') }}" class="vx-dropdown-item"><i class="bi bi-clipboard-check"></i> Tasación Formal</a>
                    <a href="{{ route('cliente.configurador') }}" class="vx-dropdown-item"><i class="bi bi-palette"></i> Configurador</a>
                    <a href="{{ route('cliente.precios') }}" class="vx-dropdown-item"><i class="bi bi-currency-euro"></i> Precios</a>
                    <a href="{{ route('cliente.campanias') }}" class="vx-dropdown-item"><i class="bi bi-megaphone"></i> Campañas</a>
                    <a href="{{ route('cliente.concesionarios') }}" class="vx-dropdown-item"><i class="bi bi-building"></i> Concesionarios</a>
                </div>
            </li>
        </ul>
        </div>
        </div>
    </div>
    @endauth

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
        <img src="{{ asset('img/vexis-icon.png') }}" alt="" style="height: 18px; width: auto; vertical-align: middle; margin-right: 6px; opacity: 0.6;">
        <span>&copy; {{ date('Y') }}, made by <a href="{{ route('home') }}">Meng Fei</a></span>
    </footer>
    </div>
    </div>

    <script>
        /* === Theme Toggle === */
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

        function setUiMode(mode) {
            const isMobile = mode === 'mobile';
            document.body.classList.toggle('vx-force-mobile', isMobile);
            localStorage.setItem('vexis-ui-mode', mode);
            document.getElementById('uiModeDesktopBtn')?.classList.toggle('active', !isMobile);
            document.getElementById('uiModeMobileBtn')?.classList.toggle('active', isMobile);
            if (!isMobile) {
                toggleModulesPanel(true);
            }
            updateMobilePreviewScale();
        }

        function setViewMode(mode) {
            const isClient = mode === 'client';
            document.body.classList.toggle('vx-client-mode', isClient);
            localStorage.setItem('vexis-view-mode', mode);
            document.getElementById('viewModeDevBtn')?.classList.toggle('active', !isClient);
            document.getElementById('viewModeClientBtn')?.classList.toggle('active', isClient);
        }

        function toggleModulesPanel(forceOpen = null) {
            const panel = document.getElementById('vxModulesPanel');
            const icon = document.getElementById('vxModulesToggleIcon');
            if (!panel) return;

            if (!document.body.classList.contains('vx-force-mobile')) {
                panel.classList.add('open');
                if (icon) icon.className = 'bi bi-chevron-up';
                return;
            }

            const nextOpen = forceOpen !== null ? forceOpen : !panel.classList.contains('open');
            panel.classList.toggle('open', nextOpen);
            if (icon) icon.className = nextOpen ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
            localStorage.setItem('vexis-modules-open', nextOpen ? '1' : '0');
        }

        function toggleMobileToolsMenu() {
            const panel = document.getElementById('vxMobileTools');
            if (!panel) return;
            panel.classList.toggle('open');
        }

        function closeMobileToolsMenu() {
            document.getElementById('vxMobileTools')?.classList.remove('open');
        }

        (function initializeUiModes() {
            const savedUiMode = localStorage.getItem('vexis-ui-mode') || 'desktop';
            const savedViewMode = localStorage.getItem('vexis-view-mode') || 'dev';
            const savedModulesOpen = localStorage.getItem('vexis-modules-open');

            setUiMode(savedUiMode);
            setViewMode(savedViewMode);
            if (savedModulesOpen === null) {
                toggleModulesPanel(true);
            } else {
                toggleModulesPanel(savedModulesOpen === '1');
            }
        })();

        function updateMobilePreviewScale() {
            if (!document.body.classList.contains('vx-force-mobile')) {
                document.documentElement.style.removeProperty('--vx-mobile-preview-scale');
                return;
            }

            const baseWidth = 390;
            const availableWidth = window.innerWidth - 34;
            const scale = Math.min(1, availableWidth / baseWidth);
            document.documentElement.style.setProperty('--vx-mobile-preview-scale', String(scale));
        }
        window.addEventListener('resize', updateMobilePreviewScale);

        /* === Loading Screen === */
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('vxLoader').classList.add('hidden');
                setTimeout(() => document.getElementById('vxLoader').remove(), 400);
            }, 600);
        });

        /* === Dropdown click outside === */
        document.addEventListener('click', function(e) {
            const navToggle = e.target.closest('.vx-module-nav .vx-nav-item > .vx-nav-link');
            if (navToggle) {
                e.preventDefault();
                const parentItem = navToggle.closest('.vx-nav-item');
                document.querySelectorAll('.vx-module-nav .vx-nav-item.open').forEach(item => {
                    if (item !== parentItem) item.classList.remove('open');
                });
                parentItem.classList.toggle('open');
                return;
            }

            if (!e.target.closest('#vxModulesPanel') && !e.target.closest('#vxModulesToggle')) {
                document.querySelectorAll('.vx-module-nav .vx-nav-item.open').forEach(i => i.classList.remove('open'));
            }
            if (!e.target.closest('#vxMobileTools')) {
                closeMobileToolsMenu();
            }

            if (!e.target.closest('.vx-nav-item')) {
                document.querySelectorAll('.vx-nav-item.open').forEach(i => i.classList.remove('open'));
            }
            if (!e.target.closest('#notifPanel') && !e.target.closest('[onclick*="toggleNotifications"]')) {
                document.getElementById('notifPanel')?.classList.remove('open');
            }
        });

        /* === Alerts auto-dismiss === */
        document.querySelectorAll('.vx-alert').forEach(alert => {
            setTimeout(() => { alert.style.opacity = '0'; alert.style.transform = 'translateY(-8px)'; alert.style.transition = 'all 0.3s'; setTimeout(() => alert.remove(), 300); }, 5000);
        });

        /* === Global Search === */
        function openSearch() { document.getElementById('vxSearch').classList.add('open'); document.getElementById('vxSearchInput').focus(); }
        function closeSearch() { document.getElementById('vxSearch').classList.remove('open'); document.getElementById('vxSearchInput').value = ''; }
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); openSearch(); }
            if (e.key === 'Escape') closeSearch();
        });
        document.getElementById('vxSearchInput')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                const q = encodeURIComponent(this.value.trim());
                window.location.href = '{{ route("dashboard") }}?search=' + q;
            }
        });

        /* === Notifications System === */
        let notifications = JSON.parse(localStorage.getItem('vexis-notifs') || '[]');

        function toggleNotifications() {
            document.getElementById('notifPanel').classList.toggle('open');
        }

        function renderNotifications() {
            const list = document.getElementById('notifList');
            const badge = document.getElementById('notifBadge');
            if (!list) return;
            const unread = notifications.filter(n => !n.read).length;
            if (badge) badge.style.display = unread > 0 ? '' : 'none';
            if (notifications.length === 0) {
                list.innerHTML = '<div class="vx-notif-empty"><i class="bi bi-bell-slash" style="font-size:24px;display:block;margin-bottom:8px;"></i>Sin notificaciones</div>';
                return;
            }
            list.innerHTML = notifications.slice(0, 20).map((n, i) => {
                const iconMap = { create: 'success', update: 'warning', delete: 'danger' };
                const iconClass = iconMap[n.type] || 'info';
                const icons = { create: 'bi-plus-circle', update: 'bi-pencil', delete: 'bi-trash' };
                return `<div class="vx-notif-item ${n.read ? '' : 'unread'}">
                    <div class="vx-notif-icon ${iconClass}"><i class="bi ${icons[n.type] || 'bi-info-circle'}"></i></div>
                    <div class="vx-notif-body"><div class="vx-notif-text">${n.message}</div><div class="vx-notif-time">${n.time}</div></div>
                    <button class="vx-notif-close" onclick="removeNotif(${i})" title="Eliminar"><i class="bi bi-x"></i></button>
                </div>`;
            }).join('');
        }

        function addNotification(type, message) {
            const now = new Date();
            const time = now.toLocaleString('es-ES', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' });
            notifications.unshift({ type, message, time, read: false });
            if (notifications.length > 50) notifications = notifications.slice(0, 50);
            localStorage.setItem('vexis-notifs', JSON.stringify(notifications));
            renderNotifications();
        }

        function markAllRead() {
            notifications.forEach(n => n.read = true);
            localStorage.setItem('vexis-notifs', JSON.stringify(notifications));
            renderNotifications();
        }

        function clearAllNotifs() {
            notifications = [];
            localStorage.setItem('vexis-notifs', JSON.stringify(notifications));
            renderNotifications();
        }

        function removeNotif(index) {
            notifications.splice(index, 1);
            localStorage.setItem('vexis-notifs', JSON.stringify(notifications));
            renderNotifications();
        }

        renderNotifications();

        @if(session('success'))
            @php
                $msg = session('success');
                $type = 'create';
                if (str_contains(strtolower($msg), 'actualiz') || str_contains(strtolower($msg), 'edit')) $type = 'update';
                elseif (str_contains(strtolower($msg), 'elimin')) $type = 'delete';
            @endphp
            addNotification('{{ $type }}', @json($msg));
        @endif
    </script>
    <script>
    // Actions dropdown toggle
    function inferActionType(item, iconClass, label) {
        const labelLower = (label || '').toLowerCase();
        const titleLower = (item.getAttribute('title') || '').toLowerCase();
        const source = `${labelLower} ${titleLower} ${iconClass}`.toLowerCase();

        if (source.includes('trash') || source.includes('eliminar') || source.includes('borrar') || source.includes('delete')) return 'delete';
        if (source.includes('check') || source.includes('aprob')) return 'approve';
        if (source.includes('x-lg') || source.includes('x-circle') || source.includes('rechaz')) return 'reject';
        if (source.includes('pencil') || source.includes('editar') || source.includes('edit')) return 'edit';
        if (source.includes('eye') || source.includes('ver') || source.includes('show')) return 'view';

        return '';
    }

    function inferActionLabel(action, fallbackTitle) {
        if (action === 'view') return 'Ver';
        if (action === 'edit') return 'Editar';
        if (action === 'delete') return 'Eliminar';
        if (action === 'approve') return 'Aprobar';
        if (action === 'reject') return 'Rechazar';
        return fallbackTitle || 'Acción';
    }

    function normalizeActionsMenus() {
        document.querySelectorAll('.vx-actions-menu').forEach(menu => {
            menu.querySelectorAll('a, button').forEach(item => {
                const icon = item.querySelector('i');
                const iconClass = icon ? icon.className : '';
                const currentLabel = item.textContent.trim();
                const action = inferActionType(item, iconClass, currentLabel);

                // Remove button styles reused from table context.
                item.classList.remove('vx-btn', 'vx-btn-info', 'vx-btn-warning', 'vx-btn-danger', 'vx-btn-success', 'vx-btn-secondary', 'vx-btn-sm');
                if (action) item.classList.add(`act-${action}`);
                if (action === 'delete') item.classList.add('act-danger');

                // Ensure all entries show readable text ("Ver", "Editar", etc.)
                if (!currentLabel) {
                    const label = inferActionLabel(action, item.getAttribute('title'));
                    item.append(document.createTextNode(` ${label}`));
                }

                // Keep a useful tooltip if missing.
                if (!item.getAttribute('title')) {
                    const finalLabel = item.textContent.trim();
                    if (finalLabel) item.setAttribute('title', finalLabel);
                }
            });
        });
    }

    function positionActionsMenu(actionsEl) {
        const toggle = actionsEl.querySelector('.vx-actions-toggle');
        const menu = actionsEl.querySelector('.vx-actions-menu');
        if (!toggle || !menu) return;

        const gap = 6;
        const toggleRect = toggle.getBoundingClientRect();
        let left = toggleRect.right + gap;
        let top = toggleRect.bottom + 4;

        // Initial paint for accurate size calculation.
        menu.style.left = `${left}px`;
        menu.style.top = `${top}px`;

        const menuRect = menu.getBoundingClientRect();
        if (left + menuRect.width > window.innerWidth - 8) {
            // Stay on the right side — clamp to the right viewport edge instead of flipping left.
            left = Math.max(toggleRect.right - menuRect.width, window.innerWidth - menuRect.width - 8);
        }
        if (top + menuRect.height > window.innerHeight - 8) {
            top = Math.max(8, window.innerHeight - menuRect.height - 8);
        }

        menu.style.left = `${left}px`;
        menu.style.top = `${top}px`;
    }

    function repositionOpenActionsMenus() {
        document.querySelectorAll('.vx-actions.open').forEach(positionActionsMenu);
    }

    normalizeActionsMenus();

    document.addEventListener('click', function(e) {
        const toggle = e.target.closest('.vx-actions-toggle');
        if (toggle) {
            e.stopPropagation();
            const parent = toggle.closest('.vx-actions');
            document.querySelectorAll('.vx-actions.open').forEach(el => {
                if (el !== parent) el.classList.remove('open');
            });
            parent.classList.toggle('open');
            if (parent.classList.contains('open')) {
                positionActionsMenu(parent);
            }
        } else {
            document.querySelectorAll('.vx-actions.open').forEach(el => el.classList.remove('open'));
        }
    });

    window.addEventListener('resize', repositionOpenActionsMenus);
    window.addEventListener('scroll', repositionOpenActionsMenus, true);
    </script>
    @stack('scripts')
</body>
</html>
