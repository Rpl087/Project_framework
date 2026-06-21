<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tentukan role yang boleh dikelola oleh role yang sedang login.
     * - Laboran  => hanya boleh manage mahasiswa
     * - Kepala Lab => hanya boleh manage laboran
     */
    private function getAllowedTargetRole(): string
    {
        return auth()->user()->role === 'laboran' ? 'mahasiswa' : 'laboran';
    }

    /**
     * Pastikan user target sesuai dengan hak akses role yang login.
     */
    private function authorizeTargetUser(User $user): void
    {
        $allowed = $this->getAllowedTargetRole();
        if ($user->role !== $allowed) {
            abort(403, 'Anda tidak memiliki izin untuk mengelola user dengan role tersebut.');
        }
    }

    /**
     * Daftar user yang sesuai dengan hak akses role yang login.
     * - Laboran  : hanya melihat daftar mahasiswa
     * - Kepala Lab : hanya melihat daftar laboran
     */
    public function index(Request $request)
    {
        $targetRole = $this->getAllowedTargetRole();

        $query = User::query()
            ->where('role', $targetRole)
            ->orderBy('name');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->withCount([
            'borrowings',
            'borrowings as active_borrowings_count' => fn($q) => $q->whereIn('status', ['active', 'overdue']),
        ])->paginate(15)->withQueryString();

        return view('users.index', compact('users', 'targetRole'));
    }

    /**
     * Form tambah user baru (role yang bisa ditambah sesuai hak akses).
     */
    public function create()
    {
        $targetRole = $this->getAllowedTargetRole();
        return view('users.create', compact('targetRole'));
    }

    /**
     * Simpan user baru (hanya role yang diizinkan).
     */
    public function store(Request $request)
    {
        $targetRole = $this->getAllowedTargetRole();

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]+$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in([$targetRole])],
        ], [
            'phone.regex' => 'Format nomor telepon tidak valid.',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => Hash::make($request->password),
            'role'              => $targetRole,
            // User yang dibuat oleh admin langsung aktif (tidak perlu verifikasi email)
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.index')
            ->with('success', "User \"{$user->name}\" berhasil ditambahkan.");
    }

    /**
     * Form edit user (hanya jika user target sesuai hak akses).
     */
    public function edit(User $user)
    {
        $this->authorizeTargetUser($user);
        $targetRole = $this->getAllowedTargetRole();
        return view('users.edit', compact('user', 'targetRole'));
    }

    /**
     * Update data user (hanya jika user target sesuai hak akses).
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeTargetUser($user);
        $targetRole = $this->getAllowedTargetRole();

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]+$/'],
        ], [
            'phone.regex' => 'Format nomor telepon tidak valid.',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role'  => $targetRole, // role tetap sesuai hak akses, tidak bisa diubah
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:8', 'confirmed'],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', "Data user \"{$user->name}\" berhasil diperbarui.");
    }

    /**
     * Hapus user (hanya jika user target sesuai hak akses).
     */
    public function destroy(User $user)
    {
        $this->authorizeTargetUser($user);

        // Tidak bisa menghapus user yang masih punya peminjaman aktif
        $activeCount = $user->borrowings()->whereIn('status', ['pending', 'approved_by_laboran', 'approved_by_kepala_lab', 'active', 'ready_for_pickup', 'overdue', 'issue_reported'])->count();
        if ($activeCount > 0) {
            return back()->with('error', "User \"{$user->name}\" masih memiliki {$activeCount} peminjaman aktif dan tidak dapat dihapus.");
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User \"{$name}\" berhasil dihapus.");
    }
}
