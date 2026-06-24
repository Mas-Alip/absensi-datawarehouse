<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAbsensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pegawai_id' => ['required', 'integer', 'exists:pegawai,id'],
            'tanggal' => ['required', 'date'],
            'jam_masuk' => ['nullable', 'date_format:H:i'],
            'jam_keluar' => ['nullable', 'date_format:H:i', 'after_or_equal:jam_masuk'],
            'status_kehadiran' => ['required', Rule::in(['hadir','izin','sakit','alpa'])],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }
}
