<?php

namespace App\Services;

use App\Exceptions\AlreadyEquippedException;
use App\Exceptions\NotEnoughMoneyException;
use App\Models\Armor;
use App\Models\Hero;
use App\Models\HeroInventory;
use App\Models\MarketplaceItem;
use App\Models\Weapon;
use Auth;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TransactionsService
{
    /**
     * @throws NotEnoughMoneyException
     * @throws \Throwable
     */
    public function equipMarketplaceItem(Request $request, int $id): ?JsonResponse
    {
        try {
            $marketplaceItem = MarketplaceItem::query()->findOrFail($request->input('item'));
            $hero = Hero::query()->findOrFail(Auth::user()->hero->id);

            DB::beginTransaction();
            $hero->pay($request->input('price'));
            switch ($marketplaceItem->tradeable_type) {
                case 'App\Models\Armor':
                    $armor = Armor::query()->with('locations')->find($request->get('tradeable_id'));
                    try {
                        if (DB::table('hero_armors')->where('hero_id', $hero->id)->where('armor_id', $armor->id)->first()) {
                            throw new AlreadyEquippedException('Już nosisz taką zbroję! Dodano ją do ekwipunku.');
                        }
                        $hero->armors()->syncWithoutDetaching($armor->id);
                        DB::commit();
                        return response()->json([
                            'item' => [
                                'item' => $armor,
                                'type' => 'armor'
                            ],
                            'wealth' => [
                                'goldCrowns' => $hero->gold_crowns,
                                'silverShillings' => $hero->silver_shillings,
                                'brassPennies' => $hero->brass_pennies,
                            ]
                        ]);
                    } catch (AlreadyEquippedException $exception) {
                        $inventoryItem = HeroInventory::query()->create([
                            'hero_id' => $id,
                            'name' => $armor->name,
                            'loading' => $armor->loading,
                        ]);
                        DB::commit();
                        return response()->json([
                            'item' => [
                                'message' => $exception->getMessage(),
                                'item' => $inventoryItem,
                                'type' => 'marketplace'
                            ],
                            'wealth' => [
                                'goldCrowns' => $hero->gold_crowns,
                                'silverShillings' => $hero->silver_shillings,
                                'brassPennies' => $hero->brass_pennies,
                            ]
                        ], Response::HTTP_OK);
                    }
                case 'App\Models\Weapon':
                    $weapon = Weapon::query()->find($request->get('weaponId'));
                    try {
                        if (DB::table('hero_weapons')->where('hero_id', $hero->id)->where('weapon_id', $weapon->id)->first()) {
                            throw new AlreadyEquippedException('Już posiadasz taką broń! Broń została schowana do ekwipunku');
                        }
                        $hero->weapons()->syncWithoutDetaching([
                            $weapon->id => ['additional_weapon_name' => $request->get('additionalWeaponName')]
                        ]);
                        DB::commit();

                        return response()->json([
                            'item' => [
                                'item' => collect([
                                    'id' => $weapon->id,
                                    'is_ranged' => $weapon->is_ranged,
                                    'name' => $weapon->name,
                                    'loading' => $weapon->loading,
                                    'category' => $weapon->category,
                                    'power' => $weapon->power,
                                    'short_range' => $weapon->short_range,
                                    'long_range' => $weapon->long_range,
                                    'reload_time' => $weapon->reload_time,
                                    'traits' => $weapon->traits,
                                    'pivot' => [
                                        'additional_weapon_name' => $request->input('customName', '')
                                    ]
                                ])
                            ],
                            'wealth' => [
                                'goldCrowns' => $hero->gold_crowns,
                                'silverShillings' => $hero->silver_shillings,
                                'brassPennies' => $hero->brass_pennies,
                            ]
                        ]);
                    } catch (AlreadyEquippedException $exception) {
                        $inventoryItem = HeroInventory::query()->create([
                            'hero_id' => $id,
                            'name' => $weapon->name . ' ' . $request->input('customName'),
                            'loading' => $weapon->loading,
                        ]);
                        DB::commit();
                        return response()->json([
                            'item' => [
                                'message' => $exception->getMessage(),
                                'item' => $inventoryItem,
                                'type' => 'marketplace'
                            ],
                            'wealth' => [
                                'goldCrowns' => $hero->gold_crowns,
                                'silverShillings' => $hero->silver_shillings,
                                'brassPennies' => $hero->brass_pennies,
                            ]
                        ], Response::HTTP_OK);
                    }
                case 'App\Models\CommonItems':
                    $inventoryItem = HeroInventory::query()->create([
                        'hero_id' => $id,
                        'name' => $request->input('customName'),
                        'loading' => $marketplaceItem->tradeable->getAttribute('loading'),
                    ]);
                    DB::commit();
                    return response()->json([
                        'item' => [
                            'item' => $inventoryItem,
                            'type' => 'marketplace'
                        ],
                        'wealth' => [
                            'goldCrowns' => $hero->gold_crowns,
                            'silverShillings' => $hero->silver_shillings,
                            'brassPennies' => $hero->brass_pennies,
                        ]
                    ]);
                default:
                    throw new BadRequestHttpException('Unknown item type: ' . $marketplaceItem->tradeable_type);
            }
        } catch (NotEnoughMoneyException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            \Log::error('ERROR EQUIPPING MARKETPLACE ITEM', [
                'exception' => $exception,
            ]);
            throw $exception;
        }
    }
}
