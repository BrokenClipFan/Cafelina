<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseItem;

class PurchaseController extends Controller
{
    public function purchase(Request $request) {
        dd($request);
    }
}
