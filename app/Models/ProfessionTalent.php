<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionTalent extends Model
{
    public $timestamps = false;

    protected $table = 'profession_talents';

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }
}
