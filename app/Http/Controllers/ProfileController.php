<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan form edit profil.
     */
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    /**
     * Update nama dan email profil.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]+$/'],
        ], [
            'phone.regex' => 'Format nomor telepon tidak valid.',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        // Jika email berubah, reset verifikasi agar user harus verifikasi ulang
        if ($request->email !== $user->email) {
            $data['email_verified_at'] = null;
        }

        $user->update($data);

        // Jika email berubah, user perlu verifikasi ulang → redirect ke notice
        if (isset($data['email_verified_at'])) {
            return redirect()->route('verification.notice')
                ->with('status', 'Email diperbarui. Silakan verifikasi alamat email baru Anda.');
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Logout setelah ganti password untuk keamanan
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'Password berhasil diubah. Silakan login kembali dengan password baru.');
    }
}
