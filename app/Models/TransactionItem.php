<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionItem extends Model
{
    protected $table = 'transactionitem';

    public $timestamps = false;

    protected $fillable = [
        'obat_id',
        'transaction_id',
        'harga_modal',
        'harga_jual',
        'qty',
        'subtotal',
        'returned_qty',
    ];

    protected $casts = [
        'harga_modal'  => 'integer',
        'harga_jual'   => 'integer',
        'qty'          => 'integer',
        'subtotal'     => 'integer',
        'returned_qty' => 'integer',
    ];

    // =========================================================
    // Relationships
    // =========================================================

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'obat_id', 'id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(TransactionReturn::class, 'transaction_item_id');
    }
}
