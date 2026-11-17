@extends('layouts.app')
@section('title', 'Lokasi Pengamatan')

@section('content')
<x-page-header title="Lokasi pengamatan" subtitle="Detail lokasi untuk setiap sesi. Satu sesi hanya memiliki satu detail lokasi.">
    <a href="{{ route('lokasi.create') }}" class="btn btn-utama">Tambah lokasi</a>
</x-page-header>

<section class="panel">
    <form method="GET" action="{{ route('lokasi.index') }}" class="filter dua" role="search">
        <div>
            <label for="search">Cari</label>
            <input type="search" id="search" name="search" class="input" value="{{ request('search') }}" placeholder="Nama lokasi, kode sesi, atau nama sesi">
        </div>
        <div class="tombol">
            <button class="btn btn-utama" type="submit">Cari</button>
            <a class="btn" href="{{ route('lokasi.index') }}">Reset</a>
        </div>
    </form>

    @if ($lokasiList->isEmpty())
        <x-empty-state pesan="Tidak ada detail lokasi yang ditemukan." />
    @else
    <div class="tabel-wrap">
        <table class="tabel">
            <thead><tr><th>Lokasi</th><th>Koordinat</th><th>Ketinggian</th><th>Sesi</th><th class="kanan">Aksi</th></tr></thead>
            <tbody>
            @foreach ($lokasiList as $l)
                <tr>
                    <td><a href="{{ route('lokasi.show', $l) }}"><strong>{{ $l->nama_lokasi }}</strong></a></td>
                    <td class="num">{{ $l->koordinat }}</td>
                    <td class="num">{{ $l->ketinggian !== null ? number_format($l->ketinggian, 0, ',', '.').' mdpl' : '-' }}</td>
                    <td><span class="kode">{{ $l->sesiPengamatan->kode_sesi }}</span><br><small class="muted">{{ $l->sesiPengamatan->nama_sesi }}</small></td>
                    <td class="kanan">
                        <a href="{{ route('lokasi.show', $l) }}" class="btn btn-kecil">Lihat</a>
                        <a href="{{ route('lokasi.edit', $l) }}" class="btn btn-kecil">Edit</a>
                        <x-delete-button :action="route('lokasi.destroy', $l)" pesan="Hapus detail lokasi ini? Sesi pengamatan terkait tidak ikut terhapus." />
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $lokasiList->links() }}
    @endif
</section>
@endsection
