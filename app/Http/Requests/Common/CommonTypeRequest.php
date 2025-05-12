<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommonTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:20|unique:listing_types,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ad alanı zorunludur.',
            'name.string'   => 'Ad metin formatında olmalıdır.',
            'name.max'      => 'Ad en fazla 255 karakter olabilir.',
            'name.unique'   => 'Bu ad zaten kullanılmış. Lütfen farklı bir ad girin.',
        ];
    }

    
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Doğrulama hatası oluştu.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
