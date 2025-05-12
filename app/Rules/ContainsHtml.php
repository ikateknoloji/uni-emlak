<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ContainsHtml implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // HTML içermiyorsa
        if ($value === strip_tags($value)) {
            $fail('İçerik HTML etiketleri içermelidir.');
            return;
        }

        // script etiketi varsa engelle
        if (preg_match('/<\s*script\b/i', $value)) {
            $fail('İçerik script etiketi içeremez.');
        }
    }
}
