<?php

namespace App\Services;

use App\Models\DetailLokasi;
use App\Models\Kategori;
use App\Models\Perlengkapan;
use App\Models\SesiPengamatan;

class DashboardService
{
    public function ringkasan(): array
    {
        $statusCount = SesiPengamatan::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        return [
            'total_sesi'        => SesiPengamatan::count(),
            'total_lokasi'      => DetailLokasi::count(),
            'total_perlengkapan' => Perlengkapan::count(),
            'total_kategori'    => Kategori::count(),
            'per_status'        => collect(SesiPengamatan::STATUS)->map(fn ($label, $key) => (int) ($statusCount[$key] ?? 0)),
            'sesi_berikutnya'   => SesiPengamatan::with(['kategori', 'detailLokasi', 'perlengkapan'])
                ->whereIn('status', ['direncanakan', 'berlangsung'])
                ->whereDate('tanggal_pengamatan', '>=', today())
                ->orderBy('tanggal_pengamatan')->orderBy('waktu_mulai')
                ->first(),
            'sesi_terbaru'      => SesiPengamatan::with(['kategori', 'detailLokasi'])
                ->latest('updated_at')->limit(5)->get(),
            'alat_terpopuler'   => Perlengkapan::withCount('sesiPengamatan')
                ->orderByDesc('sesi_pengamatan_count')->orderBy('nama')->limit(5)->get(),
            'alat_perlu_perhatian' => Perlengkapan::where('kondisi', '!=', 'baik')->orderBy('nama')->get(),
        ];
    }
}
