<?php 

namespace App\Services\Admin; 

use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Setting; 
use Carbon\Carbon;

class SalesService 
{
    /**
     * Get gross revenue across all transactions.
     */
    public function getGrossRevenue() 
    {
        return (float) Purchase::sum('total');
    }

    private function getTaxDecimal(): float
    {
        $taxSetting = Setting::where('name', 'tax')->first();
        return $taxSetting ? (float) $taxSetting->value : 0.0;
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
        $taxDecimal = $this->getTaxDecimal();
        $rawTax = Purchase::sum('total') * $taxDecimal;
        return round($rawTax, 2);
    }

    /**
     * Get the absolute sum count of items sold.
     */
    public function totalItemsSold() 
    {
        return (int) PurchaseItem::sum('count');
    }

    /**
     * --- DAILY BREAKDOWNS (HOURS) ---
     */
    public function getDailySalesByHour()
    {
        return Purchase::selectRaw('HOUR(created_at) as hour, SUM(total) as sales')
            ->whereDate('created_at', Carbon::today())
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get()
            ->pluck('sales', 'hour')
            ->all();
    }

    public function getDailyTaxByHour()
    {
        $taxDecimal = $this->getTaxDecimal();
        return Purchase::selectRaw('HOUR(created_at) as hour, ROUND(SUM(total) * ?, 2) as tax', [$taxDecimal])
            ->whereDate('created_at', Carbon::today())
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get()
            ->pluck('tax', 'hour')
            ->all();
    }

    public function getDailySubtotalByHour()
    {
        return Purchase::selectRaw('HOUR(created_at) as hour, SUM(subtotal) as subtotal')
            ->whereDate('created_at', Carbon::today())
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get()
            ->pluck('subtotal', 'hour')
            ->all();
    }

    public function getDailyItemsSoldByHour()
    {
        return PurchaseItem::selectRaw('HOUR(created_at) as hour, SUM(count) as items')
            ->whereDate('created_at', Carbon::today())
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get()
            ->pluck('items', 'hour')
            ->all();
    }

    /**
     * --- MONTHLY BREAKDOWNS (WEEKS) ---
     */
    public function getMonthlySalesByWeek()
    {
        return Purchase::selectRaw('WEEK(created_at) as week, SUM(total) as sales')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->get()
            ->pluck('sales', 'week')
            ->all();
    }

    public function getMonthlySubtotalByWeek()
    {
        return Purchase::selectRaw('WEEK(created_at) as week, SUM(subtotal) as subtotal')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->get()
            ->pluck('subtotal', 'week')
            ->all();
    }

    public function getMonthlyItemsSoldByWeek()
    {
        return PurchaseItem::selectRaw('WEEK(created_at) as week, SUM(count) as items')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->get()
            ->pluck('items', 'week')
            ->all();
    }

    public function getMonthlyTaxByWeek()
    {
        $taxDecimal = $this->getTaxDecimal();
        return Purchase::selectRaw('WEEK(created_at) as week, ROUND(SUM(total) * ?, 2) as tax', [$taxDecimal])
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->get()
            ->pluck('tax', 'week')
            ->all();
    }

    /**
     * --- YEARLY BREAKDOWNS (MONTHS) ---
     */
    public function getYearlySalesByMonth()
    {
        return Purchase::selectRaw('MONTH(created_at) as month, SUM(total) as sales')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('sales', 'month')
            ->all();
    }

    public function getYearlySubtotalByMonth()
    {
        return Purchase::selectRaw('MONTH(created_at) as month, SUM(subtotal) as subtotal')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('subtotal', 'month')
            ->all();
    }

    public function getYearlyTaxByMonth()
    {
        $taxDecimal = $this->getTaxDecimal();
        return Purchase::selectRaw('MONTH(created_at) as month, ROUND(SUM(total) * ?, 2) as tax', [$taxDecimal])
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('tax', 'month')
            ->all();
    }

    public function getYearlyItemsSoldByMonth()
    {
        return PurchaseItem::selectRaw('MONTH(created_at) as month, SUM(count) as items')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('items', 'month')
            ->all();
    }

    public function getCategoryDistribution()
    {
        return PurchaseItem::selectRaw('category, SUM(count) as total_units')
            ->groupBy('category')
            ->orderBy('total_units', 'desc')
            ->get()
            ->pluck('total_units', 'category') // Returns [ 'Beverages' => 55, 'Pastries' => 25 ]
            ->all();
    }

    /**
     * Get top 5 most popular items based on total units sold.
     */
    public function getTopPopularItems()
    {
        return PurchaseItem::selectRaw('name, category, SUM(count) as units_sold, SUM(count * price) as total_income')
            ->groupBy('name', 'category')
            ->orderBy('units_sold', 'desc')
            ->limit(5)
            ->get();
    }

    public function getPaymentMethodUsage()
    {
        $totalPurchases = Purchase::count();
        if ($totalPurchases === 0) {
            return ['cash' => 0, 'wallet' => 0, 'card' => 0];
        }

        // Adjust string values ('cash', 'wallet', 'card') to exactly match your database entries
        return [
            'cash'   => round((Purchase::where('payment_method', 'cash')->count() / $totalPurchases) * 100),
            'wallet' => round((Purchase::where('payment_method', 'wallet')->count() / $totalPurchases) * 100),
            'card'   => round((Purchase::where('payment_method', 'card')->count() / $totalPurchases) * 100),
        ];
    }
}