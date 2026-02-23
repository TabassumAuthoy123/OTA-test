<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitRechargeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'payment_method' => 'required|integer',
            'recharge_amount' => 'required|numeric|min:1',
            'transaction_id' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:500',
        ];
    }
}
