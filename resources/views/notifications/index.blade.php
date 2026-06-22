@extends('layouts.mahasiswa')
@section('title', 'Notifikasi')
@section('no_hero', true)

@section('content')
<div class="animate-in">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.75rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:var(--u-txt1);">Notifikasi</h1>
            <p style="color:var(--u-txt2);font-size:0.875rem;margin-top:0.25rem;">Semua aktivitas dan pembaruan terkait akun Anda.</p>
        </div>
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

    <div class="glass-card animate-in animate-delay-1">
        @forelse($notifications as $notif)
            @php
                $colors = [
                    'success' => ['bg' => '#d1fae5', 'color' => '#065f46', 'border' => '#a7f3d0', 'icon' => '✅'],
                    'danger'  => ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fca5a5', 'icon' => '❌'],
                    'warning' => ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => '⚠️'],
                    'info'    => ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'ℹ️'],
                ];
                $c = $colors[$notif->type] ?? $colors['info'];
            @endphp
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--u-border);display:flex;gap:1rem;align-items:flex-start;{{ $notif->isRead() ? 'opacity:0.65;' : '' }}">
                <div style="background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }};border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
                    {{ $c['icon'] }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.5rem;flex-wrap:wrap;">
                        <p style="font-size:0.875rem;font-weight:{{ $notif->isRead() ? '400' : '700' }};color:var(--u-txt1);">{{ $notif->title }}</p>
                        <span style="font-size:0.7rem;color:var(--u-txt3);white-space:nowrap;">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                    <p style="font-size:0.8rem;color:var(--u-txt2);margin-top:0.25rem;line-height:1.5;">{{ $notif->message }}</p>
                    @if($notif->link)
                        <a href="{{ $notif->link }}" style="font-size:0.75rem;color:#6366f1;text-decoration:none;font-weight:600;margin-top:0.375rem;display:inline-block;">
                            Lihat Detail →
                        </a>
                    @endif
                </div>
                @if(!$notif->isRead())
                    <div style="width:8px;height:8px;border-radius:50%;background:#6366f1;flex-shrink:0;margin-top:4px;"></div>
                @endif
            </div>
        @empty
            <div style="padding:3rem;text-align:center;">
                <div style="font-size:2.5rem;margin-bottom:1rem;">🔔</div>
                <p style="color:var(--u-txt2);font-size:0.875rem;">Belum ada notifikasi.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top:1rem;">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
