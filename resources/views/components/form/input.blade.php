@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'help' => null, 'step' => null])
@php($key = str_replace(['[', ']'], ['.', ''], $name))
<div class="field">
    <label for="{{ $key }}">{{ $label }} @if($required)<span class="wajib" title="Wajib diisi">*</span>@endif</label>
    <input type="{{ $type }}" id="{{ $key }}" name="{{ $name }}" value="{{ old($key, $value) }}"
           @if($step) step="{{ $step }}" @endif
           {{ $attributes->class(['input', 'salah' => $errors->has($key)]) }}>
    @if($help)<div class="bantuan">{{ $help }}</div>@endif
    @error($key)<div class="pesan-salah">{{ $message }}</div>@enderror
</div>
