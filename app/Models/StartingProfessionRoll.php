<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartingProfessionRoll extends Model
{
    public $timestamps = false;
    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }
}
