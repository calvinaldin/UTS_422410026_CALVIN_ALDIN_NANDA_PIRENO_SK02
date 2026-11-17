<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Perlengkapan extends Model
{
    public const JENIS = [
        'teleskop' => 'Teleskop',
        'kamera'   => 'Kamera CCD / CMOS',
        'filter'   => 'Filter',
        'tripod'   => 'Tripod / Mount',
        'lainnya'  => 'Lainnya',
    ];

    public const KONDISI = [
        'baik'            => 'Baik',
        'perlu_kalibrasi' => 'Perlu kalibrasi',
        'rusak'           => 'Rusak',
    ];

    protected $table = 'perlengkapan';

    protected $fillable = ['kode', 'nama', 'jenis', 'kondisi'];

    /** Perlengkapan dipakai pada banyak sesi (Many-to-Many). */
    public function sesiPengamatan(): BelongsToMany
    {
        return $this->belongsToMany(SesiPengamatan::class, 'perlengkapan_sesi', 'perlengkapan_id', 'sesi_pengamatan_id')
            ->withPivot('jumlah')
            ->withTimestamps();
    }

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? ucfirst((string) $this->jenis);
    }

    public function getKondisiLabelAttribute(): string
    {
        return self::KONDISI[$this->kondisi] ?? ucfirst((string) $this->kondisi);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $q, string $s) {
                $q->where(fn (Builder $w) => $w->where('kode', 'like', "%{$s}%")->orWhere('nama', 'like', "%{$s}%"));
            })
            ->when($filters['jenis'] ?? null, fn (Builder $q, $v) => $q->where('jenis', $v))
            ->when($filters['kondisi'] ?? null, fn (Builder $q, $v) => $q->where('kondisi', $v));
    }
}
