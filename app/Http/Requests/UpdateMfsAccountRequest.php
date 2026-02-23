<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMfsAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => 'required|string',
            'account_type' => 'required|string|max:100',
            'acc_no' => 'required|string|max:20',
            'status' => 'required|in:0,1',
        ];
    }
}
