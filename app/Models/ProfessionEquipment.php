<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionEquipment extends Model
{
    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(MarketplaceItem::class, 'item_id');
    }
}
