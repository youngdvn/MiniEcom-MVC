<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'code' => 'required|string|max:50|alpha_dash|unique:coupons,code,' . $id,
            'type' => 'required|in:percent,fixed',
            'value' => 'required|integer|min:1|max:100000000',
            'max_discount' => 'nullable|integer|min:1|max:100000000',
            'min_order' => 'nullable|integer|min:0|max:100000000',
            'usage_limit' => 'nullable|integer|min:1|max:100000000',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'status' => 'required|boolean',
        ];
    }
}
