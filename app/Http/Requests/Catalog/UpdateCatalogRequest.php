<?php

namespace App\Http\Requests\Catalog;

use App\Trait\HasCustomResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateCatalogRequest extends FormRequest
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
            'motor_name' => ['required' , 'string' , 'min:3' , Rule::unique('catalog_motors')->ignore($this->route('catalog'))],
            'first_catalog' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:11500',
            'second_catalog' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:11500',
            'third_catalog' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:11500',
            'charge' => 'required|numeric',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->validation_error($validator);
    }
}
