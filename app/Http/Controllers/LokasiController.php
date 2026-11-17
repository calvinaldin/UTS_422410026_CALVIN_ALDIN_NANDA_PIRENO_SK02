<?php

namespace App\Http\Controllers;

use App\Http\Requests\LokasiRequest;
use App\Models\DetailLokasi;
use App\Models\SesiPengamatan;
use App\Services\LokasiService;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function __construct(private LokasiService $service) {}

    public function index(Request $request)
    {
        return view('lokasi.index', [
            'lokasiList' => $this->service->paginate($request->only('search')),
        ]);
    }

    public function create(Request $request)
    {
        // hanya sesi yang BELUM memiliki detail lokasi yang boleh dipilih (One-to-One)
        $sesiTanpaLokasi = SesiPengamatan::doesntHave('detailLokasi')->orderBy('kode_sesi')->get();

        return view('lokasi.create', [
            'lokasi'          => new DetailLokasi(['sesi_pengamatan_id' => $request->query('sesi')]),
            'sesiTanpaLokasi' => $sesiTanpaLokasi,
        ]);
    }

    public function store(LokasiRequest $request)
    {
        $lokasi = $this->service->store($request->validated());

        return redirect()->route('lokasi.show', $lokasi)->with('success', 'Detail lokasi berhasil ditambahkan dan dihubungkan ke sesi.');
    }

    public function show(DetailLokasi $lokasi)
    {
        $lokasi->load('sesiPengamatan.kategori');

        return view('lokasi.show', compact('lokasi'));
    }

    public function edit(DetailLokasi $lokasi)
    {
        $lokasi->load('sesiPengamatan');

        return view('lokasi.edit', compact('lokasi'));
    }

    public function update(LokasiRequest $request, DetailLokasi $lokasi)
    {
        $this->service->update($lokasi, $request->validated());

        return redirect()->route('lokasi.show', $lokasi)->with('success', 'Detail lokasi berhasil diperbarui.');
    }

    public function destroy(DetailLokasi $lokasi)
    {
        $this->service->delete($lokasi);

        return redirect()->route('lokasi.index')->with('success', 'Detail lokasi dihapus. Sesi pengamatan terkait tetap ada.');
    }
}
