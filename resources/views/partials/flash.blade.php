@if (session('success'))
    <div class="alert alert-ok" role="status">
        <span>{{ session('success') }}</span>
        <button type="button" aria-label="Tutup">&times;</button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-err" role="alert">
        <span>{{ session('error') }}</span>
        <button type="button" aria-label="Tutup">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-err" role="alert">
        <div>
            <strong>Data belum bisa disimpan. Periksa {{ $errors->count() }} isian berikut:</strong>
            <ul>
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
