@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')
<div class="animate-in">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.75rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:var(--txt-1);">Notifikasi</h1>
            <p style="color:var(--txt-2);font-size:0.875rem;margin-top:0.25rem;">Semua aktivitas dan pembaruan terkait akun Anda.</p>
        </div>
        <div style="display:flex;align-items:center;gap:0.5rem;">
            @if($notifications->isNotEmpty())
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Stats Summary --}}
    @php
        $totalCount = $notifications->total();
        $unreadCount = auth()->user()->unreadNotificationsCount();
    @endphp
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:1rem;margin-bottom:1.5rem;" class="animate-in animate-delay-1">
        <div class="stat-card" style="display:flex;align-items:center;gap:1rem;">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);display:flex;align-items:center;justify-content:center;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#4f46e5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <div>
                <p style="font-size:0.72rem;font-weight:700;color:var(--txt-3);text-transform:uppercase;letter-spacing:0.05em;">Total</p>
                <p style="font-size:1.5rem;font-weight:900;color:var(--txt-1);line-height:1;">{{ $totalCount }}</p>
            </div>
        </div>
        <div class="stat-card" style="display:flex;align-items:center;gap:1rem;">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#fef3c7,#fde68a);display:flex;align-items:center;justify-content:center;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <div>
                <p style="font-size:0.72rem;font-weight:700;color:var(--txt-3);text-transform:uppercase;letter-spacing:0.05em;">Belum Dibaca</p>
                <p style="font-size:1.5rem;font-weight:900;color:var(--txt-1);line-height:1;">{{ $unreadCount }}</p>
            </div>
        </div>
    </div>

    {{-- Notification List --}}
    <div class="glass-card animate-in animate-delay-2">
        @forelse($notifications as $notif)
            @php
                $colors = [
                    'success' => ['bg' => '#d1fae5', 'color' => '#065f46', 'border' => '#a7f3d0', 'icon' => '✅', 'gradient' => 'linear-gradient(135deg,#d1fae5,#a7f3d0)'],
                    'danger'  => ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fca5a5', 'icon' => '❌', 'gradient' => 'linear-gradient(135deg,#fee2e2,#fca5a5)'],
                    'warning' => ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => '⚠️', 'gradient' => 'linear-gradient(135deg,#fef3c7,#fde68a)'],
                    'info'    => ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'ℹ️', 'gradient' => 'linear-gradient(135deg,#dbeafe,#bfdbfe)'],
                ];
                $c = $colors[$notif->type] ?? $colors['info'];
            @endphp
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-l);display:flex;gap:1rem;align-items:flex-start;transition:background 0.15s ease;{{ $notif->isRead() ? 'opacity:0.6;' : '' }}" onmouseover="this.style.background='var(--row-hover)'" onmouseout="this.style.background='transparent'">
                <div style="background:{{ $c['gradient'] }};border:1px solid {{ $c['border'] }};border-radius:12px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                    {{ $c['icon'] }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.5rem;flex-wrap:wrap;">
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <p style="font-size:0.875rem;font-weight:{{ $notif->isRead() ? '500' : '700' }};color:var(--txt-1);">{{ $notif->title }}</p>
                            @if(!$notif->isRead())
                                <span class="badge badge-indigo" style="font-size:0.6rem;padding:0.15rem 0.5rem;">BARU</span>
                            @endif
                        </div>
                        <span style="font-size:0.7rem;color:var(--txt-3);white-space:nowrap;">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                    <p style="font-size:0.8rem;color:var(--txt-2);margin-top:0.25rem;line-height:1.5;">{{ $notif->message }}</p>
                    @if($notif->link)
                        <a href="{{ $notif->link }}" style="font-size:0.75rem;color:#6366f1;text-decoration:none;font-weight:600;margin-top:0.375rem;display:inline-flex;align-items:center;gap:0.25rem;transition:gap 0.2s;" onmouseover="this.style.gap='0.5rem'" onmouseout="this.style.gap='0.25rem'">
                            Lihat Detail
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                </div>
                @if(!$notif->isRead())
                    <div style="width:8px;height:8px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#6366f1);flex-shrink:0;margin-top:6px;box-shadow:0 0 8px rgba(99,102,241,0.4);"></div>
                @endif
            </div>
        @empty
            <div style="padding:4rem 2rem;text-align:center;">
                <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <p style="font-size:1rem;font-weight:700;color:var(--txt-1);margin-bottom:0.25rem;">Belum ada notifikasi</p>
                <p style="color:var(--txt-3);font-size:0.8rem;">Notifikasi akan muncul saat ada aktivitas terkait peminjaman.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top:1rem;">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
