<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Handle registrasi akun mahasiswa baru.
     * Role selalu 'mahasiswa' — tidak dapat diubah oleh user.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]+$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar. Silakan gunakan email lain.',
            'phone.required'    => 'Nomor telepon wajib diisi.',
            'phone.regex'       => 'Format nomor telepon tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa', // selalu mahasiswa
        ]);

        return redirect()->route('login')
            ->with('status', 'Akun berhasil dibuat! Silakan login dengan kredensial Anda.');
    }
}
