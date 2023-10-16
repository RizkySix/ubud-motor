<?php

namespace App\Http\Requests\Booking;

use App\Trait\HasCustomResponse;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    use HasCustomResponse;
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
            'full_name' => 'required|string|min:3',
            'total_unit' => 'required|numeric|min:1',
            'email' => 'required|email:dns',
            'whatsapp_number' => 'required|string',
            'motor_name' => 'required|string',
            'package' => 'required|numeric',
            'amount' => 'required|numeric',
            'delivery_address' => 'required|string',
            'pickup_address' => 'required|string',
            'rental_date' => 'required|date|after_or_equal:' . now(),
            'return_date' => 'nullable|date|minimun_two_days_of_booking|after_or_equal:rental_date',
            'rental_duration' => 'nullable|numeric',
            'card_image' => 'required|file|mimes:jpg,jpeg,png|max:5400',
            'additional_message' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->validation_error($validator);
    }
}
