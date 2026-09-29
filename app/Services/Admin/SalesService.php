<?php 

namespace App\Services\Admin; 

use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Setting; 
use Carbon\Carbon;

class SalesService 
{
    public function applyPeriodFilter($query, $period = null, $date = null)
    {
        if (!$period) {
            return $query; // No filter, return all time
        }

        $targetDate = $date ? Carbon::parse($date) : Carbon::now();

        switch ($period) {
            case 'daily':
                return $query->whereDate('created_at', $targetDate);
            case 'weekly':
                return $query->whereBetween('created_at', [
                    $targetDate->copy()->startOfWeek(),
                    $targetDate->copy()->endOfWeek()
                ]);
            case 'monthly':
                return $query->whereMonth('created_at', $targetDate->month)
                             ->whereYear('created_at', $targetDate->year);
            case 'yearly':
                return $query->whereYear('created_at', $targetDate->year);
            default:
                return $query;
        }
    }

    /**
     * Get gross revenue across all transactions.
     */
    public function getGrossRevenue($period = null, $date = null) 
    {
        $query = Purchase::query();
        $this->applyPeriodFilter($query, $period, $date);
        return (float) $query->sum("total");
    }

    private function getTaxDecimal(): float
    {
        $taxSetting = Setting::where("name", "tax")->first();
        return $taxSetting ? (float) $taxSetting->value : 0.0;
    }

    /**
     * Get total subtotal collected before tax/discounts.
     */
    public function subTotalCollected($period = null, $date = null) 
    {
        $query = Purchase::query();
        $this->applyPeriodFilter($query, $period, $date);
        return (float) $query->sum("subtotal");
    }

    /**
     * Calculate accrued taxes based on a percentage value stored in Setting.
     */
    public function taxesAccrued($period = null, $date = null) 
    {
        $taxDecimal = $this->getTaxDecimal();
        
        $query = Purchase::query();
        $this->applyPeriodFilter($query, $period, $date);
        
        $rawTax = $query->sum("total") * $taxDecimal;
        return round($rawTax, 2);
    }

    /**
     * Get the absolute sum count of items sold.
     */
    public function totalItemsSold($period = null, $date = null) 
    {
        $query = PurchaseItem::query();
        $this->applyPeriodFilter($query, $period, $date);
        return (int) $query->sum("count");
    }

    /**
     * Get the total cost of inventory restocks.
     */
    public function getInventoryCost($period = null, $date = null)
    {
        $query = \App\Models\InventoryLog::query();
        $this->applyPeriodFilter($query, $period, $date);
        return (float) $query->sum("cost");
    }

    /**
     * --- DAILY BREAKDOWNS (HOURS) ---
     */
    public function getDailySalesByHour($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();
        return Purchase::selectRaw("HOUR(created_at) as hour, SUM(total) as sales")
            ->whereDate("created_at", $targetDate)
            ->groupBy("hour")
            ->orderBy("hour", "asc")
            ->get()
            ->pluck("sales", "hour")
            ->all();
    }

    public function getDailyTaxByHour($date = null)
    {
        $taxDecimal = $this->getTaxDecimal();
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();
        return Purchase::selectRaw("HOUR(created_at) as hour, ROUND(SUM(total) * ?, 2) as tax", [$taxDecimal])
            ->whereDate("created_at", $targetDate)
            ->groupBy("hour")
            ->orderBy("hour", "asc")
            ->get()
            ->pluck("tax", "hour")
            ->all();
    }

    public function getDailySubtotalByHour($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();
        return Purchase::selectRaw("HOUR(created_at) as hour, SUM(subtotal) as subtotal")
            ->whereDate("created_at", $targetDate)
            ->groupBy("hour")
            ->orderBy("hour", "asc")
            ->get()
            ->pluck("subtotal", "hour")
            ->all();
    }

    public function getDailyItemsSoldByHour($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();
        return PurchaseItem::selectRaw("HOUR(created_at) as hour, SUM(count) as items")
            ->whereDate("created_at", $targetDate)
            ->groupBy("hour")
            ->orderBy("hour", "asc")
            ->get()
            ->pluck("items", "hour")
            ->all();
    }
    
    /**
     * --- WEEKLY BREAKDOWNS (DAYS) ---
     */
    public function getWeeklySalesByDay($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();
        
        return Purchase::selectRaw("DAYOFWEEK(created_at) as day, SUM(total) as sales")
            ->whereBetween("created_at", [$startOfWeek, $endOfWeek])
            ->groupBy("day")
            ->orderBy("day", "asc")
            ->get()
            ->pluck("sales", "day")
            ->all();
    }

    public function getWeeklySubtotalByDay($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();

        return Purchase::selectRaw("DAYOFWEEK(created_at) as day, SUM(subtotal) as subtotal")
            ->whereBetween("created_at", [$startOfWeek, $endOfWeek])
            ->groupBy("day")
            ->orderBy("day", "asc")
            ->get()
            ->pluck("subtotal", "day")
            ->all();
    }

    public function getWeeklyItemsSoldByDay($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();

        return PurchaseItem::selectRaw("DAYOFWEEK(created_at) as day, SUM(count) as items")
            ->whereBetween("created_at", [$startOfWeek, $endOfWeek])
            ->groupBy("day")
            ->orderBy("day", "asc")
            ->get()
            ->pluck("items", "day")
            ->all();
    }

    public function getWeeklyTaxByDay($date = null)
    {
        $taxDecimal = $this->getTaxDecimal();
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();

        return Purchase::selectRaw("DAYOFWEEK(created_at) as day, ROUND(SUM(total) * ?, 2) as tax", [$taxDecimal])
            ->whereBetween("created_at", [$startOfWeek, $endOfWeek])
            ->groupBy("day")
            ->orderBy("day", "asc")
            ->get()
            ->pluck("tax", "day")
            ->all();
    }

