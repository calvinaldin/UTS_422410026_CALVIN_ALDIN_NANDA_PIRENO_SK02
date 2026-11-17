<?php

namespace App\Services;

use App\Models\SesiPengamatan;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Service Layer: seluruh logika bisnis & transaksi database untuk Sesi Pengamatan
 * berada di sini, sehingga controller tetap tipis.
 */
class SesiPengamatanService
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return SesiPengamatan::with(['kategori', 'detailLokasi'])
            ->withCount('perlengkapan')
            ->filter($filters)
            ->orderByDesc('tanggal_pengamatan')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Simpan sesi + detail lokasi (1-to-1) + perlengkapan (M:N) dalam SATU transaksi.
     * Jika salah satu gagal, seluruh perubahan dibatalkan (rollback).
     */
    public function store(array $data): SesiPengamatan
    {
        return DB::transaction(function () use ($data) {
            $sesi = SesiPengamatan::create($this->dataSesi($data));

            $sesi->detailLokasi()->create($data['lokasi']);
            $sesi->perlengkapan()->sync($this->petaPerlengkapan($data));

            return $sesi;
        });
    }

    public function update(SesiPengamatan $sesi, array $data): SesiPengamatan
    {
        return DB::transaction(function () use ($sesi, $data) {
            $sesi->update($this->dataSesi($data));

            // updateOrCreate: aman bila sebelumnya detail lokasi sudah dihapus dari menu Lokasi
            $sesi->detailLokasi()->updateOrCreate(
                ['sesi_pengamatan_id' => $sesi->id],
                $data['lokasi']
            );

            // sync(): tambah yang baru dipilih, lepas yang tidak dipilih lagi.
            // Hanya baris pivot yang berubah; data master perlengkapan TIDAK terhapus.
            $sesi->perlengkapan()->sync($this->petaPerlengkapan($data));

            return $sesi->refresh();
        });
    }

    public function delete(SesiPengamatan $sesi): void
    {
        DB::transaction(function () use ($sesi) {
            $sesi->perlengkapan()->detach();   // hanya melepas relasi pivot
            $sesi->detailLokasi()->delete();
            $sesi->delete();
        });
    }

    private function dataSesi(array $data): array
    {
        return collect($data)->except(['lokasi', 'perlengkapan_ids', 'jumlah'])->all();
    }

    /** Ubah input form menjadi format sync(): [perlengkapan_id => ['jumlah' => n]] */
    private function petaPerlengkapan(array $data): array
    {
        $peta = [];
        foreach ($data['perlengkapan_ids'] ?? [] as $id) {
            $peta[(int) $id] = ['jumlah' => (int) ($data['jumlah'][$id] ?? 1)];
        }

        return $peta;
    }
}
