<?php 

namespace App\Services\Admin; // Aligned namespace with the Controller's import

use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Setting; // Imported correctly

class SalesService 
{
    /**
     * Get gross revenue across all transactions.
     */
    public function getGrossRevenue() 
    {
        return (float) Purchase::sum('total');
    }

    /**
     * Get total subtotal collected before tax/discounts.
     */
    public function subTotalCollected() 
    {
        return (float) Purchase::sum('subtotal');
    }

    /**
     * Calculate accrued taxes based on a percentage value stored in Setting.
     */
    public function taxesAccrued() 
    {
        $taxSetting = Setting::where('name', 'tax')->first();
        
        $taxDecimal = $taxSetting ? (float) $taxSetting->value : 0.0;
        
        $rawTax = Purchase::sum('total') * $taxDecimal;

        // Rounding to 2 decimal places chops off those annoying trailing precision zeros
        return round($rawTax, 2);
    }

    /**
     * Get the absolute sum count of items sold.
     */
    public function totalItemsSold() 
    {
        // Fixed: Call the sum directly on the PurchaseItem class
        return (int) PurchaseItem::sum('count');
    }
}