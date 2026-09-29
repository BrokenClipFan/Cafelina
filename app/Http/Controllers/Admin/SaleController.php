<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SalesService;
use Illuminate\Http\Request;
use App\Models\PurchaseItem;
use App\Models\Purchase;
use App\Models\User;

class SaleController extends Controller
{
    public function index(Request $request, SalesService $salesService)
    {
        $period = $request->input('period', 'yearly');
        $date = $request->input('date');

        $grossRevenue       = $salesService->getGrossRevenue($period, $date);
        $subTotalCollected  = $salesService->subTotalCollected($period, $date);
        $taxAccrued         = $salesService->taxesAccrued($period, $date);
        $totalItemsSold     = $salesService->totalItemsSold($period, $date);

        $inventoryCost      = $salesService->getInventoryCost($period, $date);
        // Note: Gross revenue typically includes tax, while subtotal is revenue before tax.
        // We'll calculate profit as subTotal - inventoryCost to exclude tax from our actual profit,
        // or just grossRevenue - inventoryCost if they consider gross as their money. Let's use subTotal.
        $profit             = $subTotalCollected - $inventoryCost;

        // New Dynamic Elements
        $categoryData       = $salesService->getCategoryDistribution($period, $date);
        $topItems           = $salesService->getTopPopularItems($period, $date);

        // Fetch chart data specifically for the active period
        $activeChartData = [];
        switch ($period) {
            case 'daily':
                $activeChartData = [
                    'revenue'   => $salesService->getDailySalesByHour($date),
                ];
                break;
            case 'weekly':
                $activeChartData = [
                    'revenue'   => $salesService->getWeeklySalesByDay($date),
                ];
                break;
            case 'monthly':
                $activeChartData = [
                    'revenue'   => $salesService->getMonthlySalesByWeek($date),
                ];
                break;
            case 'overall':
                $activeChartData = [
                    'revenue'   => $salesService->getOverallSalesByYear(),
                ];
                break;
            case 'yearly':
            default:
                $activeChartData = [
                    'revenue'   => $salesService->getYearlySalesByMonth($date),
                ];
                break;
        }

        $paymentMethods = $salesService->getPaymentMethodUsage($period, $date);

        $employees = User::orderByDesc('online_status') 
        ->orderBy('name', 'asc')       
        ->take(10)
        ->get();

        $recentOrders = Purchase::with('user')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'period',
            'date',
            'grossRevenue', 
            'subTotalCollected',
            'taxAccrued',
            'totalItemsSold',
            'inventoryCost',
            'profit',
            'categoryData',
            'topItems',
            'activeChartData',
            'paymentMethods',
            'employees',
            'recentOrders'
        ));
    }

    public function getPopularItemsData(Request $request, SalesService $salesService)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'units_sold'); // Default column
        $sortDir = $request->input('sort_dir', 'desc');     // Default direction
        
        $period = $request->input('period', 'yearly');
        $date = $request->input('date');

        // Whitelist allowed sort columns to prevent SQL injection
        if (!in_array($sortBy, ['units_sold', 'total_income'])) {
            $sortBy = 'units_sold';
        }
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $query = PurchaseItem::selectRaw('name, category, SUM(count) as units_sold, SUM(count * price) as total_income')
            ->groupBy('name', 'category');

        $salesService->applyPeriodFilter($query, $period, $date);

        // Apply database text filter if search input exists
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        // Sort all records directly inside database engine
        $items = $query->orderBy($sortBy, $sortDir)->get();

        return response()->json($items);
    }

    public function getChartData(Request $request, SalesService $salesService)
    {
        $period = $request->input('period', 'yearly'); // daily, weekly, monthly, yearly
        $date = $request->input('date');

        $data = [];

        switch ($period) {
            case 'daily':
                $data = [
                    'revenue'   => $salesService->getDailySalesByHour($date),
                    'subtotal'  => $salesService->getDailySubtotalByHour($date),
                    'tax'       => $salesService->getDailyTaxByHour($date),
                    'items'     => $salesService->getDailyItemsSoldByHour($date),
                ];
                break;
            case 'weekly':
                $data = [
                    'revenue'   => $salesService->getWeeklySalesByDay($date),
                    'subtotal'  => $salesService->getWeeklySubtotalByDay($date),
                    'tax'       => $salesService->getWeeklyTaxByDay($date),
                    'items'     => $salesService->getWeeklyItemsSoldByDay($date),
                ];
                break;
            case 'monthly':
                $data = [
                    'revenue'   => $salesService->getMonthlySalesByWeek($date),
                    'subtotal'  => $salesService->getMonthlySubtotalByWeek($date),
                    'tax'       => $salesService->getMonthlyTaxByWeek($date),
                    'items'     => $salesService->getMonthlyItemsSoldByWeek($date),
                ];
                break;
            case 'yearly':
            default:
                $data = [
                    'revenue'   => $salesService->getYearlySalesByMonth($date),
                    'subtotal'  => $salesService->getYearlySubtotalByMonth($date),
                    'tax'       => $salesService->getYearlyTaxByMonth($date),
                    'items'     => $salesService->getYearlyItemsSoldByMonth($date),
                ];
                break;
        }

        return response()->json($data);
    }

    public function getRecentOrdersData()
    {
        $recentOrders = Purchase::with('user')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();
            
        $formattedOrders = $recentOrders->map(function ($order) {
            return [
                'id' => $order->id,
                'padded_id' => str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'time_diff' => $order->created_at->diffForHumans(),
                'customer_name' => $order->name ?? 'Walk-in',
                'total_formatted' => number_format($order->total, 2)
            ];
        });

        return response()->json($formattedOrders);
    }
}