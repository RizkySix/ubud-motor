<?php

namespace App\Http\Requests\Profile;

use App\Trait\HasCustomResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use PhpParser\Node\Expr\FuncCall;

class AdminProfileRequest extends FormRequest
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
            'full_name' => 'nullable|string|min:5',
            'password' => 'nullable|confirmed|string|min:8',
            'phone_number' => 'nullable|string|min:11|max:14'
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Kolom nama lengkap wajib diisi.',
            'full_name.string' => 'Kolom nama lengkap harus berupa teks.',
            'full_name.min' => 'Kolom nama lengkap minimal harus terdiri dari :min karakter.',
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
