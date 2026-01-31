<?php

namespace App\Repositories;

use App\Models\Race;
use Illuminate\Database\Eloquent\Collection;

class RacesRepository
{
    public function getRaces(): Collection
    {
        return Race::with(['bonuses', 'baseStats.characteristic', 'skills.skill', 'talents.talent'])
            ->get();
    }
}

