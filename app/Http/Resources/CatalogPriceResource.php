<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogPriceResource extends JsonResource
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
            'catalog_motor_id' => $this->catalog_motor_id,
            'package' => $this->package,
            'duration' => $this->duration . ' ' . $this->duration_suffix,
            'price' => $this->price
        ];
    }
}
