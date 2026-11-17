<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Perlengkapan;
use App\Models\SesiPengamatan;
use App\Services\SesiPengamatanService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObservatoriumTest extends TestCase
{
    use RefreshDatabase;

    private function alat(string $kode, string $nama = 'Teleskop Uji'): Perlengkapan
    {
        return Perlengkapan::create(['kode' => $kode, 'nama' => $nama, 'jenis' => 'teleskop', 'kondisi' => 'baik']);
    }

    private function payload(array $extra = []): array
    {
        $kategori = Kategori::firstOrCreate(['nama' => 'Planet']);

        return array_merge([
            'kode_sesi'          => 'SES-T-001',
            'nama_sesi'          => 'Uji Saturnus',
            'objek_sasaran'      => 'Saturnus',
            'kategori_id'        => $kategori->id,
            'tanggal_pengamatan' => '2026-12-01',
            'waktu_mulai'        => '20:00',
            'waktu_selesai'      => '23:00',
            'status'             => 'direncanakan',
            'lokasi'             => [
                'nama_lokasi' => 'Bosscha', 'lintang' => -6.8246, 'bujur' => 107.6164,
                'ketinggian' => 1310, 'catatan_cuaca' => 'Cerah',
            ],
        ], $extra);
    }

    public function test_dashboard_dapat_dibuka(): void
    {
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_simpan_sesi_dengan_lokasi_dan_banyak_perlengkapan(): void
    {
        $a = $this->alat('TLS-T1');
        $b = $this->alat('TLS-T2', 'Kamera Uji');

        $this->post(route('sesi-pengamatan.store'), $this->payload([
            'perlengkapan_ids' => [$a->id, $b->id],
            'jumlah'           => [$a->id => 2, $b->id => 1],
        ]))->assertRedirect();

        $sesi = SesiPengamatan::where('kode_sesi', 'SES-T-001')->firstOrFail();
        $this->assertDatabaseHas('detail_lokasi', ['sesi_pengamatan_id' => $sesi->id, 'nama_lokasi' => 'Bosscha']);
        $this->assertCount(2, $sesi->perlengkapan);
        $this->assertDatabaseHas('perlengkapan_sesi', ['sesi_pengamatan_id' => $sesi->id, 'perlengkapan_id' => $a->id, 'jumlah' => 2]);
    }

    public function test_validasi_menolak_input_kosong(): void
    {
        $this->post(route('sesi-pengamatan.store'), [])
            ->assertSessionHasErrors(['kode_sesi', 'nama_sesi', 'objek_sasaran', 'kategori_id', 'lokasi.nama_lokasi', 'lokasi.lintang']);

        $this->assertDatabaseCount('sesi_pengamatan', 0);
    }

    public function test_kode_sesi_harus_unik(): void
    {
        $this->post(route('sesi-pengamatan.store'), $this->payload());
        $this->post(route('sesi-pengamatan.store'), $this->payload())->assertSessionHasErrors('kode_sesi');
        $this->assertDatabaseCount('sesi_pengamatan', 1);
    }

    public function test_lepas_perlengkapan_tidak_menghapus_data_master(): void
    {
        $a = $this->alat('TLS-T1');
        $this->post(route('sesi-pengamatan.store'), $this->payload(['perlengkapan_ids' => [$a->id]]));
        $sesi = SesiPengamatan::firstOrFail();

        $this->put(route('sesi-pengamatan.update', $sesi), $this->payload())->assertRedirect();

        $this->assertDatabaseCount('perlengkapan_sesi', 0);
        $this->assertDatabaseHas('perlengkapan', ['id' => $a->id]);
    }

    public function test_satu_sesi_hanya_boleh_satu_lokasi(): void
    {
        $this->post(route('sesi-pengamatan.store'), $this->payload());
        $sesi = SesiPengamatan::firstOrFail();

        $this->post(route('lokasi.store'), [
            'sesi_pengamatan_id' => $sesi->id, 'nama_lokasi' => 'Lain', 'lintang' => 1, 'bujur' => 1,
        ])->assertSessionHasErrors('sesi_pengamatan_id');
    }

    public function test_pencarian_dan_filter(): void
    {
        $this->post(route('sesi-pengamatan.store'), $this->payload());
        $this->post(route('sesi-pengamatan.store'), $this->payload(['kode_sesi' => 'SES-T-002', 'nama_sesi' => 'Uji Jupiter', 'objek_sasaran' => 'Jupiter', 'status' => 'selesai']));

        $this->get(route('sesi-pengamatan.index', ['search' => 'Jupiter']))->assertSee('SES-T-002')->assertDontSee('SES-T-001');
        $this->get(route('sesi-pengamatan.index', ['status' => 'direncanakan']))->assertSee('SES-T-001')->assertDontSee('SES-T-002');
    }

    public function test_perlengkapan_yang_dipakai_tidak_bisa_dihapus(): void
    {
        $a = $this->alat('TLS-T1');
        $this->post(route('sesi-pengamatan.store'), $this->payload(['perlengkapan_ids' => [$a->id]]));

        $this->delete(route('perlengkapan.destroy', $a))->assertSessionHas('error');
        $this->assertDatabaseHas('perlengkapan', ['id' => $a->id]);
    }

    public function test_transaksi_dibatalkan_jika_ada_kegagalan(): void
    {
        $data = $this->payload(['perlengkapan_ids' => [9999], 'jumlah' => [9999 => 1]]);

        try {
            app(SesiPengamatanService::class)->store($data);
            $this->fail('Seharusnya terjadi kegagalan foreign key.');
        } catch (QueryException $e) {
            // diharapkan
        }

        $this->assertDatabaseCount('sesi_pengamatan', 0);
        $this->assertDatabaseCount('detail_lokasi', 0);
    }
}
