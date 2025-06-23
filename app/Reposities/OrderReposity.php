<?php

namespace App\Reposities;


use App\Models\Order;
use App\Interfaces\OrderReposiyInterface;

class OrderReposity implements OrderReposiyInterface {

    public function index($limit = 10) {
        return Order::with('items')->where('status', '!=', 'delivered')->paginate($limit);
    }

    public function store($data) {
        return Order::create($data);
    }

    public function showUserOrders() {
        $user = auth()->user();
        return Order::with('items')->where('user_id', $user->id)->where('status', '!=', 'delivered')->orderBy('id','desc')->get();
    }

    public function updateStatus($id, $status) {
        $order =$this->find($id);
        if (!$order) {
            return false;
        }
       return tap($order,function($order) use ($status) {
         return $order->update(['status' => $status]);
       });
    }

    public function cancel($id) {
        $order = $this->find($id);
        if (!$order) return false;

        if ($order->status === 'shipped') {
            return false;
        }
       return $order->update(['status' => 'canceled']);
    }

    public function find($id) {
        return Order::find($id);
    }
}
