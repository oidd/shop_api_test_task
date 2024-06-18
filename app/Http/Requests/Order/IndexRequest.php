<?php

namespace App\Http\Requests\Order;

use App\Models\Order;
use App\Rules\IsCorrectDateRange;
use App\Rules\IsCorrectNumericRange;
use App\Traits\HandlesSorting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    use HandlesSorting;

    protected function prepareForValidation()
    {
        $this->wrapAttributesIntoArray(['customer_id', 'status', 'created_at']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'array',
            'customer_id.*' =>
                Rule::when($this->has('customer_id'), 'exists:customers,id'),
            'amount_range' => [new IsCorrectNumericRange],
            'status' => 'array',
            'status.*' => 'in:new,confirmed,cancelled',
            'created_at' => 'array',
            'created_at.*' => 'date_format:Y.m.d',
            'created_at_range' => [new IsCorrectDateRange],
            'sort' => 'array',
            'sort.*' => $this->getSortAttributes(['id', 'status', 'amount', 'customer_id', 'created_at']),
        ];
    }
}
