<?php

namespace App\Http\Requests\Listing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StepOneListingRequest extends FormRequest
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
            'ilan.deed_type_id'     => 'required|integer|exists:deed_types,id',
            'ilan.property_type_id' => 'required|integer|exists:property_types,id',
            'ilan.listing_type_id'  => 'required|integer|exists:listing_types,id',
            'ilan.block_number'     => 'required|numeric',
            'ilan.parcel_number'    => 'required|numeric',
            'ilan.price'            => 'required|numeric',
            'ilan.square_meters'    => 'required|numeric',
            'ilan.title'            => 'required|string|min:10',
            'ilan.description'      => 'required|string|min:30',
            'ilan.neighborhood_id'  => 'required|integer|exists:neighborhoods,id',
            'ilan.full_address'     => 'required|string',
            'ilan.is_loan_eligible' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'ilan.deed_type_id.required'    => 'Tapu durumu alanı zorunludur.',
            'ilan.deed_type_id.integer'     => 'Tapu durumu geçerli bir ID olmalıdır.',
            'ilan.deed_type_id.exists'      => 'Seçilen tapu durumu geçersizdir.',

            'ilan.property_type_id.required' => 'Emlak tipi alanı zorunludur.',
            'ilan.property_type_id.integer' => 'Emlak tipi geçerli bir ID olmalıdır.',
            'ilan.property_type_id.exists'  => 'Seçilen emlak tipi geçersizdir.',

            'ilan.listing_type_id.required' => 'İlan türü alanı zorunludur.',
            'ilan.listing_type_id.integer'  => 'İlan türü geçerli bir ID olmalıdır.',
            'ilan.listing_type_id.exists'   => 'Seçilen ilan türü geçersizdir.',

            'ilan.block_number.required'    => 'Ada numarası zorunludur.',
            'ilan.block_number.string'      => 'Ada numarası metin formatında olmalıdır.',

            'ilan.parcel_number.required'   => 'Parsel numarası zorunludur.',
            'ilan.parcel_number.string'     => 'Parsel numarası metin formatında olmalıdır.',

            'ilan.price.required'           => 'Fiyat alanı zorunludur.',
            'ilan.price.numeric'            => 'Fiyat alanı sayısal olmalıdır.',

            'ilan.square_meters.required'   => 'Metrekare alanı zorunludur.',
            'ilan.square_meters.numeric'    => 'Metrekare alanı sayısal olmalıdır.',

            'ilan.title.required'           => 'İlan başlığı zorunludur.',
            'ilan.title.string'             => 'İlan başlığı metin olmalıdır.',
            'ilan.title.min'                => 'İlan başlığı en az 10 karakter olmalıdır.',

            'ilan.description.required'     => 'Açıklama alanı zorunludur.',
            'ilan.description.string'       => 'Açıklama metin formatında olmalıdır.',
            'ilan.description.min'          => 'Açıklama en az 30 karakter olmalıdır.',

            'ilan.neighborhood_id.required' => 'Mahalle alanı zorunludur.',
            'ilan.neighborhood_id.integer'  => 'Mahalle geçerli bir ID olmalıdır.',
            'ilan.neighborhood_id.exists'   => 'Seçilen mahalle geçersizdir.',

            'ilan.full_address.required'    => 'Adres alanı zorunludur.',
            'ilan.full_address.string'      => 'Adres alanı metin formatında olmalıdır.',

            'ilan.is_loan_eligible.boolean' => 'Krediye uygunluk bilgisi geçersiz.',
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
