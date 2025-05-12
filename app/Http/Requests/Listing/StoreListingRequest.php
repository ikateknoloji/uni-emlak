<?php

namespace App\Http\Requests\Listing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\ContainsHtml;

class StoreListingRequest extends FormRequest
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
            'ilan.price'            => 'required|numeric|min:1',
            'ilan.square_meters'    => 'required|numeric|min:0.01',
            'ilan.title'            => 'required|string|min:10',
            'ilan.description'      => 'required|string|min:30',
            'ilan.neighborhood_id'  => 'required|integer|exists:neighborhoods,id',
            'ilan.full_address'     => 'required|string',
            'ilan.is_loan_eligible' => 'boolean',

            'details.content'               => ['required', 'string', new ContainsHtml()],
            'images'                        => 'required|array|min:1',
            'images.*.image_path'           => 'required|string',
            'images.*.medium_image_path'    => 'required|string',
            'images.*.thumbnail_path'       => 'required|string',
            'images.*.width'                => 'required|integer',
            'images.*.height'               => 'required|integer',
            'images.*.main_image'           => 'required|boolean',
            'images.*.order_number'         => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'ilan.deed_type_id.required'     => 'Tapu durumu alanı zorunludur.',
            'ilan.deed_type_id.integer'      => 'Tapu durumu geçerli bir ID olmalıdır.',
            'ilan.deed_type_id.exists'       => 'Seçilen tapu durumu geçersizdir.',

            'ilan.property_type_id.required' => 'Emlak tipi alanı zorunludur.',
            'ilan.property_type_id.integer'  => 'Emlak tipi geçerli bir ID olmalıdır.',
            'ilan.property_type_id.exists'   => 'Seçilen emlak tipi geçersizdir.',

            'ilan.listing_type_id.required'  => 'İlan türü alanı zorunludur.',
            'ilan.listing_type_id.integer'   => 'İlan türü geçerli bir ID olmalıdır.',
            'ilan.listing_type_id.exists'    => 'Seçilen ilan türü geçersizdir.',

            'ilan.block_number.required'     => 'Ada numarası zorunludur.',
            'ilan.block_number.string'       => 'Ada numarası metin formatında olmalıdır.',

            'ilan.parcel_number.required'    => 'Parsel numarası zorunludur.',
            'ilan.parcel_number.string'      => 'Parsel numarası metin formatında olmalıdır.',

            'ilan.price.required'            => 'Fiyat alanı zorunludur.',
            'ilan.price.numeric'             => 'Fiyat sayısal olmalıdır.',
            'ilan.price.min'                 => 'Fiyat en az 1 olmalıdır.',

            'ilan.square_meters.required'    => 'Metrekare alanı zorunludur.',
            'ilan.square_meters.numeric'     => 'Metrekare sayısal olmalıdır.',
            'ilan.square_meters.min'         => 'Metrekare en az 0.01 olmalıdır.',

            'ilan.title.required'            => 'İlan başlığı zorunludur.',
            'ilan.title.string'              => 'İlan başlığı metin olmalıdır.',
            'ilan.title.min'                 => 'İlan başlığı en az 10 karakter olmalıdır.',

            'ilan.description.required'      => 'Açıklama alanı zorunludur.',
            'ilan.description.string'        => 'Açıklama metin formatında olmalıdır.',
            'ilan.description.min'           => 'Açıklama en az 30 karakter olmalıdır.',

            'ilan.neighborhood_id.required'  => 'Mahalle alanı zorunludur.',
            'ilan.neighborhood_id.integer'   => 'Mahalle ID geçerli olmalıdır.',
            'ilan.neighborhood_id.exists'    => 'Seçilen mahalle geçersizdir.',

            'ilan.full_address.required'     => 'Adres bilgisi zorunludur.',
            'ilan.full_address.string'       => 'Adres metin formatında olmalıdır.',

            'ilan.is_loan_eligible.boolean'  => 'Krediye uygunluk değeri true/false olmalıdır.',

            'details.content.required'       => 'Detaylı açıklama zorunludur.',
            'details.content.string'         => 'Detaylı açıklama metin olmalıdır.',

            'images.required'                => 'En az bir resim yüklemelisiniz.',
            'images.array'                   => 'Resimler uygun formatta gönderilmelidir.',
            'images.min'                     => 'En az bir resim yüklemelisiniz.',

            'images.*.image_path.required'        => 'Resim dosya yolu zorunludur.',
            'images.*.medium_image_path.required' => 'Orta boy resim yolu zorunludur.',
            'images.*.thumbnail_path.required'    => 'Küçük boy resim yolu zorunludur.',
            'images.*.width.required'             => 'Resim genişliği zorunludur.',
            'images.*.width.integer'              => 'Resim genişliği sayısal olmalıdır.',
            'images.*.height.required'            => 'Resim yüksekliği zorunludur.',
            'images.*.height.integer'             => 'Resim yüksekliği sayısal olmalıdır.',
            'images.*.main_image.required'        => 'Ana resim bilgisi zorunludur.',
            'images.*.main_image.boolean'         => 'Ana resim true/false olmalıdır.',
            'images.*.order_number.required'      => 'Resim sıralaması zorunludur.',
            'images.*.order_number.integer'       => 'Resim sıralaması sayısal olmalıdır.',
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
