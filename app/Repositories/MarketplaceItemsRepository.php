<?php

namespace App\Repositories;

use App\Models\MarketplaceItem;
use Illuminate\Support\Collection;

class MarketplaceItemsRepository
{
    public function getItemsForSale(array $select = ['*']) : Collection
    {
        return MarketplaceItem::select($select)
            ->with('tradeable')
            ->get();
    }
}
