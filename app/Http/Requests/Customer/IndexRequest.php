<?php

namespace App\Http\Requests\Customer;

use App\Models\Customer;
use App\Rules\IsCorrectNumericRange;
use App\Traits\HandlesSorting;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    use HandlesSorting;

    protected function prepareForValidation()
    {
        $this->wrapAttributesIntoArray([]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'email' => 'string',
            'balance_range' => [new IsCorrectNumericRange],
        ];
    }
}
