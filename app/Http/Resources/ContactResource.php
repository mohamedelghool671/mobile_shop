<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "message_id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "message" => $this->message,
            "users_id" => $this->user_id,
        ];
    }
}
