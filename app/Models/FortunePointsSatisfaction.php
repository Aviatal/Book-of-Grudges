<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FortunePointsSatisfaction extends Model
{
    protected $fillable = [
        'hero_id',
        'satisfied',
    ];
    protected $table = 'fortune_points_satisfaction';

    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }

    protected function casts(): array
    {
        return [
            'satisfied' => 'boolean',
        ];
    }
}
