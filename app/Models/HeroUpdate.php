<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroUpdate extends Model
{
    protected $table = 'hero_update';
    protected $guarded = ['id'];
    public const array TYPES = [
        'EXP' => 'EXPERIENCE',
        'FP' => 'FORTUNE_POINTS'
    ];

    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }
}
