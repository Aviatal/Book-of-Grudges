<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceBaseStat extends Model
{
    public $timestamps = false;

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function characteristic(): BelongsTo
    {
        return $this->belongsTo(Characteristic::class);
    }
}
