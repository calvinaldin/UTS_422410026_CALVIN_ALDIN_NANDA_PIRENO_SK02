<?php

namespace App\Services;

use App\Models\DetailLokasi;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LokasiService
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return DetailLokasi::with('sesiPengamatan.kategori')
            ->filter($filters)
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function store(array $data): DetailLokasi
    {
        return DB::transaction(fn () => DetailLokasi::create($data));
    }

    public function update(DetailLokasi $lokasi, array $data): DetailLokasi
    {
        return DB::transaction(function () use ($lokasi, $data) {
            // sesi pengamatan pemilik tidak diubah; hanya isi detailnya
            unset($data['sesi_pengamatan_id']);
            $lokasi->update($data);

            return $lokasi->refresh();
        });
    }

    /** Menghapus detail lokasi saja; sesi pengamatan tetap ada. */
    public function delete(DetailLokasi $lokasi): void
    {
        DB::transaction(fn () => $lokasi->delete());
    }
}
