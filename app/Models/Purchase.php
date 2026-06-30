<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'status',
        'name'
    ];
}
