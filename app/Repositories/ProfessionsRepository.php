<?php

namespace App\Repositories;

use App\Models\Profession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProfessionsRepository
{
    public function getRolledProfession(string $race, ?int $rolledValue): Profession|Collection
    {
        $professions = Profession::with(['skills.skill', 'talents.talent', 'startingProfessionRolls', 'characteristics.characteristic', 'equipments.item.tradeable'])
            ->when($rolledValue, function (Builder $query) use ($race, $rolledValue) {
                $query->rolledProfession($race, $rolledValue);
            })
            ->when(!$rolledValue, function (Builder $query) use ($race,) {
                $query->whereHas('startingProfessionRolls', function ($query) use ($race) {
                    $query->where('race', $race);
                })
                    ->orderBy('name');
            });
        if ($rolledValue) {
            return $professions->firstOrFail();
        }
        return $professions->get();
    }
}

