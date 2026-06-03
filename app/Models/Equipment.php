<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'description',
        'total_stock',
        'available_stock',
        'category',
        'status',
        'image',
    ];

    // ---- Relationships ----

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    // ---- Scopes ----

    public function scopeAvailable($query)
    {
        return $query->where('status', 'good')->where('available_stock', '>', 0);
    }
}
