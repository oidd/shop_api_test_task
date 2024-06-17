<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsCorrectDateRange implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail("$attribute should be a string");
        }

        if (!str_contains($value, '-')) {
            $fail("$attribute should contain delimiter '-'");
        }

        if (count($dates = explode('-', $value)) != 2) {
            $fail("$attribute should contain only one delimiter '-' and have two values");
        }

        if (!$this->isValidDate($dates[0]) || !$this->isValidDate($dates[1])) {
            $fail("$attribute: both values should be valid dates in format Y.m.d");
        }

        if (Carbon::createFromFormat('Y.m.d', $dates[0])->gt(Carbon::createFromFormat('Y.m.d', $dates[1]))) {
            $fail("$attribute: first date should be less than or equal to the second date");
        }
    }

    private function isValidDate($date): bool
    {
        $d = Carbon::createFromFormat('Y.m.d', $date);
        return $d && $d->format('Y.m.d') === $date;
    }
}
