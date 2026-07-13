<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSchedule extends Model
{
    protected $fillable = ['user_id', 'days', 'start_time', 'end_time', 'station_role', 'status'];

    // Enforces array transformation implicitly
    protected $casts = [
        'days' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
