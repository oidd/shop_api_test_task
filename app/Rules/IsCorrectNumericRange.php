<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsCorrectNumericRange implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!(is_string($value)))
            $fail("$attribute should be a string");

        if (!str_contains($value, '-'))
            $fail("$attribute should contain delimiter '-'");

        if (count($exp = explode('-', $value)) != 2)
            $fail("$attribute should contain only one delimiter '-' and has to have two values");

        if (!(is_numeric($exp[0]) && is_numeric($exp[1])))
            $fail("$attribute: both values should be numeric");

        if ((float) $exp[0] > (float) $exp[1])
            $fail("$attribute: first value should be less than or equal to $exp[0]");
    }
}
