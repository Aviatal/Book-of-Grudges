<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CharactersController extends Controller
{
    public function getCharacterSheet()
    {
        return view('Pages.character-sheet');
    }
}
