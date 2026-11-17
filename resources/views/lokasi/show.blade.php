@extends('layouts.app')
@section('title', $lokasi->nama_lokasi)

@section('content')
<x-page-header :title="$lokasi->nama_lokasi" subtitle="Detail lokasi pengamatan">
    <a href="{{ route('lokasi.edit', $lokasi) }}" class="btn">Edit</a>
    <x-delete-button :action="route('lokasi.destroy', $lokasi)" pesan="Hapus detail lokasi ini? Sesi pengamatan terkait tidak ikut terhapus." />
    <a href="{{ route('lokasi.index') }}" class="btn">Kembali</a>
</x-page-header>

<div class="grid g2">
    <section class="panel">
        <header><h3>Lokasi</h3></header>
        <div class="isi">
            <dl class="dl">
                <dt>Koordinat</dt><dd class="num">{{ $lokasi->koordinat }}</dd>
                <dt>Lintang / bujur</dt><dd class="num">{{ $lokasi->lintang }} / {{ $lokasi->bujur }}</dd>
                <dt>Ketinggian</dt><dd class="num">{{ $lokasi->ketinggian !== null ? number_format($lokasi->ketinggian, 0, ',', '.').' mdpl' : '-' }}</dd>
                <dt>Catatan cuaca</dt><dd>{{ $lokasi->catatan_cuaca ?: '-' }}</dd>
            </dl>
            <p><a href="https://www.openstreetmap.org/?mlat={{ $lokasi->lintang }}&amp;mlon={{ $lokasi->bujur }}#map=12/{{ $lokasi->lintang }}/{{ $lokasi->bujur }}" target="_blank" rel="noopener" class="btn btn-kecil">Lihat di peta</a></p>
        </div>
    </section>

    <section class="panel">
        <header><h3>Sesi terhubung</h3></header>
        <div class="isi">
            <dl class="dl">
                <dt>Kode</dt><dd class="kode">{{ $lokasi->sesiPengamatan->kode_sesi }}</dd>
                <dt>Nama sesi</dt><dd><a href="{{ route('sesi-pengamatan.show', $lokasi->sesiPengamatan) }}">{{ $lokasi->sesiPengamatan->nama_sesi }}</a></dd>
                <dt>Kategori</dt><dd>{{ $lokasi->sesiPengamatan->kategori->nama }}</dd>
                <dt>Tanggal</dt><dd>{{ $lokasi->sesiPengamatan->tanggal_pengamatan->translatedFormat('d F Y') }}</dd>
                <dt>Status</dt><dd><x-badge :jenis="$lokasi->sesiPengamatan->status" :teks="$lokasi->sesiPengamatan->status_label" /></dd>
            </dl>
        </div>
    </section>
</div>
@endsection
