<?php

namespace App\Http\Requests\Catalog;

use App\Trait\HasCustomResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCatalogRequest extends FormRequest
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
            'motor_name' => 'required|string|min:3|unique:catalog_motors',
            'charge' => 'required|numeric',
            'path_catalog' => 'required|array',
            'path_catalog.*' => 'required|string',
            'price_lists' => 'required|array',
            'price_lists.*' => 'required|array',
            'price_lists.*.price' => 'required|numeric',
            'price_lists.*.duration' => 'required|numeric|min:1',
            'price_lists.*.duration_suffix' => 'required|string|min:3',
            'price_lists.*.package' => 'required|string|min:3|distinct',
        ];
    }

    public function messages()
    {
        return [
            'motor_name.required' => 'Nama motor wajib diisi.',
            'motor_name.string' => 'Nama motor harus berupa teks.',
            'motor_name.min' => 'Nama motor setidaknya harus memiliki 3 karakter.',
            'motor_name.unique' => 'Nama motor sudah digunakan sebelumnya.',
        
            'charge.required' => 'Harga wajib diisi.',
            'charge.numeric' => 'Harga harus berupa angka.',
        
            'path_catalog.required' => 'Daftar path katalog wajib diisi.',
            'path_catalog.array' => 'Daftar path katalog harus berupa array.',
            'path_catalog.*.required' => 'Setiap path katalog wajib diisi.',
            'path_catalog.*.string' => 'Setiap path katalog harus berupa teks.',
        
            'price_lists.required' => 'Daftar harga wajib diisi.',
            'price_lists.array' => 'Daftar harga harus berupa array.',
            'price_lists.*.required' => 'Setiap harga wajib diisi.',
            'price_lists.*.array' => 'Setiap harga harus berupa array.',
            'price_lists.*.price.required' => 'Harga wajib diisi.',
            'price_lists.*.price.numeric' => 'Harga harus berupa angka.',
            'price_lists.*.duration.required' => 'Durasi wajib diisi.',
            'price_lists.*.duration.numeric' => 'Durasi harus berupa angka.',
            'price_lists.*.duration.min' => 'Durasi minimal adalah 1.',
            'price_lists.*.duration_suffix.required' => 'Sufiks durasi wajib diisi.',
            'price_lists.*.duration_suffix.string' => 'Sufiks durasi harus berupa teks.',
            'price_lists.*.duration_suffix.min' => 'Sufiks durasi minimal adalah 3 karakter.',
            'price_lists.*.package.required' => 'Paket wajib diisi.',
            'price_lists.*.package.string' => 'Paket harus berupa teks.',
            'price_lists.*.package.min' => 'Paket setidaknya harus memiliki 3 karakter.',
            'price_lists.*.package.distinct' => 'Setiap paket harus unik, tidak ada paket yang sama dalam daftar.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->validation_error($validator);
    }
}
