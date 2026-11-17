@props(['pesan' => 'Belum ada data.'])
<div class="kosong">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 15c1-2 7-2 8 0M9 9.5h.01M15 9.5h.01"/></svg>
    <div>{{ $pesan }}</div>
    {{ $slot }}
</div>
