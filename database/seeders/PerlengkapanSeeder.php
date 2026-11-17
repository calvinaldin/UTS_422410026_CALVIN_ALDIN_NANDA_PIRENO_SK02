<?php

namespace Database\Seeders;

use App\Models\Perlengkapan;
use Illuminate\Database\Seeder;

class PerlengkapanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['TLS-001', 'Teleskop Refraktor Apo 102 mm',     'teleskop', 'baik'],
            ['TLS-002', 'Teleskop Dobsonian 10 inci',        'teleskop', 'baik'],
            ['TLS-003', 'Teleskop Schmidt-Cassegrain 8 inci', 'teleskop', 'perlu_kalibrasi'],
            ['CAM-001', 'Kamera CCD ZWO ASI294MC Pro',       'kamera',   'baik'],
            ['CAM-002', 'Kamera CMOS Planetary ASI224MC',    'kamera',   'baik'],
            ['FLT-001', 'Filter H-alpha 7 nm',               'filter',   'baik'],
            ['FLT-002', 'Filter UV/IR Cut',                  'filter',   'baik'],
            ['FLT-003', 'Filter Bulan ND 0.9',               'filter',   'rusak'],
            ['TRP-001', 'Mount Equatorial EQ6-R Pro',        'tripod',   'baik'],
            ['TRP-002', 'Tripod Serat Karbon Portabel',      'tripod',   'baik'],
            ['LNY-001', 'Senter Lampu Merah (night vision)', 'lainnya',  'baik'],
            ['LNY-002', 'Laptop Akuisisi Citra',             'lainnya',  'baik'],
        ];

        foreach ($data as [$kode, $nama, $jenis, $kondisi]) {
            Perlengkapan::updateOrCreate(['kode' => $kode], compact('nama', 'jenis', 'kondisi'));
        }
    }
}
