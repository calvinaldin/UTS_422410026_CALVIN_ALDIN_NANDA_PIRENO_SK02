<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $kategori = $this->route('kategori');

        return [
            'nama'      => ['required', 'string', 'max:100', Rule::unique('kategori', 'nama')->ignore($kategori?->id)],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
