<?php

namespace Database\Seeders;

use App\Models\Ammunition;
use App\Models\Armor;
use App\Models\CommonItems;
use App\Models\MarketplaceItem;
use App\Models\Weapon;
use Illuminate\Database\Seeder;

class MarketplaceItemsSeeder extends Seeder
{
    public function run(): void
    {
        MarketplaceItem::truncate();

        $seedData = [];
        Armor::all()->each(function ($armor) use (&$seedData) {
            $seedData[] = [
                'tradeable_id' => $armor->id,
                'tradeable_type' => Armor::class,
            ];
        });
        Weapon::all()->each(function (Weapon $weapon) use (&$seedData) {
            $seedData[] = [
                'tradeable_id' => $weapon->id,
                'tradeable_type' => Weapon::class,
            ];
        });
        Ammunition::all()->each(function (Ammunition $ammunition) use (&$seedData) {
            $seedData[] = [
                'tradeable_id' => $ammunition->id,
                'tradeable_type' => Ammunition::class,
            ];
        });
        CommonItems::all()->each(function (CommonItems $commonItem) use (&$seedData) {
            $seedData[] = [
                'tradeable_id' => $commonItem->id,
                'tradeable_type' => CommonItems::class,
            ];
        });

        MarketplaceItem::insert($seedData);
    }
}
