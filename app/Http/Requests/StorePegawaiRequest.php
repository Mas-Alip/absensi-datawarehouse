<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nip' => 'required|string|max:20|unique:pegawai,nip',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => ['required', Rule::in(['male', 'female'])],
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'tanggal_masuk' => 'nullable|date',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'divisi_id' => ['required', 'integer', 'exists:divisi,id'],
            'jabatan_id' => ['required', 'integer', 'exists:jabatan,id'],
        ];
    }
}
