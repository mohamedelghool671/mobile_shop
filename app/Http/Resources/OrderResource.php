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

        $user = $request->user();
        if ($user && $user->role === 'admin') {
            if (!$this->gift_recipient_name && !$this->gift_recipient_address) {
                return [
                    "order_id" => $this->id,
                    "order_status" => $this->status,
                    "order_date" => $this->created_at->setTimezone(config('app.Time_Zone'))->longAbsoluteDiffForHumans(),
                    "user_first_name" => $this->user_first_name,
                    "user_last_name" =>$this->user_last_name,
                    "phone" => $this->phone,
                    "email" => $this->email,
                    "city" => $this->city,
                    "governorate" => $this->governorate,
                    "address"=>$this->address,
                    "country" => $this->country,
                    "postal_code" => $this->postal_code,
                    "order_item" => $this->items->map(function ($item) {
                        return [
                            "product_name" => $item->product_name,
                            "product_image" => $item->product_image ? asset("storage/".$item->product_image) : null,
                            "total_price" => $item->total_price / 100,
                            "quantity" => $item->quantity,
                        ];
                    })
                ];
            } else {
                return [
                    "order_id" => $this->id,
                    "order_status" => $this->status,
                    "order_date" => $this->created_at->setTimezone(config('app.Time_Zone'))->longAbsoluteDiffForHumans(),
                    "user_first_name" => $this->user_first_name,
                    "user_last_name" =>$this->user_last_name,
                    "phone" => $this->phone,
                    "email" => $this->email,
                    "city" => $this->city,
                    "governorate" => $this->governorate,
                    "address"=>$this->address,
                    "country" => $this->country,
                    "postal_code" => $this->postal_code,
                    "gift_recipient_phone" => $this->gift_recipient_phone,
                    "gift_recipient_name" => $this->gift_recipient_name,
                    "gift_recipient_city"=> $this->gift_recipient_city,
                    "gift_recipient_governorate" => $this->gift_recipient_governorate
                    ,"gift_recipient_address" => $this->gift_recipient_address,
                    "gift_recipient_country"=>$this->gift_recipient_country,
                    "gift_recipient_postal_code" => $this->gift_recipient_postal_code,
                    "order_item" => $this->items->map(function ($item) {
                        return [
                            "product_name" => $item->product_name,
                            "product_image" => $item->product_image ? asset("storage/".$item->product_image) : null,
                            "total_price" => $item->total_price / 100,
                            "quantity" => $item->quantity,
                        ];
                    })
                ];
            }
        }
        return [
            "order_status" => $this->status,
            "order_date" => $this->created_at->setTimezone(config('app.Time_Zone'))->longAbsoluteDiffForHumans(),
            "order_item" => $this->items->map(function ($item) {
                return [
                    "product_name" => $item->product_name,
                    "product_image" => $item->product_image ? asset("storage/".$item->product_image) : null,
                    "total_price" => $item->total_price / 100,
                    "quantity" => $item->quantity,
                ];
            })
        ];

    }
}
