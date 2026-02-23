<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveBankAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'routing_no' => 'nullable|string|max:50',
            'acc_holder_name' => 'required|string|max:255',
            'acc_no' => 'required|string|max:50',
            'swift_code' => 'nullable|string|max:20',
        ];
    }
}
