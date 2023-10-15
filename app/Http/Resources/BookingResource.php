<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'total_unit' => $this->total_unit,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'whatsapp_number' => $this->whatsapp_number,
            'motor_name' => $this->motor_name,
            'package' => $this->package,
            'amount' => $this->amount,
            'delivery_address' => $this->delivery_address,
            'pickup_address' => $this->pickup_address,
            'additional_message' => $this->additional_message,
            'is_confirmed' => $this->is_confirmed,
            'is_active' => $this->is_active,
            'booking_details' => BookingDetailResource::collection($this->whenLoaded('detail'))
        ];
    }
}
