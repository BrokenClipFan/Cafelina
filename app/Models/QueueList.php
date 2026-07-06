<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueList extends Model
{
    protected $fillable = [
        'purchase_id',
        'order_name',
        'status',
        'user_id'
    ];
}
