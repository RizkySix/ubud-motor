<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingDetailResource extends JsonResource
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
            'booking_uuid' => $this->booking_uuid,
            'motor_name' => $this->motor_name,
            'rental_date' => $this->rental_date,
            'return_date' => $this->return_date,
        ];
    }
}
