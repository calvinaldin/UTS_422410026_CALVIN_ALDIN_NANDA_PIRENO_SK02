@extends('layouts.app')
@section('title', 'Sesi Pengamatan')

@section('content')
<x-page-header title="Sesi pengamatan" subtitle="Kelola jadwal, lokasi, dan perlengkapan setiap sesi pengamatan astronomi.">
    <a href="{{ route('sesi-pengamatan.create') }}" class="btn btn-utama">Tambah sesi</a>
</x-page-header>

<section class="panel">
    <form method="GET" action="{{ route('sesi-pengamatan.index') }}" class="filter" role="search">
        <div>
            <label for="search">Cari</label>
            <input type="search" id="search" name="search" class="input" value="{{ request('search') }}" placeholder="Kode, nama, objek, atau lokasi">
        </div>
        <div>
            <label for="kategori_id">Kategori</label>
            <select id="kategori_id" name="kategori_id" class="input">
                <option value="">Semua kategori</option>
                @foreach ($kategoriList as $k)
                    <option value="{{ $k->id }}" @selected(request('kategori_id') == $k->id)>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="status">Status</label>
            <select id="status" name="status" class="input">
                <option value="">Semua status</option>
                @foreach (\App\Models\SesiPengamatan::STATUS as $kunci => $label)
                    <option value="{{ $kunci }}" @selected(request('status') === $kunci)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div><label for="dari">Dari tanggal</label><input type="date" id="dari" name="dari" class="input" value="{{ request('dari') }}"></div>
        <div><label for="sampai">Sampai tanggal</label><input type="date" id="sampai" name="sampai" class="input" value="{{ request('sampai') }}"></div>
        <div class="tombol">
            <button class="btn btn-utama" type="submit">Terapkan</button>
            <a class="btn" href="{{ route('sesi-pengamatan.index') }}">Reset</a>
        </div>
    </form>

    @if ($sesiList->isEmpty())
        <x-empty-state pesan="Tidak ada sesi yang cocok dengan pencarian atau filter.">
            <p><a href="{{ route('sesi-pengamatan.create') }}" class="btn btn-kecil">Tambah sesi baru</a></p>
        </x-empty-state>
    @else
    <div class="tabel-wrap">
        <table class="tabel">
            <thead>
                <tr><th>Kode</th><th>Sesi / objek sasaran</th><th>Tanggal</th><th>Lokasi</th><th>Alat</th><th>Status</th><th class="kanan">Aksi</th></tr>
            </thead>
            <tbody>
            @foreach ($sesiList as $s)
                <tr>
                    <td class="kode">{{ $s->kode_sesi }}</td>
                    <td>
                        <a href="{{ route('sesi-pengamatan.show', $s) }}"><strong>{{ $s->nama_sesi }}</strong></a><br>
                        <small class="muted">{{ $s->objek_sasaran }} &middot; {{ $s->kategori->nama }}</small>
                    </td>
                    <td class="num">{{ $s->tanggal_pengamatan->translatedFormat('d M Y') }}</td>
                    <td>{{ $s->detailLokasi->nama_lokasi ?? '-' }}</td>
                    <td class="num">{{ $s->perlengkapan_count }}</td>
                    <td><x-badge :jenis="$s->status" :teks="$s->status_label" /></td>
                    <td class="kanan">
                        <a href="{{ route('sesi-pengamatan.show', $s) }}" class="btn btn-kecil">Lihat</a>
                        <a href="{{ route('sesi-pengamatan.edit', $s) }}" class="btn btn-kecil">Edit</a>
                        <x-delete-button :action="route('sesi-pengamatan.destroy', $s)" :pesan="'Hapus sesi '.$s->kode_sesi.'? Detail lokasi ikut terhapus, data master perlengkapan tetap aman.'" />
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $sesiList->links() }}
    @endif
</section>
@endsection
