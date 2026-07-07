<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SalesService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(SalesService $salesService) 
    {
        // These calls will no longer throw missing argument exceptions
        $grossRevenue      = $salesService->getGrossRevenue();
        $subTotalCollected = $salesService->subTotalCollected();
        $taxAccrued        = $salesService->taxesAccrued();
        $totalItemsSold    = $salesService->totalItemsSold();

        return view('admin.dashboard', compact(
            'grossRevenue', 
            'subTotalCollected',
            'taxAccrued',
            'totalItemsSold'
        ));
    }
}