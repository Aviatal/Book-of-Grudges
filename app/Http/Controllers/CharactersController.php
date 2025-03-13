<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use App\Models\HeroCharacteristic;
use App\Models\HeroInventory;
use App\Models\Skill;
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
