<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
    'purchase_id',
    'name',
    'category',
    'price',
    'count'
    ];
}
