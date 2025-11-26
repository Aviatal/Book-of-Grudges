<?php

namespace App\Services;

use App\Models\Hero;
use App\Models\MarketplaceItem;
use Illuminate\Http\Request;

class PurchaseService
{
    public function sendPurchaseToPlayer(Request $request): void
    {
        $hero = Hero::findOrFail($request->input('hero_id'));
        $item = MarketplaceItem::findOrFail($request->input('item_id'));
        $price = $request->input('price');
        $customName = $request->input('custom_name');
        event(new \App\Events\PurchaseEvent($hero->id, $item, $price, $customName));
    }
}
