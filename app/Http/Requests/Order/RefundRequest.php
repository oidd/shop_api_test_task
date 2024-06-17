<?php

namespace App\Http\Requests\Order;

use App\Models\Admin;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('refund', Order::class);
    }
}
