<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QueueList;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;

class QueueListController extends Controller
{
    public function getOrders() {
        // Fetch queue list data joined with their matching items
        $ordersWithItems = DB::table('queue_lists')
            ->join('purchase_items', 'queue_lists.purchase_id', '=', 'purchase_items.purchase_id')
            ->select(
                'queue_lists.order_name as name',
                'queue_lists.status',
                'purchase_items.name as item_name',
                'purchase_items.count'
            )
            ->get();

        // Group them by the order name so it formats perfectly for your element builder
        $formattedOrders = $ordersWithItems->groupBy('name')->map(function ($group, $orderName) {
            return [
                'name' => $orderName,
                'status' => $group->first()->status,
                'timeAgo' => 'Just now', 
                'items' => $group->map(function ($item) {
                    return [
                        'name' => $item->item_name,
                        'count' => $item->count
                    ];
                })->values()->all()
            ];
        })->values()->all();

        return response()->json([
            'status' => 'success',
            'data' => $formattedOrders
        ]);
    }
}

