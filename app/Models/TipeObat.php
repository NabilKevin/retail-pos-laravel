<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipeObat extends Model
{
    protected $table = 'tipeobat';

    public $timestamps = false;

    protected $fillable = ['nama'];

    // =========================================================
    // Relationships
    // =========================================================

    public function obats(): HasMany
    {
        return $this->hasMany(Obat::class, 'tipe_id', 'id');
    }
}
