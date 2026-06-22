<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Sistem Manajemen &amp; Peminjaman Infrastruktur Laboratorium">

        {{-- Early theme init â€” prevents flash of wrong theme --}}
        <script>
            (function() {
                var t = localStorage.getItem('theme') ||
                    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', t);
            })();
        </script>

        <title>{{ config('app.name', 'LabManager') }} - @yield('title', 'Dashboard')</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { font-family: 'Inter', sans-serif; }

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Smooth Global Transitions
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
            html { scroll-behavior: smooth; }
            body { -webkit-font-smoothing: antialiased; }

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               CSS Custom Properties â€” Light Mode (default)
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               CSS Custom Properties â€” Dark Mode
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Theme-switch transition helper
               (only active for 500ms during toggle, avoids
               killing normal hover/animation transitions)
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
            .theme-transition,
            .theme-transition * {
                transition: background-color 0.35s ease,
                            color 0.35s ease,
                            border-color 0.35s ease,
                            box-shadow 0.35s ease !important;
            }

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Sidebar
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
            .sidebar {
                background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
                width: 260px;
                min-height: 100vh;
                position: fixed;
                left: 0;
                top: 0;
                z-index: 40;
                transition: transform 0.38s cubic-bezier(0.22, 1, 0.36, 1),
                            box-shadow 0.38s cubic-bezier(0.22, 1, 0.36, 1);
                overflow: hidden;
                will-change: transform;
            }
            .sidebar.sidebar-open {
                box-shadow: 4px 0 32px rgba(0, 0, 0, 0.35);
            }
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Main Layout
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
            .main-content {
                margin-left: 260px;
                min-height: 100vh;
                background: var(--bg);
                transition: margin-left 0.38s cubic-bezier(0.22, 1, 0.36, 1),
                            background-color 0.35s ease;
                will-change: margin-left;
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Cards
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Badge
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Buttons
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Theme Toggle Button
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Form
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Table
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Page Transitions
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Overlay
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.45);
                z-index: 35;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.38s cubic-bezier(0.22, 1, 0.36, 1),
                            visibility 0.38s cubic-bezier(0.22, 1, 0.36, 1);
                backdrop-filter: blur(2px);
            }
            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Hamburger Button
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Table Responsive
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
            .table-responsive {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Alert
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Scrollbar
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
            ::-webkit-scrollbar       { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: var(--scrollbar); border-radius: 3px; }
            ::-webkit-scrollbar-thumb:hover { background: var(--txt-3); }

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Dark Mode â€” Global body/main bg
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
            [data-theme="dark"] body { background: var(--bg); }
            [data-theme="dark"] main { background: var(--bg); }

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Dark Mode â€” Inline style overrides for content pages
               (Scoped to .main-content so sidebar is unaffected)
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Responsive â€” Tablet (â‰¤1024px)
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
            @media (max-width: 1024px) {
                .sidebar { transform: translateX(-100%); }
                .sidebar.sidebar-open { transform: translateX(0); }
                .main-content { margin-left: 0 !important; }
                .sidebar-overlay.active { display: block; }
            }

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Responsive â€” Mobile (â‰¤768px)
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
            @media (max-width: 768px) {
                .top-bar { padding: 0.625rem 1rem; }
                .top-bar h2 { font-size: 0.95rem !important; }
                main { padding: 1rem !important; }
                .stat-card { padding: 1.15rem; }
                .data-table thead th,
                .data-table tbody td { padding: 0.625rem 0.75rem; font-size: 0.8rem; white-space: nowrap; }
                .btn { padding: 0.45rem 1rem; font-size: 0.8rem; }
            }

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Responsive â€” Small Mobile (â‰¤480px)
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Responsive â€” Medium (â‰¤640px)
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
            @media (max-width: 640px) {
                main { padding: 1rem !important; }
                .top-bar-date { display: none; }
            }

            /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
               Deadline Alert Bar â€” Peringatan batas waktu pinjam
            â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
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

        {{-- LOADING BAR + SMOOTH NAVIGATION SYSTEM --}}
        <div id="lp-bar" style="position:fixed;top:0;left:0;z-index:999999;height:3px;width:0%;background:linear-gradient(90deg,#6366f1,#8b5cf6,#a78bfa);box-shadow:0 0 10px rgba(99,102,241,0.6),0 0 20px rgba(99,102,241,0.3);transition:width 0.25s cubic-bezier(0.4,0,0.2,1),opacity 0.3s ease;opacity:0;pointer-events:none;border-radius:0 2px 2px 0;"></div>
        <div id="page-overlay" style="position:fixed;inset:0;z-index:99998;background:var(--bg,#f1f5f9);opacity:0;pointer-events:none;transition:opacity 0.18s ease;"></div>

        {{-- â”€â”€ LOADING PROGRESS BAR â”€â”€ --}}
        <div id="lp-bar" style="
            position:fixed;top:0;left:0;z-index:999999;
            height:3px;width:0%;
            background:linear-gradient(90deg,#6366f1,#8b5cf6,#a78bfa);
            box-shadow:0 0 10px rgba(99,102,241,0.6),0 0 20px rgba(99,102,241,0.3);
            transition:width 0.25s cubic-bezier(0.4,0,0.2,1),opacity 0.3s ease;
            opacity:0;pointer-events:none;border-radius:0 2px 2px 0;
        "></div>

        {{-- â”€â”€ PAGE TRANSITION OVERLAY â”€â”€ --}}
        <div id="page-overlay" style="
            position:fixed;inset:0;z-index:99998;
            background:var(--bg,#f1f5f9);
            opacity:0;pointer-events:none;
            transition:opacity 0.18s ease;
        "></div>

        <!-- Sidebar Overlay (mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <div class="flex items-center gap-3">
                    <div style="width:42px;height:42px;border-radius:12px;overflow:hidden;flex-shrink:0;border:2px solid rgba(99,102,241,0.3);box-shadow:0 2px 10px rgba(99,102,241,0.25);background:#fff;">
                        <img src="{{ asset('images/logo.png') }}" alt="LabManager Logo" style="width:100%;height:100%;object-fit:cover;display:block;">
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

                {{-- â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                     Deadline Alert Bar
                     Ditampilkan secara dinamis oleh JavaScript di bawah.
                     Mengingatkan user tentang batas waktu pengembalian alat.
                â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
                <div id="deadline-alert-bar"></div>

                @yield('content')
            </main>
        </div>

        <script>
            /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
               Sidebar toggle (click only â€” desktop & mobile)
            â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
            const sidebar     = document.getElementById('sidebar');
            const overlay     = document.getElementById('sidebarOverlay');
            const hamburger   = document.getElementById('hamburgerBtn');
            const mainContent = document.getElementById('mainContent');
            let sidebarVisible = window.innerWidth > 1024;

            // Set initial state without transition (no animation on load)
            sidebar.style.transition = 'none';
            mainContent.style.transition = 'none';
            if (!sidebarVisible) {
                sidebar.style.transform      = 'translateX(-100%)';
                mainContent.style.marginLeft = '0';
            }
            // Re-enable transitions after initial paint
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    sidebar.style.transition     = '';
                    mainContent.style.transition = '';
                });
            });

            function showSidebar() {
                sidebarVisible = true;
                if (window.innerWidth > 1024) {
                    sidebar.style.transform      = 'translateX(0)';
                    mainContent.style.marginLeft = '260px';
                    sidebar.classList.add('sidebar-open');
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
                    sidebar.classList.remove('sidebar-open');
                } else {
                    sidebar.classList.remove('sidebar-open');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }

            // Buka/tutup hanya via klik tombol hamburger
            hamburger.addEventListener('click', () => {
                sidebarVisible ? hideSidebar() : showSidebar();
            });

            // Tutup saat overlay (background gelap mobile) diklik
            overlay.addEventListener('click', () => hideSidebar());

            window.addEventListener('resize', () => {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('sidebar-open');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                    sidebar.style.transform      = sidebarVisible ? 'translateX(0)' : 'translateX(-100%)';
                    mainContent.style.marginLeft = sidebarVisible ? '260px' : '0';
                    if (sidebarVisible) sidebar.classList.add('sidebar-open');
                } else {
                    sidebar.style.transform      = '';
                    mainContent.style.marginLeft = '0';
                    if (!sidebar.classList.contains('sidebar-open')) {
                        sidebarVisible = false;
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

            /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
               Dark / Light Mode Toggle
            â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
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

            /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
               Smooth Page Transitions
               Intercept link clicks â†’ fade-out â†’ navigate
            â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
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
        {{-- â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
             Deadline Alert Engine
             Menampilkan peringatan real-time saat mendekati batas
             waktu pengembalian alat (jam operasional 08:00 - 20:00).
        â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
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
            /* â”€â”€ Konfigurasi â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
            const LAB_CLOSE      = '20:00'; // Batas tutup lab
            const WARN_MINUTES   = 60;      // Mulai peringatan X menit sebelum
            const DANGER_MINUTES = 15;      // Level bahaya X menit sebelum
            const CHECK_INTERVAL = 30000;   // Cek setiap 30 detik

            const activeBorrowings = @json($deadlineAlertData);

            /* â”€â”€ Helper â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
            function toMins(hhmm) {
                const [h, m] = hhmm.substring(0,5).split(':').map(Number);
                return h * 60 + m;
            }
            function nowHHMM() {
                const d = new Date();
                return String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
            }

            /* â”€â”€ Bangun daftar alert â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
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
                        icon:  isDanger ? 'ðŸš¨' : 'â°',
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
                            id: 'borrow-' + b.id, level: 'danger', icon: 'ðŸ”´',
                            html: '<strong>' + b.name + '</strong>: Peminjaman dari <strong>hari sebelumnya</strong> belum dikembalikan! <a href="' + b.url + '" style="text-decoration:underline;font-weight:700;color:inherit;">Segera kembalikan &rarr;</a>',
                        });
                        return; // skip time-based check untuk borrowing lama
                    }

                    // Kasus normal: borrowing hari ini, cek berdasarkan waktu
                    const endMin   = toMins(b.end_date);
                    const minsLeft = endMin - nowMin;

                    if (minsLeft <= 0) {
                        alerts.push({
                            id: 'borrow-' + b.id, level: 'danger', icon: 'ðŸ”´',
                            html: '<strong>' + b.name + '</strong>: Batas waktu pengembalian pukul ' + b.end_date + ' <strong>telah lewat!</strong> <a href="' + b.url + '" style="text-decoration:underline;font-weight:700;color:inherit;">Lihat detail &rarr;</a>',
                        });
                    } else if (minsLeft <= DANGER_MINUTES) {
                        alerts.push({
                            id: 'borrow-' + b.id, level: 'danger', icon: 'ðŸ”´',
                            html: '<strong>' + b.name + '</strong>: Harus dikembalikan dalam <strong>' + minsLeft + ' menit</strong> (pukul ' + b.end_date + ')! <a href="' + b.url + '" style="text-decoration:underline;color:inherit;">Detail &rarr;</a>',
                        });
                    } else if (minsLeft <= WARN_MINUTES) {
                        alerts.push({
                            id: 'borrow-' + b.id, level: 'warning', icon: 'âš ï¸',
                            html: '<strong>' + b.name + '</strong>: Batas pengembalian dalam <strong>' + minsLeft + ' menit</strong> (pukul ' + b.end_date + '). <a href="' + b.url + '" style="text-decoration:underline;color:inherit;">Lihat &rarr;</a>',
                        });
                    }
                });

                return alerts;
            }

            /* â”€â”€ Render ke DOM â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
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

            /* â”€â”€ Jalankan â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
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

        {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
             GLOBAL CONFIRM MODAL â€” Menggantikan native browser confirm()
             Gunakan: showConfirm({ title, message, icon, type, onConfirm })
             type: 'danger' | 'warning' | 'success' | 'info' (default)
        â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
        <div id="globalConfirmModal" aria-modal="true" role="dialog" style="
            display:none;
            position:fixed;inset:0;z-index:99999;
            align-items:center;justify-content:center;
            padding:1rem;
        ">
            {{-- Backdrop --}}
            <div id="gcmBackdrop" style="
                position:absolute;inset:0;
                background:rgba(2,6,23,0.65);
                backdrop-filter:blur(6px);
                -webkit-backdrop-filter:blur(6px);
                animation:gcmFadeIn 0.2s ease forwards;
            "></div>

            {{-- Modal Card --}}
            <div id="gcmCard" style="
                position:relative;z-index:1;
                background:var(--surface-g);
                backdrop-filter:blur(20px);
                border:1px solid var(--border-s);
                border-radius:1.25rem;
                width:100%;max-width:420px;
                box-shadow:0 32px 80px rgba(0,0,0,0.45);
                animation:gcmSlideIn 0.3s cubic-bezier(0.22,1,0.36,1) forwards;
                overflow:hidden;
            ">
                {{-- Top accent bar (color injected by JS) --}}
                <div id="gcmAccentBar" style="height:4px;width:100%;background:linear-gradient(90deg,#4f46e5,#6366f1);"></div>

                <div style="padding:1.75rem 1.75rem 1.5rem;">
                    {{-- Icon + Title --}}
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
                        <div id="gcmIconWrap" style="
                            width:48px;height:48px;border-radius:12px;flex-shrink:0;
                            display:flex;align-items:center;justify-content:center;
                            background:linear-gradient(135deg,#e0e7ff,#c7d2fe);
                        ">
                            <span id="gcmIcon" style="font-size:1.4rem;line-height:1;">âš¡</span>
                        </div>
                        <div>
                            <h3 id="gcmTitle" style="font-size:1.05rem;font-weight:800;color:var(--txt-1);margin:0;line-height:1.3;">Konfirmasi Aksi</h3>
                            <p id="gcmSubtitle" style="font-size:0.72rem;color:var(--txt-3);margin:0.15rem 0 0;"></p>
                        </div>
                    </div>

                    {{-- Message --}}
                    <p id="gcmMessage" style="
                        font-size:0.9rem;color:var(--txt-2);line-height:1.6;
                        background:var(--th-bg);
                        border:1px solid var(--border-s);
                        border-radius:0.6rem;
                        padding:0.75rem 1rem;
                        margin-bottom:1.5rem;
                    ">Apakah Anda yakin?</p>

                    {{-- Action Buttons --}}
                    <div style="display:flex;gap:0.625rem;justify-content:flex-end;">
                        <button id="gcmCancelBtn" style="
                            display:inline-flex;align-items:center;gap:0.4rem;
                            padding:0.6rem 1.25rem;border-radius:0.5rem;
                            background:transparent;border:1px solid var(--border-s);
                            color:var(--txt-2);font-size:0.875rem;font-weight:600;
                            cursor:pointer;transition:all 0.18s ease;
                        " onmouseover="this.style.background='var(--row-hover)';this.style.borderColor='var(--txt-3)'"
                           onmouseout="this.style.background='transparent';this.style.borderColor='var(--border-s)'">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </button>
                        <button id="gcmConfirmBtn" style="
                            display:inline-flex;align-items:center;gap:0.4rem;
                            padding:0.6rem 1.4rem;border-radius:0.5rem;
                            background:linear-gradient(135deg,#4f46e5,#6366f1);
                            border:none;color:#fff;font-size:0.875rem;font-weight:700;
                            cursor:pointer;transition:all 0.18s ease;
                            box-shadow:0 4px 12px rgba(79,70,229,0.3);
                        ">
                            <svg id="gcmConfirmIcon" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span id="gcmConfirmLabel">Ya, Lanjutkan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            @keyframes gcmFadeIn {
                from { opacity:0; }
                to   { opacity:1; }
            }
            @keyframes gcmSlideIn {
                from { opacity:0; transform:scale(0.88) translateY(20px); }
                to   { opacity:1; transform:scale(1)    translateY(0);    }
            }
            @keyframes gcmSlideOut {
                from { opacity:1; transform:scale(1)    translateY(0); }
                to   { opacity:0; transform:scale(0.92) translateY(10px); }
            }
            #globalConfirmModal.gcm-closing #gcmCard      { animation:gcmSlideOut 0.2s ease forwards; }
            #globalConfirmModal.gcm-closing #gcmBackdrop  { animation:gcmFadeIn  0.2s ease reverse forwards; }
            #gcmConfirmBtn:hover { transform:translateY(-1px); filter:brightness(1.08); }
            #gcmConfirmBtn:active { transform:translateY(0); filter:brightness(0.95); }
        </style>

        <script>
        /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
           Global Confirm Modal System
           â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
           API:
             showConfirm({
               title   : 'Setujui Peminjaman',          // heading
               subtitle: 'Alat: Laptop ASUS ROG',       // small text under title (optional)
               message : 'Yakin ingin menyetujui...?',  // body text
               icon    : 'âœ…',                           // emoji icon
               type    : 'success',                     // 'success'|'danger'|'warning'|'info'
               confirmLabel : 'Ya, Setujui',            // confirm button label
               onConfirm    : () => form.submit()       // callback on confirm
             })

           Untuk form submit convenience:
             submitWithConfirm(formEl, options)
        â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
        (function() {
            const modal      = document.getElementById('globalConfirmModal');
            const backdrop   = document.getElementById('gcmBackdrop');
            const card       = document.getElementById('gcmCard');
            const title      = document.getElementById('gcmTitle');
            const subtitle   = document.getElementById('gcmSubtitle');
            const message    = document.getElementById('gcmMessage');
            const iconWrap   = document.getElementById('gcmIconWrap');
            const icon       = document.getElementById('gcmIcon');
            const accentBar  = document.getElementById('gcmAccentBar');
            const confirmBtn = document.getElementById('gcmConfirmBtn');
            const cancelBtn  = document.getElementById('gcmCancelBtn');
            const confirmLbl = document.getElementById('gcmConfirmLabel');
            const confirmIco = document.getElementById('gcmConfirmIcon');

            const themes = {
                success: {
                    bar  : 'linear-gradient(90deg,#059669,#10b981)',
                    icon : 'linear-gradient(135deg,#d1fae5,#a7f3d0)',
                    btn  : 'linear-gradient(135deg,#059669,#10b981)',
                    shadow: 'rgba(16,185,129,0.35)',
                    svg  : '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
                },
                danger: {
                    bar  : 'linear-gradient(90deg,#dc2626,#ef4444)',
                    icon : 'linear-gradient(135deg,#fee2e2,#fca5a5)',
                    btn  : 'linear-gradient(135deg,#dc2626,#ef4444)',
                    shadow: 'rgba(239,68,68,0.35)',
                    svg  : '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>',
                },
                warning: {
                    bar  : 'linear-gradient(90deg,#d97706,#f59e0b)',
                    icon : 'linear-gradient(135deg,#fef3c7,#fde68a)',
                    btn  : 'linear-gradient(135deg,#d97706,#f59e0b)',
                    shadow: 'rgba(245,158,11,0.35)',
                    svg  : '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                },
                info: {
                    bar  : 'linear-gradient(90deg,#4f46e5,#6366f1)',
                    icon : 'linear-gradient(135deg,#e0e7ff,#c7d2fe)',
                    btn  : 'linear-gradient(135deg,#4f46e5,#6366f1)',
                    shadow: 'rgba(79,70,229,0.35)',
                    svg  : '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                },
            };

            let _callback = null;

            function closeModal() {
                modal.classList.add('gcm-closing');
                setTimeout(() => {
                    modal.style.display  = 'none';
                    modal.classList.remove('gcm-closing');
                    document.body.style.overflow = '';
                    _callback = null;
                }, 200);
            }

            window.showConfirm = function(opts) {
                const t = themes[opts.type] || themes.info;

                // Apply theme
                accentBar.style.background   = t.bar;
                iconWrap.style.background    = t.icon;
                confirmBtn.style.background  = t.btn;
                confirmBtn.style.boxShadow   = '0 4px 12px ' + t.shadow;
                confirmIco.innerHTML         = t.svg;

                // Set content
                title.textContent   = opts.title   || 'Konfirmasi';
                subtitle.textContent= opts.subtitle || '';
                subtitle.style.display = opts.subtitle ? 'block' : 'none';
                message.textContent = opts.message  || 'Apakah Anda yakin?';
                icon.textContent    = opts.icon     || 'âš¡';
                confirmLbl.textContent = opts.confirmLabel || 'Ya, Lanjutkan';

                _callback = opts.onConfirm || null;

                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';

                // Focus confirm button after animation
                setTimeout(() => confirmBtn.focus(), 310);
            };

            // Confirm button
            confirmBtn.addEventListener('click', () => {
                closeModal();
                if (typeof _callback === 'function') {
                    // Small delay so modal closes before action
                    setTimeout(_callback, 60);
                }
            });

            // Cancel
            cancelBtn.addEventListener('click', closeModal);

            // Backdrop click
            backdrop.addEventListener('click', closeModal);

            // Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.style.display === 'flex') closeModal();
                if (e.key === 'Enter'  && modal.style.display === 'flex') confirmBtn.click();
            });

            /* â”€â”€ Convenience wrapper for form submit â”€â”€ */
            window.submitWithConfirm = function(formEl, opts) {
                opts.onConfirm = () => formEl.submit();
                window.showConfirm(opts);
            };

            /* â”€â”€ Auto-intercept semua onclick="return confirm(...)" â”€â”€
               Digantikan oleh custom modal saat halaman load.
               Cara kerja: cari semua elemen dengan data-confirm="..." attribute.
               Gunakan attribute data-confirm, data-confirm-title, data-confirm-type
               data-confirm-icon, data-confirm-label pada button/a/form.
            â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[data-confirm]').forEach(function(el) {
                    const tag = el.tagName.toLowerCase();

                    if (tag === 'form') {
                        el.addEventListener('submit', function(e) {
                            e.preventDefault();
                            window.showConfirm({
                                title        : el.dataset.confirmTitle   || 'Konfirmasi Aksi',
                                subtitle     : el.dataset.confirmSubtitle|| '',
                                message      : el.dataset.confirm,
                                icon         : el.dataset.confirmIcon    || 'âš¡',
                                type         : el.dataset.confirmType    || 'info',
                                confirmLabel : el.dataset.confirmLabel   || 'Ya, Lanjutkan',
                                onConfirm    : () => el.submit(),
                            });
                        });
                    } else {
                        // button or a
                        const form = el.closest('form') || el.form;
                        el.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            window.showConfirm({
                                title        : el.dataset.confirmTitle   || 'Konfirmasi Aksi',
                                subtitle     : el.dataset.confirmSubtitle|| '',
                                message      : el.dataset.confirm,
                                icon         : el.dataset.confirmIcon    || 'âš¡',
                                type         : el.dataset.confirmType    || 'info',
                                confirmLabel : el.dataset.confirmLabel   || 'Ya, Lanjutkan',
                                onConfirm    : () => {
                                    if (form) form.submit();
                                    else if (el.href) window.location.href = el.href;
                                },
                            });
                        });
                    }
                });
            });
        })();
        </script>
        <script>
        /* ── LP: Loading Progress bar ── */
        window.LP=(function(){
            var b=document.getElementById('lp-bar');
            var o=document.getElementById('page-overlay');
            var tid=null,fake=null,cur=0;
            function sw(p,i){if(!b)return;if(i){b.style.transition='none';}b.style.width=p+'%';if(i){void b.offsetWidth;b.style.transition='';}}
            function start(){
                clearTimeout(tid);clearInterval(fake);cur=0;sw(0,true);
                if(b){b.style.opacity='1';}
                if(o){o.style.opacity='0.25';o.style.pointerEvents='all';}
                fake=setInterval(function(){
                    if(cur<30)cur+=3;else if(cur<60)cur+=1.5;else if(cur<80)cur+=0.7;else if(cur<90)cur+=0.2;else{clearInterval(fake);return;}
                    sw(cur);
                },120);
            }
            function done(){
                clearInterval(fake);sw(100);
                if(o){o.style.opacity='0';setTimeout(function(){o.style.pointerEvents='none';},200);}
                tid=setTimeout(function(){if(b){b.style.opacity='0';setTimeout(function(){sw(0,true);cur=0;},350);}},220);
            }
            function fail(){
                clearInterval(fake);
                if(b){b.style.background='linear-gradient(90deg,#ef4444,#f87171)';b.style.boxShadow='0 0 10px rgba(239,68,68,0.5)';}
                sw(100);
                tid=setTimeout(function(){if(b){b.style.opacity='0';setTimeout(function(){sw(0,true);b.style.background='linear-gradient(90deg,#6366f1,#8b5cf6,#a78bfa)';b.style.boxShadow='0 0 10px rgba(99,102,241,0.6)';cur=0;},400);}},600);
            }
            return{start:start,done:done,fail:fail};
        })();

        document.addEventListener('click',function(e){
            var a=e.target.closest('a[href]');if(!a)return;
            var h=a.getAttribute('href');
            if(!h||h.startsWith('#')||h.startsWith('mailto:')||h.startsWith('tel:')||h.startsWith('javascript:')||a.target==='_blank'||e.ctrlKey||e.metaKey||e.shiftKey)return;
            try{var u=new URL(h,window.location.href);if(u.origin!==window.location.origin)return;if(u.pathname===window.location.pathname&&u.hash)return;}catch(er){return;}
            LP.start();
        },true);

        document.addEventListener('submit',function(e){if(!e.defaultPrevented)LP.start();},true);

        if(document.readyState==='complete'){LP.done();}
        else{window.addEventListener('pageshow',LP.done);window.addEventListener('load',LP.done);}

        document.addEventListener('DOMContentLoaded',function(){
            var s=document.createElement('style');
            s.textContent=
                '.rpl-host{position:relative;overflow:hidden}'+
                '.rpl{position:absolute;border-radius:50%;transform:scale(0);animation:rplAnim 0.55s linear;pointer-events:none;background:rgba(255,255,255,0.28)}'+
                '@keyframes rplAnim{to{transform:scale(4);opacity:0}}'+
                '.btn:active,.u-btn:active{transform:scale(0.96)!important;transition:transform 0.08s ease!important}'+
                '.stat-card,.glass-card,.u-card{transition:transform 0.2s cubic-bezier(0.22,1,0.36,1),box-shadow 0.2s ease,background-color 0.25s ease,border-color 0.25s ease!important}'+
                '.form-input,.u-form-input{transition:border-color 0.18s ease,box-shadow 0.18s ease,background-color 0.18s ease!important}'+
                '.badge,.u-badge{transition:transform 0.15s ease,background-color 0.2s ease!important}'+
                '.badge:hover,.u-badge:hover{transform:scale(1.08)}'+
                '.alert,.u-alert{animation:alertSlide 0.32s cubic-bezier(0.22,1,0.36,1) both!important}'+
                '@keyframes alertSlide{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}'+
                '.sidebar-link{transition:all 0.18s cubic-bezier(0.22,1,0.36,1)!important}'+
                '.sidebar{transition:transform 0.32s cubic-bezier(0.22,1,0.36,1),box-shadow 0.32s ease!important}'+
                '.data-table tbody tr,.u-table tbody tr{transition:background-color 0.1s ease!important}'+
                '.u-nav-link{transition:all 0.18s ease!important}'+
                '.top-bar{transition:background-color 0.25s ease,border-color 0.25s ease!important}'+
                '#stb{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9000;width:42px;height:42px;border-radius:50%;border:none;cursor:pointer;background:linear-gradient(135deg,#4f46e5,#6366f1);color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(79,70,229,0.35);opacity:0;transform:translateY(12px) scale(0.85);transition:opacity 0.25s ease,transform 0.25s cubic-bezier(0.22,1,0.36,1),box-shadow 0.2s ease;pointer-events:none}'+
                '#stb.vis{opacity:1!important;transform:translateY(0) scale(1)!important;pointer-events:all!important}'+
                '#stb:hover{transform:translateY(-3px) scale(1.1)!important;box-shadow:0 10px 28px rgba(79,70,229,0.5)!important}';
            document.head.appendChild(s);

            function addRipple(el){
                if(el.dataset.rpl)return;el.dataset.rpl='1';el.classList.add('rpl-host');
                el.addEventListener('mousedown',function(e){
                    var r=el.getBoundingClientRect(),d=Math.max(r.width,r.height);
                    var rp=document.createElement('span');rp.className='rpl';
                    rp.style.cssText='width:'+d+'px;height:'+d+'px;left:'+(e.clientX-r.left-d/2)+'px;top:'+(e.clientY-r.top-d/2)+'px';
                    el.appendChild(rp);rp.addEventListener('animationend',function(){rp.remove();});
                });
            }
            document.querySelectorAll('.btn,.u-btn,button[type="submit"]').forEach(addRipple);
            if(window.MutationObserver){
                new MutationObserver(function(muts){muts.forEach(function(m){m.addedNodes.forEach(function(n){if(n.nodeType!==1)return;if(n.matches&&n.matches('.btn,.u-btn,button[type="submit"]'))addRipple(n);n.querySelectorAll&&n.querySelectorAll('.btn,.u-btn,button[type="submit"]').forEach(addRipple);});});}).observe(document.body,{childList:true,subtree:true});
            }

            var sb=document.createElement('button');sb.id='stb';
            sb.innerHTML='<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>';
            sb.setAttribute('aria-label','Scroll to top');sb.setAttribute('title','Kembali ke atas');
            document.body.appendChild(sb);
            var mc=document.getElementById('mainContent')||document.documentElement;
            function chk(){var t=(mc===document.documentElement)?window.scrollY:mc.scrollTop;t>280?sb.classList.add('vis'):sb.classList.remove('vis');}
            mc.addEventListener('scroll',chk,{passive:true});window.addEventListener('scroll',chk,{passive:true});
            sb.addEventListener('click',function(){(mc===document.documentElement?window:mc).scrollTo({top:0,behavior:'smooth'});});
        });
        </script>


    </body>
</html>


