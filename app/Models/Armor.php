<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Armor extends Model
{
    protected $guarded = ['id'];
    public function marketplaceItem(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(MarketplaceItem::class, 'tradeable');
    }
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(ArmorLocation::class, 'armor_armor_location', 'armor_id', 'armor_location_id');
    }
}
