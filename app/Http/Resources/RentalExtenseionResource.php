<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentalExtenseionResource extends JsonResource
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
            'motor_name' => $this->booking_detail->motor_name,
            'package' => $this->package,
            'amount' => $this->amount,
            'expired_payment' => $this->expired_payment,
            'extension_from' => $this->extension_from,
            'extension_to' => $this->extension_to,
            'is_confirmed' => $this->is_confirmed,
        ];
    }
}
