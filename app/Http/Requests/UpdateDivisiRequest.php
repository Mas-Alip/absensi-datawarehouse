<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDivisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_divisi' => [
                'required',
                'string',
                'max:255',
                Rule::unique('divisi', 'nama_divisi')->ignore($this->divisi),
            ],
        ];
    }
}
