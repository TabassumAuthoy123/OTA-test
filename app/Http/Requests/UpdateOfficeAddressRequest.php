<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfficeAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'office_address_id' => 'required|integer',
            'office_name_update' => 'required|string|max:255',
            'office_address_update' => 'required|string|max:1000',
            'office_address_status' => 'required|in:0,1',
        ];
    }
}
