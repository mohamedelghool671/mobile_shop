<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'product_id' => $this->product_id,
                'product_details' => [
                'product_name' => $this->product->name,
                'product_price' => $this->product->price / 100,
                'product_description' => $this->product->description,
                'product_image' => asset("storage/" . $this->product->image),
    ],
];
}
}
