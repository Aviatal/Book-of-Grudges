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
        return Hero::with(
            'previousProfession', 'currentProfession', 'description',
            'characteristic', 'coldWeapons.traits', 'rangedWeapons.traits', 'armors.locations',
            'skills', 'talents', 'inventory'
        )
            ->where('user_id', $userId)
            ->first();
    }
    public function getCharacterSheet(Request $request, int $id)
    {
        if ($id !== Auth::user()->getAuthIdentifier()) {
            abort(404);
        }
        $hero = $this->getHero($id);
        if ($request->get('wantsJson')) {
            return response()->json($hero);
        }
        $skills = Skill::whereNotIn('id', $hero && $hero->skills ? $hero->skills->pluck('id') : [])->get();
        return view('Pages.character-sheet', compact('hero', 'skills'));
    }
    public function updateHeroData(Request $request, Hero $hero)
    {
        $hero->update($request->all());
        return $this->getHero($hero->id);
    }
    public function updateHeroDescription(Request $request, Hero $hero)
    {
        $hero->description()->update($request->except(['id', 'hero_id', 'updated_at', 'created_at']));
        return $this->getHero($hero->id);
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
        return HeroInventory::query()->create([
            'hero_id' => $id,
            'name' => $request->get('name'),
            'loading' => $request->get('loading'),
            'description' => $request->get('description')
        ]);
    }
}
