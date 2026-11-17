@extends('layouts.app')
@section('title', 'Kategori')

@section('content')
<x-page-header title="Kategori" subtitle="Pengelompokan jenis sesi pengamatan.">
    <a href="{{ route('kategori.create') }}" class="btn btn-utama">Tambah kategori</a>
</x-page-header>

<section class="panel">
    <form method="GET" action="{{ route('kategori.index') }}" class="filter dua" role="search">
        <div>
            <label for="search">Cari</label>
            <input type="search" id="search" name="search" class="input" value="{{ request('search') }}" placeholder="Nama atau deskripsi kategori">
        </div>
        <div class="tombol">
            <button class="btn btn-utama" type="submit">Cari</button>
            <a class="btn" href="{{ route('kategori.index') }}">Reset</a>
        </div>
    </form>

    @if ($kategoriList->isEmpty())
        <x-empty-state pesan="Tidak ada kategori yang ditemukan." />
    @else
    <div class="tabel-wrap">
        <table class="tabel">
            <thead><tr><th>Nama</th><th>Deskripsi</th><th>Jumlah sesi</th><th class="kanan">Aksi</th></tr></thead>
            <tbody>
            @foreach ($kategoriList as $k)
                <tr>
                    <td><strong>{{ $k->nama }}</strong></td>
                    <td>{{ $k->deskripsi ?: '-' }}</td>
                    <td class="num">{{ $k->sesi_pengamatan_count }}</td>
                    <td class="kanan">
                        <a href="{{ route('sesi-pengamatan.index', ['kategori_id' => $k->id]) }}" class="btn btn-kecil">Lihat sesi</a>
                        <a href="{{ route('kategori.edit', $k) }}" class="btn btn-kecil">Edit</a>
                        <x-delete-button :action="route('kategori.destroy', $k)" :pesan="'Hapus kategori '.$k->nama.'?'" />
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $kategoriList->links() }}
    @endif
</section>
@endsection
