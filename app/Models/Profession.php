<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profession extends Model
{
    public function startingProfessionRolls(): HasMany
    {
        return $this->hasMany(StartingProfessionRoll::class);
    }
    public function characteristics(): HasMany
    {
        return $this->hasMany(ProfessionCharacteristic::class);
    }
    public function equipments(): HasMany
    {
        return $this->hasMany(ProfessionEquipment::class);
    }
    public function skills(): HasMany
    {
        return $this->hasMany(ProfessionSkill::class);
    }
    public function talents(): HasMany
    {
        return $this->hasMany(ProfessionTalent::class);
    }

    public function scopeRolledProfession($query, string $race, int $rolledValue)
    {
        return $query->whereHas('startingProfessionRolls', function ($query) use ($rolledValue, $race) {
            $query->where('min_roll', '<=', $rolledValue)->where('max_roll', '>=', $rolledValue)->where('race', $race);
        });
    }
}
