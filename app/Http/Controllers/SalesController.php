<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Dashboard;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Dashboard $dashboardService)
    {
        $user = Auth::user();

        if($user->role === 'admin'){
            return redirect('admin/dashboard');
        }

        $result = $dashboardService->userSales($user->id);

        $salesToday       = $result->salesToday;
        $itemsSoldToday   = $result->itemsSoldToday;
        $weeklySales      = $result->weeklySales;
        $weeklyLabels     = $result->weeklyLabels;
        $weeklyItemCounts = $result->weeklyItemCounts; // Added here

        return view('dashboard', compact('salesToday', 'itemsSoldToday', 'weeklySales', 'weeklyLabels', 'weeklyItemCounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
