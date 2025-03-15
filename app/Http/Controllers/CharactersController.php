<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use App\Models\HeroCharacteristic;
use App\Models\HeroInventory;
use App\Models\Skill;
use App\Models\Weapon;
use Auth;
use DB;
use Illuminate\Http\Request;

class CharactersController extends Controller
{
    public function getHero(int $userId)
    {
        if ($userId !== Auth::user()->getAuthIdentifier()) {
            abort(404);
        }

        return response()->json(
            Hero::with(
                'previousProfession', 'currentProfession', 'description',
                'characteristic', 'coldWeapons.traits', 'rangedWeapons.traits', 'armors.locations',
                'skills', 'talents', 'inventory'
            )
                ->where('user_id', $userId)
                ->first()
        );
    }

    public function index(Request $request, int $id)
    {
        return view('Pages.character-sheet', compact('id'));
    }

    public function updateHero(Request $request, Hero $hero)
    {
        try {
            if (!$request->has(['field', 'value'])) {
                throw new \Exception('Nie przesłano wymaganych danych');
            }
            $hero->update([$request->get('field') => $request->get('value')]);
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function updateDescription(Request $request, Hero $hero)
    {
        try {
            if (!$request->has(['field', 'value'])) {
                throw new \Exception('Nie przesłano wymaganych danych');
            }
            $hero->description()->update([$request->get('field') => $request->get('value')]);
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function updateCharacteristic(Request $request, Hero $hero)
    {
        $hero->loadMissing('characteristic');
        try {
            DB::beginTransaction();
            if (!$request->has('characteristic')) {
                throw new \Exception('Nie przesłano wymaganych pól');
            }
            $characteristic = $request->get("characteristic");
            $hero->characteristic()->syncWithoutDetaching([
                $characteristic['id'] => [
                    'start_value' => $characteristic['pivot']['start_value'],
                    'advancement' => $characteristic['pivot']['advancement'],
                    'current_value' => $characteristic['pivot']['current_value']
                ]
            ]);
            $changeCurrentWounds = 0;
            if (
                $characteristic['short_name'] == 'Zyw' &&
                $hero->getRelation('characteristic')[$characteristic['short_name']]->pivot->current_value == $hero->current_wounds
            ) {
                $hero->update(['current_wounds' => $characteristic['pivot']['current_value']]);
                $changeCurrentWounds = $characteristic['pivot']['current_value'];
            }
            DB::commit();
        } catch (\Throwable $exception) {
            try {
                DB::rollBack();
            } catch (\Throwable $e) {
                \Log::error('FATAL ERROR DURING ROLLBACK CHANGE ' . $e->getMessage());
                return response()->json(['message' => $exception->getMessage()]);
            }
            return response()->json(['message' => $exception->getMessage()]);
        }

        return response()->json(['message' => 'Udało się zakutalizować cechę bohatera', 'changeCurrentWounds' => $changeCurrentWounds]);
    }

    public function addWeapon(Request $request, Hero $hero)
    {
        $weapon = Weapon::query()->find($request->get('weaponId'));
        try {
            if (DB::table('hero_weapons')->where('hero_id', $hero->id)->where('weapon_id',$weapon->id)->first()) {
                throw new \Exception('Już posiadasz taką broń! Jeśli chcesz jej używać, aktualną broń tego typu usuń z zakładki "Broń" i zapisz do ekwipunku');
            }
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 502);
        }
        $hero->weapons()->syncWithoutDetaching([
            $weapon->id => ['additional_weapon_name' => $request->get('additionalWeaponName')]
        ]);

        return response()->json(collect([
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
                'additional_weapon_name' => $request->get('additionalWeaponName')
            ]
        ]));
    }

    public function editWeapon(Request $request, Hero $hero)
    {
        $hero->weapons()->syncWithoutDetaching([
            $request->get('weapon')['id'] => ['additional_weapon_name' => $request->get('additionalWeaponName')]
        ]);

        return response()->json(['message' => 'Pomyślnie zedytowano broń']);
    }

    public function dropWeapon(Request $request, Hero $hero)
    {
        $weapon = $request->get('weapon');
        $hero->weapons()->detach($weapon['id']);

        return response()->json(['message' => 'Pomyślnie usunięto broń']);
    }

    public function unequipWeapon(Request $request, Hero $hero)
    {
        $weapon = $request->get('weapon');
        $hero->weapons()->detach($weapon['id']);
        $newInventoryItem = $hero->inventory()->create([
            'name' => $weapon['name'],
            'loading' => $weapon['loading'],
            'description' => $weapon['pivot']['additional_weapon_name']
        ]);

        return response()->json(['message' => 'Pomyślnie schowano broń do ekwipunku', 'inventory' => $newInventoryItem]);
    }

    public function addItem(Request $request, int $id)
    {
        return response()->json(HeroInventory::query()->create([
            'hero_id' => $id,
            'name' => $request->get('name'),
            'loading' => $request->get('loading'),
            'description' => $request->get('description')
        ]));
    }
}
