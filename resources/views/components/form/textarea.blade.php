@props(['name', 'label', 'value' => null, 'rows' => 3, 'help' => null])
@php($key = str_replace(['[', ']'], ['.', ''], $name))
<div class="field">
    <label for="{{ $key }}">{{ $label }}</label>
    <textarea id="{{ $key }}" name="{{ $name }}" rows="{{ $rows }}" {{ $attributes->class(['input', 'salah' => $errors->has($key)]) }}>{{ old($key, $value) }}</textarea>
    @if($help)<div class="bantuan">{{ $help }}</div>@endif
    @error($key)<div class="pesan-salah">{{ $message }}</div>@enderror
</div>
