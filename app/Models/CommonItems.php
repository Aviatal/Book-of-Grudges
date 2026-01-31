<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonItems extends Model
{
    public $timestamps = false;

    public function marketplaceItem(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(MarketplaceItem::class, 'tradeable');
    }
}
