<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Weapon extends Model
{
    protected $guarded = ['id'];

    public function traits(): BelongsToMany
    {
        return $this->belongsToMany(WeaponTrait::class, 'weapon_traits_weapons', 'weapon_id', 'trait_id');
    }
}
