<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $image = isset($this->data['image']) == false ? null : $this->data['image'] ;
        return [
            'title' => $this->data['title'],
            'body' => $this->data['body'],
            "image" =>$image,
            "time" => $this->created_at->setTimezone(config('app.Time_Zone'))->format('Y/m/d h:i A')
        ];
    }
}
