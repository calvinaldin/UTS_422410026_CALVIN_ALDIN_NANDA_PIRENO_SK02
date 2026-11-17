@props(['jenis' => 'netral', 'teks' => null])
<span class="badge b-{{ $jenis }}">{{ $teks ?? $slot }}</span>
