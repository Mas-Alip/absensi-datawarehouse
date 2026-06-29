<?php

namespace App\Http\Requests;

use App\Enums\AttendanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePresensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'jenis_presensi' => [
                'required',
                Rule::in([
                    AttendanceStatus::HADIR->value,
                ]),
            ],
        ];
    }
}
