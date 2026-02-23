<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveB2bUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'comission' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'brand_logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:20',
        ];
    }
}
