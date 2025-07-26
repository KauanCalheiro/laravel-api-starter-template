<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    protected ?string $formatted = null;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $digits = preg_replace('/\D+/', '', (string) $value);

        if (str_starts_with($digits, '55')) {
            $digits = mb_substr($digits, 2);
        }

        $len = strlen($digits);
        if ($len === 10) {
            $pattern = '/^[1-9]{2}[2-9]\d{7}$/';
            $example = '(11) 2345-6789';
        } elseif ($len === 11) {
            $pattern = '/^[1-9]{2}9\d{8}$/';
            $example = '(21) 91234-5678';
        } else {
            $pattern = null;
            $example = '(21) 91234-5678';
        }

        if (! $pattern || ! preg_match($pattern, $digits)) {
            $fail(
                __('validation.phone_number', [
                    'attribute' => $attribute,
                    'format'    => $example,
                ]),
            );
            return;
        }

        $this->formatted = $this->formatDigits($digits);
    }

    public function formatDigits(string $digits): string
    {
        $ddd  = substr($digits, 0, 2);
        $rest = substr($digits, 2);

        if (strlen($rest) === 8) {
            return sprintf(
                '(%s) %s-%s',
                $ddd,
                substr($rest, 0, 4),
                substr($rest, 4),
            );
        }

        return sprintf(
            '(%s) %s-%s',
            $ddd,
            substr($rest, 0, 5),
            substr($rest, 5),
        );
    }

    public function getFormatted(): ?string
    {
        return $this->formatted;
    }
}
