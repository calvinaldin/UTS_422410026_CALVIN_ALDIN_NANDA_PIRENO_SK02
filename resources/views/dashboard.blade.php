@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<x-page-header title="Dashboard" subtitle="Ringkasan sesi pengamatan, lokasi, dan perlengkapan observatorium.">
    <a href="{{ route('sesi-pengamatan.create') }}" class="btn btn-utama">Tambah sesi pengamatan</a>
</x-page-header>

@php($next = $data['sesi_berikutnya'])
<section class="langit" aria-label="Sesi pengamatan berikutnya">
    <svg class="bintang" viewBox="0 0 800 220" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
        <g fill="#fff">
            <circle cx="620" cy="30" r="1.6"/><circle cx="700" cy="70" r="1"/><circle cx="560" cy="110" r="1.2"/>
            <circle cx="740" cy="150" r="1.8"/><circle cx="660" cy="190" r="1"/><circle cx="520" cy="40" r="1"/>
            <circle cx="770" cy="25" r="1.1"/><circle cx="600" cy="165" r="1.3"/><circle cx="470" cy="90" r="0.9"/>
        </g>
        <g stroke="#6f86c7" stroke-width="1" fill="none" opacity=".7">
            <polyline points="620,30 700,70 740,150 660,190 600,165 560,110 620,30"/>
        </g>
        <circle cx="700" cy="70" r="7" fill="none" stroke="#d42f4b" stroke-width="1.5"/>
    </svg>
    <div class="teks">
        @if ($next)
            <div class="label">Sesi pengamatan berikutnya</div>
            <h2>{{ $next->nama_sesi }}</h2>
            <div class="meta">
                <span>Sasaran: {{ $next->objek_sasaran }}</span>
                <span>{{ $next->tanggal_pengamatan->translatedFormat('l, d F Y') }}@if($next->waktu_mulai), pukul {{ substr($next->waktu_mulai, 0, 5) }} WIB @endif</span>
                @if ($next->detailLokasi)<span>{{ $next->detailLokasi->nama_lokasi }} ({{ $next->detailLokasi->koordinat }})</span>@endif
                <span>{{ $next->perlengkapan->count() }} perlengkapan disiapkan</span>
            </div>
            <a href="{{ route('sesi-pengamatan.show', $next) }}" class="btn">Buka rincian sesi</a>
        @else
            <div class="label">Belum ada jadwal</div>
            <h2>Tidak ada sesi yang akan datang</h2>
            <div class="meta"><span>Rencanakan pengamatan berikutnya dari menu Sesi pengamatan.</span></div>
            <a href="{{ route('sesi-pengamatan.create') }}" class="btn">Buat sesi baru</a>
        @endif
    </div>
</section>

<div class="statistik">
    <div class="stat"><div class="angka num">{{ $data['total_sesi'] }}</div><div class="ket">Sesi pengamatan</div></div>
    <div class="stat"><div class="angka num">{{ $data['total_lokasi'] }}</div><div class="ket">Detail lokasi</div></div>
    <div class="stat"><div class="angka num">{{ $data['total_perlengkapan'] }}</div><div class="ket">Perlengkapan</div></div>
    <div class="stat"><div class="angka num">{{ $data['total_kategori'] }}</div><div class="ket">Kategori</div></div>
</div>

<div class="grid g-12">
    <section class="panel">
        <header><h3>Sesi terbaru diperbarui</h3><a href="{{ route('sesi-pengamatan.index') }}">Semua sesi</a></header>
        @if ($data['sesi_terbaru']->isEmpty())
            <x-empty-state pesan="Belum ada sesi pengamatan." />
        @else
        <div class="tabel-wrap">
            <table class="tabel">
                <thead><tr><th>Kode</th><th>Sesi</th><th>Tanggal</th><th>Status</th></tr></thead>
                <tbody>
                @foreach ($data['sesi_terbaru'] as $s)
                    <tr>
                        <td class="kode">{{ $s->kode_sesi }}</td>
                        <td><a href="{{ route('sesi-pengamatan.show', $s) }}">{{ $s->nama_sesi }}</a><br><small class="muted">{{ $s->kategori->nama }}</small></td>
                        <td class="num">{{ $s->tanggal_pengamatan->translatedFormat('d M Y') }}</td>
                        <td><x-badge :jenis="$s->status" :teks="$s->status_label" /></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </section>

    <div>
        <section class="panel">
            <header><h3>Sesi menurut status</h3></header>
            <div class="isi">
                @php($maks = max(1, $data['per_status']->max()))
                @foreach (\App\Models\SesiPengamatan::STATUS as $kunci => $label)
                    <div class="batang">
                        <span>{{ $label }}</span>
                        <div class="trek"><div class="isi-batang" style="width: {{ ($data['per_status'][$kunci] / $maks) * 100 }}%"></div></div>
                        <span class="num">{{ $data['per_status'][$kunci] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="panel">
            <header><h3>Perlengkapan paling sering dipakai</h3></header>
            <div class="isi">
                <ul class="daftar-polos">
                    @forelse ($data['alat_terpopuler'] as $a)
                        <li><span>{{ $a->nama }}</span><span class="num muted">{{ $a->sesi_pengamatan_count }} sesi</span></li>
                    @empty
                        <li class="muted">Belum ada data.</li>
                    @endforelse
                </ul>
            </div>
        </section>

        @if ($data['alat_perlu_perhatian']->isNotEmpty())
        <section class="panel">
            <header><h3>Perlengkapan perlu perhatian</h3></header>
            <div class="isi">
                <ul class="daftar-polos">
                    @foreach ($data['alat_perlu_perhatian'] as $a)
                        <li><span>{{ $a->nama }}</span><x-badge :jenis="$a->kondisi" :teks="$a->kondisi_label" /></li>
                    @endforeach
                </ul>
            </div>
        </section>
        @endif
    </div>
</div>
@endsection
