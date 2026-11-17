<section class="panel">
    <header><h3>Data kategori</h3></header>
    <div class="isi">
        <x-form.input name="nama" label="Nama kategori" :required="true" :value="$kategori->nama" />
        <x-form.textarea name="deskripsi" label="Deskripsi" :value="$kategori->deskripsi" />
    </div>
</section>
<div class="form-aksi">
    <button type="submit" class="btn btn-utama">Simpan kategori</button>
    <a href="{{ route('kategori.index') }}" class="btn">Batal</a>
</div>
