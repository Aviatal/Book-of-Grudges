<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionCharacteristic extends Model
{
    public $timestamps = false;

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function characteristic(): BelongsTo
    {
        return $this->belongsTo(Characteristic::class);
    }
}
