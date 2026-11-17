@if ($paginator->hasPages())
<nav class="paginasi" role="navigation" aria-label="Halaman">
    <div class="muted num">Menampilkan {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data</div>
    <ul>
        @if ($paginator->onFirstPage())
            <li class="mati"><span class="hal">Sebelumnya</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="mati"><span class="hal">{{ $element }}</span></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="aktif"><span class="hal" aria-current="page">{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a></li>
        @else
            <li class="mati"><span class="hal">Berikutnya</span></li>
        @endif
    </ul>
</nav>
@endif
