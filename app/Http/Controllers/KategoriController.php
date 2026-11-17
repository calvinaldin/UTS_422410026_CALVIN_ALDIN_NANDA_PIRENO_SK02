<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriRequest;
use App\Models\Kategori;
use App\Services\KategoriService;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function __construct(private KategoriService $service) {}

    public function index(Request $request)
    {
        return view('kategori.index', [
            'kategoriList' => $this->service->paginate($request->only('search')),
        ]);
    }

    public function create()
    {
        return view('kategori.create', ['kategori' => new Kategori]);
    }

    public function store(KategoriRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(KategoriRequest $request, Kategori $kategori)
    {
        $this->service->update($kategori, $request->validated());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        try {
            $this->service->delete($kategori);
        } catch (\DomainException $e) {
            return redirect()->route('kategori.index')->with('error', $e->getMessage());
        }

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
