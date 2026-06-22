<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'equipment_id',
        'start_date',
        'end_date',
        'purpose',
        'status',
        'return_condition',
        'reject_reason',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'string',  // format HH:MM (waktu dalam sehari)
            'end_date'   => 'string',  // format HH:MM (waktu dalam sehari)
        ];
    }

    // ---- Relationships ----

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function logs()
    {
        return $this->hasMany(BorrowingLog::class);
    }

    // ---- Status Helpers ----

    public function getStatusLabelAttribute(): string
    {
        // Jika status rejected tapi dibatalkan oleh peminjam sendiri,
        // tampilkan "Dibatalkan" bukan "Ditolak" untuk kejelasan UX
        if ($this->status === 'rejected' && $this->reject_reason === 'Dibatalkan oleh peminjam.') {
            return 'Dibatalkan';
        }

        return match ($this->status) {
            'pending'                 => 'Menunggu Persetujuan Laboran',
            'approved_by_laboran'     => 'Menunggu Persetujuan Kepala Lab',
            'approved_by_kepala_lab'  => 'Siap Diambil',
            'ready_for_pickup'        => 'Siap Diambil',
            'active'                  => 'Sedang Dipinjam',
            'completed'               => 'Selesai',
            'rejected'                => 'Ditolak',
            'overdue'                 => 'Terlambat Dikembalikan',
            'issue_reported'          => 'Ada Masalah',
            default                   => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        // Warna abu-abu untuk peminjaman yang dibatalkan sendiri
        if ($this->status === 'rejected' && $this->reject_reason === 'Dibatalkan oleh peminjam.') {
            return 'gray';
        }

        return match ($this->status) {
            'pending'                 => 'amber',
            'approved_by_laboran'     => 'blue',
            'approved_by_kepala_lab'  => 'indigo',
            'ready_for_pickup'        => 'cyan',
            'active'                  => 'emerald',
            'completed'               => 'green',
            'rejected'                => 'red',
            'overdue'                 => 'orange',
            'issue_reported'          => 'rose',
            default                   => 'gray',
        };
    }
}
