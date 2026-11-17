<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Planet', 'Pengamatan planet tata surya: Merkurius sampai Neptunus.'],
            ['Bulan', 'Pengamatan permukaan, fase, dan gerhana Bulan.'],
            ['Deep Sky Object', 'Nebula, gugus bintang, dan galaksi yang redup.'],
            ['Astrofotografi', 'Sesi pengambilan citra dengan kamera dan teleskop.'],
            ['Hujan Meteor', 'Pengamatan visual dan pencatatan meteor.'],
            ['Bintang Ganda & Variabel', 'Pemantauan sistem bintang ganda dan bintang variabel.'],
        ];

        foreach ($data as [$nama, $deskripsi]) {
            Kategori::updateOrCreate(['nama' => $nama], ['deskripsi' => $deskripsi]);
        }
    }
}
