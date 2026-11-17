<?php

namespace App\Http\Requests;

use App\Models\SesiPengamatan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SesiPengamatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sesi = $this->route('sesi'); // null saat store, model saat update

        return [
            'kode_sesi'          => ['required', 'string', 'max:20', Rule::unique('sesi_pengamatan', 'kode_sesi')->ignore($sesi?->id)],
            'nama_sesi'          => ['required', 'string', 'max:255'],
            'objek_sasaran'      => ['required', 'string', 'max:255'],
            'kategori_id'        => ['required', 'integer', 'exists:kategori,id'],
            'tanggal_pengamatan' => ['required', 'date'],
            'waktu_mulai'        => ['nullable', 'date_format:H:i'],
            // tanpa aturan "after": pengamatan malam hari sering melewati tengah malam (mis. 22:00 - 04:00)
            'waktu_selesai'      => ['nullable', 'date_format:H:i'],
            'status'             => ['required', Rule::in(array_keys(SesiPengamatan::STATUS))],
            'catatan'            => ['nullable', 'string', 'max:2000'],

            // Detail lokasi (One-to-One)
            'lokasi.nama_lokasi'   => ['required', 'string', 'max:255'],
            'lokasi.lintang'       => ['required', 'numeric', 'between:-90,90'],
            'lokasi.bujur'         => ['required', 'numeric', 'between:-180,180'],
            'lokasi.ketinggian'    => ['nullable', 'integer', 'min:0', 'max:9000'],
            'lokasi.catatan_cuaca' => ['nullable', 'string', 'max:1000'],

            // Perlengkapan (Many-to-Many)
            'perlengkapan_ids'   => ['nullable', 'array'],
            'perlengkapan_ids.*' => ['integer', 'distinct', 'exists:perlengkapan,id'],
            'jumlah'             => ['nullable', 'array'],
            'jumlah.*'           => ['nullable', 'integer', 'min:1', 'max:99'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_sesi'            => 'kode sesi',
            'nama_sesi'            => 'nama sesi',
            'objek_sasaran'        => 'objek sasaran',
            'kategori_id'          => 'kategori',
            'tanggal_pengamatan'   => 'tanggal pengamatan',
            'waktu_mulai'          => 'waktu mulai',
            'waktu_selesai'        => 'waktu selesai',
            'lokasi.nama_lokasi'   => 'nama lokasi',
            'lokasi.lintang'       => 'lintang',
            'lokasi.bujur'         => 'bujur',
            'lokasi.ketinggian'    => 'ketinggian',
            'lokasi.catatan_cuaca' => 'catatan cuaca',
            'perlengkapan_ids.*'   => 'perlengkapan',
            'jumlah.*'             => 'jumlah',
        ];
    }
}
