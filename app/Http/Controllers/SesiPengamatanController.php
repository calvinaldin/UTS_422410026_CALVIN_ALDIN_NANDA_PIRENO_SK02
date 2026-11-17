<?php

namespace App\Http\Controllers;

use App\Http\Requests\SesiPengamatanRequest;
use App\Models\Kategori;
use App\Models\Perlengkapan;
use App\Models\SesiPengamatan;
use App\Services\SesiPengamatanService;
use Illuminate\Http\Request;

class SesiPengamatanController extends Controller
{
    public function __construct(private SesiPengamatanService $service) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'kategori_id', 'status', 'dari', 'sampai']);

        return view('sesi.index', [
            'sesiList'     => $this->service->paginate($filters),
            'kategoriList' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function create()
    {
        return view('sesi.create', [
            'sesi'             => new SesiPengamatan(['status' => 'direncanakan', 'tanggal_pengamatan' => today()]),
            'kategoriList'     => Kategori::orderBy('nama')->get(),
            'perlengkapanList' => Perlengkapan::orderBy('jenis')->orderBy('nama')->get()->groupBy('jenis'),
        ]);
    }

    public function store(SesiPengamatanRequest $request)
    {
        $sesi = $this->service->store($request->validated());

        return redirect()->route('sesi-pengamatan.show', $sesi)
            ->with('success', "Sesi {$sesi->kode_sesi} berhasil dibuat.");
    }

    public function show(SesiPengamatan $sesi)
    {
        $sesi->load(['kategori', 'detailLokasi', 'perlengkapan']);

        return view('sesi.show', compact('sesi'));
    }

    public function edit(SesiPengamatan $sesi)
    {
        $sesi->load(['detailLokasi', 'perlengkapan']);

        return view('sesi.edit', [
            'sesi'             => $sesi,
            'kategoriList'     => Kategori::orderBy('nama')->get(),
            'perlengkapanList' => Perlengkapan::orderBy('jenis')->orderBy('nama')->get()->groupBy('jenis'),
        ]);
    }

    public function update(SesiPengamatanRequest $request, SesiPengamatan $sesi)
    {
        $this->service->update($sesi, $request->validated());

        return redirect()->route('sesi-pengamatan.show', $sesi)
            ->with('success', "Sesi {$sesi->kode_sesi} berhasil diperbarui.");
    }

    public function destroy(SesiPengamatan $sesi)
    {
        $kode = $sesi->kode_sesi;
        $this->service->delete($sesi);

        return redirect()->route('sesi-pengamatan.index')
            ->with('success', "Sesi {$kode} berhasil dihapus. Data master perlengkapan tetap tersimpan.");
    }
}
