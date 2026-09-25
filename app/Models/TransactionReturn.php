<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionReturn extends Model
{
    protected $table = 'transaction_returns';

    protected $fillable = [
        'transaction_id',
        'transaction_item_id',
        'user_id',
        'qty',
        'amount',
        'reason',
    ];

    protected $casts = [
        'qty'    => 'integer',
        'amount' => 'integer',
    ];

    // =========================================================
    // Relationships
    // =========================================================

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(TransactionItem::class, 'transaction_item_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
