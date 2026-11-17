<section class="panel">
    <header><h3>Detail lokasi</h3></header>
    <div class="isi baris">
        <div class="c12">
            @if ($lokasi->exists)
                <div class="field">
                    <span class="label">Sesi pengamatan</span>
                    <div><span class="kode">{{ $lokasi->sesiPengamatan->kode_sesi }}</span> &ndash; {{ $lokasi->sesiPengamatan->nama_sesi }}</div>
                    <div class="bantuan">Hubungan lokasi dengan sesi tidak dapat dipindahkan.</div>
                </div>
            @else
                @if ($sesiTanpaLokasi->isEmpty())
                    <div class="alert alert-err">Semua sesi sudah memiliki detail lokasi. Tambahkan sesi baru, atau hapus lokasi sebuah sesi terlebih dahulu.</div>
                @endif
                @php($opsiSesi = $sesiTanpaLokasi->mapWithKeys(fn ($s) => [$s->id => $s->kode_sesi.' - '.$s->nama_sesi]))
                <x-form.select name="sesi_pengamatan_id" label="Hubungkan ke sesi" :required="true"
                    :options="$opsiSesi"
                    :value="$lokasi->sesi_pengamatan_id" placeholder="-- Pilih sesi yang belum punya lokasi --" />
            @endif
        </div>
        <div class="c6"><x-form.input name="nama_lokasi" label="Nama lokasi" :required="true" :value="$lokasi->nama_lokasi" /></div>
        <div class="c3"><x-form.input type="number" step="any" name="lintang" label="Lintang" :required="true" :value="$lokasi->lintang" help="-90 s/d 90" /></div>
        <div class="c3"><x-form.input type="number" step="any" name="bujur" label="Bujur" :required="true" :value="$lokasi->bujur" help="-180 s/d 180" /></div>
        <div class="c4"><x-form.input type="number" name="ketinggian" label="Ketinggian (mdpl)" :value="$lokasi->ketinggian" /></div>
        <div class="c8"><x-form.textarea name="catatan_cuaca" label="Catatan cuaca" rows="2" :value="$lokasi->catatan_cuaca" /></div>
    </div>
</section>
<div class="form-aksi">
    <button type="submit" class="btn btn-utama">Simpan lokasi</button>
    <a href="{{ route('lokasi.index') }}" class="btn">Batal</a>
</div>
