<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Perlengkapan;
use App\Models\SesiPengamatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SesiPengamatanSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = Kategori::pluck('id', 'nama');
        $alat = Perlengkapan::pluck('id', 'kode');

        // [kode, nama, objek, kategori, selisih hari dari hari ini, mulai, selesai, status, catatan,
        //  [lokasi, lintang, bujur, mdpl, cuaca], [[kode alat, jumlah], ...]]
        $data = [
            ['SES-2026-001', 'Pengamatan Cincin Saturnus', 'Saturnus', 'Planet', -45, '19:30', '23:00', 'selesai',
                'Seeing baik, cincin terlihat jelas pada perbesaran 150x.',
                ['Observatorium Bosscha, Lembang', -6.8246, 107.6164, 1310, 'Cerah, kelembapan 62%, seeing 3/5'],
                [['TLS-001', 1], ['CAM-002', 1], ['TRP-001', 1]]],
            ['SES-2026-002', 'Foto Nebula Orion', 'M42 Nebula Orion', 'Astrofotografi', -30, '20:00', '02:00', 'selesai',
                '120 frame x 60 detik, stacking selesai.',
                ['Penanjakan, Bromo', -7.9425, 112.9533, 2770, 'Cerah berawan tipis, suhu 6°C'],
                [['TLS-002', 1], ['CAM-001', 1], ['FLT-002', 1], ['TRP-001', 1], ['LNY-002', 1]]],
            ['SES-2026-003', 'Gerhana Bulan Sebagian', 'Bulan', 'Bulan', -18, '18:30', '22:30', 'selesai',
                'Pencatatan kontak U1 sampai U4.',
                ['Pantai Parangtritis, Bantul', -8.0250, 110.3300, 5, 'Berawan sebagian, angin 12 km/jam'],
                [['TLS-003', 1], ['TRP-002', 1], ['LNY-001', 2]]],
            ['SES-2026-004', 'Pemantauan Jupiter dan Satelit Galilean', 'Jupiter', 'Planet', -7, '21:00', '00:30', 'dibatalkan',
                'Dibatalkan karena hujan.',
                ['Dataran Tinggi Dieng, Wonosobo', -7.2000, 109.9000, 2093, 'Hujan lebat, awan tebal'],
                [['TLS-001', 1], ['CAM-002', 1]]],
            ['SES-2026-005', 'Malam Hujan Meteor Geminid', 'Geminid', 'Hujan Meteor', 3, '22:00', '04:00', 'direncanakan',
                'Bawa kursi lipat dan senter lampu merah.',
                ['Observatorium Nasional Timau, Kupang', -9.5833, 123.9500, 1300, 'Prakiraan cerah, bebas polusi cahaya'],
                [['TRP-002', 1], ['LNY-001', 3], ['CAM-001', 1]]],
            ['SES-2026-006', 'Survei Galaksi Andromeda (M31)', 'M31 Andromeda', 'Deep Sky Object', 9, '20:30', '01:30', 'direncanakan',
                'Uji coba filter baru.',
                ['Observatorium Bosscha, Lembang', -6.8246, 107.6164, 1310, 'Prakiraan cerah berawan'],
                [['TLS-002', 1], ['FLT-001', 1], ['CAM-001', 1], ['TRP-001', 1]]],
            ['SES-2026-007', 'Bintang Ganda Albireo', 'Albireo (Beta Cygni)', 'Bintang Ganda & Variabel', 14, '19:45', '22:00', 'direncanakan',
                'Pengukuran separasi sudut.',
                ['Kawah Ijen, Banyuwangi', -8.0580, 114.2420, 2386, 'Prakiraan cerah, angin lemah'],
                [['TLS-001', 1], ['TRP-002', 1]]],
            ['SES-2026-008', 'Astrofotografi Bulan Purnama', 'Bulan', 'Astrofotografi', 0, '19:00', '21:00', 'berlangsung',
                'Sesi sedang berjalan.',
                ['Observatorium Bosscha, Lembang', -6.8246, 107.6164, 1310, 'Cerah, seeing 4/5'],
                [['TLS-003', 1], ['CAM-002', 1], ['TRP-001', 1]]],
        ];

        DB::transaction(function () use ($data, $kategori, $alat) {
            foreach ($data as [$kode, $nama, $objek, $kat, $hari, $mulai, $selesai, $status, $catatan, $lok, $perl]) {
                $sesi = SesiPengamatan::updateOrCreate(['kode_sesi' => $kode], [
                    'nama_sesi'          => $nama,
                    'objek_sasaran'      => $objek,
                    'kategori_id'        => $kategori[$kat],
                    'tanggal_pengamatan' => today()->addDays($hari),
                    'waktu_mulai'        => $mulai,
                    'waktu_selesai'      => $selesai,
                    'status'             => $status,
                    'catatan'            => $catatan,
                ]);

                [$namaLokasi, $lintang, $bujur, $mdpl, $cuaca] = $lok;
                $sesi->detailLokasi()->updateOrCreate([], [
                    'nama_lokasi' => $namaLokasi, 'lintang' => $lintang, 'bujur' => $bujur,
                    'ketinggian' => $mdpl, 'catatan_cuaca' => $cuaca,
                ]);

                $sync = [];
                foreach ($perl as [$kodeAlat, $jumlah]) {
                    $sync[$alat[$kodeAlat]] = ['jumlah' => $jumlah];
                }
                $sesi->perlengkapan()->sync($sync);
            }
        });
    }
}
