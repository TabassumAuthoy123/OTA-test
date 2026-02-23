<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'nullable|url|max:500',
            'status' => 'required|in:0,1',
            'banner_id' => 'required|integer',
        ];
    }
}
