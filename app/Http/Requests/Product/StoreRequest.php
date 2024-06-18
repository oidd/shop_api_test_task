<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|string',
            'price'         => 'required|numeric|decimal:0,2|gte:0',
            'category_id'   => 'required|exists:categories,id',
            'stock'         => 'integer|gte:0'
        ];
    }
}
