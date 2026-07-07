<?php 

namespace App\Services;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Purchase;

class Dashboard {
  
  public function userSales($id) 
  {
      // 1. Get today's total sales revenue for THIS specific user
      $salesToday = Purchase::where('user_id', $id)
          ->whereDate('created_at', today())
          ->sum('total');

      // 2. Get today's total items sold by THIS specific user
      $itemsSoldToday = DB::table('purchase_items')
          ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
          ->where('purchases.user_id', $id)
          ->whereDate('purchases.created_at', today())
          ->sum('purchase_items.count');

      // 3. Fetch sales from the last 7 days for THIS specific user
      $pastWeekSales = Purchase::where('user_id', $id)
          ->where('created_at', '>=', today()->subDays(6))
          ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total_sales'))
          ->groupBy('date')
          ->pluck('total_sales', 'date');

      // NEW: Fetch item counts from the last 7 days for THIS specific user
      $pastWeekItems = DB::table('purchase_items')
          ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
          ->where('purchases.user_id', $id)
          ->where('purchases.created_at', '>=', today()->subDays(6))
          ->select(DB::raw('DATE(purchases.created_at) as date'), DB::raw('SUM(purchase_items.count) as total_items'))
          ->groupBy('date')
          ->pluck('total_items', 'date');

      // 4. Prepare arrays for the chart
      $weeklyLabels = [];
      $weeklySales = [];
      $weeklyItemCounts = []; // New array

      for ($i = 6; $i >= 0; $i--) {
          $date = today()->subDays($i);
          $dateString = $date->format('Y-m-d');
          
          $weeklyLabels[] = $date->format('D'); 
          $weeklySales[] = $pastWeekSales->get($dateString, 0); 
          $weeklyItemCounts[] = (int) $pastWeekItems->get($dateString, 0); // New mapping
      }

      // FIX: Return a data object so the controller can parse it cleanly
      return (object) [
          'salesToday'       => $salesToday,
          'itemsSoldToday'   => $itemsSoldToday,
          'weeklyLabels'     => $weeklyLabels,
          'weeklySales'      => $weeklySales,
          'weeklyItemCounts' => $weeklyItemCounts,
      ];
  }
}