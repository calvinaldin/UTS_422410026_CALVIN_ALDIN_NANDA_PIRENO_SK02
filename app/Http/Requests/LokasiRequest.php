<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LokasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama_lokasi'   => ['required', 'string', 'max:255'],
            'lintang'       => ['required', 'numeric', 'between:-90,90'],
            'bujur'         => ['required', 'numeric', 'between:-180,180'],
            'ketinggian'    => ['nullable', 'integer', 'min:0', 'max:9000'],
            'catatan_cuaca' => ['nullable', 'string', 'max:1000'],
        ];

        // Hanya saat membuat: pilih sesi yang belum punya lokasi (menjaga One-to-One)
        if ($this->isMethod('POST')) {
            $rules['sesi_pengamatan_id'] = [
                'required', 'integer', 'exists:sesi_pengamatan,id',
                Rule::unique('detail_lokasi', 'sesi_pengamatan_id'),
            ];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'sesi_pengamatan_id' => 'sesi pengamatan',
            'nama_lokasi'        => 'nama lokasi',
            'catatan_cuaca'      => 'catatan cuaca',
        ];
    }

    public function messages(): array
    {
        return [
            'sesi_pengamatan_id.unique' => 'Sesi pengamatan ini sudah memiliki detail lokasi (relasi One-to-One).',
        ];
    }
}
