<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Helpers\Paginate;
use App\Models\OrderItem;
use App\Helpers\ApiResponse;
use App\Reposities\OrderReposity;
use App\Notifications\OrderDeliverd;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Notification;

class OrderService
{
    public function __construct(protected OrderReposity $orderRepository)
    {

    }

    public function index($limit = 10)
    {
        $orders =  $this->orderRepository->index($limit);
        $data = Paginate::paginate($orders, OrderResource::collection($orders), "orders");

        if ($data) {
            return ApiResponse::sendResponse("list of orders", 200, $data);
        }

        return ApiResponse::sendResponse("no orders");
    }

    public function store($data, $user)
    {
        $cart = Cart::where('user_id', $user->id)->first();
        if (!$cart || $cart->items->isEmpty()) {
            return ['error' => 'Cart is empty.'];
        }
        $data['user_id'] = $user->id;
        $order = $this->orderRepository->store($data,$user);
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
        return ApiResponse::sendResponse("order placed successfully", 200, ['order_id' => $order->id]);
    }

    public function showUserOrders($user)
    {
        $orders = $this->orderRepository->showUserOrders($user);
        if ($orders) {
            return ApiResponse::sendResponse("return order success", 200, OrderResource::collection($orders));
        }
        return ApiResponse::sendResponse("no orders", 422);
    }

    public function updateStatus($id, $status)
    {
        $updated = $this->orderRepository->updateStatus($id, $status);
        if ($updated) {
            $order = Order::find($id);
            if ($order && $status === 'delivered') {
                Notification::route('mail', $order->user->email)
                    ->notify(new OrderDeliverd($order, $order->user));
            }
            return ApiResponse::sendResponse("order status updated successfully", 200);
        }
        return ApiResponse::sendResponse("record not found", 422);
    }

    public function cancel($id)
    {
        $result = $this->orderRepository->cancel($id);
        if ($result === true) {
            return ApiResponse::sendResponse("order canceled successfully", 200);
        }
        return ApiResponse::sendResponse($result['error'], 422);
    }
}
