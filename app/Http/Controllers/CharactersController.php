<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use App\Models\HeroCharacteristic;
use App\Models\Skill;
use Illuminate\Http\Request;

class CharactersController extends Controller
{
    public function getHero(int $id)
    {
        return Hero::with(
            'previousProfession', 'currentProfession', 'description',
            'characteristic', 'coldWeapons.traits', 'rangedWeapons.traits', 'armors.locations',
            'skills', 'talents'
        )
            ->find($id);
    }
    public function getCharacterSheet(int $id)
    {
        $hero = $this->getHero($id);
        $skills = Skill::whereNotIn('id', $hero->skills->pluck('id'))->get();
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
                $upsertData[] = $characteristic;
            }
        }
        HeroCharacteristic::upsert($upsertData, ['id'], ['start_value', 'advancement', 'current_value']);

        return $this->getHero($hero->id);
    }
}
