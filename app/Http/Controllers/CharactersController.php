<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use Illuminate\Http\Request;

class CharactersController extends Controller
{
    public function getHero(int $id)
    {
        return Hero::with('previousProfession', 'currentProfession', 'description')->find($id);
    }
    public function getCharacterSheet(int $id)
    {
        $hero = $this->getHero($id);
        return view('Pages.character-sheet', compact('hero'));
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
}
