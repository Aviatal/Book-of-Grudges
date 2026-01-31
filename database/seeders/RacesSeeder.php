<?php

namespace Database\Seeders;

use App\Models\Race;
use App\Models\Skill;
use App\Models\Talent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RacesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = Skill::all()->pluck('id', 'name')->toArray();
        $talents = Talent::all()->pluck('id', 'name')->toArray();
        $races = [
            [
                'name' => 'Krasnolud',
                'key' => 'dwarf',
                'icon' => 'dwarf.png',
                'description' => 'Krzepcy i odważni',
                'bonuses' => ['Odporność na magię', 'Krzepki', 'Widzenie w ciemności'],
                'random_talents' => 0,
                'base_stats' => [1 => 30, 2 => 20, 3 => 20, 4 => 30, 5 => 10, 6 => 20, 7 => 20, 8 => 10],
                'skills' => [
                    [
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'Górnictwo'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'Kamieniarstwo'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'Kowalstwo']
                    ],
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Krasnoludy']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'Khazalid']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'Staroświatowy']]
                ],
                'talents' => [
                    [['talent_id' => $talents['Krasnoludzki fach'], 'additional_name' => '']],
                    [['talent_id' => $talents['Krzepki'], 'additional_name' => '']],
                    [['talent_id' => $talents['Odporność na magię'], 'additional_name' => '']],
                    [['talent_id' => $talents['Odwaga'], 'additional_name' => '']],
                    [['talent_id' => $talents['Widzenie w ciemności'], 'additional_name' => '']],
                    [['talent_id' => $talents['Zapiekła nienawiść'], 'additional_name' => '']],
                ]
            ],
            [
                'name' => 'Człowiek',
                'key' => 'human',
                'icon' => 'human.png',
                'description' => 'Wszechstronni i ambitni',
                'bonuses' => ['Wszechstronność', 'Ambicja'],
                'random_talents' => 2,
                'base_stats' => [1 => 20, 2 => 20, 3 => 20, 4 => 20, 5 => 20, 6 => 20, 7 => 20, 8 => 20],
                'skills' => [
                    [['skill_id' => $skills['Plotkowanie'], 'additional_name' => '']],
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Imperium']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'Staroświatowy']]
                ],
                'talents' => []
            ],
            [
                'name' => 'Elf',
                'key' => 'elf',
                'icon' => 'elf.png',
                'description' => 'Dostojni i długowieczni',
                'bonuses' => ['Bystry wzrok', 'Nocne widzenie'],
                'random_talents' => 0,
                'base_stats' => [1 => 20, 2 => 30, 3 => 20, 4 => 20, 5 => 30, 6 => 20, 7 => 20, 8 => 20],
                'skills' => [
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Elfy']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'Eltharin']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'Staroświatowy']]
                ],
                'talents' => [
                    [
                        ['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'Długi łuk'],
                        ['talent_id' => $talents['Zmysł magii'], 'additional_name' => '']
                    ],
                    [['talent_id' => $talents['Bystry wzrok'], 'additional_name' => '']],
                    [
                        ['talent_id' => $talents['Opanowanie'], 'additional_name' => ''],
                        ['talent_id' => $talents['Błyskotliwość'], 'additional_name' => '']
                    ],
                    [['talent_id' => $talents['Zapiekła nienawiść'], 'additional_name' => '']],
                ]
            ],
            [
                'name' => 'Niziołek',
                'key' => 'halfling',
                'icon' => 'halfling.png',
                'description' => 'Spokojni i łakomi',
                'random_talents' => 1,
                'bonuses' => ['Odporność na Chaos', 'Nocne widzenie'],
                'base_stats' => [1 => 10, 2 => 30, 3 => 10, 4 => 10, 5 => 30, 6 => 20, 7 => 20, 8 => 30],
                'skills' => [
                    [
                        ['skill_id' => $skills['Nauka'], 'additional_name' => 'Genealogia'],
                        ['skill_id' => $skills['Nauka'], 'additional_name' => 'Heraldyka']
                    ],
                    [['skill_id' => $skills['Plotkowanie'], 'additional_name' => '']],
                    [
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'Gotowanie'],
                        ['skill_id' => $skills['Rzemiosło'], 'additional_name' => 'Uprawa ziemi']
                    ],
                    [['skill_id' => $skills['Wiedza'], 'additional_name' => 'Niziołki']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'Niziołków']],
                    [['skill_id' => $skills['Znajomość języka'], 'additional_name' => 'Staroświatowy']],
                ],
                'talents' => [
                    [['talent_id' => $talents['Broń specjalna'], 'additional_name' => 'Proca']],
                    [['talent_id' => $talents['Odporność na Chaos'], 'additional_name' => '']],
                    [['talent_id' => $talents['Widzenie w ciemności'], 'additional_name' => '']],
                ]
            ],
        ];
        \DB::table('races')->truncate();
        \DB::table('race_bonuses')->truncate();
        \DB::table('race_base_stats')->truncate();
        \DB::table('race_skills')->truncate();
        \DB::table('race_talents')->truncate();
        foreach ($races as $race) {
            $raceModel = Race::create([
                'name' => $race['name'],
                'key' => $race['key'],
                'icon' => $race['icon'],
                'description' => $race['description'],
                'random_talents' => $race['random_talents']
            ]);
            foreach ($race['bonuses'] as $bonus) {
                \DB::table('race_bonuses')->insert(['bonus_name' => $bonus, 'race_id' => $raceModel->id]);
            }
            foreach ($race['base_stats'] as $characteristicId => $bonus) {
                \DB::table('race_base_stats')->insert(['characteristic_id' => $characteristicId, 'race_id' => $raceModel->id, 'value' => $bonus]);
            }
            foreach ($race['skills'] as $groupId => $skillGroup) {
                foreach ($skillGroup as $skill) {
                    \DB::table('race_skills')->insert([
                        'skill_id' => $skill['skill_id'],
                        'group_id' => $groupId,
                        'race_id' => $raceModel->id,
                        'additional_name' => $skill['additional_name']
                    ]);
                }
            }
            foreach ($race['talents'] as $groupId => $talentGroup) {
                foreach ($talentGroup as $talent) {
                    \DB::table('race_talents')->insert([
                        'talent_id' => $talent['talent_id'],
                        'group_id' => $groupId,
                        'race_id' => $raceModel->id,
                        'additional_name' => $talent['additional_name']
                    ]);
                }
            }
        }
    }
}
