<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroCharacteristic extends Model
{
    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }
}
