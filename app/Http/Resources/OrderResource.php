<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
         $shipping = (int) env("shipping");
        $total = $this->items->sum('total_price') / 100 + $shipping;
        $user = $request->user();
        if ($user && $user->role === 'admin') {
            return [
                "order_id" => $this->id,
                "order_status" => $this->status,
                "order_date" => $this->created_at->setTimezone(config('app.Time_Zone'))->toDateString(),
                "name" => $this->name,
                "phone" => $this->phone,
                "city" => $this->city,
                "street" => $this->street,
                "postal_code" => $this->postal_code,
                "order_price" => $this->items->sum('total_price') / 100,
                "total" =>$total,
                "delevery_price" => $shipping,
                "order_item" => $this->items->map(function ($item) {
                    return [
                        "product_name" => $item->product_name,
                        "product_price" => $item->total_price / 100,
                        "product_image" => asset("storage/".$item->product_image),
                        "product_quantity" => $item->quantity,
                    ];
                }),
            ];
        }
            return [
                "order_id" =>$this->id,
                "order_status" => $this->status,
                "order_date" => $this->created_at->setTimezone(config('app.Time_Zone'))->toDateString(),
                "city" => $this->city,
                "street" => $this->street,
                "postal_code" => $this->postal_code,
                "order_price" => $this->items->sum('total_price') / 100,
                "total" =>$total,
                "delevery_price" => $shipping,
                "order_item" => $this->items->map(function ($item) {
                    return [
                        "product_name" => $item->product_name,
                        "product_price" => $item->total_price / 100,
                        "product_image" => asset("storage/".$item->product_image),
                        "product_quantity" => $item->quantity,
                    ];
                })
            ];
    }
}
