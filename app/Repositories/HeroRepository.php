<?php

namespace App\Repositories;

use App\Models\Hero;

class HeroRepository
{
    public function spendFortunePoint(Hero $hero): void
    {
        $hero->decrement('fortune_points');
    }
}
