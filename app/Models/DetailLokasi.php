<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailLokasi extends Model
{
    protected $table = 'detail_lokasi';

    protected $fillable = [
        'sesi_pengamatan_id', 'nama_lokasi', 'lintang', 'bujur', 'ketinggian', 'catatan_cuaca',
    ];

    protected $casts = [
        'lintang' => 'float',
        'bujur'   => 'float',
    ];

    /** Detail lokasi milik satu sesi pengamatan. */
    public function sesiPengamatan(): BelongsTo
    {
        return $this->belongsTo(SesiPengamatan::class, 'sesi_pengamatan_id');
    }

    public function getKoordinatAttribute(): string
    {
        $ns = $this->lintang < 0 ? 'LS' : 'LU';
        $ew = $this->bujur < 0 ? 'BB' : 'BT';

        return number_format(abs($this->lintang), 4, ',', '.')."° {$ns}, ".number_format(abs($this->bujur), 4, ',', '.')."° {$ew}";
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['search'] ?? null, function (Builder $q, string $s) {
            $q->where(function (Builder $w) use ($s) {
                $w->where('nama_lokasi', 'like', "%{$s}%")
                    ->orWhereHas('sesiPengamatan', fn (Builder $x) => $x
                        ->where('kode_sesi', 'like', "%{$s}%")
                        ->orWhere('nama_sesi', 'like', "%{$s}%"));
            });
        });
    }
}
