<?php

namespace App\Services;

use App\Models\Profession;
use App\Repositories\ProfessionsRepository;
use App\Repositories\RacesRepository;
use Illuminate\Database\Eloquent\Collection;

readonly class CreateCharacterService
{
    public function __construct(
        private ProfessionsRepository $professionsRepository,
        private RacesRepository $racesRepository
    ) {}

    public function getFormatedRolledProfession(string $race, int $rolledValue): Profession
    {
        $profession = $this->professionsRepository->getRolledProfession($race, $rolledValue);
        $profession->setRelation('talents', $profession->talents->groupBy('group_id'));
        $profession->setRelation('skills', $profession->skills->groupBy('group_id'));
        $profession->setRelation('equipments', $profession->equipments->groupBy('group_id'));
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
}
