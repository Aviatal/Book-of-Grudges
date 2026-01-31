<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceTalent extends Model
{
    public $timestamps = false;

    protected $table = 'race_talents';

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }
}
