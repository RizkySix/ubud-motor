<?php

namespace App\Http\Requests\Authentication;

use App\Trait\HasCustomResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'full_name' => 'required|string|min:5',
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|confirmed|string|min:8',
            'phone_number' => 'required|numeric|digits_between:11,14'
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Kolom nama lengkap wajib diisi.',
            'full_name.string' => 'Kolom nama lengkap harus berupa teks.',
            'full_name.min' => 'Kolom nama lengkap minimal harus terdiri dari :min karakter.',
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Kolom email harus berisi alamat email yang valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.required' => 'Kolom kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.string' => 'Kolom kata sandi harus berupa number.',
            'password.min' => 'Kata sandi minimal harus terdiri dari :min karakter.',
            'phone_number.required' => 'Kolom nomor telepon wajib diisi.',
            'phone_number.numeric' => 'Kolom nomor telepon harus berupa angka.',
            'phone_number.digits_between' => 'Nomor telepon harus terdiri dari :min sampai :max karakter.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $this->validation_error($validator);
    }
}
