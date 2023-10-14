<?php

namespace App\Http\Requests\Catalog;

use App\Trait\HasCustomResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PriceListRequest extends FormRequest
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
            'catalog_motor_id' => 'required|numeric',
            'price_lists' => 'required|array',
            'price_lists.*' => 'required|array',
            'price_lists.*.price' => 'required|numeric',
            'price_lists.*.duration' => 'required|string|min:3',
            'price_lists.*.package' => ['required' , 'string' , 'min:3' , Rule::unique('catalog_prices' , 'package')->where('catalog_motor_id' , $this->input('catalog_motor_id'))]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->validation_error($validator);
    }
}
