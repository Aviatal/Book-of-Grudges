<?php

namespace Database\Seeders;

use App\Models\HeroCharacteristic;
use Illuminate\Database\Seeder;

class HeroCharacteristicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $advancement = [null, 5, 10, 15, 20];
        HeroCharacteristic::insert([
            [
                'hero_id' => 1,
                'name' => 'Walka Wręcz',
                'short_name' => 'WW',
                'start_value' => rand(10, 40),
                'advancement' => $advancement[array_rand($advancement)],
                'current_value' => rand(40, 80),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Umiejętności strzeleckie',
                'short_name' => 'US',
                'start_value' => rand(10, 40),
                'advancement' => $advancement[array_rand($advancement)],
                'current_value' => rand(40, 80),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Krzepa',
                'short_name' => 'K',
                'start_value' => rand(10, 40),
                'advancement' => $advancement[array_rand($advancement)],
                'current_value' => rand(40, 80),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Odporność',
                'short_name' => 'Odp',
                'start_value' => rand(10, 40),
                'advancement' => $advancement[array_rand($advancement)],
                'current_value' => rand(40, 80),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Zręczność',
                'short_name' => 'Zr',
                'start_value' => rand(10, 40),
                'advancement' => $advancement[array_rand($advancement)],
                'current_value' => rand(40, 80),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Inteligencja',
                'short_name' => 'Int',
                'start_value' => rand(10, 40),
                'advancement' => $advancement[array_rand($advancement)],
                'current_value' => rand(40, 80),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Siła woli',
                'short_name' => 'SW',
                'start_value' => rand(10, 40),
                'advancement' => $advancement[array_rand($advancement)],
                'current_value' => rand(40, 80),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Ogłada',
                'short_name' => 'Ogd',
                'start_value' => rand(10, 40),
                'advancement' => $advancement[array_rand($advancement)],
                'current_value' => rand(40, 80),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Ataki',
                'short_name' => 'A',
                'start_value' => 1,
                'advancement' => rand(0, 1),
                'current_value' => rand(1, 2),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Żywotność',
                'short_name' => 'Zyw',
                'start_value' => rand(8, 12),
                'advancement' => rand(1, 3),
                'current_value' => rand(12, 16),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Siła',
                'short_name' => 'S',
                'start_value' => rand(0, 3),
                'advancement' => null,
                'current_value' => rand(0, 3),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Wytrzymałość',
                'short_name' => 'Wt',
                'start_value' => rand(0, 3),
                'advancement' => null,
                'current_value' => rand(0, 3),
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Szybkość',
                'short_name' => 'Sz',
                'start_value' => 3,
                'advancement' => null,
                'current_value' => 3,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Magia',
                'short_name' => 'Mag',
                'start_value' => 0,
                'advancement' => null,
                'current_value' => 0,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Punkty obłędu',
                'short_name' => 'PO',
                'start_value' => 0,
                'advancement' => null,
                'current_value' => 0,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'hero_id' => 1,
                'name' => 'Punkty przeznaczenia',
                'short_name' => 'PP',
                'start_value' => rand(1, 3),
                'advancement' => null,
                'current_value' => rand(0, 1),
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }
}
