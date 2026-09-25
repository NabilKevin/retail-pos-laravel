<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'kode_barcode',
        'nama',
        'stok',
        'tipe_id',
        'harga_modal',
        'harga_jual',
        'expired_at',
    ];

    protected $casts = [
        'expired_at'  => 'date',
        'stok'        => 'integer',
        'harga_modal' => 'integer',
        'harga_jual'  => 'integer',
    ];

    // =========================================================
    // Relationships
    // =========================================================

    public function tipe(): BelongsTo
    {
        return $this->belongsTo(TipeObat::class, 'tipe_id', 'id');
    }

    // =========================================================
    // Query Scopes
    // =========================================================

    /** Filter obat yang sudah kadaluarsa (expired_at < today). */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('expired_at')
            ->whereDate('expired_at', '<', now());
    }

    /** Filter obat yang akan kadaluarsa dalam N hari ke depan. */
    public function scopeExpiringSoon(Builder $query, int $days = 30): Builder
    {
        return $query->whereNotNull('expired_at')
            ->whereDate('expired_at', '>=', now())
            ->whereDate('expired_at', '<=', now()->addDays($days));
    }

    /** Filter obat dengan stok di bawah ambang batas. */
    public function scopeLowStock(Builder $query, int $threshold = 10): Builder
    {
        return $query->where('stok', '<', $threshold);
    }

    /** Filter obat dengan stok habis. */
    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('stok', 0);
    }
}
