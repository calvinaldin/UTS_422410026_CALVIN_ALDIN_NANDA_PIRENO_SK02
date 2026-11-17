@extends('layouts.app')
@section('title', 'Perlengkapan')

@section('content')
<x-page-header title="Perlengkapan" subtitle="Data master teleskop, kamera, filter, tripod, dan alat pendukung.">
    <a href="{{ route('perlengkapan.create') }}" class="btn btn-utama">Tambah perlengkapan</a>
</x-page-header>

<section class="panel">
    <form method="GET" action="{{ route('perlengkapan.index') }}" class="filter tiga" role="search">
        <div>
            <label for="search">Cari</label>
            <input type="search" id="search" name="search" class="input" value="{{ request('search') }}" placeholder="Kode atau nama perlengkapan">
        </div>
        <div>
            <label for="jenis">Jenis</label>
            <select id="jenis" name="jenis" class="input">
                <option value="">Semua jenis</option>
                @foreach (\App\Models\Perlengkapan::JENIS as $k => $v)
                    <option value="{{ $k }}" @selected(request('jenis') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="kondisi">Kondisi</label>
            <select id="kondisi" name="kondisi" class="input">
                <option value="">Semua kondisi</option>
                @foreach (\App\Models\Perlengkapan::KONDISI as $k => $v)
                    <option value="{{ $k }}" @selected(request('kondisi') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="tombol">
            <button class="btn btn-utama" type="submit">Terapkan</button>
            <a class="btn" href="{{ route('perlengkapan.index') }}">Reset</a>
        </div>
    </form>

    @if ($perlengkapanList->isEmpty())
        <x-empty-state pesan="Tidak ada perlengkapan yang cocok." />
    @else
    <div class="tabel-wrap">
        <table class="tabel">
            <thead><tr><th>Kode</th><th>Nama</th><th>Jenis</th><th>Kondisi</th><th>Dipakai</th><th class="kanan">Aksi</th></tr></thead>
            <tbody>
            @foreach ($perlengkapanList as $p)
                <tr>
                    <td class="kode">{{ $p->kode }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->jenis_label }}</td>
                    <td><x-badge :jenis="$p->kondisi" :teks="$p->kondisi_label" /></td>
                    <td class="num">{{ $p->sesi_pengamatan_count }} sesi</td>
                    <td class="kanan">
                        <a href="{{ route('perlengkapan.edit', $p) }}" class="btn btn-kecil">Edit</a>
                        <x-delete-button :action="route('perlengkapan.destroy', $p)" :pesan="'Hapus perlengkapan '.$p->nama.'?'" />
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $perlengkapanList->links() }}
    @endif
</section>
@endsection
