<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSmsGatewayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider' => 'required|string|in:elitbuzz,revesms,khudebarta',
            'api_endpoint' => 'required|url|max:500',
            'api_key' => 'required|string|max:255',
            'sender_id' => 'required|string|max:50',
        ];
    }
}
