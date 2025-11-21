<?php

namespace App\Repositories;

use App\Models\FortunePointsSatisfaction;

class FortunePointsSatisfactionRepository
{
    public function insertSatisfactionToDatabase(int $heroId, bool $satisfied): void
    {
        FortunePointsSatisfaction::create([
            'hero_id' => $heroId,
            'satisfied' => $satisfied
        ]);
    }
}
