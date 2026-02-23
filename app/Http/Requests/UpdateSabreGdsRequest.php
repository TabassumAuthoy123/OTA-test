<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSabreGdsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pcc' => 'required|string|max:20',
            'user_id' => 'required|string|max:100',
            'password' => 'required|string|max:255',
            'is_production' => 'nullable|in:0,1',
        ];
    }
}
