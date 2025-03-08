<?php

namespace Database\Seeders;

use App\Models\Armor;
use App\Models\ArmorLocation;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArmorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $armors = [
            ['name' => 'Hełm', 'category' => 'SKÓRZANA','price' => 3 * 240, 'loading' => 10, 'armor_points' => 1, 'availability' => 'Przeciętna'],
            ['name' => 'Kaftan', 'category' => 'SKÓRZANA','price' => 6 * 240, 'loading' => 40, 'armor_points' => 1, 'availability' => 'Przeciętna'],
            ['name' => 'Kurta', 'category' => 'SKÓRZANA','price' => 12 * 240, 'loading' => 50, 'armor_points' => 1, 'availability' => 'Przeciętna'],
            ['name' => 'Nogawice', 'category' => 'SKÓRZANA','price' => 10 * 240, 'loading' => 20, 'armor_points' => 1, 'availability' => 'Przeciętna'],
            ['name' => 'Skórznia', 'category' => 'SKÓRZANA','price' => 25 * 240, 'loading' => 80, 'armor_points' => 1, 'availability' => 'Mała'],

            ['name' => 'Czepiec', 'category' => 'KOLCZA','price' => 20 * 240, 'loading' => 30, 'armor_points' => 2, 'availability' => 'Mała'],
            ['name' => 'Kaftan', 'category' => 'KOLCZA','price' => 60 * 240, 'loading' => 60, 'armor_points' => 2, 'availability' => 'Mała'],
            ['name' => 'Koszulka', 'category' => 'KOLCZA','price' => 95 * 240, 'loading' => 80, 'armor_points' => 2, 'availability' => 'Mała'],
            ['name' => 'Kolczuga', 'category' => 'KOLCZA','price' => 75 * 240, 'loading' => 80, 'armor_points' => 2, 'availability' => 'Mała'],
            ['name' => 'Kolczuga z rękawami', 'category' => 'KOLCZA','price' => 130 * 240, 'loading' => 100, 'armor_points' => 2, 'availability' => 'Mała'],
            ['name' => 'Nogawice', 'category' => 'KOLCZA','price' => 20 * 240, 'loading' => 40, 'armor_points' => 2, 'availability' => 'Sporadyczna'],
            ['name' => 'Zbroja kolcza', 'category' => 'KOLCZA','price' => 170 * 240, 'loading' => 210, 'armor_points' => 3, 'availability' => 'Sporadyczna'],

            ['name' => 'Hełm', 'category' => 'PŁYTOWA','price' => 30 * 240, 'loading' => 40, 'armor_points' => 2, 'availability' => 'Sporadyczna'],
            ['name' => 'Naramienniki', 'category' => 'PŁYTOWA','price' => 70 * 240, 'loading' => 75, 'armor_points' => 2, 'availability' => 'Sporadyczna'],
            ['name' => 'Nogawice', 'category' => 'PŁYTOWA','price' => 60 * 240, 'loading' => 30, 'armor_points' => 2, 'availability' => 'Sporadyczna'],
            ['name' => 'Napierśnik', 'category' => 'PŁYTOWA','price' => 70 * 240, 'loading' => 75, 'armor_points' => 2, 'availability' => 'Sporadyczna'],
            ['name' => 'Zbroja płytowa', 'category' => 'PŁYTOWA','price' => 400 * 240, 'loading' => 395, 'armor_points' => 5, 'availability' => 'Rzadka']
        ];
        $locations = [
            ['name' => 'Głowa'],
            ['name' => 'Korpus'],
            ['name' => 'Ręce'],
            ['name' => 'Nogi'],
        ];
        Armor::insert($armors);
        ArmorLocation::insert($locations);
        $armorLocation = [
            [
                'armor_location_id' => 1,
                'armor_id' => 1,
            ],
            [
                'armor_location_id' => 2,
                'armor_id' => 2,
            ],
            [
                'armor_location_id' => 2,
                'armor_id' => 3,
            ],
            [
                'armor_location_id' => 3,
                'armor_id' => 3,
            ],
            [
                'armor_location_id' => 4,
                'armor_id' => 4,
            ],
            [
                'armor_location_id' => 1,
                'armor_id' => 5,
            ],
            [
                'armor_location_id' => 2,
                'armor_id' => 5,
            ],
            [
                'armor_location_id' => 3,
                'armor_id' => 5,
            ],
            [
                'armor_location_id' => 4,
                'armor_id' => 5,
            ],
            [
                'armor_location_id' => 1,
                'armor_id' => 6,
            ],
            [
                'armor_location_id' => 2,
                'armor_id' => 7,
            ],
            [
                'armor_location_id' => 2,
                'armor_id' => 8,
            ],
            [
                'armor_location_id' => 3,
                'armor_id' => 8,
            ],
            [
                'armor_location_id' => 2,
                'armor_id' => 9,
            ],
            [
                'armor_location_id' => 4,
                'armor_id' => 9,
            ],
            [
                'armor_location_id' => 2,
                'armor_id' => 10,
            ],
            [
                'armor_location_id' => 3,
                'armor_id' => 10,
            ],
            [
                'armor_location_id' => 4,
                'armor_id' => 10,
            ],
            [
                'armor_location_id' => 4,
                'armor_id' => 11,
            ],
            [
                'armor_location_id' => 1,
                'armor_id' => 12,
            ],
            [
                'armor_location_id' => 2,
                'armor_id' => 12,
            ],
            [
                'armor_location_id' => 3,
                'armor_id' => 12,
            ],
            [
                'armor_location_id' => 4,
                'armor_id' => 12,
            ],
            [
                'armor_location_id' => 1,
                'armor_id' => 13,
            ],
            [
                'armor_location_id' => 3,
                'armor_id' => 14,
            ],
            [
                'armor_location_id' => 4,
                'armor_id' => 15,
            ],
            [
                'armor_location_id' => 2,
                'armor_id' => 16,
            ],
            [
                'armor_location_id' => 1,
                'armor_id' => 17,
            ],
            [
                'armor_location_id' => 2,
                'armor_id' => 17,
            ],
            [
                'armor_location_id' => 3,
                'armor_id' => 17,
            ],
            [
                'armor_location_id' => 4,
                'armor_id' => 17,
            ],
        ];
        DB::table('armor_armor_location')->insert($armorLocation);
    }
}
