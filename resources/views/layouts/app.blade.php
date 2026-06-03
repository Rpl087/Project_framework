<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Sistem Manajemen &amp; Peminjaman Infrastruktur Laboratorium">

        {{-- Early theme init — prevents flash of wrong theme --}}
        <script>
            (function() {
                var t = localStorage.getItem('theme') ||
                    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', t);
            })();
        </script>

        <title>{{ config('app.name', 'LabManager') }} - @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { font-family: 'Inter', sans-serif; }

            /* ═══════════════════════════════════════════════════
               CSS Custom Properties — Light Mode (default)
            ═══════════════════════════════════════════════════ */
            :root {
                --bg:          #f1f5f9;
                --surface:     rgba(255,255,255,0.85);
                --surface-g:   rgba(255,255,255,0.90);
                --border:      rgba(226,232,240,0.60);
                --border-s:    #e2e8f0;
                --border-l:    #f1f5f9;
                --txt-1:       #0f172a;
                --txt-2:       #64748b;
                --txt-3:       #94a3b8;
                --txt-4:       #334155;
                --row-hover:   #f8fafc;
                --th-bg:       #f8fafc;
                --topbar:      rgba(255,255,255,0.85);
                --input-bg:    #ffffff;
                --scrollbar:   #cbd5e1;
                --ham-bar:     #64748b;
                --ham-hover:   #1e293b;
                --shadow:      rgba(0,0,0,0.08);
                --shadow-lg:   rgba(0,0,0,0.28);
                --alert-ok-bg: #d1fae5;
                --alert-ok-c:  #065f46;
                --alert-ok-b:  #a7f3d0;
                --alert-er-bg: #fee2e2;
                --alert-er-c:  #991b1b;
                --alert-er-b:  #fca5a5;
            }

            /* ═══════════════════════════════════════════════════
               CSS Custom Properties — Dark Mode
            ═══════════════════════════════════════════════════ */
            [data-theme="dark"] {
                --bg:          #0f172a;
                --surface:     rgba(30,41,59,0.95);
                --surface-g:   rgba(30,41,59,0.98);
                --border:      rgba(51,65,85,0.70);
                --border-s:    #334155;
                --border-l:    #1e293b;
                --txt-1:       #f1f5f9;
                --txt-2:       #94a3b8;
                --txt-3:       #64748b;
                --txt-4:       #cbd5e1;
                --row-hover:   #1e293b;
                --th-bg:       #1e293b;
                --topbar:      rgba(15,23,42,0.95);
                --input-bg:    #1e293b;
                --scrollbar:   #334155;
                --ham-bar:     #94a3b8;
                --ham-hover:   #f1f5f9;
                --shadow:      rgba(0,0,0,0.40);
                --shadow-lg:   rgba(0,0,0,0.55);
                --alert-ok-bg: rgba(5,150,105,0.15);
                --alert-ok-c:  #34d399;
                --alert-ok-b:  rgba(52,211,153,0.25);
                --alert-er-bg: rgba(239,68,68,0.15);
                --alert-er-c:  #f87171;
                --alert-er-b:  rgba(248,113,113,0.25);
            }

            /* ═══════════════════════════════════════════════════
               Theme-switch transition helper
               (only active for 500ms during toggle, avoids
               killing normal hover/animation transitions)
            ═══════════════════════════════════════════════════ */
            .theme-transition,
            .theme-transition * {
                transition: background-color 0.35s ease,
                            color 0.35s ease,
                            border-color 0.35s ease,
                            box-shadow 0.35s ease !important;
            }

            /* ═══════════════════════════════════════════════════
               Sidebar
            ═══════════════════════════════════════════════════ */
            .sidebar {
                background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
                width: 260px;
                min-height: 100vh;
                position: fixed;
                left: 0;
                top: 0;
                z-index: 40;
                transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                            transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                overflow: hidden;
            }
            .sidebar-hidden { transform: translateX(-100%); }
            .sidebar-logo {
                padding: 1.5rem;
                border-bottom: 1px solid rgba(255,255,255,0.08);
            }
            .sidebar-nav { padding: 1rem 0.75rem; }
            .sidebar-link {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                color: #94a3b8;
                text-decoration: none;
                font-size: 0.875rem;
                font-weight: 500;
                transition: all 0.2s ease;
                margin-bottom: 0.25rem;
            }
            .sidebar-link:hover {
                background: rgba(99, 102, 241, 0.15);
                color: #e2e8f0;
            }
            .sidebar-link.active {
                background: linear-gradient(135deg, #4f46e5, #6366f1);
                color: #fff;
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
            }
            .sidebar-section {
                font-size: 0.7rem;
                font-weight: 700;
                color: #475569;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                padding: 1.25rem 1rem 0.5rem;
            }

            /* ═══════════════════════════════════════════════════
               Main Layout
            ═══════════════════════════════════════════════════ */
            .main-content {
                margin-left: 260px;
                min-height: 100vh;
                background: var(--bg);
                transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                            background-color 0.35s ease;
            }
            .top-bar {
                background: var(--topbar);
                backdrop-filter: blur(12px);
                border-bottom: 1px solid var(--border-s);
                padding: 0.75rem 1.5rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: sticky;
                top: 0;
                z-index: 30;
                transition: background-color 0.35s ease, border-color 0.35s ease;
            }

            /* ═══════════════════════════════════════════════════
               Cards
            ═══════════════════════════════════════════════════ */
            .stat-card {
                background: var(--surface);
                backdrop-filter: blur(12px);
                border: 1px solid var(--border);
                border-radius: 1rem;
                padding: 1.5rem;
                transition: all 0.3s ease;
            }
            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 28px var(--shadow);
            }
            .glass-card {
                background: var(--surface-g);
                backdrop-filter: blur(16px);
                border: 1px solid var(--border);
                border-radius: 1rem;
                overflow: hidden;
                transition: background-color 0.35s ease, border-color 0.35s ease;
            }

            /* ═══════════════════════════════════════════════════
               Badge
            ═══════════════════════════════════════════════════ */
            .badge {
                display: inline-flex;
                align-items: center;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
            }
            .badge-amber   { background: #fef3c7; color: #92400e; }
            .badge-blue    { background: #dbeafe; color: #1e40af; }
            .badge-indigo  { background: #e0e7ff; color: #3730a3; }
            .badge-cyan    { background: #cffafe; color: #155e75; }
            .badge-emerald { background: #d1fae5; color: #065f46; }
            .badge-green   { background: #dcfce7; color: #166534; }
            .badge-red     { background: #fee2e2; color: #991b1b; }
            .badge-orange  { background: #ffedd5; color: #9a3412; }
            .badge-rose    { background: #ffe4e6; color: #9f1239; }
            .badge-gray    { background: #f1f5f9; color: #475569; }

            /* ═══════════════════════════════════════════════════
               Buttons
            ═══════════════════════════════════════════════════ */
            .btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1.25rem;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                font-weight: 600;
                transition: all 0.2s ease;
                cursor: pointer;
                border: none;
                text-decoration: none;
            }
            .btn-primary { background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; }
            .btn-primary:hover {
                background: linear-gradient(135deg, #4338ca, #4f46e5);
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
                transform: translateY(-1px);
            }
            .btn-success { background: linear-gradient(135deg, #059669, #10b981); color: #fff; }
            .btn-success:hover {
                background: linear-gradient(135deg, #047857, #059669);
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
            }
            .btn-danger { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; }
            .btn-danger:hover {
                background: linear-gradient(135deg, #b91c1c, #dc2626);
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.35);
            }
            .btn-warning { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; }
            .btn-outline {
                background: transparent;
                border: 1px solid var(--border-s);
                color: var(--txt-2);
            }
            .btn-outline:hover {
                background: var(--row-hover);
                border-color: var(--txt-3);
            }
            .btn-sm { padding: 0.375rem 0.875rem; font-size: 0.8rem; }

            /* ═══════════════════════════════════════════════════
               Theme Toggle Button
            ═══════════════════════════════════════════════════ */
            .theme-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 38px;
                height: 38px;
                border-radius: 0.5rem;
                border: 1px solid var(--border-s);
                background: transparent;
                color: var(--txt-2);
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
                overflow: hidden;
                flex-shrink: 0;
            }
            .theme-toggle:hover {
                background: var(--row-hover);
                color: var(--txt-1);
                transform: scale(1.05);
            }
            .theme-toggle svg {
                position: absolute;
                transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1),
                            opacity 0.25s ease;
            }
            /* Light mode: sun visible, moon hidden */
            .theme-toggle .icon-sun  { transform: scale(1)  rotate(0deg);   opacity: 1; }
            .theme-toggle .icon-moon { transform: scale(0)  rotate(-90deg); opacity: 0; }
            /* Dark mode: moon visible, sun hidden */
            [data-theme="dark"] .theme-toggle .icon-sun  { transform: scale(0)  rotate(90deg);  opacity: 0; }
            [data-theme="dark"] .theme-toggle .icon-moon { transform: scale(1)  rotate(0deg);   opacity: 1; }

            /* ═══════════════════════════════════════════════════
               Form
            ═══════════════════════════════════════════════════ */
            .form-input {
                width: 100%;
                padding: 0.625rem 0.875rem;
                border: 1px solid var(--border-s);
                border-radius: 0.5rem;
                font-size: 0.875rem;
                transition: all 0.2s ease;
                background: var(--input-bg);
                color: var(--txt-1);
            }
            .form-input:focus {
                outline: none;
                border-color: #818cf8;
                box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
            }
            .form-label {
                display: block;
                font-size: 0.8rem;
                font-weight: 600;
                color: var(--txt-4);
                margin-bottom: 0.375rem;
            }

            /* ═══════════════════════════════════════════════════
               Table
            ═══════════════════════════════════════════════════ */
            .data-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }
            .data-table thead th {
                background: var(--th-bg);
                padding: 0.75rem 1rem;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: var(--txt-2);
                text-align: left;
                border-bottom: 1px solid var(--border-s);
                transition: background-color 0.35s ease, color 0.35s ease, border-color 0.35s ease;
            }
            .data-table tbody td {
                padding: 0.875rem 1rem;
                font-size: 0.875rem;
                color: var(--txt-4);
                border-bottom: 1px solid var(--border-l);
                transition: background-color 0.35s ease, color 0.35s ease, border-color 0.35s ease;
            }
            .data-table tbody tr { transition: background 0.15s ease; }
            .data-table tbody tr:hover { background: var(--row-hover); }

            /* ═══════════════════════════════════════════════════
               Page Transitions
            ═══════════════════════════════════════════════════ */
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(16px); }
                to   { opacity: 1; transform: translateY(0);    }
            }
            @keyframes pageExit {
                from { opacity: 1; transform: translateY(0);     }
                to   { opacity: 0; transform: translateY(-12px); }
            }
            .animate-in { animation: fadeInUp 0.38s ease-out forwards; }
            .animate-delay-1 { animation-delay: 0.07s; opacity: 0; }
            .animate-delay-2 { animation-delay: 0.14s; opacity: 0; }
            .animate-delay-3 { animation-delay: 0.21s; opacity: 0; }
            .animate-delay-4 { animation-delay: 0.28s; opacity: 0; }
            body.page-exiting main { animation: pageExit 0.22s ease-in forwards; }

            /* ═══════════════════════════════════════════════════
               Overlay
            ═══════════════════════════════════════════════════ */
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.4);
                z-index: 35;
            }

            /* ═══════════════════════════════════════════════════
               Hamburger Button
            ═══════════════════════════════════════════════════ */
            .hamburger-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                background: none;
                border: none;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 0.5rem;
                transition: all 0.25s ease;
                width: 40px;
                height: 40px;
            }
            .hamburger-btn:hover  { background: var(--row-hover); }
            .hamburger-btn:active { transform: scale(0.92); }
            .hamburger-bars {
                width: 20px;
                height: 14px;
                position: relative;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .hamburger-bars span {
                display: block;
                width: 100%;
                height: 2px;
                background: var(--ham-bar);
                border-radius: 2px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                transform-origin: center;
            }
            .hamburger-btn:hover .hamburger-bars span { background: var(--ham-hover); }

            /* ═══════════════════════════════════════════════════
               Table Responsive
            ═══════════════════════════════════════════════════ */
            .table-responsive {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* ═══════════════════════════════════════════════════
               Alert
            ═══════════════════════════════════════════════════ */
            .alert {
                padding: 0.875rem 1.25rem;
                border-radius: 0.75rem;
                margin-bottom: 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                transition: background-color 0.35s ease, color 0.35s ease, border-color 0.35s ease;
            }
            .alert-success { background: var(--alert-ok-bg); color: var(--alert-ok-c); border: 1px solid var(--alert-ok-b); }
            .alert-error   { background: var(--alert-er-bg); color: var(--alert-er-c); border: 1px solid var(--alert-er-b); }

            /* ═══════════════════════════════════════════════════
               Scrollbar
            ═══════════════════════════════════════════════════ */
            ::-webkit-scrollbar       { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 3px; }
            ::-webkit-scrollbar-thumb:hover { background: var(--txt-3); }

            /* ═══════════════════════════════════════════════════
               Dark Mode — Global body/main bg
            ═══════════════════════════════════════════════════ */
            [data-theme="dark"] body { background: var(--bg); }
            [data-theme="dark"] main { background: var(--bg); }

            /* ═══════════════════════════════════════════════════
               Dark Mode — Inline style overrides for content pages
               (Scoped to .main-content so sidebar is unaffected)
            ═══════════════════════════════════════════════════ */
            [data-theme="dark"] .main-content [style*="color:#0f172a"] { color: var(--txt-1) !important; }
            [data-theme="dark"] .main-content [style*="color:#1e293b"] { color: var(--txt-1) !important; }
            [data-theme="dark"] .main-content [style*="color:#334155"] { color: var(--txt-4) !important; }
            [data-theme="dark"] .main-content [style*="color:#374151"] { color: var(--txt-4) !important; }
            [data-theme="dark"] .main-content [style*="color:#64748b"] { color: var(--txt-2) !important; }
            [data-theme="dark"] .main-content [style*="color:#475569"] { color: var(--txt-2) !important; }
            [data-theme="dark"] .main-content [style*="color:#94a3b8"] { color: var(--txt-2) !important; }
            [data-theme="dark"] .main-content [style*="background:linear-gradient(135deg,#f8fafc,#f1f5f9)"] {
                background: linear-gradient(135deg, #1e293b, #263349) !important;
            }
            [data-theme="dark"] .main-content [style*="border-top:1px solid #f1f5f9"] {
                border-top-color: var(--border-s) !important;
            }
            [data-theme="dark"] .main-content [style*="border-top:1px solid #e2e8f0"] {
                border-top-color: var(--border-s) !important;
            }
            [data-theme="dark"] .main-content [style*="border-bottom:1px solid #e2e8f0"] {
                border-bottom-color: var(--border-s) !important;
            }
            [data-theme="dark"] .main-content [style*="background:#f8fafc"] {
                background: var(--th-bg) !important;
            }
            /* ANEH-4 FIX: Tambahan dark mode override yang sebelumnya hilang */
            [data-theme="dark"] .main-content [style*="background:#fee2e2"] {
                background: rgba(239,68,68,0.12) !important;
            }
            [data-theme="dark"] .main-content [style*="background:#d1fae5"] {
                background: rgba(16,185,129,0.12) !important;
            }
            [data-theme="dark"] .main-content [style*="background:#eff6ff"] {
                background: rgba(59,130,246,0.12) !important;
            }
            [data-theme="dark"] .main-content [style*="background:#fef3c7"] {
                background: rgba(245,158,11,0.12) !important;
            }
            [data-theme="dark"] .main-content [style*="background:#fff7ed"] {
                background: rgba(249,115,22,0.12) !important;
            }
            [data-theme="dark"] .main-content [style*="background:#f0fdf4"] {
                background: rgba(34,197,94,0.10) !important;
            }
            [data-theme="dark"] .main-content [style*="background:#fdf2f8"] {
                background: rgba(168,85,247,0.10) !important;
            }
            [data-theme="dark"] .main-content [style*="color:#065f46"] { color: #34d399 !important; }
            [data-theme="dark"] .main-content [style*="color:#991b1b"] { color: #f87171 !important; }
            [data-theme="dark"] .main-content [style*="color:#1e40af"] { color: #93c5fd !important; }
            [data-theme="dark"] .main-content [style*="color:#92400e"] { color: #fbbf24 !important; }
            [data-theme="dark"] .main-content [style*="color:#9a3412"] { color: #fb923c !important; }
            [data-theme="dark"] .main-content [style*="border:1px solid #a7f3d0"] { border-color: rgba(52,211,153,0.3) !important; }
            [data-theme="dark"] .main-content [style*="border:1px solid #fca5a5"] { border-color: rgba(248,113,113,0.3) !important; }
            [data-theme="dark"] .main-content [style*="border:1px solid #bfdbfe"] { border-color: rgba(147,197,253,0.3) !important; }
            [data-theme="dark"] .main-content [style*="border:1px solid #fed7aa"] { border-color: rgba(251,146,60,0.3) !important; }

            /* ═══════════════════════════════════════════════════
               Responsive — Tablet (≤1024px)
            ═══════════════════════════════════════════════════ */
            @media (max-width: 1024px) {
                .sidebar { transform: translateX(-100%); }
                .sidebar.sidebar-open { transform: translateX(0); }
                .main-content { margin-left: 0 !important; }
                .sidebar-overlay.active { display: block; }
            }

            /* ═══════════════════════════════════════════════════
               Responsive — Mobile (≤768px)
            ═══════════════════════════════════════════════════ */
            @media (max-width: 768px) {
                .top-bar { padding: 0.625rem 1rem; }
                .top-bar h2 { font-size: 0.95rem !important; }
                main { padding: 1rem !important; }
                .stat-card { padding: 1.15rem; }
                .data-table thead th,
                .data-table tbody td { padding: 0.625rem 0.75rem; font-size: 0.8rem; white-space: nowrap; }
                .btn { padding: 0.45rem 1rem; font-size: 0.8rem; }
            }

            /* ═══════════════════════════════════════════════════
               Responsive — Small Mobile (≤480px)
            ═══════════════════════════════════════════════════ */
            @media (max-width: 480px) {
                .top-bar { padding: 0.5rem 0.75rem; }
                .top-bar h2 { font-size: 0.875rem !important; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
                main { padding: 0.75rem !important; }
                .stat-card { padding: 1rem; }
                .btn { padding: 0.4rem 0.75rem; font-size: 0.75rem; }
                .btn-sm { padding: 0.3rem 0.625rem; font-size: 0.7rem; }
                .badge { font-size: 0.65rem; padding: 0.2rem 0.5rem; }
                .top-bar-date { display: none; }
            }

            /* ═══════════════════════════════════════════════════
               Responsive — Medium (≤640px)
            ═══════════════════════════════════════════════════ */
            @media (max-width: 640px) {
                main { padding: 1rem !important; }
                .top-bar-date { display: none; }
            }

            /* ═══════════════════════════════════════════════════
               Deadline Alert Bar — Peringatan batas waktu pinjam
            ═══════════════════════════════════════════════════ */
            #deadline-alert-bar {
                display: none;
                position: sticky;
                top: 0;
                z-index: 900;
                margin: -1.5rem -1.5rem 1.5rem -1.5rem;
            }
            .deadline-bar-item {
                display: flex;
                align-items: center;
                gap: 0.6rem;
                padding: 0.6rem 1.25rem;
                font-size: 0.82rem;
                font-weight: 600;
                border-left: 4px solid transparent;
                animation: slideBarIn 0.4s cubic-bezier(0.22,1,0.36,1);
            }
            .deadline-bar-warning {
                background: #fffbeb;
                color: #92400e;
                border-left-color: #f59e0b;
            }
            .deadline-bar-danger {
                background: linear-gradient(90deg, #fef2f2, #fee2e2);
                color: #991b1b;
                border-left-color: #ef4444;
                animation: slideBarIn 0.4s cubic-bezier(0.22,1,0.36,1),
                           pulseBg 2s ease-in-out infinite;
            }
            .deadline-bar-item .dbar-icon {
                font-size: 1rem;
                flex-shrink: 0;
            }
            .deadline-bar-item .dbar-dismiss {
                margin-left: auto;
                background: none;
                border: none;
                cursor: pointer;
                opacity: 0.5;
                font-size: 1.1rem;
                line-height: 1;
                padding: 0 0.25rem;
                color: inherit;
            }
            .deadline-bar-item .dbar-dismiss:hover { opacity: 1; }
            @keyframes slideBarIn {
                from { opacity: 0; transform: translateY(-8px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes pulseBg {
                0%, 100% { background: linear-gradient(90deg,#fef2f2,#fee2e2); }
                50%       { background: linear-gradient(90deg,#fee2e2,#fecaca); }
            }
        </style>
    </head>
    <body class="antialiased">
        <!-- Sidebar Overlay (mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <div class="flex items-center gap-3">
                    <div style="width:40px;height:40px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 style="color:#f1f5f9;font-size:1.05rem;font-weight:700;line-height:1.2;">LabManager</h1>
                        <p style="color:#64748b;font-size:0.7rem;font-weight:500;">Manajemen Lab</p>
                    </div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-section">Menu Utama</div>

                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                @if(auth()->user()->isMahasiswa())
                    <div class="sidebar-section">Peminjaman</div>
                    <a href="{{ route('catalog') }}" class="sidebar-link {{ request()->routeIs('catalog') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Katalog Alat
                    </a>
                    <a href="{{ route('borrowings.create') }}" class="sidebar-link {{ request()->routeIs('borrowings.create') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Ajukan Peminjaman
                    </a>
                    <a href="{{ route('borrowings.index') }}" class="sidebar-link {{ request()->routeIs('borrowings.index') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Riwayat Peminjaman
                    </a>
                @endif

                @if(auth()->user()->isLaboran())
                    <div class="sidebar-section">Manajemen Alat</div>
                    <a href="{{ route('equipments.index') }}" class="sidebar-link {{ request()->routeIs('equipments.*') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Kelola Alat
                    </a>

                    <div class="sidebar-section">Peminjaman</div>
                    <a href="{{ route('borrowings.index') }}" class="sidebar-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Daftar Peminjaman
                    </a>

                    <div class="sidebar-section">Pengguna</div>
                    <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Kelola User
                    </a>
                @endif

                @if(auth()->user()->isKepalaLab())
                    <div class="sidebar-section">Persetujuan</div>
                    <a href="{{ route('borrowings.index') }}?status=approved_by_laboran" class="sidebar-link {{ request()->routeIs('borrowings.*') && request('status') === 'approved_by_laboran' ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Butuh Persetujuan
                    </a>
                    <a href="{{ route('borrowings.index') }}" class="sidebar-link {{ request()->routeIs('borrowings.index') && !request('status') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Semua Peminjaman
                    </a>

                    <div class="sidebar-section">Pengguna</div>
                    <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Kelola User
                    </a>
                @endif
            </nav>

            <!-- User Info at bottom -->
            <div style="position:absolute;bottom:0;left:0;right:0;border-top:1px solid rgba(255,255,255,0.08);">
                <a href="{{ route('profile.edit') }}" style="display:flex;align-items:center;gap:0.75rem;padding:1rem;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='rgba(99,102,241,0.15)'" onmouseout="this.style.background='transparent'">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;flex-shrink:0;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="color:#e2e8f0;font-size:0.8rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</p>
                        <p style="color:#64748b;font-size:0.7rem;text-transform:capitalize;">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                    </div>
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#475569" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Top Bar -->
            <div class="top-bar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu">
                        <div class="hamburger-bars">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                    <h2 style="font-size:1.1rem;font-weight:700;color:var(--txt-1);">@yield('title', 'Dashboard')</h2>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <span class="top-bar-date" style="font-size:0.8rem;color:var(--txt-2);">{{ now()->format('d M Y') }}</span>

                    {{-- Notification Bell --}}
                    @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
                    <a href="{{ route('notifications.index') }}" style="position:relative;display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:0.5rem;border:1px solid var(--border-s);color:var(--txt-2);text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='var(--row-hover)';this.style.color='var(--txt-1)'" onmouseout="this.style.background='transparent';this.style.color='var(--txt-2)'" title="Notifikasi">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($unreadCount > 0)
                            <span style="position:absolute;top:4px;right:4px;width:8px;height:8px;background:#ef4444;border-radius:50%;border:2px solid var(--topbar);"></span>
                        @endif
                    </a>

                    <!-- Dark / Light Mode Toggle -->
                    <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode" title="Ganti mode gelap/terang">
                        <!-- Sun icon (shown in light mode) -->
                        <svg class="icon-sun" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1"  x2="12" y2="3"/>
                            <line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22"   x2="5.64"  y2="5.64"/>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1"  y1="12" x2="3"  y2="12"/>
                            <line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22"  y1="19.78" x2="5.64"  y2="18.36"/>
                            <line x1="18.36" y1="5.64"  x2="19.78" y2="4.22"/>
                        </svg>
                        <!-- Moon icon (shown in dark mode) -->
                        <svg class="icon-moon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                        </svg>
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Page Content -->
            <main style="padding:1.5rem;">
                @if(session('success'))
                    <div class="alert alert-success animate-in">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error animate-in">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- ─────────────────────────────────────────────────────────
                     Deadline Alert Bar
                     Ditampilkan secara dinamis oleh JavaScript di bawah.
                     Mengingatkan user tentang batas waktu pengembalian alat.
                ───────────────────────────────────────────────────────── --}}
                <div id="deadline-alert-bar"></div>

                @yield('content')
            </main>
        </div>

        <script>
            /* ────────────────────────────────────────────────────
               Sidebar toggle (hover + click pin on desktop,
               overlay tap on mobile)
            ──────────────────────────────────────────────────── */
            const sidebar     = document.getElementById('sidebar');
            const overlay     = document.getElementById('sidebarOverlay');
            const hamburger   = document.getElementById('hamburgerBtn');
            const mainContent = document.getElementById('mainContent');
            let sidebarVisible = window.innerWidth > 1024;
            let sidebarPinned  = sidebarVisible;
            let hoverTimeout   = null;

            if (!sidebarVisible) {
                sidebar.style.transform    = 'translateX(-100%)';
                mainContent.style.marginLeft = '0';
            }

            function showSidebar() {
                clearTimeout(hoverTimeout);
                sidebarVisible = true;
                if (window.innerWidth > 1024) {
                    sidebar.style.transform      = 'translateX(0)';
                    mainContent.style.marginLeft = '260px';
                } else {
                    sidebar.classList.add('sidebar-open');
                    overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }

            function hideSidebar() {
                sidebarVisible = false;
                if (window.innerWidth > 1024) {
                    sidebar.style.transform      = 'translateX(-100%)';
                    mainContent.style.marginLeft = '0';
                } else {
                    sidebar.classList.remove('sidebar-open');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }

            hamburger.addEventListener('click', () => {
                if (sidebarVisible && sidebarPinned) {
                    sidebarPinned = false;
                    hideSidebar();
                } else {
                    sidebarPinned = true;
                    showSidebar();
                }
            });

            hamburger.addEventListener('mouseenter', () => {
                if (window.innerWidth > 1024 && !sidebarVisible) {
                    sidebarPinned = false;
                    showSidebar();
                }
            });

            sidebar.addEventListener('mouseleave', () => {
                if (window.innerWidth > 1024 && !sidebarPinned) {
                    hoverTimeout = setTimeout(hideSidebar, 300);
                }
            });

            sidebar.addEventListener('mouseenter', () => clearTimeout(hoverTimeout));

            overlay.addEventListener('click', () => {
                sidebarPinned = false;
                hideSidebar();
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('sidebar-open');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                    sidebar.style.transform      = sidebarVisible ? 'translateX(0)' : 'translateX(-100%)';
                    mainContent.style.marginLeft = sidebarVisible ? '260px' : '0';
                } else {
                    sidebar.style.transform      = '';
                    mainContent.style.marginLeft = '0';
                    if (!sidebar.classList.contains('sidebar-open')) {
                        sidebarVisible = false;
                        sidebarPinned  = false;
                    }
                }
            });

            // Wrap tables for horizontal scroll on small screens
            document.querySelectorAll('.data-table').forEach(table => {
                if (!table.parentElement.classList.contains('table-responsive')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'table-responsive';
                    table.parentNode.insertBefore(wrapper, table);
                    wrapper.appendChild(table);
                }
            });

            /* ────────────────────────────────────────────────────
               Dark / Light Mode Toggle
            ──────────────────────────────────────────────────── */
            const themeToggle = document.getElementById('themeToggle');
            const htmlEl      = document.documentElement;

            function applyTheme(theme, animate) {
                if (animate) {
                    document.body.classList.add('theme-transition');
                    setTimeout(() => document.body.classList.remove('theme-transition'), 500);
                }
                htmlEl.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
            }

            // Sync with what the early-init script already set
            const savedTheme = localStorage.getItem('theme') ||
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            htmlEl.setAttribute('data-theme', savedTheme);

            themeToggle.addEventListener('click', () => {
                const current = htmlEl.getAttribute('data-theme') || 'light';
                applyTheme(current === 'dark' ? 'light' : 'dark', true);
            });

            /* ────────────────────────────────────────────────────
               Smooth Page Transitions
               Intercept link clicks → fade-out → navigate
            ──────────────────────────────────────────────────── */
            document.querySelectorAll('a[href]').forEach(link => {
                const href = link.getAttribute('href');
                if (!href ||
                    href.startsWith('#') ||
                    href.startsWith('javascript:') ||
                    href.startsWith('mailto:') ||
                    link.target === '_blank' ||
                    link.closest('form')) return;

                link.addEventListener('click', function(e) {
                    // Allow browser-modified clicks to work normally
                    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                    e.preventDefault();
                    const url = this.href;
                    document.body.classList.add('page-exiting');
                    setTimeout(() => { window.location.href = url; }, 220);
                });
            });
        </script>

        @auth
        {{-- ──────────────────────────────────────────────────────────
             Deadline Alert Engine
             Menampilkan peringatan real-time saat mendekati batas
             waktu pengembalian alat (jam operasional 08:00 - 20:00).
        ────────────────────────────────────────────────────────── --}}
        @php
            // FIX ANEH-2: Hapus filter whereDate('created_at', today) agar
            // peminjaman aktif dari HARI SEBELUMNYA juga masuk ke data alert.
            // Sebelumnya, borrowing yang tidak dikembalikan kemarin tidak mendapat alert.
            $deadlineAlertData = auth()->user()->borrowings()
                ->where('status', 'active')
                ->with('equipment:id,name')
                ->select('id', 'end_date', 'equipment_id', 'created_at')
                ->latest()
                ->get()
                ->map(fn($b) => [
                    'id'           => $b->id,
                    'end_date'     => $b->end_date,
                    'name'         => $b->equipment->name,
                    'url'          => route('borrowings.show', $b->id),
                    'created_today'=> $b->created_at->isToday(), // flag untuk JS
                ]);
        @endphp
        <script>
        (function () {
            /* ── Konfigurasi ─────────────────────────────── */
            const LAB_CLOSE      = '20:00'; // Batas tutup lab
            const WARN_MINUTES   = 60;      // Mulai peringatan X menit sebelum
            const DANGER_MINUTES = 15;      // Level bahaya X menit sebelum
            const CHECK_INTERVAL = 30000;   // Cek setiap 30 detik

            const activeBorrowings = @json($deadlineAlertData);

            /* ── Helper ──────────────────────────────────── */
            function toMins(hhmm) {
                const [h, m] = hhmm.substring(0,5).split(':').map(Number);
                return h * 60 + m;
            }
            function nowHHMM() {
                const d = new Date();
                return String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
            }

            /* ── Bangun daftar alert ────────────────────────── */
            function buildAlerts() {
                const nowMin   = toMins(nowHHMM());
                const closeMin = toMins(LAB_CLOSE);
                const alerts   = [];

                /* [1] Peringatan penutupan lab (untuk semua user) */
                const minsToClose = closeMin - nowMin;
                if (minsToClose > 0 && minsToClose <= WARN_MINUTES) {
                    const isDanger = minsToClose <= DANGER_MINUTES;
                    alerts.push({
                        id:    'lab-close',
                        level: isDanger ? 'danger' : 'warning',
                        icon:  isDanger ? '🚨' : '⏰',
                        html:  isDanger
                            ? 'Lab <strong>tutup dalam ' + minsToClose + ' menit</strong> (pukul 20:00). Segera kembalikan semua alat!'
                            : 'Lab tutup dalam <strong>' + minsToClose + ' menit</strong> (pukul 20:00). Pastikan alat kembali tepat waktu.',
                    });
                }

                /* [2] Peringatan per-peminjaman (untuk mahasiswa) */
                /* FIX ANEH-2: Tambahkan kasus borrowing dari hari SEBELUMNYA
                   yang masih aktif (tidak dikembalikan kemarin = kondisi darurat). */
                activeBorrowings.forEach(function (b) {
                    // Kasus darurat: dipinjam kemarin/sebelumnya dan belum dikembalikan
                    if (!b.created_today) {
                        alerts.push({
                            id: 'borrow-' + b.id, level: 'danger', icon: '🔴',
                            html: '<strong>' + b.name + '</strong>: Peminjaman dari <strong>hari sebelumnya</strong> belum dikembalikan! <a href="' + b.url + '" style="text-decoration:underline;font-weight:700;color:inherit;">Segera kembalikan &rarr;</a>',
                        });
                        return; // skip time-based check untuk borrowing lama
                    }

                    // Kasus normal: borrowing hari ini, cek berdasarkan waktu
                    const endMin   = toMins(b.end_date);
                    const minsLeft = endMin - nowMin;

                    if (minsLeft <= 0) {
                        alerts.push({
                            id: 'borrow-' + b.id, level: 'danger', icon: '🔴',
                            html: '<strong>' + b.name + '</strong>: Batas waktu pengembalian pukul ' + b.end_date + ' <strong>telah lewat!</strong> <a href="' + b.url + '" style="text-decoration:underline;font-weight:700;color:inherit;">Lihat detail &rarr;</a>',
                        });
                    } else if (minsLeft <= DANGER_MINUTES) {
                        alerts.push({
                            id: 'borrow-' + b.id, level: 'danger', icon: '🔴',
                            html: '<strong>' + b.name + '</strong>: Harus dikembalikan dalam <strong>' + minsLeft + ' menit</strong> (pukul ' + b.end_date + ')! <a href="' + b.url + '" style="text-decoration:underline;color:inherit;">Detail &rarr;</a>',
                        });
                    } else if (minsLeft <= WARN_MINUTES) {
                        alerts.push({
                            id: 'borrow-' + b.id, level: 'warning', icon: '⚠️',
                            html: '<strong>' + b.name + '</strong>: Batas pengembalian dalam <strong>' + minsLeft + ' menit</strong> (pukul ' + b.end_date + '). <a href="' + b.url + '" style="text-decoration:underline;color:inherit;">Lihat &rarr;</a>',
                        });
                    }
                });

                return alerts;
            }

            /* ── Render ke DOM ─────────────────────────────── */
            var dismissed = new Set();

            function render() {
                var container = document.getElementById('deadline-alert-bar');
                if (!container) return;

                var alerts  = buildAlerts();
                var visible = alerts.filter(function (a) { return !dismissed.has(a.id); });

                if (visible.length === 0) {
                    container.style.display = 'none';
                    container.innerHTML = '';
                    return;
                }

                container.style.display = 'block';

                var existingIds = [].slice.call(container.querySelectorAll('[data-alert-id]'))
                    .map(function (el) { return el.dataset.alertId; });
                var newIds = visible.map(function (a) { return a.id; });

                // Hapus item yang tidak relevan lagi
                existingIds.filter(function (id) { return !newIds.includes(id); }).forEach(function (id) {
                    var el = container.querySelector('[data-alert-id="' + id + '"]');
                    if (el) el.remove();
                });

                // Tambahkan item baru
                visible.forEach(function (a) {
                    if (existingIds.includes(a.id)) return;
                    var div = document.createElement('div');
                    div.className        = 'deadline-bar-item deadline-bar-' + a.level;
                    div.dataset.alertId  = a.id;
                    div.innerHTML        = '<span class="dbar-icon">' + a.icon + '</span>' +
                                           '<span>' + a.html + '</span>' +
                                           '<button class="dbar-dismiss" aria-label="Tutup peringatan" title="Tutup">&times;</button>';
                    div.querySelector('.dbar-dismiss').addEventListener('click', function () {
                        dismissed.add(a.id);
                        div.remove();
                        if (!container.querySelector('.deadline-bar-item')) container.style.display = 'none';
                    });
                    container.appendChild(div);
                });
            }

            /* ── Jalankan ──────────────────────────────────── */
            render();
            setInterval(function () {
                // Alert level danger selalu muncul kembali meski sudah di-dismiss
                buildAlerts().forEach(function (a) {
                    if (a.level === 'danger') dismissed.delete(a.id);
                });
                render();
            }, CHECK_INTERVAL);
        })();
        </script>
        @endauth

        @stack('scripts')
    </body>
</html>
