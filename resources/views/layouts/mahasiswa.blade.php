<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Peminjaman Alat Laboratorium â€” Mahasiswa">
    <title>{{ config('app.name', 'LabManager') }} â€” @yield('title', 'Beranda')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }

        :root {
            --u-bg:        #f0f4ff;
            --u-surface:   #ffffff;
            --u-border:    #e2e8f0;
            --u-txt1:      #0f172a;
            --u-txt2:      #475569;
            --u-txt3:      #94a3b8;
            --u-accent:    #6366f1;
            --u-accent2:   #8b5cf6;
            --u-green:     #059669;
            --u-amber:     #d97706;
            --u-red:       #ef4444;
            --u-shadow:    rgba(99,102,241,0.12);
            --u-shadow-lg: rgba(0,0,0,0.1);
        }

        body { background: var(--u-bg); min-height: 100vh; }

        /* â•â•â• TOP NAVBAR â•â•â• */
        .u-nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--u-border);
            box-shadow: 0 1px 24px rgba(99,102,241,0.07);
        }
        .u-nav-inner {
            max-width: 1200px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1.5rem;
            gap: 1rem;
        }
        .u-nav-brand {
            display: flex; align-items: center; gap: 0.625rem;
            text-decoration: none;
        }
        .u-nav-brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .u-nav-brand-text { font-size: 1rem; font-weight: 800; color: var(--u-txt1); }
        .u-nav-brand-sub  { font-size: 0.62rem; color: var(--u-txt3); font-weight: 500; }

        .u-nav-links {
            display: flex; align-items: center; gap: 0.25rem;
        }
        .u-nav-link {
            display: flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 0.875rem; border-radius: 0.5rem;
            font-size: 0.85rem; font-weight: 600;
            color: var(--u-txt2); text-decoration: none;
            transition: all 0.18s ease;
        }
        .u-nav-link:hover { background: #f1f5f9; color: var(--u-txt1); }
        .u-nav-link.active {
            background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(139,92,246,0.1));
            color: var(--u-accent);
        }
        .u-nav-link.active svg { stroke: var(--u-accent); }

        /* Badge notif */
        .u-notif-badge {
            position: absolute; top: -4px; right: -4px;
            width: 16px; height: 16px; border-radius: 50%;
            background: #ef4444; color: #fff;
            font-size: 0.6rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #fff;
        }

        /* Avatar */
        .u-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 800; font-size: 0.8rem;
            cursor: pointer; flex-shrink: 0;
            border: 2px solid rgba(99,102,241,0.3);
            transition: all 0.2s;
        }
        .u-avatar:hover { transform: scale(1.08); box-shadow: 0 4px 12px rgba(99,102,241,0.35); }

        /* Dropdown */
        .u-dropdown { position: relative; }
        .u-dropdown-menu {
            display: none; position: absolute; right: 0; top: calc(100% + 0.5rem);
            background: #fff; border: 1px solid var(--u-border);
            border-radius: 0.75rem; box-shadow: 0 12px 40px rgba(0,0,0,0.12);
            min-width: 200px; padding: 0.5rem; z-index: 200;
            animation: dropIn 0.18s ease;
        }
        .u-dropdown-menu.open { display: block; }
        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .u-dropdown-item {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.625rem 0.875rem; border-radius: 0.5rem;
            font-size: 0.85rem; font-weight: 500; color: var(--u-txt2);
            text-decoration: none; cursor: pointer; border: none;
            background: none; width: 100%; text-align: left;
            transition: background 0.15s;
        }
        .u-dropdown-item:hover { background: #f8fafc; color: var(--u-txt1); }
        .u-dropdown-item.danger { color: #ef4444; }
        .u-dropdown-item.danger:hover { background: #fee2e2; }
        .u-dropdown-divider { height: 1px; background: var(--u-border); margin: 0.25rem 0; }

        /* â•â•â• HERO BANNER â•â•â• */
        .u-hero {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 40%, #8b5cf6 70%, #a78bfa 100%);
            position: relative; overflow: hidden;
            padding: 2.5rem 1.5rem;
        }
        .u-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .u-hero::after {
            content: '';
            position: absolute;
            right: -80px; top: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .u-hero-inner { max-width: 1200px; margin: 0 auto; position: relative; z-index: 1; }
        .u-hero-greeting {
            font-size: 0.75rem; font-weight: 700; color: rgba(255,255,255,0.7);
            text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.4rem;
        }
        .u-hero-title {
            font-size: 1.75rem; font-weight: 900; color: #fff; line-height: 1.2;
            margin-bottom: 0.5rem;
        }
        .u-hero-sub { font-size: 0.9rem; color: rgba(255,255,255,0.75); }

        .u-hero-stats {
            display: flex; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap;
        }
        .u-hero-stat {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 0.75rem; padding: 0.75rem 1rem;
            min-width: 110px;
        }
        .u-hero-stat-num  { font-size: 1.6rem; font-weight: 900; color: #fff; }
        .u-hero-stat-label{ font-size: 0.7rem; color: rgba(255,255,255,0.7); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }

        /* â•â•â• CONTENT WRAPPER â•â•â• */
        .u-content { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* â•â•â• CARDS â•â•â• */
        .u-card {
            background: var(--u-surface);
            border: 1px solid var(--u-border);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 2px 12px var(--u-shadow-lg);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .u-card:hover { box-shadow: 0 8px 30px var(--u-shadow); }

        .u-card-header {
            padding: 1.125rem 1.5rem;
            border-bottom: 1px solid var(--u-border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .u-card-title { font-size: 0.95rem; font-weight: 700; color: var(--u-txt1); }
        .u-card-body { padding: 1.25rem 1.5rem; }

        /* â•â•â• STAT MINI CARDS â•â•â• */
        .u-stat {
            background: #fff; border: 1px solid var(--u-border); border-radius: 1rem;
            padding: 1.25rem; display: flex; align-items: center; gap: 1rem;
            box-shadow: 0 1px 8px var(--u-shadow-lg);
            transition: all 0.2s;
        }
        .u-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 20px var(--u-shadow); }
        .u-stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .u-stat-label { font-size: 0.72rem; font-weight: 700; color: var(--u-txt3); text-transform: uppercase; letter-spacing: 0.05em; }
        .u-stat-value { font-size: 1.75rem; font-weight: 900; color: var(--u-txt1); line-height: 1; margin-top: 0.2rem; }

        /* â•â•â• BUTTONS â•â•â• */
        .u-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.6rem 1.25rem; border-radius: 0.625rem;
            font-size: 0.875rem; font-weight: 600;
            cursor: pointer; border: none; text-decoration: none;
            transition: all 0.2s ease;
        }
        .u-btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff; box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }
        .u-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,0.4); }
        .u-btn-success {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff; box-shadow: 0 4px 12px rgba(16,185,129,0.25);
        }
        .u-btn-success:hover { transform: translateY(-1px); }
        .u-btn-outline {
            background: transparent; border: 1.5px solid var(--u-border);
            color: var(--u-txt2);
        }
        .u-btn-outline:hover { background: #f8fafc; border-color: var(--u-accent); color: var(--u-accent); }
        .u-btn-sm { padding: 0.4rem 0.875rem; font-size: 0.78rem; border-radius: 0.5rem; }
        .u-btn-danger { background: linear-gradient(135deg,#dc2626,#ef4444); color:#fff; }
        .u-btn-warning { background: linear-gradient(135deg,#d97706,#f59e0b); color:#fff; }

        /* â•â•â• BADGE â•â•â• */
        .u-badge {
            display: inline-flex; align-items: center;
            padding: 0.3rem 0.75rem; border-radius: 9999px;
            font-size: 0.72rem; font-weight: 700;
        }
        .u-badge-indigo  { background: #e0e7ff; color: #3730a3; }
        .u-badge-blue    { background: #dbeafe; color: #1e40af; }
        .u-badge-emerald { background: #d1fae5; color: #065f46; }
        .u-badge-amber   { background: #fef3c7; color: #92400e; }
        .u-badge-red     { background: #fee2e2; color: #991b1b; }
        .u-badge-gray    { background: #f1f5f9; color: #475569; }
        .u-badge-cyan    { background: #cffafe; color: #155e75; }
        .u-badge-purple  { background: #f3e8ff; color: #6b21a8; }

        /* â•â•â• FORM â•â•â• */
        .u-form-input {
            width: 100%; padding: 0.7rem 1rem;
            border: 1.5px solid var(--u-border); border-radius: 0.625rem;
            font-size: 0.875rem; background: #fff; color: var(--u-txt1);
            transition: all 0.2s;
        }
        .u-form-input:focus {
            outline: none; border-color: var(--u-accent);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        .u-form-label {
            display: block; font-size: 0.8rem; font-weight: 700;
            color: var(--u-txt2); margin-bottom: 0.375rem;
        }

        /* â•â•â• TABLE â•â•â• */
        .u-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .u-table thead th {
            padding: 0.75rem 1rem; background: #f8fafc;
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.05em; color: var(--u-txt3); text-align: left;
            border-bottom: 1px solid var(--u-border);
        }
        .u-table tbody td {
            padding: 0.875rem 1rem; font-size: 0.875rem; color: var(--u-txt2);
            border-bottom: 1px solid #f8fafc;
        }
        .u-table tbody tr:hover td { background: #fafbff; }
        .u-table tbody tr:last-child td { border-bottom: none; }

        /* â•â•â• ALERTS â•â•â• */
        .u-alert {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.875rem 1.25rem; border-radius: 0.75rem;
            font-size: 0.875rem; font-weight: 500;
            margin-bottom: 1.25rem; animation: slideDown 0.3s ease;
        }
        .u-alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .u-alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        @keyframes slideDown {
            from { opacity:0; transform:translateY(-8px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* â•â•â• BOTTOM NAV (Mobile) â•â•â• */
        .u-bottom-nav {
            display: none;
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 100;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-top: 1px solid var(--u-border);
            padding: 0.5rem 0 calc(0.5rem + env(safe-area-inset-bottom));
            box-shadow: 0 -4px 20px rgba(0,0,0,0.07);
        }
        .u-bottom-nav-inner {
            display: flex; justify-content: space-around; align-items: center;
        }
        .u-bottom-nav-item {
            display: flex; flex-direction: column; align-items: center; gap: 0.2rem;
            padding: 0.4rem 1rem; border-radius: 0.5rem;
            color: var(--u-txt3); text-decoration: none;
            font-size: 0.65rem; font-weight: 600;
            transition: all 0.18s;
        }
        .u-bottom-nav-item.active { color: var(--u-accent); }
        .u-bottom-nav-item.active svg { stroke: var(--u-accent); }

        /* â•â•â• MOBILE HAMBURGER â•â•â• */
        .u-mobile-menu-btn {
            display: none;
            background: none; border: none; cursor: pointer;
            padding: 0.4rem; border-radius: 0.5rem;
            color: var(--u-txt2);
        }

        /* â•â•â• ANIMATIONS â•â•â• */
        .u-animate { animation: uFadeUp 0.35s ease-out forwards; }
        .u-delay-1 { animation-delay: 0.07s; opacity: 0; }
        .u-delay-2 { animation-delay: 0.14s; opacity: 0; }
        .u-delay-3 { animation-delay: 0.21s; opacity: 0; }
        .u-delay-4 { animation-delay: 0.28s; opacity: 0; }
        @keyframes uFadeUp {
            from { opacity:0; transform:translateY(14px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* Hint */
        .u-hint { font-size: 0.72rem; color: var(--u-txt3); margin-top: 0.25rem; }
        .hint    { font-size: 0.72rem; color: var(--u-txt3); margin-top: 0.25rem; }

        /* â•â•â• COMPATIBILITY ALIASES (agar komponen share bisa pakai di kedua layout) â•â•â• */
        .form-input  { width:100%;padding:0.7rem 1rem;border:1.5px solid var(--u-border);border-radius:0.625rem;font-size:0.875rem;background:#fff;color:var(--u-txt1);transition:all 0.2s; }
        .form-input:focus { outline:none;border-color:var(--u-accent);box-shadow:0 0 0 3px rgba(99,102,241,0.15); }
        .form-label  { display:block;font-size:0.8rem;font-weight:700;color:var(--u-txt2);margin-bottom:0.375rem; }
        .glass-card  { background:#fff;border:1px solid var(--u-border);border-radius:1rem;overflow:hidden;box-shadow:0 2px 12px var(--u-shadow-lg); }
        .stat-card   { background:#fff;border:1px solid var(--u-border);border-radius:1rem;padding:1.5rem;transition:all 0.2s; }
        .stat-card:hover { transform:translateY(-2px);box-shadow:0 8px 24px var(--u-shadow); }
        .btn         { display:inline-flex;align-items:center;gap:0.5rem;padding:0.6rem 1.25rem;border-radius:0.625rem;font-size:0.875rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all 0.2s; }
        .btn-primary { background:linear-gradient(135deg,#4f46e5,#6366f1);color:#fff;box-shadow:0 4px 12px rgba(79,70,229,0.25); }
        .btn-primary:hover { transform:translateY(-1px);box-shadow:0 6px 20px rgba(79,70,229,0.35); }
        .btn-success { background:linear-gradient(135deg,#059669,#10b981);color:#fff; }
        .btn-success:hover { transform:translateY(-1px); }
        .btn-danger  { background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff; }
        .btn-warning { background:linear-gradient(135deg,#d97706,#f59e0b);color:#fff; }
        .btn-outline { background:transparent;border:1.5px solid var(--u-border);color:var(--u-txt2); }
        .btn-outline:hover { background:#f8fafc;border-color:var(--u-accent);color:var(--u-accent); }
        .btn-sm      { padding:0.375rem 0.875rem;font-size:0.78rem;border-radius:0.5rem; }
        .badge           { display:inline-flex;align-items:center;padding:0.28rem 0.7rem;border-radius:9999px;font-size:0.72rem;font-weight:700; }
        .badge-amber     { background:#fef3c7;color:#92400e; }
        .badge-blue      { background:#dbeafe;color:#1e40af; }
        .badge-indigo    { background:#e0e7ff;color:#3730a3; }
        .badge-cyan      { background:#cffafe;color:#155e75; }
        .badge-emerald   { background:#d1fae5;color:#065f46; }
        .badge-green     { background:#dcfce7;color:#166534; }
        .badge-red       { background:#fee2e2;color:#991b1b; }
        .badge-orange    { background:#ffedd5;color:#9a3412; }
        .badge-rose      { background:#ffe4e6;color:#9f1239; }
        .badge-gray      { background:#f1f5f9;color:#475569; }
        .data-table      { width:100%;border-collapse:separate;border-spacing:0; }
        .data-table thead th { background:#f8fafc;padding:0.75rem 1rem;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--u-txt3);text-align:left;border-bottom:1px solid var(--u-border); }
        .data-table tbody td { padding:0.875rem 1rem;font-size:0.875rem;color:var(--u-txt2);border-bottom:1px solid #f8fafc; }
        .data-table tbody tr:hover { background:#fafbff; }
        .animate-in      { animation:uFadeUp 0.35s ease-out forwards; }
        .animate-delay-1 { animation-delay:0.07s;opacity:0; }
        .animate-delay-2 { animation-delay:0.14s;opacity:0; }
        .animate-delay-3 { animation-delay:0.21s;opacity:0; }
        .animate-delay-4 { animation-delay:0.28s;opacity:0; }

        /* â•â•â• RESPONSIVE â•â•â• */
        @media (max-width: 768px) {
            .u-hero { padding: 1.75rem 1.25rem; }
            .u-hero-title { font-size: 1.35rem; }
            .u-content { padding: 1.25rem 1rem; }
            .u-nav-links { display: none; }
            .u-mobile-menu-btn { display: flex; }
            .u-bottom-nav { display: block; }
            .u-content { padding-bottom: 5rem; }
        }
        @media (max-width: 480px) {
            .u-hero-stats { gap: 0.5rem; }
            .u-hero-stat { min-width: 90px; padding: 0.625rem 0.75rem; }
            .u-hero-stat-num { font-size: 1.25rem; }
        }
    </style>
</head>
<body>

        <div id="lp-bar" style="position:fixed;top:0;left:0;z-index:999999;height:3px;width:0%;background:linear-gradient(90deg,#6366f1,#8b5cf6,#a78bfa);box-shadow:0 0 10px rgba(99,102,241,0.6),0 0 20px rgba(99,102,241,0.3);transition:width 0.25s cubic-bezier(0.4,0,0.2,1),opacity 0.3s ease;opacity:0;pointer-events:none;border-radius:0 2px 2px 0;"></div>
        <div id="page-overlay" style="position:fixed;inset:0;z-index:99998;background:#f0f4ff;opacity:0;pointer-events:none;transition:opacity 0.18s ease;"></div>

        <script>
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
                '.rpl{position:absolute;border-radius:50%;transform:scale(0);animation:rplAnim 0.55s linear;pointer-events:none;background:rgba(255,255,255,0.3)}'+
                '@keyframes rplAnim{to{transform:scale(4);opacity:0}}'+
                '.btn:active,.u-btn:active{transform:scale(0.96)!important;transition:transform 0.08s ease!important}'+
                '.u-card,.stat-card,.glass-card{transition:transform 0.2s cubic-bezier(0.22,1,0.36,1),box-shadow 0.2s ease!important}'+
                '.form-input,.u-form-input{transition:border-color 0.18s ease,box-shadow 0.18s ease!important}'+
                '.badge,.u-badge{transition:transform 0.15s ease!important}'+
                '.badge:hover,.u-badge:hover{transform:scale(1.08)}'+
                '.alert,.u-alert{animation:alertSlide 0.32s cubic-bezier(0.22,1,0.36,1) both!important}'+
                '@keyframes alertSlide{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}'+
                '.u-nav-link{transition:all 0.18s cubic-bezier(0.22,1,0.36,1)!important}'+
                '.u-nav{transition:box-shadow 0.25s ease!important}'+
                '.u-table tbody tr{transition:background-color 0.1s ease!important}'+
                '#stb2{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9000;width:42px;height:42px;border-radius:50%;border:none;cursor:pointer;background:linear-gradient(135deg,#4f46e5,#6366f1);color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(79,70,229,0.35);opacity:0;transform:translateY(12px) scale(0.85);transition:opacity 0.25s ease,transform 0.25s cubic-bezier(0.22,1,0.36,1),box-shadow 0.2s ease;pointer-events:none}'+
                '#stb2.vis{opacity:1!important;transform:translateY(0) scale(1)!important;pointer-events:all!important}'+
                '#stb2:hover{transform:translateY(-3px) scale(1.1)!important;box-shadow:0 10px 28px rgba(79,70,229,0.5)!important}'+
                '@media(max-width:768px){#stb2{bottom:5.5rem}}';
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

            var sb=document.createElement('button');sb.id='stb2';
            sb.innerHTML='<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>';
            sb.setAttribute('aria-label','Scroll to top');sb.setAttribute('title','Kembali ke atas');
            document.body.appendChild(sb);
            function chk(){var t=window.scrollY||document.documentElement.scrollTop;t>250?sb.classList.add('vis'):sb.classList.remove('vis');}
            window.addEventListener('scroll',chk,{passive:true});
            sb.addEventListener('click',function(){window.scrollTo({top:0,behavior:'smooth'});});
        });
        </script>


    {{-- â•â•â• TOP NAVIGATION â•â•â• --}}
    <nav class="u-nav">
        <div class="u-nav-inner">
            {{-- Brand --}}
            <a href="{{ route('dashboard') }}" class="u-nav-brand">
                <div class="u-nav-brand-icon">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <div>
                    <div class="u-nav-brand-text">LabManager</div>
                    <div class="u-nav-brand-sub">Portal Mahasiswa</div>
                </div>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="u-nav-links">
                <a href="{{ route('dashboard') }}" class="u-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Beranda
                </a>
                <a href="{{ route('catalog') }}" class="u-nav-link {{ request()->routeIs('catalog') ? 'active' : '' }}">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Katalog Alat
                </a>
                <a href="{{ route('borrowings.create') }}" class="u-nav-link {{ request()->routeIs('borrowings.create') ? 'active' : '' }}">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Pinjam Alat
                </a>
                <a href="{{ route('borrowings.index') }}" class="u-nav-link {{ request()->routeIs('borrowings.index') ? 'active' : '' }}">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Riwayat
                </a>
            </div>

            {{-- Right: Notif + Avatar --}}
            <div style="display:flex;align-items:center;gap:0.75rem;">
                {{-- Notifications --}}
                @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
                <a href="{{ route('notifications.index') }}" style="position:relative;display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:0.5rem;border:1.5px solid var(--u-border);color:var(--u-txt2);text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($unreadCount > 0)
                        <span class="u-notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </a>

                {{-- Avatar Dropdown --}}
                <div class="u-dropdown" id="avatarDropdown">
                    <div class="u-avatar" onclick="toggleDropdown()" title="{{ auth()->user()->name }}">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="u-dropdown-menu" id="dropdownMenu">
                        <div style="padding:0.625rem 0.875rem;border-bottom:1px solid var(--u-border);margin-bottom:0.25rem;">
                            <p style="font-size:0.85rem;font-weight:700;color:var(--u-txt1);">{{ auth()->user()->name }}</p>
                            <p style="font-size:0.72rem;color:var(--u-txt3);">{{ auth()->user()->email }}</p>
                            <span style="display:inline-block;margin-top:0.3rem;padding:0.2rem 0.5rem;border-radius:4px;background:#e0e7ff;color:#3730a3;font-size:0.65rem;font-weight:700;">MAHASISWA</span>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="u-dropdown-item">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>
                        <a href="{{ route('notifications.index') }}" class="u-dropdown-item">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            Notifikasi
                            @if($unreadCount > 0)
                                <span style="margin-left:auto;background:#ef4444;color:#fff;border-radius:9999px;padding:0.1rem 0.4rem;font-size:0.65rem;font-weight:700;">{{ $unreadCount }}</span>
                            @endif
                        </a>
                        <div class="u-dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="u-dropdown-item danger">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Mobile hamburger --}}
                <button class="u-mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu Drawer --}}
        <div id="mobileMenu" style="display:none;border-top:1px solid var(--u-border);padding:0.75rem 1.5rem;">
            <a href="{{ route('dashboard') }}" class="u-dropdown-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="margin-bottom:0.2rem;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>
            <a href="{{ route('catalog') }}" class="u-dropdown-item" style="margin-bottom:0.2rem;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Katalog Alat
            </a>
            <a href="{{ route('borrowings.create') }}" class="u-dropdown-item" style="margin-bottom:0.2rem;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Pinjam Alat
            </a>
            <a href="{{ route('borrowings.index') }}" class="u-dropdown-item">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Riwayat Peminjaman
            </a>
        </div>
    </nav>

    {{-- â•â•â• HERO BANNER â•â•â• --}}
    @hasSection('no_hero')
    @else
    <div class="u-hero">
        <div class="u-hero-inner">
            <div class="u-hero-greeting">Selamat datang</div>
            <h1 class="u-hero-title">{{ auth()->user()->name }} ðŸ‘‹</h1>
            <p class="u-hero-sub">@yield('hero_sub', 'Apa yang ingin Anda pinjam hari ini?')</p>

            @hasSection('hero_stats')
                <div class="u-hero-stats">@yield('hero_stats')</div>
            @endif
        </div>
    </div>
    @endif

    {{-- â•â•â• MAIN CONTENT â•â•â• --}}
    <div class="u-content">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="u-alert u-alert-success u-animate">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="u-alert u-alert-error u-animate">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    {{-- â•â•â• BOTTOM NAV (Mobile) â•â•â• --}}
    <nav class="u-bottom-nav">
        <div class="u-bottom-nav-inner">
            <a href="{{ route('dashboard') }}" class="u-bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>
            <a href="{{ route('catalog') }}" class="u-bottom-nav-item {{ request()->routeIs('catalog') ? 'active' : '' }}">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Katalog
            </a>
            <a href="{{ route('borrowings.create') }}" class="u-bottom-nav-item {{ request()->routeIs('borrowings.create') ? 'active' : '' }}" style="position:relative;">
                <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#8b5cf6);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(79,70,229,0.4);margin-top:-16px;">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span style="margin-top:2px;">Pinjam</span>
            </a>
            <a href="{{ route('borrowings.index') }}" class="u-bottom-nav-item {{ request()->routeIs('borrowings.index') ? 'active' : '' }}">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Riwayat
            </a>
            <a href="{{ route('profile.edit') }}" class="u-bottom-nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil
            </a>
        </div>
    </nav>

    <script>
        // Avatar dropdown
        function toggleDropdown() {
            document.getElementById('dropdownMenu').classList.toggle('open');
        }
        document.addEventListener('click', function(e) {
            const d = document.getElementById('avatarDropdown');
            if (d && !d.contains(e.target)) {
                document.getElementById('dropdownMenu').classList.remove('open');
            }
        });

        // Mobile menu
        document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
            const m = document.getElementById('mobileMenu');
            m.style.display = m.style.display === 'none' ? 'block' : 'none';
        });
    </script>

    @stack('scripts')

    {{-- Reuse Global Confirm Modal dari app.blade.php --}}
    <div id="globalConfirmModal" aria-modal="true" role="dialog" style="display:none;position:fixed;inset:0;z-index:99999;align-items:center;justify-content:center;padding:1rem;">
        <div id="gcmBackdrop" style="position:absolute;inset:0;background:rgba(2,6,23,0.65);backdrop-filter:blur(6px);animation:gcmFadeIn 0.2s ease forwards;"></div>
        <div id="gcmCard" style="position:relative;z-index:1;background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;width:100%;max-width:420px;box-shadow:0 32px 80px rgba(0,0,0,0.25);animation:gcmSlideIn 0.3s cubic-bezier(0.22,1,0.36,1) forwards;overflow:hidden;">
            <div id="gcmAccentBar" style="height:4px;width:100%;background:linear-gradient(90deg,#4f46e5,#6366f1);"></div>
            <div style="padding:1.75rem 1.75rem 1.5rem;">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
                    <div id="gcmIconWrap" style="width:48px;height:48px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);">
                        <span id="gcmIcon" style="font-size:1.4rem;line-height:1;">âš¡</span>
                    </div>
                    <div>
                        <h3 id="gcmTitle" style="font-size:1.05rem;font-weight:800;color:#0f172a;margin:0;line-height:1.3;">Konfirmasi Aksi</h3>
                        <p id="gcmSubtitle" style="font-size:0.72rem;color:#94a3b8;margin:0.15rem 0 0;"></p>
                    </div>
                </div>
                <p id="gcmMessage" style="font-size:0.9rem;color:#475569;line-height:1.6;background:#f8fafc;border:1px solid #e2e8f0;border-radius:0.6rem;padding:0.75rem 1rem;margin-bottom:1.5rem;">Apakah Anda yakin?</p>
                <div style="display:flex;gap:0.625rem;justify-content:flex-end;">
                    <button id="gcmCancelBtn" style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.6rem 1.25rem;border-radius:0.5rem;background:transparent;border:1px solid #e2e8f0;color:#64748b;font-size:0.875rem;font-weight:600;cursor:pointer;transition:all 0.18s ease;">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </button>
                    <button id="gcmConfirmBtn" style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.6rem 1.4rem;border-radius:0.5rem;background:linear-gradient(135deg,#4f46e5,#6366f1);border:none;color:#fff;font-size:0.875rem;font-weight:700;cursor:pointer;transition:all 0.18s ease;box-shadow:0 4px 12px rgba(79,70,229,0.3);">
                        <svg id="gcmConfirmIcon" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span id="gcmConfirmLabel">Ya, Lanjutkan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <style>
        @keyframes gcmFadeIn { from{opacity:0} to{opacity:1} }
        @keyframes gcmSlideIn { from{opacity:0;transform:scale(0.88) translateY(20px)} to{opacity:1;transform:scale(1) translateY(0)} }
        @keyframes gcmSlideOut { from{opacity:1;transform:scale(1) translateY(0)} to{opacity:0;transform:scale(0.92) translateY(10px)} }
        #globalConfirmModal.gcm-closing #gcmCard { animation:gcmSlideOut 0.2s ease forwards; }
        #globalConfirmModal.gcm-closing #gcmBackdrop { animation:gcmFadeIn 0.2s ease reverse forwards; }
        #gcmConfirmBtn:hover { transform:translateY(-1px); filter:brightness(1.08); }
    </style>
    <script>
    (function() {
        const modal=document.getElementById('globalConfirmModal'),backdrop=document.getElementById('gcmBackdrop'),title=document.getElementById('gcmTitle'),subtitle=document.getElementById('gcmSubtitle'),message=document.getElementById('gcmMessage'),iconWrap=document.getElementById('gcmIconWrap'),icon=document.getElementById('gcmIcon'),accentBar=document.getElementById('gcmAccentBar'),confirmBtn=document.getElementById('gcmConfirmBtn'),cancelBtn=document.getElementById('gcmCancelBtn'),confirmLbl=document.getElementById('gcmConfirmLabel'),confirmIco=document.getElementById('gcmConfirmIcon');
        const themes={success:{bar:'linear-gradient(90deg,#059669,#10b981)',icon:'linear-gradient(135deg,#d1fae5,#a7f3d0)',btn:'linear-gradient(135deg,#059669,#10b981)',shadow:'rgba(16,185,129,0.35)',svg:'<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>'},danger:{bar:'linear-gradient(90deg,#dc2626,#ef4444)',icon:'linear-gradient(135deg,#fee2e2,#fca5a5)',btn:'linear-gradient(135deg,#dc2626,#ef4444)',shadow:'rgba(239,68,68,0.35)',svg:'<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>'},warning:{bar:'linear-gradient(90deg,#d97706,#f59e0b)',icon:'linear-gradient(135deg,#fef3c7,#fde68a)',btn:'linear-gradient(135deg,#d97706,#f59e0b)',shadow:'rgba(245,158,11,0.35)',svg:'<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'},info:{bar:'linear-gradient(90deg,#4f46e5,#6366f1)',icon:'linear-gradient(135deg,#e0e7ff,#c7d2fe)',btn:'linear-gradient(135deg,#4f46e5,#6366f1)',shadow:'rgba(79,70,229,0.35)',svg:'<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'}};
        let _cb=null;
        function closeModal(){modal.classList.add('gcm-closing');setTimeout(()=>{modal.style.display='none';modal.classList.remove('gcm-closing');document.body.style.overflow='';_cb=null;},200);}
        window.showConfirm=function(opts){const t=themes[opts.type]||themes.info;accentBar.style.background=t.bar;iconWrap.style.background=t.icon;confirmBtn.style.background=t.btn;confirmBtn.style.boxShadow='0 4px 12px '+t.shadow;confirmIco.innerHTML=t.svg;title.textContent=opts.title||'Konfirmasi';subtitle.textContent=opts.subtitle||'';subtitle.style.display=opts.subtitle?'block':'none';message.textContent=opts.message||'Apakah Anda yakin?';icon.textContent=opts.icon||'âš¡';confirmLbl.textContent=opts.confirmLabel||'Ya, Lanjutkan';_cb=opts.onConfirm||null;modal.style.display='flex';document.body.style.overflow='hidden';setTimeout(()=>confirmBtn.focus(),310);};
        confirmBtn.addEventListener('click',()=>{closeModal();if(typeof _cb==='function')setTimeout(_cb,60);});
        cancelBtn.addEventListener('click',closeModal);
        backdrop.addEventListener('click',closeModal);
        document.addEventListener('keydown',(e)=>{if(e.key==='Escape'&&modal.style.display==='flex')closeModal();if(e.key==='Enter'&&modal.style.display==='flex')confirmBtn.click();});
        window.submitWithConfirm=function(formEl,opts){opts.onConfirm=()=>formEl.submit();window.showConfirm(opts);};
    })();
    </script>
</body>
</html>

