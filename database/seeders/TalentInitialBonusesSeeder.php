<?php

namespace Database\Seeders;

use App\Models\Characteristic;
use App\Models\Talent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TalentInitialBonusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $talents = Talent::get()->pluck('id', 'name');
        $characteristics = Characteristic::get()->pluck('id', 'short_name');
        $talentInitialBonuses = [
            [
                'talent_id' => $talents['Bardzo silny'],
                'characteristic_id' => $characteristics['K'],
                'bonus' => 5,
            ],
            [
                'talent_id' => $talents['Błyskotliwość'],
                'characteristic_id' => $characteristics['Int'],
                'bonus' => 5,
            ],
            [
                'talent_id' => $talents['Charyzmatyczny'],
                'characteristic_id' => $characteristics['Ogd'],
                'bonus' => 5,
            ],
            [
                'talent_id' => $talents['Niezwykle odporny'],
                'characteristic_id' => $characteristics['Odp'],
                'bonus' => 5,
            ],
            [
                'talent_id' => $talents['Twardziel'],
                'characteristic_id' => $characteristics['Żyw'],
                'bonus' => 1,
            ],
            [
                'talent_id' => $talents['Urodzony wojownik'],
                'characteristic_id' => $characteristics['WW'],
                'bonus' => 5,
            ],
            [
                'talent_id' => $talents['Urodzony wojownik'],
                'characteristic_id' => $characteristics['WW'],
                'bonus' => 5,
            ],
        ];
        \DB::table('talent_initial_bonuses')->insert($talentInitialBonuses);
    }
}
