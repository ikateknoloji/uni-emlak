<?php

namespace App\Http\Requests\Listing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\ContainsHtml;

class StepTwoListingRequest extends FormRequest
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
            'images'                        => 'required|array',
            'images.*.image_path'           => 'required|string',
            'images.*.medium_image_path'    => 'required|string',
            'images.*.thumbnail_path'       => 'required|string',
            'images.*.width'                => 'required|integer',
            'images.*.height'               => 'required|integer',
            'images.*.main_image'           => 'required|boolean',
            'images.*.order_number'         => 'required|integer',
            'details.content'               => ['required', 'string', 'min:50', new ContainsHtml()],
        ];
    }

    public function messages(): array
    {
        return [
            'images.required'                        => 'En az bir resim yüklemelisiniz.',
            'images.array'                           => 'Resimler uygun formatta gönderilmedi.',
            'images.*.image_path.required'           => 'Resmin ana dosya yolu zorunludur.',
            'images.*.image_path.string'             => 'Resmin ana dosya yolu metin formatında olmalıdır.',
            'images.*.medium_image_path.required'    => 'Resmin orta boy dosya yolu zorunludur.',
            'images.*.medium_image_path.string'      => 'Resmin orta boy dosya yolu metin formatında olmalıdır.',
            'images.*.thumbnail_path.required'       => 'Resmin küçük boy dosya yolu zorunludur.',
            'images.*.thumbnail_path.string'         => 'Resmin küçük boy dosya yolu metin formatında olmalıdır.',
            'images.*.width.required'                => 'Resim genişliği zorunludur.',
            'images.*.width.integer'                 => 'Resim genişliği sayısal olmalıdır.',
            'images.*.height.required'               => 'Resim yüksekliği zorunludur.',
            'images.*.height.integer'                => 'Resim yüksekliği sayısal olmalıdır.',
            'images.*.main_image.required'           => 'Ana resim bilgisi zorunludur.',
            'images.*.main_image.boolean'            => 'Ana resim bilgisi true veya false olmalıdır.',
            'images.*.order_number.required'         => 'Resim sıralama numarası zorunludur.',
            'images.*.order_number.integer'          => 'Resim sıralama numarası sayısal olmalıdır.',
            'details.content.required'               => 'Detaylı açıklama (içerik) alanı zorunludur.',
            'details.content.string'                 => 'Detaylı açıklama metin formatında olmalıdır.',
            'details.content.min'                    => 'Detaylı açıklama en az 50 karakter olmalıdır.',
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
