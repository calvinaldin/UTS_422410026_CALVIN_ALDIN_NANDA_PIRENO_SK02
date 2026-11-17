<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = ['nama', 'deskripsi'];

    /** Satu kategori memiliki banyak sesi pengamatan (1-to-Many). */
    public function sesiPengamatan(): HasMany
    {
        return $this->hasMany(SesiPengamatan::class, 'kategori_id');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['search'] ?? null, function (Builder $q, string $s) {
            $q->where(fn (Builder $w) => $w->where('nama', 'like', "%{$s}%")->orWhere('deskripsi', 'like', "%{$s}%"));
        });
    }
}
