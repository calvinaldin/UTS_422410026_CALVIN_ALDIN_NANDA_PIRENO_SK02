@extends('layouts.app')
@section('title', $sesi->kode_sesi)

@section('content')
<x-page-header :title="$sesi->nama_sesi" :subtitle="$sesi->kode_sesi.' · '.$sesi->kategori->nama">
    <a href="{{ route('sesi-pengamatan.edit', $sesi) }}" class="btn">Edit</a>
    <x-delete-button :action="route('sesi-pengamatan.destroy', $sesi)" :pesan="'Hapus sesi '.$sesi->kode_sesi.'?'" />
    <a href="{{ route('sesi-pengamatan.index') }}" class="btn">Kembali</a>
</x-page-header>

<div class="grid g2">
    <section class="panel">
        <header><h3>Data sesi</h3><x-badge :jenis="$sesi->status" :teks="$sesi->status_label" /></header>
        <div class="isi">
            <dl class="dl">
                <dt>Objek sasaran</dt><dd>{{ $sesi->objek_sasaran }}</dd>
                <dt>Kategori</dt><dd>{{ $sesi->kategori->nama }}</dd>
                <dt>Tanggal</dt><dd>{{ $sesi->tanggal_pengamatan->translatedFormat('l, d F Y') }}</dd>
                <dt>Waktu</dt><dd class="num">{{ $sesi->waktu_mulai ? substr($sesi->waktu_mulai, 0, 5) : '-' }} &ndash; {{ $sesi->waktu_selesai ? substr($sesi->waktu_selesai, 0, 5) : '-' }} WIB</dd>
                <dt>Catatan</dt><dd>{{ $sesi->catatan ?: '-' }}</dd>
            </dl>
        </div>
    </section>

    <section class="panel">
        <header><h3>Lokasi pengamatan</h3></header>
        <div class="isi">
            @if ($sesi->detailLokasi)
                <dl class="dl">
                    <dt>Nama lokasi</dt><dd>{{ $sesi->detailLokasi->nama_lokasi }}</dd>
                    <dt>Koordinat</dt><dd class="num">{{ $sesi->detailLokasi->koordinat }}</dd>
                    <dt>Ketinggian</dt><dd class="num">{{ $sesi->detailLokasi->ketinggian !== null ? number_format($sesi->detailLokasi->ketinggian, 0, ',', '.').' mdpl' : '-' }}</dd>
                    <dt>Catatan cuaca</dt><dd>{{ $sesi->detailLokasi->catatan_cuaca ?: '-' }}</dd>
                </dl>
                <p><a href="{{ route('lokasi.edit', $sesi->detailLokasi) }}" class="btn btn-kecil">Ubah detail lokasi</a></p>
            @else
                <x-empty-state pesan="Sesi ini belum memiliki detail lokasi.">
                    <p><a href="{{ route('lokasi.create', ['sesi' => $sesi->id]) }}" class="btn btn-kecil btn-utama">Tambah lokasi</a></p>
                </x-empty-state>
            @endif
        </div>
    </section>
</div>

<section class="panel">
    <header><h3>Perlengkapan yang digunakan ({{ $sesi->perlengkapan->count() }})</h3></header>
    @if ($sesi->perlengkapan->isEmpty())
        <x-empty-state pesan="Belum ada perlengkapan yang dipilih untuk sesi ini." />
    @else
    <div class="tabel-wrap">
        <table class="tabel">
            <thead><tr><th>Kode</th><th>Nama</th><th>Jenis</th><th>Kondisi</th><th>Jumlah</th></tr></thead>
            <tbody>
            @foreach ($sesi->perlengkapan as $p)
                <tr>
                    <td class="kode">{{ $p->kode }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->jenis_label }}</td>
                    <td><x-badge :jenis="$p->kondisi" :teks="$p->kondisi_label" /></td>
                    <td class="num">{{ $p->pivot->jumlah }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</section>
@endsection
