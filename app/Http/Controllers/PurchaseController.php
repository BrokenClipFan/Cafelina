<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;
use App\Services\QueueService;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function purchase(Request $request, QueueService $queueService) {
        $items = $request['items'];
        $name = $items[0]['orderName'] ?? mt_rand(1000, 99999);
        $orderId = null;

        try {
            DB::transaction(function () use ($items, &$name, $queueService, &$orderId) {
                $subtotal = 0;
                foreach($items as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }

                $userId = Auth::id();

                $taxString = Setting::where('name', 'tax')->first();
                $taxDecimal = (float) $taxString->value;
                $total = $subtotal + ($subtotal * $taxDecimal);
                $payment = "Cash";


                $purchase = Purchase::create([
                    'user_id' => $userId,
                    'subtotal' => $subtotal,
                    'tax' => $taxDecimal,
                    'total' => $total,
                    'payment_method' => $payment,
                    'name' => $name
                ]);

                $orderId = $purchase->id;

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
            'orderId' => $orderId,       
            'orderName' => $name,
        ]);
    }

    public function search(Request $request) {

        $order = Purchase::findOrFail($request->search);
        $order->load('user');

        return view('admin.receipt', compact('order'));
    }

    public function updateStatus(Request $request) {
        $order = Purchase::findOrFail(intval($request->order_id));
        $order->load('user');

        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully.',
            'status'  => $order->status
        ]);
    }

    public function autocomplete(Request $request) {
        $query = $request->input('q');
        if (!$query) {
            return response()->json([]);
        }

        // Strip leading zeros if the query is purely numeric so that '00115' matches id '115'
        $idQuery = ltrim($query, '0');
        if (empty($idQuery) && is_numeric($query)) {
            $idQuery = '0'; // Handle if they just type '0' or '000'
        }

        $results = Purchase::where('id', 'LIKE', "%{$idQuery}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->orderByDesc('created_at')
            ->take(8)
            ->get(['id', 'name', 'total', 'created_at']);

        $formatted = $results->map(function($order) {
            return [
                'id' => $order->id,
                'padded_id' => str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'name' => $order->name ?? 'Walk-in',
                'total' => number_format($order->total, 2)
            ];
        });

        return response()->json($formatted);
    }
}
