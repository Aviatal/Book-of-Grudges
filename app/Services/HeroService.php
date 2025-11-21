<?php

namespace App\Services;

use App\Exceptions\NotEnoughFortunePointsException;
use App\Models\Hero;
use App\Repositories\HeroRepository;

class HeroService
{
    private HeroRepository $heroRepository;
    public function __construct()
    {
        $this->heroRepository = new HeroRepository();
    }
    /**
     * @throws NotEnoughFortunePointsException
     */
    public function spendFortunePoint(Hero $hero): void
    {
        if ($hero->fortune_points === 0) {
            throw new NotEnoughFortunePointsException('Nie masz już punktów szczęścia, powodzenia!');
        }
        $this->heroRepository->spendFortunePoint($hero);
    }
}