    /**
     * --- MONTHLY BREAKDOWNS (WEEKS) ---
     */
    public function getMonthlySalesByWeek($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        return Purchase::selectRaw("WEEK(created_at) as week, SUM(total) as sales")
            ->whereMonth("created_at", $targetDate->month)
            ->whereYear("created_at", $targetDate->year)
            ->groupBy("week")
            ->orderBy("week", "asc")
            ->get()
            ->pluck("sales", "week")
            ->all();
    }

    public function getMonthlySubtotalByWeek($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        return Purchase::selectRaw("WEEK(created_at) as week, SUM(subtotal) as subtotal")
            ->whereMonth("created_at", $targetDate->month)
            ->whereYear("created_at", $targetDate->year)
            ->groupBy("week")
            ->orderBy("week", "asc")
            ->get()
            ->pluck("subtotal", "week")
            ->all();
    }

    public function getMonthlyItemsSoldByWeek($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        return PurchaseItem::selectRaw("WEEK(created_at) as week, SUM(count) as items")
            ->whereMonth("created_at", $targetDate->month)
            ->whereYear("created_at", $targetDate->year)
            ->groupBy("week")
            ->orderBy("week", "asc")
            ->get()
            ->pluck("items", "week")
            ->all();
    }

    public function getMonthlyTaxByWeek($date = null)
    {
        $taxDecimal = $this->getTaxDecimal();
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        return Purchase::selectRaw("WEEK(created_at) as week, ROUND(SUM(total) * ?, 2) as tax", [$taxDecimal])
            ->whereMonth("created_at", $targetDate->month)
            ->whereYear("created_at", $targetDate->year)
            ->groupBy("week")
            ->orderBy("week", "asc")
            ->get()
            ->pluck("tax", "week")
            ->all();
    }

    /**
     * --- YEARLY BREAKDOWNS (MONTHS) ---
     */
    public function getYearlySalesByMonth($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        return Purchase::selectRaw("MONTH(created_at) as month, SUM(total) as sales")
            ->whereYear("created_at", $targetDate->year)
            ->groupBy("month")
            ->orderBy("month", "asc")
            ->get()
            ->pluck("sales", "month")
            ->all();
    }

    public function getYearlySubtotalByMonth($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        return Purchase::selectRaw("MONTH(created_at) as month, SUM(subtotal) as subtotal")
            ->whereYear("created_at", $targetDate->year)
            ->groupBy("month")
            ->orderBy("month", "asc")
            ->get()
            ->pluck("subtotal", "month")
            ->all();
    }

    public function getYearlyTaxByMonth($date = null)
    {
        $taxDecimal = $this->getTaxDecimal();
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        return Purchase::selectRaw("MONTH(created_at) as month, ROUND(SUM(total) * ?, 2) as tax", [$taxDecimal])
            ->whereYear("created_at", $targetDate->year)
            ->groupBy("month")
            ->orderBy("month", "asc")
            ->get()
            ->pluck("tax", "month")
            ->all();
    }

    public function getYearlyItemsSoldByMonth($date = null)
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::now();
        return PurchaseItem::selectRaw("MONTH(created_at) as month, SUM(count) as items")
            ->whereYear("created_at", $targetDate->year)
            ->groupBy("month")
            ->orderBy("month", "asc")
            ->get()
            ->pluck("items", "month")
            ->all();
    }

    /**
     * --- OVERALL BREAKDOWNS (YEARS) ---
     */
    public function getOverallSalesByYear()
    {
        return Purchase::selectRaw("YEAR(created_at) as year, SUM(total) as sales")
            ->groupBy("year")
            ->orderBy("year", "asc")
            ->get()
            ->pluck("sales", "year")
            ->all();
    }

    public function getCategoryDistribution($period = null, $date = null)
    {
        $query = PurchaseItem::selectRaw("category, SUM(count) as total_units")
            ->groupBy("category")
            ->orderBy("total_units", "desc");
            
        $this->applyPeriodFilter($query, $period, $date);
            
        return $query->get()
            ->pluck("total_units", "category") // Returns [ "Beverages" => 55, "Pastries" => 25 ]
            ->all();
    }

    /**
     * Get top 5 most popular items based on total units sold.
     */
    public function getTopPopularItems($period = null, $date = null)
    {
        $query = PurchaseItem::selectRaw("name, category, SUM(count) as units_sold, SUM(count * price) as total_income")
            ->groupBy("name", "category")
            ->orderBy("units_sold", "desc")
            ->limit(5);
            
        $this->applyPeriodFilter($query, $period, $date);
            
        return $query->get();
    }

    public function getPaymentMethodUsage($period = null, $date = null)
    {
        $baseQuery = Purchase::query();
        $this->applyPeriodFilter($baseQuery, $period, $date);
        
        $totalPurchases = $baseQuery->count();
        if ($totalPurchases === 0) {
            return ["cash" => 0, "wallet" => 0, "card" => 0];
        }

        $cashQuery = Purchase::where("payment_method", "cash");
        $walletQuery = Purchase::where("payment_method", "wallet");
        $cardQuery = Purchase::where("payment_method", "card");

        $this->applyPeriodFilter($cashQuery, $period, $date);
        $this->applyPeriodFilter($walletQuery, $period, $date);
        $this->applyPeriodFilter($cardQuery, $period, $date);

        return [
            "cash"   => round(($cashQuery->count() / $totalPurchases) * 100),
            "wallet" => round(($walletQuery->count() / $totalPurchases) * 100),
            "card"   => round(($cardQuery->count() / $totalPurchases) * 100),
        ];
    }
}
