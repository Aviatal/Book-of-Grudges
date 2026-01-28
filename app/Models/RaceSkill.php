<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceSkill extends Model
{
    public $timestamps = false;

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
