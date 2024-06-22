<?php

namespace App\Http\Requests;

use App\Rules\IsCorrectDateRange;
use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from' => 'date_format:Y.m.d',
            'to' => 'date_format:Y.m.d',
        ];
    }
}
