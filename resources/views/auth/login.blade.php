<x-guest-layout>
    <div style="text-align:center;margin-bottom:2rem;">
        <div style="width:56px;height:56px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
        </div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#f1f5f9;">LabManager</h1>
        <p style="color:#64748b;font-size:0.85rem;margin-top:0.25rem;">Sistem Manajemen Peminjaman Lab</p>
    </div>

    <div style="background:rgba(255,255,255,0.05);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.1);border-radius:1rem;padding:2rem;">
        @if(session('status'))
            <div style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);border-radius:0.5rem;padding:0.75rem;margin-bottom:1rem;color:#34d399;font-size:0.85rem;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="margin-bottom:1.25rem;">
                <label for="email" style="display:block;font-size:0.8rem;font-weight:600;color:#94a3b8;margin-bottom:0.375rem;">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    style="width:100%;padding:0.625rem 0.875rem;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;transition:border 0.2s;"
                    onfocus="this.style.borderColor='#818cf8';this.style.boxShadow='0 0 0 3px rgba(129,140,248,0.2)'"
                    onblur="this.style.borderColor='rgba(255,255,255,0.15)';this.style.boxShadow='none'"
                    placeholder="nama@email.com">
                @error('email')
                    <p style="color:#f87171;font-size:0.75rem;margin-top:0.375rem;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom:1.25rem;">
                <label for="password" style="display:block;font-size:0.8rem;font-weight:600;color:#94a3b8;margin-bottom:0.375rem;">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    style="width:100%;padding:0.625rem 0.875rem;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;transition:border 0.2s;"
                    onfocus="this.style.borderColor='#818cf8';this.style.boxShadow='0 0 0 3px rgba(129,140,248,0.2)'"
                    onblur="this.style.borderColor='rgba(255,255,255,0.15)';this.style.boxShadow='none'"
                    placeholder="••••••••">
                @error('password')
                    <p style="color:#f87171;font-size:0.75rem;margin-top:0.375rem;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:flex;align-items:center;margin-bottom:1.5rem;">
                <input id="remember_me" type="checkbox" name="remember"
                    style="width:16px;height:16px;border-radius:4px;accent-color:#6366f1;cursor:pointer;">
                <label for="remember_me" style="margin-left:0.5rem;font-size:0.8rem;color:#94a3b8;cursor:pointer;">Ingat saya</label>
            </div>

            <button type="submit"
                style="width:100%;padding:0.75rem;background:linear-gradient(135deg,#4f46e5,#6366f1);color:#fff;border:none;border-radius:0.5rem;font-size:0.875rem;font-weight:700;cursor:pointer;transition:all 0.2s;"
                onmouseover="this.style.background='linear-gradient(135deg,#4338ca,#4f46e5)';this.style.boxShadow='0 4px 16px rgba(79,70,229,0.4)';this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='linear-gradient(135deg,#4f46e5,#6366f1)';this.style.boxShadow='none';this.style.transform='none'">
                Masuk
            </button>
        </form>
    </div>

    <div style="margin-top:1.5rem;text-align:center;">
        <p style="color:#475569;font-size:0.75rem;">Demo Accounts:</p>
        <div style="display:grid;gap:0.375rem;margin-top:0.5rem;">
            <p style="color:#64748b;font-size:0.7rem;">📚 mahasiswa@lab.test &nbsp;|&nbsp; 🔬 laboran@lab.test &nbsp;|&nbsp; 🏛️ kepalalab@lab.test</p>
            <p style="color:#475569;font-size:0.7rem;">Password: <code style="background:rgba(255,255,255,0.08);padding:0.15rem 0.4rem;border-radius:3px;color:#94a3b8;">password</code></p>
        </div>
    </div>
</x-guest-layout>
