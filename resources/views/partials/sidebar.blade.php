<aside class="sidebar">
    <a href="{{ route('dashboard') }}" class="brand">
        <svg width="38" height="38" viewBox="0 0 40 40" aria-hidden="true">
            <circle cx="20" cy="20" r="18" fill="none" stroke="#d42f4b" stroke-width="2"/>
            <path d="M20 5 L22.2 17.8 L35 20 L22.2 22.2 L20 35 L17.8 22.2 L5 20 L17.8 17.8 Z" fill="#fff"/>
        </svg>
        <span><b>Langit Gelap</b><small>Observatorium</small></span>
    </a>

    <nav class="nav" aria-label="Menu utama">
        <a href="{{ route('dashboard') }}" @class(['aktif' => request()->routeIs('dashboard')])>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
            Dashboard
        </a>
        <div class="grup">Pengamatan</div>
        <a href="{{ route('sesi-pengamatan.index') }}" @class(['aktif' => request()->routeIs('sesi-pengamatan.*')])>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            Sesi pengamatan
        </a>
        <a href="{{ route('lokasi.index') }}" @class(['aktif' => request()->routeIs('lokasi.*')])>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
            Lokasi pengamatan
        </a>
        <div class="grup">Data master</div>
        <a href="{{ route('perlengkapan.index') }}" @class(['aktif' => request()->routeIs('perlengkapan.*')])>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 18l9-9 3 3-9 9z"/><path d="M14 4l6 6"/><path d="M16 2l6 6"/></svg>
            Perlengkapan
        </a>
        <a href="{{ route('kategori.index') }}" @class(['aktif' => request()->routeIs('kategori.*')])>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h7l9 9-7 7-9-9z"/><circle cx="8.5" cy="8.5" r="1"/></svg>
            Kategori
        </a>
    </nav>

    <div class="sidebar-foot">UTS Pemrograman Web Lanjut<br>Laravel 12 &middot; MySQL</div>
</aside>
