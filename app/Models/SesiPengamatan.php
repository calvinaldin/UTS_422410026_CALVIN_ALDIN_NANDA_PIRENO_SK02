<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SesiPengamatan extends Model
{
    public const STATUS = [
        'direncanakan' => 'Direncanakan',
        'berlangsung'  => 'Berlangsung',
        'selesai'      => 'Selesai',
        'dibatalkan'   => 'Dibatalkan',
    ];

    protected $table = 'sesi_pengamatan';

    protected $fillable = [
        'kode_sesi', 'nama_sesi', 'objek_sasaran', 'kategori_id',
        'tanggal_pengamatan', 'waktu_mulai', 'waktu_selesai', 'status', 'catatan',
    ];

    protected $casts = [
        'tanggal_pengamatan' => 'date',
    ];

    /** Sesi dimiliki oleh satu kategori (Many-to-One). */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /** Satu sesi memiliki satu detail lokasi (One-to-One). */
    public function detailLokasi(): HasOne
    {
        return $this->hasOne(DetailLokasi::class, 'sesi_pengamatan_id');
    }

    /** Satu sesi memakai banyak perlengkapan, satu perlengkapan dipakai banyak sesi (Many-to-Many). */
    public function perlengkapan(): BelongsToMany
    {
        return $this->belongsToMany(Perlengkapan::class, 'perlengkapan_sesi', 'sesi_pengamatan_id', 'perlengkapan_id')
            ->withPivot('jumlah')
            ->withTimestamps();
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status] ?? ucfirst((string) $this->status);
    }

    /** Pencarian: kode, nama, objek sasaran, nama lokasi. Filter: kategori, status, rentang tanggal. */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $q, string $s) {
                $q->where(function (Builder $w) use ($s) {
                    $w->where('kode_sesi', 'like', "%{$s}%")
                        ->orWhere('nama_sesi', 'like', "%{$s}%")
                        ->orWhere('objek_sasaran', 'like', "%{$s}%")
                        ->orWhereHas('detailLokasi', fn (Builder $l) => $l->where('nama_lokasi', 'like', "%{$s}%"));
                });
            })
            ->when($filters['kategori_id'] ?? null, fn (Builder $q, $v) => $q->where('kategori_id', $v))
            ->when($filters['status'] ?? null, fn (Builder $q, $v) => $q->where('status', $v))
            ->when($filters['dari'] ?? null, fn (Builder $q, $v) => $q->whereDate('tanggal_pengamatan', '>=', $v))
            ->when($filters['sampai'] ?? null, fn (Builder $q, $v) => $q->whereDate('tanggal_pengamatan', '<=', $v));
    }
}
