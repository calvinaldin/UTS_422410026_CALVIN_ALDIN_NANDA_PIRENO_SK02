<?php

namespace App\Services;

use App\Models\Perlengkapan;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PerlengkapanService
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return Perlengkapan::withCount('sesiPengamatan')
            ->filter($filters)
            ->orderBy('jenis')
            ->orderBy('nama')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function store(array $data): Perlengkapan
    {
        return DB::transaction(fn () => Perlengkapan::create($data));
    }

    public function update(Perlengkapan $perlengkapan, array $data): Perlengkapan
    {
        return DB::transaction(function () use ($perlengkapan, $data) {
            $perlengkapan->update($data);

            return $perlengkapan->refresh();
        });
    }

    /** @throws \DomainException bila perlengkapan masih dipakai oleh sesi pengamatan */
    public function delete(Perlengkapan $perlengkapan): void
    {
        DB::transaction(function () use ($perlengkapan) {
            if ($perlengkapan->sesiPengamatan()->exists()) {
                throw new \DomainException(
                    "Perlengkapan \"{$perlengkapan->nama}\" masih dipakai pada sesi pengamatan. Lepaskan dari sesi terlebih dahulu."
                );
            }
            $perlengkapan->delete();
        });
    }
}
