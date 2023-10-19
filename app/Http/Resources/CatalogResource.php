<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'motor_name' => $this->motor_name,
            'first_catalog' => $this->first_catalog,
            'second_catalog' => $this->second_catalog,
            'third_catalog' => $this->third_catalog,
            'charge' => $this->charge,
            'price_lists' => CatalogPriceResource::collection($this->whenLoaded('price'))
        ];
    }
}
