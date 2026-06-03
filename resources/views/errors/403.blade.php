<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — Akses Ditolak | LabManager</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; } body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); margin: 0; }</style>
</head>
<body>
    <div style="text-align:center;max-width:400px;padding:2rem;">
        <div style="font-size:4rem;margin-bottom:1rem;">🚫</div>
        <h1 style="font-size:2rem;font-weight:800;color:#f1f5f9;margin-bottom:0.5rem;">403</h1>
        <h2 style="font-size:1.1rem;font-weight:600;color:#94a3b8;margin-bottom:0.75rem;">Akses Ditolak</h2>
        <p style="color:#64748b;font-size:0.875rem;line-height:1.6;margin-bottom:1.5rem;">
            Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
        </p>
        <a href="javascript:history.back()" style="display:inline-block;padding:0.625rem 1.5rem;background:linear-gradient(135deg,#4f46e5,#6366f1);color:#fff;text-decoration:none;border-radius:0.5rem;font-weight:600;font-size:0.875rem;margin-right:0.5rem;">
            ← Kembali
        </a>
        <a href="{{ url('/dashboard') }}" style="display:inline-block;padding:0.625rem 1.5rem;border:1px solid #334155;color:#94a3b8;text-decoration:none;border-radius:0.5rem;font-weight:600;font-size:0.875rem;">
            Dashboard
        </a>
    </div>
</body>
</html>
