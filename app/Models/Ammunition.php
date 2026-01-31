<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ammunition extends Model
{
    protected $table = 'ammunition';
    public function marketplaceItem(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(MarketplaceItem::class, 'tradeable');
    }
}
