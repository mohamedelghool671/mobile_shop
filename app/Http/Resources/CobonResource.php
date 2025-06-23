<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CobonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cobon' => $this->cobon,
            'name' => $this->name,
            'description' => $this->desc,
            'value' => $this->value,
            'expire_date' => $this->expire_date,
            'number_uses' => $this->number_uses,
        ];
    }
}
