<?php

namespace App\Repositories;

use App\Models\Characteristic;
use App\Models\Hero;
use App\Models\Race;
use http\Exception\InvalidArgumentException;
use Illuminate\Auth\Access\AuthorizationException;

class CreateHeroRepository
{
    public function createHero(array $personalDetails, int $professionId, string $race, array $secondaryCharacteristics, array $money): Hero
    {
        if(!\Auth::check()) {
            throw new AuthorizationException('NIe jesteś zalogowany');
        }
        return Hero::create([
            'user_id' => \Auth::user()->getAuthIdentifier(),
            'name' => $personalDetails['name'],
            'race' => $race,
            'current_profession_id' => $professionId,
            'current_experience' => 0,
            'all_experience' => 0,
            'gold_crowns' => $money['gc'],
            'silver_shillings' => $money['ss'],
            'brass_pennies' => $money['bp'],
            'current_wounds' => $secondaryCharacteristics['wounds'],
            'fortune_points' => $secondaryCharacteristics['fate'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function attachDescription(Hero $hero, array $personalDetails): void
    {
        $hero->description()->create([
            'age' => $personalDetails['age'],
            'gender' => $personalDetails['gender'],
            'eye_color' => $personalDetails['eyeColor'],
            'hair_color' => $personalDetails['hairColor'],
            'star_sign' => $personalDetails['starSign'],
            'weight' => $personalDetails['weight'],
            'height' => $personalDetails['height'],
            'siblings' => $personalDetails['siblings'],
            'place_of_birth' => $personalDetails['birthplace'],
            'distinguishing_signs' => $personalDetails['distinguishingMark'],
        ]);
    }

    public function attachCharacteristics(Hero $hero, array $primaryCharacteristics, array $secondaryCharacteristics, array $freeAdvance)
    {
        $characteristicsModels = Characteristic::all()->pluck('id', 'short_name');
        $attachData = [];
        foreach ($primaryCharacteristics as $characteristicName => $startValue) {
            $attachData[$characteristicsModels[$characteristicName]] = [
                'start_value' => $startValue,
                'advancement' => 0,
            ];
        }
        if (!isset($secondaryCharacteristics['wounds'], $secondaryCharacteristics['fate'])) {
            throw new InvalidArgumentException('Nie podano wartości dla Żywotności i/lub Punktów Przeznaczenia');
        }
        $secondaryStartValues = [
            'A' => 1,
            'Żyw' => $secondaryCharacteristics['wounds'],
            'Sz' => Race::SPEED_VALUES[$hero->race],
            'Mag' => 0,
            'PO' => 0,
            'PP' => $secondaryCharacteristics['fate'],
        ];
        foreach ($secondaryStartValues as $characteristicName => $secondaryStartValue) {
            $attachData[$characteristicsModels[$characteristicName]] = [
                'start_value' => $secondaryStartValue,
                'advancement' => 0,
            ];
        }
        $attachData[$characteristicsModels[$freeAdvance['key']]]['start_value'] += in_array($freeAdvance['value'], Characteristic::PRIMARY_CHARACTERISTICS, true) ? 5 : 1;
        $hero->characteristic()->attach($attachData);
    }

    public function attachSkills(Hero $hero, array $skills): void
    {
        $attachData = [];
        foreach ($skills as $skill) {
            $attachData[$skill['id']] = [
                'additional_skill_name' => $skill['specialization'],
                'hurdled' => true,
                'first_level' => $skill['advances'] <= 2,
                'second_level' => $skill['advances'] <= 3,
            ];
        }
        $hero->skills()->attach($attachData);
    }

    public function attachTalents(Hero $hero, array $talents): void
    {
        $attachData = [];
        foreach ($talents as $talent) {
            $attachData[$talent['id']] = [
                'additional_talent_name' => $talent['specialization'],
            ];
        }
        $hero->talents()->attach($attachData);
    }

    public function addEquipments(Hero $hero, array $equipments): void
    {
        foreach ($equipments as $equipment) {
            switch ($equipment['item']['tradeable_type']) {
                case 'App\Models\Weapon':
                    $hero->weapons()->syncWithoutDetaching([
                        $equipment['item']['tradeable_id'] => ['additional_weapon_name' => $equipment['item_name'] ?? $equipment['item']['tradeable']['name']]
                    ]);
                    break;
                case 'App\Models\Armor':
                    $hero->armors()->syncWithoutDetaching($equipment['item']['tradeable_id']);
                    break;
                case 'App\Models\Ammunition':
                case 'App\Models\CommonItems':
                    $hero->inventory()->create([
                        'name' => $equipment['item_name'] ?? $equipment['item']['tradeable']['name'],
                        'loading' => $equipment['item']['tradeable']['loading'],
                        'description' => ''
                    ]);

            }
        }
    }
}
