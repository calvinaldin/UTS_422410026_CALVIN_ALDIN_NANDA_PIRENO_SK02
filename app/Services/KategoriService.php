<?php

namespace App\Services;

use App\Models\Kategori;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class KategoriService
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return Kategori::withCount('sesiPengamatan')
            ->filter($filters)
            ->orderBy('nama')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function store(array $data): Kategori
    {
        return DB::transaction(fn () => Kategori::create($data));
    }

    public function update(Kategori $kategori, array $data): Kategori
    {
        return DB::transaction(function () use ($kategori, $data) {
            $kategori->update($data);

            return $kategori->refresh();
        });
    }

    /** @throws \DomainException bila kategori masih dipakai oleh sesi pengamatan */
    public function delete(Kategori $kategori): void
    {
        DB::transaction(function () use ($kategori) {
            if ($kategori->sesiPengamatan()->exists()) {
                throw new \DomainException(
                    "Kategori \"{$kategori->nama}\" masih dipakai oleh sesi pengamatan dan tidak dapat dihapus."
                );
            }
            $kategori->delete();
        });
    }
}
