<?php

namespace App\Http\Requests\Listing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListingImageStoreRequest extends FormRequest
{
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
            'listing_id'         => 'required|exists:listings,id',
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
            'listing_id.required'        => 'İlan ID alanı zorunludur.',
            'listing_id.exists'          => 'İlan bulunamadı.',
            'image_path.required'        => 'Resim yolu zorunludur.',
            'medium_image_path.required' => 'Orta boy resim yolu zorunludur.',
            'thumbnail_path.required'    => 'Küçük boy resim yolu zorunludur.',
            'width.required'             => 'Genişlik zorunludur.',
            'height.required'            => 'Yükseklik zorunludur.',
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
