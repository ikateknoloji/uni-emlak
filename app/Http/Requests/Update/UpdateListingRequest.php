<?php

namespace App\Http\Requests\Update;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'deed_type_id'     => 'required|integer|exists:deed_types,id',
            'property_type_id' => 'required|integer|exists:property_types,id',
            'listing_type_id'  => 'required|integer|exists:listing_types,id',
            'block_number'     => 'required|string',
            'parcel_number'    => 'required|string',
            'price'            => 'required|numeric|min:1',
            'square_meters'    => 'required|numeric|min:0.01',
            'title'            => 'required|string|min:10',
            'description'      => 'required|string|min:30',
            'neighborhood_id'  => 'required|integer|exists:neighborhoods,id',
            'full_address'     => 'required|string',
            'is_loan_eligible' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'deed_type_id.required'     => 'Tapu durumu zorunludur.',
            'property_type_id.required' => 'Emlak tipi zorunludur.',
            'listing_type_id.required'  => 'İlan türü zorunludur.',
            'block_number.required'     => 'Ada numarası zorunludur.',
            'parcel_number.required'    => 'Parsel numarası zorunludur.',
            'price.required'            => 'Fiyat zorunludur.',
            'square_meters.required'    => 'Metrekare bilgisi zorunludur.',
            'title.required'            => 'Başlık zorunludur.',
            'description.required'      => 'Açıklama zorunludur.',
            'neighborhood_id.required'  => 'Mahalle ID zorunludur.',
            'full_address.required'     => 'Adres bilgisi zorunludur.',
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
