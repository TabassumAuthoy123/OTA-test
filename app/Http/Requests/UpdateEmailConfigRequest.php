<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
            'mail_from_name' => 'nullable|string|max:255',
            'mail_from_email' => 'required|email|max:255',
            'encryption' => 'required|in:0,1,2',
        ];
    }
}
