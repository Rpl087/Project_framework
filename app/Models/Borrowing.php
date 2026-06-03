<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

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
        return match ($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved_by_laboran' => 'Disetujui Laboran',
            'approved_by_kepala_lab' => 'Disetujui Kepala Lab',
            'ready_for_pickup' => 'Siap Diambil',
            'active' => 'Sedang Dipinjam',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            'overdue' => 'Terlambat',
            'issue_reported' => 'Ada Masalah',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'approved_by_laboran' => 'blue',
            'approved_by_kepala_lab' => 'indigo',
            'ready_for_pickup' => 'cyan',
            'active' => 'emerald',
            'completed' => 'green',
            'rejected' => 'red',
            'overdue' => 'orange',
            'issue_reported' => 'rose',
            default => 'gray',
        };
    }
}
