<?php

namespace App\Http\Requests\Order;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
//        return $this->user()->can('show', $this->route('order'));
    }
}
