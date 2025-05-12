<?php

namespace App\Http\Requests\Listing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListingImageUpdateRequest extends FormRequest
{
  
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image_path'         => 'required|string',
            'medium_image_path'  => 'required|string',
            'thumbnail_path'     => 'required|string',
            'width'              => 'required|integer',
            'height'             => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'image_path.required'         => 'Resmin ana yolu zorunludur.',
            'medium_image_path.required'  => 'Orta boy resim yolu zorunludur.',
            'thumbnail_path.required'     => 'Küçük boy resim yolu zorunludur.',
            'width.required'              => 'Genişlik değeri zorunludur.',
            'width.integer'               => 'Genişlik sayısal olmalıdır.',
            'height.required'             => 'Yükseklik değeri zorunludur.',
            'height.integer'              => 'Yükseklik sayısal olmalıdır.',
            'order_number.required'       => 'Sıralama değeri zorunludur.',
            'order_number.integer'        => 'Sıralama değeri sayısal olmalıdır.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'status'  => false,
                'message' => 'Doğrulama hatası oluştu.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
