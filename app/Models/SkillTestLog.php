<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillTestLog extends Model
{
    protected $fillable = [
        'hero_id',
        'skill_id',
        'campaign_id',
        'skill_name',
        'characteristic',
        'characteristic_value',
        'effective_value',
        'modifier',
        'half',
        'has_modifier',
        'roll',
        'passed',
    ];

    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    protected function casts(): array
    {
        return [
            'half' => 'boolean',
            'has_modifier' => 'boolean',
            'passed' => 'boolean',
        ];
    }
}
