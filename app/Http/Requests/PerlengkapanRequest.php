<?php

namespace App\Http\Requests;

use App\Models\Perlengkapan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerlengkapanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $perlengkapan = $this->route('perlengkapan');

        return [
            'kode'    => ['required', 'string', 'max:20', Rule::unique('perlengkapan', 'kode')->ignore($perlengkapan?->id)],
            'nama'    => ['required', 'string', 'max:255'],
            'jenis'   => ['required', Rule::in(array_keys(Perlengkapan::JENIS))],
            'kondisi' => ['required', Rule::in(array_keys(Perlengkapan::KONDISI))],
        ];
    }
}
