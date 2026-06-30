<?php

namespace App\Services;
use App\Models\QueueList;

class QueueService {
  public function addOrder($order) {
    QueueList::create([
      'purchase_id' => $order->id,
      'order_name' => $order->name,
      'status' => 'preparing'
    ]);
  }
}