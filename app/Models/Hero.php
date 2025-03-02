<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hero extends Model
{
    protected $table = 'heros';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currentProfession(): BelongsTo
    {
        return $this->belongsTo(Profession::class, 'current_profession_id');
    }

    public function previousProfession(): BelongsTo
    {
        return $this->belongsTo(Profession::class, 'previous_profession_id');
    }
}
