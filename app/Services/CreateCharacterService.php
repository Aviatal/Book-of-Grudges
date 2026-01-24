<?php

namespace App\Services;

use App\Models\Profession;
use App\Repositories\ProfessionsRepository;

readonly class CreateCharacterService
{
    public function __construct(private ProfessionsRepository $professionsRepository){}
    public function getFormatedRolledProfession(string $race, int $rolledValue): Profession
    {
        $profession = $this->professionsRepository->getRolledProfession($race, $rolledValue);
        $profession->setRelation('talents', $profession->talents->groupBy('group_id'));
        $profession->setRelation('skills', $profession->skills->groupBy('group_id'));
        $profession->setRelation('equipments', $profession->equipments->groupBy('group_id'));
        return $profession;
    }
}
