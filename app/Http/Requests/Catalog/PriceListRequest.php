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
            'motor_name' => 'required|string',
            'price_lists' => 'required|array',
            'price_lists.*' => 'required|array',
            'price_lists.*.price' => 'required|numeric',
            'price_lists.*.duration' => 'required|numeric|min:1',
            'price_lists.*.duration_suffix' => 'required|string|min:3',
            'price_lists.*.package' => ['required' , 'string' , 'distinct' , 'min:3' , Rule::unique('catalog_prices' , 'package')->where('catalog_motor_id' , $this->get('catalog')->id)]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->validation_error($validator);
    }
}
