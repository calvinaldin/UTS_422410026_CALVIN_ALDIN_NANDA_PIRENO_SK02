@props(['title', 'subtitle' => null])
<div class="page-head">
    <div>
        <h1>{{ $title }}</h1>
        @if($subtitle)<p>{{ $subtitle }}</p>@endif
    </div>
    @if($slot->isNotEmpty())<div class="aksi">{{ $slot }}</div>@endif
</div>
