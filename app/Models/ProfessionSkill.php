<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionSkill extends Model
{
    public $timestamps = false;

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
