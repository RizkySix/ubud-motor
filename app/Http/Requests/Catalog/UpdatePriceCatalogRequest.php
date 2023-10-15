<?php

namespace App\Http\Requests\Catalog;

use App\Trait\HasCustomResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdatePriceCatalogRequest extends FormRequest
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
        $priceInstace = $this->route('price');
        return [
            'package' => ['required' , 'string' , 'min:3' , Rule::unique('catalog_prices' , 'package')->where('catalog_motor_id' , $priceInstace->catalog_motor_id)->ignore($priceInstace)],
            'price' => 'required|numeric',
            'duration' => 'required|numeric|min:1',
            'duration_suffix' => 'required|string|min:3'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->validation_error($validator);
    }
}
