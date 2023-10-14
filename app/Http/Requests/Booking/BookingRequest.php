<?php

namespace App\Http\Requests\Booking;

use App\Trait\HasCustomResponse;
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
            'delivery_address' => 'required|string',
            'pickup_address' => 'required|string',
            'additional_message' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->validation_error($validator);
    }
}
