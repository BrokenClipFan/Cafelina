<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class PurchaseItem extends Model
{
    protected $fillable = [
    'purchase_id',
    'name',
    'category',
    'price',
    'count'
    ];

    public function purchase() {
        return $this->belongTo(Purchase::class);
    }
}
