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
        $cardImage = '|file|mimes:jpg,jpeg,png,pdf|max:5400';
        return [
            'full_name' => 'required|string|min:3',
            'total_unit' => 'required|numeric|min:1',
            'email' => 'required|email:dns',
            'whatsapp_number' => 'required|string',
            'motor_name' => 'required|string',
            'package' => 'required|numeric',
            'amount' => 'required|numeric',
            'delivery_address' => 'nullable|string',
            'pickup_address' => 'required|string',
            'rental_date' => 'required|date|after_or_equal:' . now(),
            'return_date' => 'nullable|date|minimun_two_days_of_booking|after:rental_date',
            'rental_duration' => 'nullable|numeric',
            'card_image' => $this->route()->getName() === 'add.booking' ? 'required' . $cardImage : 'nullable' . $cardImage,
            'additional_message' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'The full name field is required.',
            'full_name.string' => 'The full name must be a string.',
            'full_name.min' => 'The full name must be at least :min characters.',
            'total_unit.required' => 'The total unit field is required.',
            'total_unit.numeric' => 'The total unit must be a number.',
            'total_unit.min' => 'The total unit must be at least :min.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.dns' => 'The email domain is not resolvable.',
            'whatsapp_number.required' => 'The WhatsApp number field is required.',
            'whatsapp_number.string' => 'The WhatsApp number must be a string.',
            'motor_name.required' => 'The motor name field is required.',
            'motor_name.string' => 'The motor name must be a string.',
            'package.required' => 'The package field is required.',
            'package.numeric' => 'The package must be a number.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a number.',
            //'delivery_address.required' => 'The delivery address field is required.',
            'delivery_address.string' => 'The delivery address must be a string.',
            'pickup_address.required' => 'The pickup address field is required.',
            'pickup_address.string' => 'The pickup address must be a string.',
            'rental_date.required' => 'The rental date field is required.',
            'rental_date.date' => 'The rental date must be a valid date.',
            'rental_date.after_or_equal' => 'The rental date must be after or equal to the current date.',
            'return_date.date' => 'The return date must be a valid date.',
            'return_date.minimun_two_days_of_booking' => 'The return date must be at least two days after the rental date.',
            'return_date.after' => 'The return date must be after the rental date.',
            'rental_duration.numeric' => 'The rental duration must be a number.',
            'card_image.required' => 'The card image field is required.',
            'card_image.file' => 'The card image must be a file.',
            'card_image.mimes' => 'The card image must be in one of the following formats: jpg, jpeg, png, pdf.',
            'card_image.max' => 'The card image may not be greater than :max kilobytes.',
            'additional_message.string' => 'The additional message must be a string.',

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->validation_error($validator);
    }
}
