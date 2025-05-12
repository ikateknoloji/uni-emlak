<?php

namespace App\Http\Requests\Listing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePublicationStatusRequest extends FormRequest
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
            'status' => 'required|in:published,inactive,archived',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Yayın durumu zorunludur.',
            'status.in'       => 'Yayın durumu yalnızca published, inactive veya archived olabilir.',
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
