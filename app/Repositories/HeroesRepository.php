<?php

namespace App\Repositories;

use App\Models\Characteristic;
use App\Models\Hero;
use Auth;
use DB;
use Illuminate\Support\Collection;

class HeroesRepository
{
    public function getHero(int $userId): ?Hero
    {
        if ($userId !== Auth::user()->getAuthIdentifier()) {
            abort(404);
        }

        return Hero::with([
            'previousProfession', 'currentProfession', 'description',
            'characteristic', 'coldWeapons.traits', 'rangedWeapons.traits', 'armors.locations',
            'skills', 'talents', 'inventory', 'spells'
        ])
            ->where('user_id', $userId)
            ->first();
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
    public function spendFatePoint(Hero $hero): void
    {
        $hero->characteristic()->updateExistingPivot(Characteristic::FATE_POINTS_CHARACTERISTIC_ID, [
            'advancement' => DB::raw('advancement - 1')
        ]);
    }
}
