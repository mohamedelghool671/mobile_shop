<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $last = $this->total_visits;
       $is_favourite = $this->is_favourite ? 1 : 0;
        if ($last) {
            return [
                "product_name" => $this->name ,
                "product_id" => $this->id ,
                "product_description" => $this->description,
                "product_image" => asset("storage"."/".$this->image),
                "product_images" => collect(json_decode($this->images))->map(function ($image) {
                        return asset("storage/" . $image);
                    })->toArray(),
                "product_price" => $this->price / 100,
                "product_quantity" => $this->quantity,
                "category" => [
                    "category_name" => $this->category->name,
                    "category_id" => $this->category->id
                ],
                "comments" => $this->comment->map(function($comment) {
                 return [
                    "id" => $comment->id,
                    "content" => $comment->content,
                    "rating" => $comment->rating ? $comment->rating : 0,
                    "user"=>$comment->user->name ?? null ,
                    "time" => $comment->created_at->setTimezone(config('app.Time_Zone'))->format('Y/m/d h:i A')

                ];
                }),
                'is_favourite' => $is_favourite ,
                "rating" =>$this->rating ? round($this->rating,1) : 0,
                "number_rating" =>$this->comment->avg('rating') === null ? 0 :$this->comment->count('rating'),
                "total_visits" => $this->total_visits ?? 0,
                "last_visit" =>Carbon::parse($this->last_visit)->setTimezone('Africa/Cairo')->longAbsoluteDiffForHumans() ?? null
            ];
        }
        return [
            "product_name" => $this->name ,
            "product_id" => $this->id ,
            "product_description" => $this->description,
            "product_image" => asset("storage"."/".$this->image),
            "product_images" => collect(json_decode($this->images))->map(function ($image) {
                     return asset("storage/" . $image);
                 })->toArray(),
            "product_price" => $this->price / 100,
            "product_quantity" => $this->quantity,
            "category" => [
                "category_name" => $this->category->name,
                "category_id" => $this->category->id
            ],
            "comments" => $this->comment->map(function($comment) {
                return [
                    "id" => $comment->id,
                    "content" => $comment->content,
                    "rating" => $comment->rating ? $comment->rating : 0,
                    "user"=>$comment->user->name ?? null ,
                    "time" => $comment->created_at->setTimezone(config('app.Time_Zone'))->format('Y/m/d h:i A')

                ];
            }),
            'is_favourite' => $is_favourite ,
            "rating" => $this->rating ? round($this->rating,1) : 0,
            "number_rating" =>$this->comment->avg('rating') === null ? 0 :$this->comment->count('rating'),
        ];
    }
}
