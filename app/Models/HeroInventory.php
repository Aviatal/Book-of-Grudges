<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroInventory extends Model
{
    protected $table = 'hero_inventory';
    protected $guarded = ['id'];

    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }
}
