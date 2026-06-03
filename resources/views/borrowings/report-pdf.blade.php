<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman — LabManager</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #0f172a; }
        .header { background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; padding: 16px 20px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
        .header p { font-size: 10px; opacity: 0.8; }
        .meta { background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 16px; margin-bottom: 16px; border-radius: 4px; }
        .meta p { font-size: 10px; color: #475569; margin-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead tr { background: #0f172a; color: #fff; }
        thead th { padding: 8px 10px; font-size: 10px; font-weight: 600; text-align: left; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody td { padding: 7px 10px; font-size: 10px; color: #334155; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 20px; font-size: 9px; font-weight: 600; }
        .badge-completed { background: #d1fae5; color: #065f46; }
        .badge-active { background: #dbeafe; color: #1e40af; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-overdue { background: #fef3c7; color: #92400e; }
        .badge-other { background: #f1f5f9; color: #475569; }
        .stats { display: flex; gap: 12px; margin-bottom: 16px; }
        .stat-box { flex: 1; border: 1px solid #e2e8f0; border-radius: 4px; padding: 10px; text-align: center; }
        .stat-box .num { font-size: 20px; font-weight: 700; color: #4f46e5; }
        .stat-box .lbl { font-size: 9px; color: #64748b; margin-top: 2px; }
        .footer { margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 9px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Laporan Data Peminjaman</h1>
        <p>LabManager — Sistem Manajemen Peminjaman Alat Lab IT</p>
        <p>Dicetak: {{ now()->format('d F Y, H:i') }} WIB &nbsp;|&nbsp; Oleh: {{ auth()->user()->name }}</p>
    </div>

    @php
        $total     = $borrowings->count();
        $completed = $borrowings->where('status', 'completed')->count();
        $active    = $borrowings->whereIn('status', ['active', 'ready_for_pickup'])->count();
        $rejected  = $borrowings->where('status', 'rejected')->count();
        $overdue   = $borrowings->where('status', 'overdue')->count();
    @endphp

    <table style="width:100%;border-collapse:collapse;margin-bottom:16px;">
        <tr>
            <td style="text-align:center;padding:10px;border:1px solid #e2e8f0;">
                <div style="font-size:20px;font-weight:700;color:#4f46e5;">{{ $total }}</div>
                <div style="font-size:9px;color:#64748b;">Total</div>
            </td>
            <td style="text-align:center;padding:10px;border:1px solid #e2e8f0;">
                <div style="font-size:20px;font-weight:700;color:#10b981;">{{ $completed }}</div>
                <div style="font-size:9px;color:#64748b;">Selesai</div>
            </td>
            <td style="text-align:center;padding:10px;border:1px solid #e2e8f0;">
                <div style="font-size:20px;font-weight:700;color:#3b82f6;">{{ $active }}</div>
                <div style="font-size:9px;color:#64748b;">Aktif</div>
            </td>
            <td style="text-align:center;padding:10px;border:1px solid #e2e8f0;">
                <div style="font-size:20px;font-weight:700;color:#f59e0b;">{{ $overdue }}</div>
                <div style="font-size:9px;color:#64748b;">Terlambat</div>
            </td>
            <td style="text-align:center;padding:10px;border:1px solid #e2e8f0;">
                <div style="font-size:20px;font-weight:700;color:#ef4444;">{{ $rejected }}</div>
                <div style="font-size:9px;color:#64748b;">Ditolak</div>
            </td>
        </tr>
    </table>

    @if(isset($filterInfo))
    <div class="meta">
        <p><strong>Filter aktif:</strong> {{ $filterInfo }}</p>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Kategori</th>
                <th>Waktu Pinjam</th>
                <th>Waktu Kembali</th>
                <th>Tanggal Ajuan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $i => $b)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $b->user->name }}</td>
                <td>{{ $b->equipment->name }}</td>
                <td>{{ ucfirst($b->equipment->category) }}</td>
                <td>{{ $b->start_date }}</td>
                <td>{{ $b->end_date }}</td>
                <td>{{ $b->created_at->format('d/m/Y') }}</td>
                <td>
                    @php
                        $badgeMap = [
                            'completed' => 'completed',
                            'active'    => 'active',
                            'rejected'  => 'rejected',
                            'overdue'   => 'overdue',
                        ];
                        $cls = $badgeMap[$b->status] ?? 'other';
                    @endphp
                    <span class="badge badge-{{ $cls }}">{{ $b->status_label }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:20px;color:#94a3b8;">Tidak ada data peminjaman.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        LabManager — Sistem Peminjaman Alat Lab IT &nbsp;|&nbsp; Laporan ini dibuat secara otomatis
    </div>
</body>
</html>
