<?php

namespace App\Http\Controllers;

use App\Models\Spell;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SpellsController extends Controller
{
    public function spellsIndex()
    {
        return view('Pages.spells');
    }

    public function getSpells(Request $request): \Illuminate\Http\JsonResponse
    {
        $spells = Spell::query()
            ->when($request->query('search'), function (Builder $query) use ($request) {
                $query->whereAny(['name', 'type', 'specialization', 'ingredient'], 'like', '%' . $request->query('search') . '%');
            })
            ->orderBy('id')
            ->get();
        return response()->json($spells);
    }
}
