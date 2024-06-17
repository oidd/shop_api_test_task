<?php

namespace App\Http\Requests\Product;

use App\Rules\IsCorrectIntRange;
use App\Rules\IsCorrectNumericRange;
use App\Traits\HandlesSorting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    use HandlesSorting;

    protected function prepareForValidation()
    {
        $this->wrapAttributesIntoArray(['category_id']);
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
            'category_id' => 'array',
            'category_id.*' =>
                Rule::when($this->has('category_id'), 'exists:categories,id'),
            'price_range' => [new IsCorrectNumericRange],
            'stock_range' => [new IsCorrectIntRange],
            'in_stock' => 'boolean',
            'sort' => 'array',
            'sort.*' => $this->getSortAttributes(['name', 'category_id', 'price', 'stock']),
        ];
    }
}
