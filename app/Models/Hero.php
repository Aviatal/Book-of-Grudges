<?php

namespace App\Models;

use App\Helpers\Traits\HasManyKeyBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Hero extends Model
{
    use HasFactory, HasManyKeyBy;
    protected $guarded = ['id'];

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

    public function description(): HasOne
    {
        return $this->hasOne(HeroDescription::class);
    }

    public function characteristic(): HasMany
    {
        return $this->hasManyKeyBy('short_name', HeroCharacteristic::class);
    }

    public function weapons(): BelongsToMany
    {
        return $this->belongsToMany(Weapon::class, 'hero_weapons', 'hero_id', 'weapon_id');
    }
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withPivot(['additional_skill_name', 'first_level', 'second_level']);
    }

    public function armors(): BelongsToMany
    {
        return $this->belongsToMany(Armor::class, 'hero_armors', 'hero_id', 'armor_id');
    }

    public function coldWeapons(): BelongsToMany
    {
        return $this->weapons()->where('is_ranged', 0);
    }

    public function rangedWeapons(): BelongsToMany
    {
        return $this->weapons()->where('is_ranged', 1);
    }
}
