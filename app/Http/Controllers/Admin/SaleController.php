<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SalesService;
use Illuminate\Http\Request;
use App\Models\PurchaseItem;
use App\Models\User;

class SaleController extends Controller
{
    public function index(SalesService $salesService)
    {
        $grossRevenue       = $salesService->getGrossRevenue();
        $subTotalCollected  = $salesService->subTotalCollected();
        $taxAccrued         = $salesService->taxesAccrued();
        $totalItemsSold     = $salesService->totalItemsSold();

        // New Dynamic Elements
        $categoryData       = $salesService->getCategoryDistribution();
        $topItems           = $salesService->getTopPopularItems();

        $daily = [
            'revenue'   => $salesService->getDailySalesByHour(),
            'subtotal'  => $salesService->getDailySubtotalByHour(),
            'tax'       => $salesService->getDailyTaxByHour(),
            'items'     => $salesService->getDailyItemsSoldByHour(),
        ];

        $monthly = [
            'revenue'   => $salesService->getMonthlySalesByWeek(),
            'subtotal'  => $salesService->getMonthlySubtotalByWeek(),
            'tax'       => $salesService->getMonthlyTaxByWeek(),
            'items'     => $salesService->getMonthlyItemsSoldByWeek(),
        ];

        $yearly = [
            'revenue'   => $salesService->getYearlySalesByMonth(),
            'subtotal'  => $salesService->getYearlySubtotalByMonth(),
            'tax'       => $salesService->getYearlyTaxByMonth(),
            'items'     => $salesService->getYearlyItemsSoldByMonth(),
        ];

        $paymentMethods = $salesService->getPaymentMethodUsage();

        $onlineEmployees = User::where('role', 'employee') // optional role filter
        ->where('online_status', true) // or ->where('last_seen_at', '>=', now()->subMinutes(5))
        ->take(4)
        ->get();

        return view('admin.dashboard', compact(
            'grossRevenue', 
            'subTotalCollected',
            'taxAccrued',
            'totalItemsSold',
            'categoryData',
            'topItems',
            'daily',
            'monthly',
            'yearly',
            'paymentMethods',
            'onlineEmployees'
        ));
    }

    public function getPopularItemsData(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'units_sold'); // Default column
        $sortDir = $request->input('sort_dir', 'desc');     // Default direction

        // Whitelist allowed sort columns to prevent SQL injection
        if (!in_array($sortBy, ['units_sold', 'total_income'])) {
            $sortBy = 'units_sold';
        }
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $query = PurchaseItem::selectRaw('name, category, SUM(count) as units_sold, SUM(count * price) as total_income')
            ->groupBy('name', 'category');

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
}