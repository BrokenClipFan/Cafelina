<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;
use App\Services\QueueService;

class PurchaseController extends Controller
{
    public function purchase(Request $request, QueueService $queueService) {
        $items = $request['items'];
        $name = $items[0]['orderName'] ?? mt_rand(1000, 99999);

        try {
            DB::transaction(function () use ($items, &$name, $queueService) {
                $subtotal = 0;
                foreach($items as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }

                $userId = auth()->id();
                $tax = 0.08;
                $total = $subtotal + ($subtotal * $tax);
                $payment = "Cash";

                $purchase = Purchase::create([
                    'user_id' => $userId,
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total,
                    'payment_method' => $payment,
                    'name' => $name
                ]);

                $queueService->addOrder($purchase);

                foreach($items as $item) {
                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'name' => $item['name'],
                        'category' => $item['category'],
                        'price' => $item['price'],
                        'count' => $item['quantity']
                    ]);
                }
            });
        } catch (\Exception $e) {
            // If anything failed inside the transaction, changes are automatically rolled back
            return response()->json([
                'status'  => 'error',
                'message' => 'Checkout failed. Please try again.',
                'error'   => $e->getMessage() // Turn off in production for security!
            ], 500);
        }

        return response()->json([
            'message' => 'Checkout Success',
            'orderName' => $name,
        ]);
    }
}
