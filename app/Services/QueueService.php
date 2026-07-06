<?php

namespace App\Services;
use App\Models\QueueList;

class QueueService {

  public function addOrder($order) {
    QueueList::create([
      'purchase_id' => $order->id,
      'order_name' => $order->name,
      'status' => 'preparing',
      'user_id' => $order->user_id
    ]);
  }

  public function markAsReady($orderName) {
    QueueList::where('order_name', $orderName)
              ->update([
                'status' => 'ready'
              ]);
  }

  public function completeOrder($orderName) {
    QueueList::where('order_name', $orderName)->delete();
  }
}