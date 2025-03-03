<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use Illuminate\Http\Request;

class CharactersController extends Controller
{
    public function getHero(int $id)
    {
        return Hero::with('previousProfession', 'currentProfession')->find($id);
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
}
