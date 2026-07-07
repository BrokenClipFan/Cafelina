<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift_date',
        'start_time',
        'end_time',
        'role',
    ];

    protected $casts = [
        'shift_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: a user's upcoming shifts, soonest first.
     */
    public function scopeUpcomingFor(Builder $query, int $userId, int $limit = 5): Builder
    {
        return $query->where('user_id', $userId)
            ->whereDate('shift_date', '>=', now()->toDateString())
            ->orderBy('shift_date')
            ->orderBy('start_time')
            ->limit($limit);
    }
}