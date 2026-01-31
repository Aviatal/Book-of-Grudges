<?php

namespace App\Repositories;

use App\Models\Hero;
use Auth;
use Illuminate\Support\Collection;

class HeroesRepository
{
    public function getHero(int $userId): Hero
    {
        if ($userId !== Auth::user()->getAuthIdentifier()) {
            abort(404);
        }

        return Hero::with([
            'previousProfession', 'currentProfession', 'description',
            'characteristic', 'coldWeapons.traits', 'rangedWeapons.traits', 'armors.locations',
            'skills', 'talents', 'inventory'
        ])
            ->where('user_id', $userId)
            ->firstOrFail();
    }
    public function getHeroes(array $select = ['*'], bool $onlyActive = true) :Collection
    {
        return Hero::select($select)
            ->when($onlyActive, fn($query) => $query->onlyActiveUsers())
            ->get();
    }
    public function spendFortunePoint(Hero $hero): void
    {
        $hero->decrement('fortune_points');
    }
}
