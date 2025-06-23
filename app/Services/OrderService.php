<?php

namespace App\Services;


use App\Helpers\Paginate;
use App\Models\OrderItem;
use App\Helpers\SendNotification;
use App\Http\Resources\OrderResource;
use App\Interfaces\OrderReposiyInterface;
use App\Reposities\CartReposity;

class OrderService
{
    public function __construct(protected OrderReposiyInterface $orderRepository)
    {

    }

    public function index($limit = 10)
    {
        $orders =  $this->orderRepository->index($limit);
        return Paginate::paginate($orders, OrderResource::collection($orders), "orders");
    }

    public function store($data)
    {
        $user = auth()->user();
        $cart = new CartReposity();
        $cart = $cart->find($user->id);
        if (!$cart || $cart->items->isEmpty()) {
            return false;
        }
        $data['user_id'] = $user->id;
        $order = $this->orderRepository->store($data->toArray());
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item->product->name,
                'product_image' => $item->product->image,
                'total_price' => $item->price,
                'quantity' => $item->quantity,
            ]);
        }
        $cart->items()->delete();
        return true;
    }

    public function showUserOrders()
    {
        return $this->orderRepository->showUserOrders();
    }

    public function updateStatus($id, $status)
    {
        $order = $this->orderRepository->updateStatus($id, $status);
        SendNotification::sendTo($order->user,[
            "title" => "order status",
            "body" => "yor order is $order->status please wait "
        ]);
           return $order;
    }

    public function cancel($id)
    {
        return $this->orderRepository->cancel($id);
    }
}
