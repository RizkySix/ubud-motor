<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class RentalExtensionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'booking_detail_id' => 'required|numeric',
            'package' => 'required|numeric',
            'amount' => 'required|numeric',
            'return_date' => 'nullable|date',
            'rental_duration' => 'nullable|numeric',
        ];
    }
}
