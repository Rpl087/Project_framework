<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Sistem Manajemen & Peminjaman Infrastruktur Laboratorium">

        <title>{{ config('app.name', 'LabManager') }} - @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { font-family: 'Inter', sans-serif; }

            /* Sidebar */
            .sidebar {
                background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
                width: 260px;
                min-height: 100vh;
                position: fixed;
                left: 0;
                top: 0;
                z-index: 40;
                transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

            /* Main */
            .main-content {
                margin-left: 260px;
                min-height: 100vh;
                background: #f1f5f9;
                transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .top-bar {
                background: rgba(255,255,255,0.85);
                backdrop-filter: blur(12px);
                border-bottom: 1px solid #e2e8f0;
                padding: 0.75rem 1.5rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: sticky;
                top: 0;
                z-index: 30;
            }

            /* Cards */
            .stat-card {
                background: rgba(255,255,255,0.85);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(226,232,240,0.6);
                border-radius: 1rem;
                padding: 1.5rem;
                transition: all 0.3s ease;
            }
            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 28px rgba(0,0,0,0.08);
            }
            .glass-card {
                background: rgba(255,255,255,0.9);
                backdrop-filter: blur(16px);
                border: 1px solid rgba(226,232,240,0.5);
                border-radius: 1rem;
                overflow: hidden;
            }

            /* Badge */
            .badge {
                display: inline-flex;
                align-items: center;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
            }
            .badge-amber { background: #fef3c7; color: #92400e; }
            .badge-blue { background: #dbeafe; color: #1e40af; }
            .badge-indigo { background: #e0e7ff; color: #3730a3; }
            .badge-cyan { background: #cffafe; color: #155e75; }
            .badge-emerald { background: #d1fae5; color: #065f46; }
            .badge-green { background: #dcfce7; color: #166534; }
            .badge-red { background: #fee2e2; color: #991b1b; }
            .badge-orange { background: #ffedd5; color: #9a3412; }
            .badge-rose { background: #ffe4e6; color: #9f1239; }
            .badge-gray { background: #f1f5f9; color: #475569; }

            /* Buttons */
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
            .btn-primary {
                background: linear-gradient(135deg, #4f46e5, #6366f1);
                color: #fff;
            }
            .btn-primary:hover {
                background: linear-gradient(135deg, #4338ca, #4f46e5);
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
                transform: translateY(-1px);
            }
            .btn-success {
                background: linear-gradient(135deg, #059669, #10b981);
                color: #fff;
            }
            .btn-success:hover {
                background: linear-gradient(135deg, #047857, #059669);
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
            }
            .btn-danger {
                background: linear-gradient(135deg, #dc2626, #ef4444);
                color: #fff;
            }
            .btn-danger:hover {
                background: linear-gradient(135deg, #b91c1c, #dc2626);
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.35);
            }
            .btn-warning {
                background: linear-gradient(135deg, #d97706, #f59e0b);
                color: #fff;
            }
            .btn-outline {
                background: transparent;
                border: 1px solid #e2e8f0;
                color: #475569;
            }
            .btn-outline:hover {
                background: #f8fafc;
                border-color: #cbd5e1;
            }
            .btn-sm {
                padding: 0.375rem 0.875rem;
                font-size: 0.8rem;
            }

            /* Form */
            .form-input {
                width: 100%;
                padding: 0.625rem 0.875rem;
                border: 1px solid #e2e8f0;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                transition: all 0.2s ease;
                background: #fff;
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
                color: #374151;
                margin-bottom: 0.375rem;
            }

            /* Table */
            .data-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }
            .data-table thead th {
                background: #f8fafc;
                padding: 0.75rem 1rem;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #64748b;
                text-align: left;
                border-bottom: 1px solid #e2e8f0;
            }
            .data-table tbody td {
                padding: 0.875rem 1rem;
                font-size: 0.875rem;
                color: #334155;
                border-bottom: 1px solid #f1f5f9;
            }
            .data-table tbody tr {
                transition: background 0.15s ease;
            }
            .data-table tbody tr:hover {
                background: #f8fafc;
            }

            /* Animations */
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(16px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-in {
                animation: fadeInUp 0.4s ease-out forwards;
            }
            .animate-delay-1 { animation-delay: 0.1s; opacity: 0; }
            .animate-delay-2 { animation-delay: 0.2s; opacity: 0; }
            .animate-delay-3 { animation-delay: 0.3s; opacity: 0; }
            .animate-delay-4 { animation-delay: 0.4s; opacity: 0; }

            /* Overlay */
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.4);
                z-index: 35;
            }

            /* Hamburger Button */
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
            .hamburger-btn:hover {
                background: #f1f5f9;
            }
            .hamburger-btn:active {
                transform: scale(0.92);
            }

            /* Animated hamburger bars */
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
                background: #64748b;
                border-radius: 2px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                transform-origin: center;
            }
            .hamburger-btn:hover .hamburger-bars span {
                background: #1e293b;
            }

            /* Responsive table wrapper */
            .table-responsive {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* Responsive: Tablet (<=1024px) */
            @media (max-width: 1024px) {
                .sidebar {
                    transform: translateX(-100%);
                }
                .sidebar.sidebar-open {
                    transform: translateX(0);
                }
                .main-content {
                    margin-left: 0 !important;
                }
                .sidebar-overlay.active {
                    display: block;
                }
            }

            /* Responsive: Mobile (<=768px) */
            @media (max-width: 768px) {
                .top-bar {
                    padding: 0.625rem 1rem;
                }
                .top-bar h2 {
                    font-size: 0.95rem !important;
                }
                main {
                    padding: 1rem !important;
                }
                .stat-card {
                    padding: 1.15rem;
                }
                .data-table thead th,
                .data-table tbody td {
                    padding: 0.625rem 0.75rem;
                    font-size: 0.8rem;
                    white-space: nowrap;
                }
                .btn {
                    padding: 0.45rem 1rem;
                    font-size: 0.8rem;
                }
            }

            /* Responsive: Small Mobile (<=480px) */
            @media (max-width: 480px) {
                .top-bar {
                    padding: 0.5rem 0.75rem;
                }
                main {
                    padding: 0.75rem !important;
                }
                .stat-card {
                    padding: 1rem;
                }
                .btn {
                    padding: 0.4rem 0.75rem;
                    font-size: 0.75rem;
                }
                .btn-sm {
                    padding: 0.3rem 0.625rem;
                    font-size: 0.7rem;
                }
                .badge {
                    font-size: 0.65rem;
                    padding: 0.2rem 0.5rem;
                }
            }

            /* Alert */
            .alert {
                padding: 0.875rem 1.25rem;
                border-radius: 0.75rem;
                margin-bottom: 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .alert-success {
                background: #d1fae5;
                color: #065f46;
                border: 1px solid #a7f3d0;
            }
            .alert-error {
                background: #fee2e2;
                color: #991b1b;
                border: 1px solid #fca5a5;
            }

            /* Scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
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
                @endif

                @if(auth()->user()->isKepalaLab())
                    <div class="sidebar-section">Persetujuan</div>
                    <a href="{{ route('borrowings.index') }}" class="sidebar-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Persetujuan Alat Khusus
                    </a>
                @endif
            </nav>

            <!-- User Info at bottom -->
            <div style="position:absolute;bottom:0;left:0;right:0;padding:1rem;border-top:1px solid rgba(255,255,255,0.08);">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="color:#e2e8f0;font-size:0.8rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</p>
                        <p style="color:#64748b;font-size:0.7rem;text-transform:capitalize;">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                    </div>
                </div>
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
                    <h2 style="font-size:1.1rem;font-weight:700;color:#1e293b;">@yield('title', 'Dashboard')</h2>
                </div>
                <div style="display:flex;align-items:center;gap:1rem;">
                    <span style="font-size:0.8rem;color:#64748b;">{{ now()->format('d M Y') }}</span>
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

                @yield('content')
            </main>
        </div>

        <script>
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const hamburger = document.getElementById('hamburgerBtn');
            const mainContent = document.getElementById('mainContent');
            let sidebarVisible = window.innerWidth > 1024;
            let sidebarPinned = sidebarVisible;
            let hoverTimeout = null;

            // Init state
            if (!sidebarVisible) {
                sidebar.style.transform = 'translateX(-100%)';
                mainContent.style.marginLeft = '0';
            }

            function showSidebar() {
                clearTimeout(hoverTimeout);
                sidebarVisible = true;
                if (window.innerWidth > 1024) {
                    sidebar.style.transform = 'translateX(0)';
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
                    sidebar.style.transform = 'translateX(-100%)';
                    mainContent.style.marginLeft = '0';
                } else {
                    sidebar.classList.remove('sidebar-open');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }

            // Click: toggle pinned state
            hamburger.addEventListener('click', () => {
                if (sidebarVisible && sidebarPinned) {
                    sidebarPinned = false;
                    hideSidebar();
                } else {
                    sidebarPinned = true;
                    showSidebar();
                }
            });

            // Hover: expand on desktop (only if not pinned open)
            hamburger.addEventListener('mouseenter', () => {
                if (window.innerWidth > 1024 && !sidebarVisible) {
                    sidebarPinned = false;
                    showSidebar();
                }
            });

            // Mouse leave sidebar area: collapse if not pinned
            sidebar.addEventListener('mouseleave', (e) => {
                if (window.innerWidth > 1024 && !sidebarPinned) {
                    hoverTimeout = setTimeout(() => {
                        hideSidebar();
                    }, 300);
                }
            });

            // Keep open while hovering sidebar
            sidebar.addEventListener('mouseenter', () => {
                clearTimeout(hoverTimeout);
            });

            // Overlay click (mobile)
            overlay.addEventListener('click', () => {
                sidebarPinned = false;
                hideSidebar();
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('sidebar-open');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                    sidebar.style.transform = sidebarVisible ? 'translateX(0)' : 'translateX(-100%)';
                    mainContent.style.marginLeft = sidebarVisible ? '260px' : '0';
                } else {
                    sidebar.style.transform = '';
                    mainContent.style.marginLeft = '0';
                    if (!sidebar.classList.contains('sidebar-open')) {
                        sidebarVisible = false;
                        sidebarPinned = false;
                    }
                }
            });

            // Wrap tables for responsive scroll
            document.querySelectorAll('.data-table').forEach(table => {
                if (!table.parentElement.classList.contains('table-responsive')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'table-responsive';
                    table.parentNode.insertBefore(wrapper, table);
                    wrapper.appendChild(table);
                }
            });
        </script>
        @stack('scripts')
    </body>
</html>
