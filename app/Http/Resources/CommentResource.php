<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "user_name" => $this->user->first_name ." ".$this->user->last_name,
            "product_name" => $this->product->name,
            "comment_content" => $this->content,
            "comment_id" => $this->id,
            "rating" => $this->rating
        ];
    }
}
