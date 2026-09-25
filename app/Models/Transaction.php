<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $table = 'transaction';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'status',
        'total_transaksi',
        'total_dibayar',
        'total_kembalian',
        'total_return',
        'void_reason',
        'user_id',
        'void_by',
        'void_at',
        'paid_at',
    ];

    protected $casts = [
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
        'void_at'         => 'datetime',
        'paid_at'         => 'datetime',
        'total_transaksi' => 'integer',
        'total_dibayar'   => 'integer',
        'total_kembalian' => 'integer',
        'total_return'    => 'integer',
    ];

    // =========================================================
    // Relationships
    // =========================================================

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id', 'id');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(TransactionReturn::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function voidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'void_by');
    }

    // =========================================================
    // Accessors
    // =========================================================

    /**
     * Returns the name of the kasir who processed the transaction.
     * Safe to call only when the 'user' relation is already eager-loaded.
     */
    public function getKasirAttribute(): string
    {
        return $this->relationLoaded('user') && $this->user
            ? $this->user->nama
            : '-';
    }
}
