<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerlengkapanRequest;
use App\Models\Perlengkapan;
use App\Services\PerlengkapanService;
use Illuminate\Http\Request;

class PerlengkapanController extends Controller
{
    public function __construct(private PerlengkapanService $service) {}

    public function index(Request $request)
    {
        return view('perlengkapan.index', [
            'perlengkapanList' => $this->service->paginate($request->only(['search', 'jenis', 'kondisi'])),
        ]);
    }

    public function create()
    {
        return view('perlengkapan.create', ['perlengkapan' => new Perlengkapan(['kondisi' => 'baik'])]);
    }

    public function store(PerlengkapanRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()->route('perlengkapan.index')->with('success', 'Perlengkapan berhasil ditambahkan.');
    }

    public function edit(Perlengkapan $perlengkapan)
    {
        return view('perlengkapan.edit', compact('perlengkapan'));
    }

    public function update(PerlengkapanRequest $request, Perlengkapan $perlengkapan)
    {
        $this->service->update($perlengkapan, $request->validated());

        return redirect()->route('perlengkapan.index')->with('success', 'Perlengkapan berhasil diperbarui.');
    }

    public function destroy(Perlengkapan $perlengkapan)
    {
        try {
            $this->service->delete($perlengkapan);
        } catch (\DomainException $e) {
            return redirect()->route('perlengkapan.index')->with('error', $e->getMessage());
        }

        return redirect()->route('perlengkapan.index')->with('success', 'Perlengkapan berhasil dihapus.');
    }
}
