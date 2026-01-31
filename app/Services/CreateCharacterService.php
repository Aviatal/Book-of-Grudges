<?php

namespace App\Services;

use App\Models\Profession;
use App\Repositories\CreateHeroRepository;
use App\Repositories\HeroesRepository;
use App\Repositories\ProfessionsRepository;
use App\Repositories\RacesRepository;
use Auth;
use Illuminate\Database\Eloquent\Collection;

readonly class CreateCharacterService
{
    public function __construct(
        private ProfessionsRepository $professionsRepository,
        private RacesRepository $racesRepository,
        private CreateHeroRepository $createHeroRepository,
        private HeroesRepository $heroesRepository
    ) {}

    public function getFormatedRolledProfession(string $race, ?int $rolledValue = null): Profession|Collection
    {
        $profession = $this->professionsRepository->getRolledProfession($race, $rolledValue);
        if (!$rolledValue) {
            foreach ($profession as $item) {
                $item->setRelation('talents', $item->talents->groupBy('group_id'));
                $item->setRelation('skills', $item->skills->groupBy('group_id'));
                $item->setRelation('equipments', $item->equipments->groupBy('group_id'));
            }
        } else {
            $profession->setRelation('talents', $profession->talents->groupBy('group_id'));
            $profession->setRelation('skills', $profession->skills->groupBy('group_id'));
            $profession->setRelation('equipments', $profession->equipments->groupBy('group_id'));
        }

        return $profession;
    }
    public function getRaces(): Collection
    {
        $races = $this->racesRepository->getRaces();
        foreach ($races as &$race) {
            $race->setRelation('talents', $race->talents->groupBy('group_id'));
            $race->setRelation('skills', $race->skills->groupBy('group_id'));
        }
        return $races;
    }

    /**
     * @throws \Throwable
     */
    public function createHero(array $data)
    {
        \DB::transaction(function () use ($data, &$hero) {
            $hero = $this->createHeroRepository->createHero($data['personalDetails'], $data['profession'], $data['race'], $data['secondaryCharacteristics'], $data['money']);
            $this->createHeroRepository->attachDescription($hero, $data['personalDetails']);
            $this->createHeroRepository->attachCharacteristics($hero, $data['characteristics'], $data['secondaryCharacteristics'], $data['freeAdvance']);
            $this->createHeroRepository->attachSkills($hero, $data['skills']);
            $this->createHeroRepository->attachTalents($hero, $data['talents']);
            $this->createHeroRepository->addEquipments($hero, $data['equipments']);
        });
        return $this->heroesRepository->getHero(Auth::user()->getAuthIdentifier());
    }
}
