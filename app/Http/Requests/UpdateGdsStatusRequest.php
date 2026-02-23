<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGdsStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gds_code' => 'required|string|max:20',
            'gds_status' => 'required|in:0,1',
        ];
    }
}
