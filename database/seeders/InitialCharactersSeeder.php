<?php

namespace Database\Seeders;

use App\Models\Hero;
use App\Models\HeroCharacteristic;
use App\Models\HeroDescription;
use App\Models\HeroInventory;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialCharactersSeeder extends Seeder
{
    public function run(): void
    {
        Hero::create([
            'user_id' => 1,
            'name' => "Safis'traks (Nadchodzaca zmierzch)",
            'race' => "Elf",
            'current_profession_id' => 57,
            'previous_profession_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'current_experience' => 100,
            'all_experience' => 550,
            'current_wounds' => 5,
            'gold_crowns' => 15,
            'silver_shillings' => 1,
            'brass_pennies' => 14,
            'fortune_points' => 1,
        ]);
        HeroDescription::create([
            'hero_id' => 1,
            'age' => 147,
            'gender' => 'M',
            'eye_color' => 'Zielony',
            'hair_color' => 'Miedziane (krótkie)',
            'star_sign' => 'Lancet',
            'weight' => 60,
            'height' => 189,
            'siblings' => 'Siostra',
            'place_of_birth' => 'Ulthuan',
            'distinguishing_signs' => 'Obcięte końcówki uszu'
        ]);
        HeroCharacteristic::insert([
            [
                'hero_id' => 1,
                'name' => 'Walka wręcz',
                'short_name' => 'WW',
                'start_value' => 35,
                'advancement' => null,
                'current_value' => 35,
            ],
            [
                'hero_id' => 1,
                'name' => 'Umiejętności strzeleckie',
                'short_name' => 'US',
                'start_value' => 41,
                'advancement' => null,
                'current_value' => 41,
            ],
            [
                'hero_id' => 1,
                'name' => 'Krzepa',
                'short_name' => 'K',
                'start_value' => 34,
                'advancement' => null,
                'current_value' => 34,
            ],
            [
                'hero_id' => 1,
                'name' => 'Odporność',
                'short_name' => 'Odp',
                'start_value' => 35,
                'advancement' => null,
                'current_value' => 35,
            ],
            [
                'hero_id' => 1,
                'name' => 'Zręczność',
                'short_name' => 'Zr',
                'start_value' => 45,
                'advancement' => 10,
                'current_value' => 50,
            ],
            [
                'hero_id' => 1,
                'name' => 'Inteligencja',
                'short_name' => 'Int',
                'start_value' => 42,
                'advancement' => 10,
                'current_value' => 52,
            ],
            [
                'hero_id' => 1,
                'name' => 'Siła woli',
                'short_name' => 'SW',
                'start_value' => 42,
                'advancement' => 5,
                'current_value' => 42,
            ],
            [
                'hero_id' => 1,
                'name' => 'Ogłada',
                'short_name' => 'Ogd',
                'start_value' => 34,
                'advancement' => 10,
                'current_value' => 39,
            ],
            [
                'hero_id' => 1,
                'name' => 'Ataki',
                'short_name' => 'A',
                'start_value' => 1,
                'advancement' => null,
                'current_value' => 1,
            ],
            [
                'hero_id' => 1,
                'name' => 'Żywotność',
                'short_name' => 'Zyw',
                'start_value' => 10,
                'advancement' => 2,
                'current_value' => 10,
            ],
            [
                'hero_id' => 1,
                'name' => 'Siła',
                'short_name' => 'S',
                'start_value' => 3,
                'advancement' => null,
                'current_value' => 3,
            ],
            [
                'hero_id' => 1,
                'name' => 'Wytrzymałość',
                'short_name' => 'Wt',
                'start_value' => 3,
                'advancement' => null,
                'current_value' => 3,
            ],
            [
                'hero_id' => 1,
                'name' => 'Szybkość',
                'short_name' => 'Sz',
                'start_value' => 5,
                'advancement' => null,
                'current_value' => 5,
            ],
            [
                'hero_id' => 1,
                'name' => 'Magia',
                'short_name' => 'Mag',
                'start_value' => 0,
                'advancement' => null,
                'current_value' => null,
            ],
            [
                'hero_id' => 1,
                'name' => 'Punkty obłędu',
                'short_name' => 'PO',
                'start_value' => 0,
                'advancement' => null,
                'current_value' => null,
            ],
            [
                'hero_id' => 1,
                'name' => 'Punkty przeznaczenia',
                'short_name' => 'PP',
                'start_value' => 1,
                'advancement' => null,
                'current_value' => 1,
            ],
        ]);
        DB::table('hero_weapons')->insert([
            [
                'hero_id' => 1,
                'weapon_id' => 17,
                'additional_weapon_name' => null
            ],
            [
                'hero_id' => 1,
                'weapon_id' => 4,
                'additional_weapon_name' => 'Kordelas'
            ],
            [
                'hero_id' => 1,
                'weapon_id' => 30,
                'additional_weapon_name' => null
            ],
        ]);
        DB::table('hero_armors')->insert([
            [
                'hero_id' => 1,
                'armor_id' => 8
            ],
            [
                'hero_id' => 1,
                'armor_id' => 2
            ],
        ]);
        DB::table('hero_skill')->insert([
            [
                'skill_id' => 7,
                'hero_id' => 1,
                'additional_skill_name' => null,
                'first_level' => 1,
                'second_level' => 0,
            ],
            [
                'skill_id' => 20,
                'hero_id' => 1,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 27,
                'hero_id' => 1,
                'additional_skill_name' => null,
                'first_level' => 1,
                'second_level' => 0,
            ],
            [
                'skill_id' => 36,
                'hero_id' => 1,
                'additional_skill_name' => 'Elfy',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 44,
                'hero_id' => 1,
                'additional_skill_name' => 'Eltharin',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 44,
                'hero_id' => 1,
                'additional_skill_name' => 'Staroświatowy',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 44,
                'hero_id' => 1,
                'additional_skill_name' => 'Klasyczny',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 3,
                'hero_id' => 1,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 12,
                'hero_id' => 1,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 47,
                'hero_id' => 1,
                'additional_skill_name' => 'Magia',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 47,
                'hero_id' => 1,
                'additional_skill_name' => 'Alchemia',
                'first_level' => 0,
                'second_level' => 0,
            ],
        ]);
        DB::table('hero_talent')->insert([
            [
                'talent_id' => 65,
                'hero_id' => 1,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 32,
                'hero_id' => 1,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 44,
                'hero_id' => 1,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 58,
                'hero_id' => 1,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 28,
                'hero_id' => 1,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 70,
                'hero_id' => 1,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 71,
                'hero_id' => 1,
                'additional_talent_name' => null
            ],
        ]);
        HeroInventory::insert([
            [
                'hero_id' => 1,
                'name' => 'Nauk. Ksiąga (M)',
                'description' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Nauk. Ksiega (A)',
                'description' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Przybory do pisania',
                'description' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Modździerz',
                'description' => 'do rozdrobnienia ziól',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Menzurka',
                'description' => 'Woda kultystów',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Olej',
                'description' => '1/2 bukłak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Butelka gorzały',
                'description' => '1/4 butelki',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Ulotka (bagna)',
                'description' => '"Rozkosze przeklętych bagien"',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Kwiatek (bagna)',
                'description' => '(mit o zauroczeniu)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Bukłak',
                'description' => 'płyn paraliżujący',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Bukłak',
                'description' => 'płyn paraliżujący',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Najszyjnik',
                'description' => 'Złoty + zęby rekina',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Latarnia',
                'description' => 'Działa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Lina',
                'description' => '1m',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Zapałki',
                'description' => '2 paczki',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);


        Hero::create([
            'user_id' => 2,
            'name' => "Ludo (Szczurzy pasterz)",
            'race' => "Niziołek",
            'current_profession_id' => 56,
            'previous_profession_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'current_experience' => 70,
            'all_experience' => 525,
            'current_wounds' => 11,
            'gold_crowns' => 6,
            'silver_shillings' => 0,
            'brass_pennies' => 0,
            'fortune_points' => 2,
        ]);
        HeroDescription::create([
            'hero_id' => 2,
            'age' => 27,
            'gender' => 'M',
            'eye_color' => 'Brązowy',
            'hair_color' => 'Czarny',
            'star_sign' => 'Głupiec',
            'weight' => 40,
            'height' => 121,
            'siblings' => '3',
            'place_of_birth' => 'Kraina zgromadzenia',
            'distinguishing_signs' => 'Niewielka łysina'
        ]);
        HeroCharacteristic::insert([
            [
                'hero_id' => 2,
                'name' => 'Walka wręcz',
                'short_name' => 'WW',
                'start_value' => 21,
                'advancement' => 5,
                'current_value' => 21,
            ],
            [
                'hero_id' => 2,
                'name' => 'Umiejętności strzeleckie',
                'short_name' => 'US',
                'start_value' => 44,
                'advancement' => 5,
                'current_value' => 44,
            ],
            [
                'hero_id' => 2,
                'name' => 'Krzepa',
                'short_name' => 'K',
                'start_value' => 21,
                'advancement' => null,
                'current_value' => 21,
            ],
            [
                'hero_id' => 2,
                'name' => 'Odporność',
                'short_name' => 'Odp',
                'start_value' => 20,
                'advancement' => null,
                'current_value' => 20,
            ],
            [
                'hero_id' => 2,
                'name' => 'Zręczność',
                'short_name' => 'Zr',
                'start_value' => 43,
                'advancement' => 15,
                'current_value' => 43,
            ],
            [
                'hero_id' => 2,
                'name' => 'Inteligencja',
                'short_name' => 'Int',
                'start_value' => 29,
                'advancement' => 5,
                'current_value' => 34,
            ],
            [
                'hero_id' => 2,
                'name' => 'Siła woli',
                'short_name' => 'SW',
                'start_value' => 33,
                'advancement' => null,
                'current_value' => 33,
            ],
            [
                'hero_id' => 2,
                'name' => 'Ogłada',
                'short_name' => 'Ogd',
                'start_value' => 38,
                'advancement' => 10,
                'current_value' => 43,
            ],
            [
                'hero_id' => 2,
                'name' => 'Ataki',
                'short_name' => 'A',
                'start_value' => 1,
                'advancement' => null,
                'current_value' => 1,
            ],
            [
                'hero_id' => 2,
                'name' => 'Żywotność',
                'short_name' => 'Zyw',
                'start_value' => 11,
                'advancement' => 2,
                'current_value' => 11,
            ],
            [
                'hero_id' => 2,
                'name' => 'Siła',
                'short_name' => 'S',
                'start_value' => 2,
                'advancement' => null,
                'current_value' => 2,
            ],
            [
                'hero_id' => 2,
                'name' => 'Wytrzymałość',
                'short_name' => 'Wt',
                'start_value' => 2,
                'advancement' => null,
                'current_value' => 2,
            ],
            [
                'hero_id' => 2,
                'name' => 'Szybkość',
                'short_name' => 'Sz',
                'start_value' => 4,
                'advancement' => null,
                'current_value' => 4,
            ],
            [
                'hero_id' => 2,
                'name' => 'Magia',
                'short_name' => 'Mag',
                'start_value' => 0,
                'advancement' => null,
                'current_value' => null,
            ],
            [
                'hero_id' => 2,
                'name' => 'Punkty obłędu',
                'short_name' => 'PO',
                'start_value' => 0,
                'advancement' => null,
                'current_value' => null,
            ],
            [
                'hero_id' => 2,
                'name' => 'Punkty przeznaczenia',
                'short_name' => 'PP',
                'start_value' => 2,
                'advancement' => null,
                'current_value' => 2,
            ],
        ]);
        DB::table('hero_weapons')->insert([
            [
                'hero_id' => 2,
                'weapon_id' => 17,
                'additional_weapon_name' => null
            ],
            [
                'hero_id' => 2,
                'weapon_id' => 38,
                'additional_weapon_name' => null
            ],
            [
                'hero_id' => 2,
                'weapon_id' => 45,
                'additional_weapon_name' => null
            ],
            [
                'hero_id' => 2,
                'weapon_id' => 30,
                'additional_weapon_name' => null
            ],
        ]);
        DB::table('hero_armors')->insert([
            [
                'hero_id' => 2,
                'armor_id' => 2
            ],
        ]);
        DB::table('hero_skill')->insert([
            [
                'skill_id' => 2,
                'hero_id' => 2,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 17,
                'hero_id' => 2,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 21,
                'hero_id' => 2,
                'additional_skill_name' => null,
                'first_level' =>0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 25,
                'hero_id' => 2,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 27,
                'hero_id' => 2,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 33,
                'hero_id' => 2,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 44,
                'hero_id' => 2,
                'additional_skill_name' => 'Staroświatowy',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 39,
                'hero_id' => 2,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 36,
                'hero_id' => 2,
                'additional_skill_name' => 'Genealogia',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 22,
                'hero_id' => 2,
                'additional_skill_name' => 'Gotowanie',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 36,
                'hero_id' => 2,
                'additional_skill_name' => 'Niziołki',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 44,
                'hero_id' => 2,
                'additional_skill_name' => 'Niziołków',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 45,
                'hero_id' => 2,
                'additional_skill_name' => null,
                'first_level' => 1,
                'second_level' => 0,
            ],
            [
                'skill_id' => 48,
                'hero_id' => 2,
                'additional_skill_name' => null,
                'first_level' =>0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 23,
                'hero_id' => 2,
                'additional_skill_name' => 'Złodziei',
                'first_level' =>0,
                'second_level' => 0,
            ],
        ]);
        DB::table('hero_talent')->insert([
            [
                'talent_id' => 31,
                'hero_id' => 2,
                'additional_talent_name' => 'Proce'
            ],
            [
                'talent_id' => 37,
                'hero_id' => 2,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 58,
                'hero_id' => 2,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 44,
                'hero_id' => 2,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 71,
                'hero_id' => 2,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 55,
                'hero_id' => 2,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 7,
                'hero_id' => 2,
                'additional_talent_name' => null
            ],
        ]);

        Hero::create([
            'user_id' => 3,
            'name' => "Santiago",
            'race' => "Człowiek",
            'current_profession_id' => 34,
            'previous_profession_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'current_experience' => 180,
            'all_experience' => null,
            'current_wounds' => 11,
            'gold_crowns' => 5,
            'silver_shillings' => 16,
            'brass_pennies' => 10,
            'fortune_points' => 2,
        ]);
        HeroDescription::create([
            'hero_id' => 3,
            'age' => 0,
            'gender' => 'M',
            'eye_color' => '',
            'hair_color' => '',
            'star_sign' => '',
            'weight' => 0,
            'height' => 0,
            'siblings' => '',
            'place_of_birth' => '',
            'distinguishing_signs' => ''
        ]);
        HeroCharacteristic::insert([
            [
                'hero_id' => 3,
                'name' => 'Walka wręcz',
                'short_name' => 'WW',
                'start_value' => 34,
                'advancement' => null,
                'current_value' => 34,
            ],
            [
                'hero_id' => 3,
                'name' => 'Umiejętności strzeleckie',
                'short_name' => 'US',
                'start_value' => 35,
                'advancement' => 5,
                'current_value' => 35,
            ],
            [
                'hero_id' => 3,
                'name' => 'Krzepa',
                'short_name' => 'K',
                'start_value' => 31,
                'advancement' => 10,
                'current_value' => 41,
            ],
            [
                'hero_id' => 3,
                'name' => 'Odporność',
                'short_name' => 'Odp',
                'start_value' => 34,
                'advancement' => 5,
                'current_value' => 34,
            ],
            [
                'hero_id' => 3,
                'name' => 'Zręczność',
                'short_name' => 'Zr',
                'start_value' => 37,
                'advancement' => 10,
                'current_value' => 47,
            ],
            [
                'hero_id' => 3,
                'name' => 'Inteligencja',
                'short_name' => 'Int',
                'start_value' => 32,
                'advancement' => 5,
                'current_value' => 32,
            ],
            [
                'hero_id' => 3,
                'name' => 'Siła woli',
                'short_name' => 'SW',
                'start_value' => 32,
                'advancement' => null,
                'current_value' => 32,
            ],
            [
                'hero_id' => 3,
                'name' => 'Ogłada',
                'short_name' => 'Ogd',
                'start_value' => 29,
                'advancement' => null,
                'current_value' => 29,
            ],
            [
                'hero_id' => 3,
                'name' => 'Ataki',
                'short_name' => 'A',
                'start_value' => 1,
                'advancement' => null,
                'current_value' => 1,
            ],
            [
                'hero_id' => 3,
                'name' => 'Żywotność',
                'short_name' => 'Zyw',
                'start_value' => 11,
                'advancement' => 2,
                'current_value' => 11,
            ],
            [
                'hero_id' => 3,
                'name' => 'Siła',
                'short_name' => 'S',
                'start_value' => 3,
                'advancement' => null,
                'current_value' => 3,
            ],
            [
                'hero_id' => 3,
                'name' => 'Wytrzymałość',
                'short_name' => 'Wt',
                'start_value' => 3,
                'advancement' => null,
                'current_value' => 3,
            ],
            [
                'hero_id' => 3,
                'name' => 'Szybkość',
                'short_name' => 'Sz',
                'start_value' => 4,
                'advancement' => null,
                'current_value' => 4,
            ],
            [
                'hero_id' => 3,
                'name' => 'Magia',
                'short_name' => 'Mag',
                'start_value' => 0,
                'advancement' => null,
                'current_value' => null,
            ],
            [
                'hero_id' => 3,
                'name' => 'Punkty obłędu',
                'short_name' => 'PO',
                'start_value' => 0,
                'advancement' => null,
                'current_value' => null,
            ],
            [
                'hero_id' => 3,
                'name' => 'Punkty przeznaczenia',
                'short_name' => 'PP',
                'start_value' => 2,
                'advancement' => null,
                'current_value' => 2,
            ],
        ]);
        DB::table('hero_weapons')->insert([
            [
                'hero_id' => 3,
                'weapon_id' => 19,
                'additional_weapon_name' => null
            ],
            [
                'hero_id' => 3,
                'weapon_id' => 41,
                'additional_weapon_name' => null
            ]
        ]);
        DB::table('hero_armors')->insert([
            [
                'hero_id' => 3,
                'armor_id' => 3
            ],
        ]);
        DB::table('hero_skill')->insert([
            [
                'skill_id' => 13,
                'hero_id' => 3,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 17,
                'hero_id' => 3,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 18,
                'hero_id' => 3,
                'additional_skill_name' => null,
                'first_level' =>0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 27,
                'hero_id' => 3,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 28,
                'hero_id' => 3,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 38,
                'hero_id' => 3,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 44,
                'hero_id' => 3,
                'additional_skill_name' => 'Staroświatowy',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 44,
                'hero_id' => 3,
                'additional_skill_name' => 'Norski',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 36,
                'hero_id' => 3,
                'additional_skill_name' => 'Imperium',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 36,
                'hero_id' => 3,
                'additional_skill_name' => 'Jałowa kraina',
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 14,
                'hero_id' => 3,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
            [
                'skill_id' => 46,
                'hero_id' => 3,
                'additional_skill_name' => null,
                'first_level' => 0,
                'second_level' => 0,
            ],
        ]);
        DB::table('hero_talent')->insert([
            [
                'talent_id' => 38,
                'hero_id' => 3,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 40,
                'hero_id' => 3,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 54,
                'hero_id' => 3,
                'additional_talent_name' => null
            ],
            [
                'talent_id' => 25,
                'hero_id' => 3,
                'additional_talent_name' => null
            ],
        ]);
    }
}
