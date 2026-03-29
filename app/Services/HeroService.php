<?php

namespace App\Services;

use App\Exceptions\NotEnoughFatePointsException;
use App\Exceptions\NotEnoughFortunePointsException;
use App\Models\Hero;
use App\Repositories\HeroesRepository;

class HeroService
{
    private HeroesRepository $heroesRepository;
    public function __construct()
    {
        $this->heroesRepository = new HeroesRepository();
    }
    /**
     * @throws NotEnoughFortunePointsException
     */
    public function spendFortunePoint(Hero $hero): void
    {
        if ($hero->fortune_points === 0) {
            throw new NotEnoughFortunePointsException('Nie masz już punktów szczęścia, powodzenia!');
        }
        $this->heroesRepository->spendFortunePoint($hero);
    }
    /**
     * @throws NotEnoughFatePointsException()
     */
    public function spendFatePoint(Hero $hero): void
    {
        if ($hero->fate_points === 0) {
            throw new NotEnoughFatePointsException('Nie masz już punktów przeznaczenia, jaka będzie twoja kolejna postać?');
        }
        $this->heroesRepository->spendFatePoint($hero);
    }
}
