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

    public function messages()
    {
        return [
            'price_lists.required' => 'Daftar harga wajib diisi.',
            'price_lists.*.required' => 'Setiap detail harga wajib diisi.',
            'price_lists.*.price.required' => 'Harga wajib diisi.',
            'price_lists.*.price.numeric' => 'Harga harus berupa angka.',
            'price_lists.*.duration.required' => 'Durasi wajib diisi.',
            'price_lists.*.duration.numeric' => 'Durasi harus berupa angka.',
            'price_lists.*.duration.min' => 'Durasi minimal adalah 1.',
            'price_lists.*.duration_suffix.required' => 'Sufiks durasi wajib diisi.',
            'price_lists.*.duration_suffix.string' => 'Sufiks durasi harus berupa teks.',
            'price_lists.*.duration_suffix.min' => 'Sufiks durasi minimal adalah 3 karakter.',
            'price_lists.*.package.required' => 'Nama paket wajib diisi.',
            'price_lists.*.package.string' => 'Nama paket harus berupa teks.',
            'price_lists.*.package.distinct' => 'Nama paket tidak boleh duplikat.',
            'price_lists.*.package.min' => 'Nama paket minimal adalah 3 karakter.',
            'price_lists.*.package.unique' => 'Nama paket ini sudah digunakan.'

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->validation_error($validator);
    }
}
