<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Intervention\Image\Laravel\Facades\Image;

class IsLandscapeImage implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $image = Image::read($value);

            if ($image->width() <= $image->height()) {
                $fail('Yüklenen resim yatay (landscape) formatta olmalıdır.');
            }
        } catch (\Throwable $e) {
            $fail('Resim doğrulanırken hata oluştu.');
        }
    }
}
