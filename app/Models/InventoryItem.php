<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'current_stock',
        'unit',
        'price_per_unit',
        'max_stock',
    ];

    protected $appends = ['status'];

    public function logs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function getStatusAttribute()
    {
        if ($this->current_stock <= 0) {
            return 'Out of Stock';
        }

        if ($this->max_stock > 0) {
            $threshold = $this->max_stock * 0.25;
            if ($this->current_stock <= $threshold) {
                return 'Low Stock';
            }
        }

        return 'In Stock';
    }
}
