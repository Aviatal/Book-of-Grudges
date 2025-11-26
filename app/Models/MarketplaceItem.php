<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceItem extends Model
{
    public $timestamps = false;

    public function tradeable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
