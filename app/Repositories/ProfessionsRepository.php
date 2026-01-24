<?php

namespace App\Repositories;

use App\Models\Profession;

class ProfessionsRepository
{
    public function getRolledProfession(string $race, int $rolledValue): Profession
    {
        return Profession::with(['skills.skill', 'talents.talent', 'startingProfessionRolls', 'characteristics.characteristic', 'equipments.item.tradeable'])
            ->rolledProfession($race, $rolledValue)
            ->firstOrFail();
    }
}

