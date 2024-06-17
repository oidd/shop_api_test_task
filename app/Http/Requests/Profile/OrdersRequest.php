<?php

namespace App\Http\Requests\Profile;

use App\Contracts\HaveOrdersContract;
use Illuminate\Foundation\Http\FormRequest;

class OrdersRequest extends FormRequest
{
    public function authorize()
    {
        return ($this->user() instanceof HaveOrdersContract);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
