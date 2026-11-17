@php
    // '_alat' penanda form sudah dikirim, agar "semua dicentang-lepas" tidak kembali ke data lama
    $terpilih = array_map('strval', old('_alat')
        ? old('perlengkapan_ids', [])
        : ($sesi->exists ? $sesi->perlengkapan->pluck('id')->all() : []));
    $jumlahPivot = $sesi->exists ? $sesi->perlengkapan->pluck('pivot.jumlah', 'id')->all() : [];
    $lokasi = $sesi->detailLokasi;
@endphp

<section class="panel">
    <header><h3>Data sesi</h3></header>
    <div class="isi baris">
        <div class="c4"><x-form.input name="kode_sesi" label="Kode sesi" :required="true" :value="$sesi->kode_sesi" help="Contoh: SES-2026-009" /></div>
        <div class="c8"><x-form.input name="nama_sesi" label="Nama sesi" :required="true" :value="$sesi->nama_sesi" /></div>
        <div class="c6"><x-form.input name="objek_sasaran" label="Objek sasaran" :required="true" :value="$sesi->objek_sasaran" help="Contoh: Saturnus, M31, Bulan" /></div>
        <div class="c6"><x-form.select name="kategori_id" label="Kategori" :required="true" :options="$kategoriList->pluck('nama', 'id')" :value="$sesi->kategori_id" /></div>
        <div class="c4"><x-form.input type="date" name="tanggal_pengamatan" label="Tanggal pengamatan" :required="true" :value="$sesi->tanggal_pengamatan?->format('Y-m-d')" /></div>
        <div class="c3"><x-form.input type="time" name="waktu_mulai" label="Waktu mulai" :value="$sesi->waktu_mulai ? substr($sesi->waktu_mulai, 0, 5) : null" /></div>
        <div class="c3"><x-form.input type="time" name="waktu_selesai" label="Waktu selesai" :value="$sesi->waktu_selesai ? substr($sesi->waktu_selesai, 0, 5) : null" help="Boleh melewati tengah malam." /></div>
        <div class="c4"><x-form.select name="status" label="Status" :required="true" :options="\App\Models\SesiPengamatan::STATUS" :value="$sesi->status" :placeholder="null" /></div>
        <div class="c12"><x-form.textarea name="catatan" label="Catatan sesi" :value="$sesi->catatan" /></div>
    </div>
</section>

<section class="panel">
    <header><h3>Detail lokasi pengamatan</h3><small class="muted">Satu sesi = satu lokasi (One-to-One)</small></header>
    <div class="isi baris">
        <div class="c6"><x-form.input name="lokasi[nama_lokasi]" label="Nama lokasi" :required="true" :value="$lokasi?->nama_lokasi" /></div>
        <div class="c3"><x-form.input type="number" step="any" name="lokasi[lintang]" label="Lintang" :required="true" :value="$lokasi?->lintang" help="-90 s/d 90. Selatan bernilai negatif." /></div>
        <div class="c3"><x-form.input type="number" step="any" name="lokasi[bujur]" label="Bujur" :required="true" :value="$lokasi?->bujur" help="-180 s/d 180." /></div>
        <div class="c4"><x-form.input type="number" name="lokasi[ketinggian]" label="Ketinggian (mdpl)" :value="$lokasi?->ketinggian" /></div>
        <div class="c8"><x-form.textarea name="lokasi[catatan_cuaca]" label="Catatan cuaca" rows="2" :value="$lokasi?->catatan_cuaca" /></div>
    </div>
</section>

<section class="panel">
    <header><h3>Perlengkapan yang digunakan</h3><small class="muted">Pilih lebih dari satu (Many-to-Many)</small></header>
    <div class="isi">
        <input type="hidden" name="_alat" value="1">
        @forelse ($perlengkapanList as $jenis => $items)
            <div class="alat-grup">
                <h4>{{ \App\Models\Perlengkapan::JENIS[$jenis] ?? ucfirst($jenis) }}</h4>
                <div class="alat-daftar">
                    @foreach ($items as $p)
                        @php($dipilih = in_array((string) $p->id, $terpilih, true))
                        <label class="alat">
                            <input type="checkbox" name="perlengkapan_ids[]" value="{{ $p->id }}" @checked($dipilih)>
                            <span class="teks">
                                <b>{{ $p->nama }}</b>
                                <small>{{ $p->kode }} &middot; {{ $p->kondisi_label }}</small>
                            </span>
                            <input type="number" min="1" max="99" class="input jml" name="jumlah[{{ $p->id }}]"
                                   value="{{ old('jumlah.'.$p->id, $jumlahPivot[$p->id] ?? 1) }}" aria-label="Jumlah {{ $p->nama }}" @disabled(! $dipilih)>
                        </label>
                    @endforeach
                </div>
            </div>
        @empty
            <x-empty-state pesan="Belum ada data perlengkapan.">
                <p><a href="{{ route('perlengkapan.create') }}" class="btn btn-kecil">Tambah perlengkapan</a></p>
            </x-empty-state>
        @endforelse
        @error('perlengkapan_ids.*')<div class="pesan-salah">{{ $message }}</div>@enderror
        @error('jumlah.*')<div class="pesan-salah">{{ $message }}</div>@enderror
    </div>
</section>

<div class="form-aksi">
    <button type="submit" class="btn btn-utama">Simpan sesi</button>
    <a href="{{ route('sesi-pengamatan.index') }}" class="btn">Batal</a>
</div>
