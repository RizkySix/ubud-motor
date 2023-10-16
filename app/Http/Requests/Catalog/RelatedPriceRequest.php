<?php

namespace App\Http\Requests\Catalog;

use App\Trait\HasCustomResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RelatedPriceRequest extends FormRequest
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
            'motor' => 'required|numeric|min:1',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return $this->validation_error($validator);
    }
}
