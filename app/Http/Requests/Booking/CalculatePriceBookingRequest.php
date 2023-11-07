<?php

namespace App\Http\Requests\Booking;

use App\Trait\HasCustomResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CalculatePriceBookingRequest extends FormRequest
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
            'package' => 'required|numeric',
            'type' => 'nullable|string',
            'total_unit' => 'required|numeric|min:1',
            'rental_date' => 'required|date|after_or_equal:' . now(),
            'return_date' => [
                'nullable',
                'date',
                'after:rental_date',
                !$this->filled('type') ? 'minimun_two_days_of_booking' : '', 
            ],
            'rental_duration' => 'nullable|numeric'
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        $this->validation_error($validator);
    }
}
