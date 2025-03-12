<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use App\Models\HeroCharacteristic;
use App\Models\HeroInventory;
use App\Models\Skill;
use Auth;
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
    public function updateHeroDescription(Request $request, Hero $hero)
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
    public function updateHeroCharacteristic(Request $request, Hero $hero)
    {
        $hero->loadMissing('characteristic');
        $upsertData = [];

        foreach ($request->all() as $key => $characteristic) {
            if (
                $characteristic['start_value'] != $hero->getRelation('characteristic')[$key]->start_value ||
                $characteristic['advancement'] != $hero->getRelation('characteristic')[$key]->advancement ||
                $characteristic['current_value'] != $hero->getRelation('characteristic')[$key]->current_value
            ) {
                unset($characteristic['created_at'], $characteristic['updated_at']);
                if ($characteristic['short_name'] == 'Zyw' && $hero->getRelation('characteristic')[$key]->current_value == $hero->current_wounds) {
                    $hero->update(['current_wounds' => $characteristic['current_value']]);
                }
                $upsertData[] = $characteristic;
            }
        }
        HeroCharacteristic::upsert($upsertData, ['id'], ['start_value', 'advancement', 'current_value']);

        return $this->getHero($hero->id);
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
