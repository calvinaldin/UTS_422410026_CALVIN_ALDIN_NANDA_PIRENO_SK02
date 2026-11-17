@props(['name', 'label', 'options' => [], 'value' => null, 'required' => false, 'placeholder' => '-- Pilih --'])
@php($key = str_replace(['[', ']'], ['.', ''], $name))
<div class="field">
    <label for="{{ $key }}">{{ $label }} @if($required)<span class="wajib" title="Wajib diisi">*</span>@endif</label>
    <select id="{{ $key }}" name="{{ $name }}" {{ $attributes->class(['input', 'salah' => $errors->has($key)]) }}>
        @if($placeholder !== null)<option value="">{{ $placeholder }}</option>@endif
        @foreach ($options as $val => $teks)
            <option value="{{ $val }}" @selected((string) old($key, $value) === (string) $val)>{{ $teks }}</option>
        @endforeach
    </select>
    @error($key)<div class="pesan-salah">{{ $message }}</div>@enderror
</div>
