<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingLog extends Model
{
    protected $fillable = [
        'borrowing_id',
        'user_id',
        'action_description',
    ];

    // ---- Relationships ----

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
