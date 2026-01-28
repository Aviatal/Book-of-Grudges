<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    public $timestamps = false;

    public function bonuses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RaceBonus::class);
    }
    public function baseStats(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RaceBaseStat::class);
    }
    public function skills(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RaceSkill::class);
    }
    public function talents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RaceTalent::class);
    }
}
