<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRecipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'inventory_item_id',
        'quantity_used',
    ];

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
