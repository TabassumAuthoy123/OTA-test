<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFlyhubGdsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'api_endpoint' => 'required|url|max:500',
            'api_key' => 'required|string|max:255',
            'is_production' => 'nullable|in:0,1',
        ];
    }
}
