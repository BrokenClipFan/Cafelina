<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseItem;

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

    public function items() {
        return $this->hasMany(PurchaseItem::class, 'purchase_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id');
    }
}
