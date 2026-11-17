@props(['action', 'pesan' => 'Yakin ingin menghapus data ini?', 'label' => 'Hapus'])
<form action="{{ $action }}" method="POST" class="inline" data-konfirmasi="{{ $pesan }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-kecil btn-bahaya">{{ $label }}</button>
</form>
