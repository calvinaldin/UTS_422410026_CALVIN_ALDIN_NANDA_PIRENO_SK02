<section class="panel">
    <header><h3>Data perlengkapan</h3></header>
    <div class="isi baris">
        <div class="c4"><x-form.input name="kode" label="Kode" :required="true" :value="$perlengkapan->kode" help="Contoh: TLS-004" /></div>
        <div class="c8"><x-form.input name="nama" label="Nama perlengkapan" :required="true" :value="$perlengkapan->nama" /></div>
        <div class="c6"><x-form.select name="jenis" label="Jenis" :required="true" :options="\App\Models\Perlengkapan::JENIS" :value="$perlengkapan->jenis" /></div>
        <div class="c6"><x-form.select name="kondisi" label="Kondisi" :required="true" :options="\App\Models\Perlengkapan::KONDISI" :value="$perlengkapan->kondisi" :placeholder="null" /></div>
    </div>
</section>
<div class="form-aksi">
    <button type="submit" class="btn btn-utama">Simpan perlengkapan</button>
    <a href="{{ route('perlengkapan.index') }}" class="btn">Batal</a>
</div>
