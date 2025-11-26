<?php

namespace App\Repositories;

use App\Models\Hero;
use Illuminate\Support\Collection;

class HeroesRepository
{
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
