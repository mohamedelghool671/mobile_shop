<?php

namespace App\Reposities;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Events\OrderCreated;
use App\Events\OrderDeliverd;
use App\Interfaces\OrderReposiyInterface;

class OrderReposity implements OrderReposiyInterface {

    public function index($limit = 10) {
        return Order::with('items')->where('status', '!=', 'delivered')->paginate($limit);
    }

    public function store($data, $user) {
        return Order::create($data);
    }

    public function showUserOrders($user) {
        return Order::with('items')->where('user_id', $user->id)->where('status', '!=', 'delivered')->get();
    }

    public function updateStatus($id, $status) {
        $order = Order::find($id);
        if (!$order) return false;

        $order->update(['status' => $status]);
        return true;
    }

    public function cancel($id) {
        $order = Order::find($id);
        if (!$order) return false;

        if ($order->status === 'shipped') {
            return ['error' => "Order can't be canceled because it's already shipped"];
        }
        $order->update(['status' => 'canceled']);
        return true;
    }
}
