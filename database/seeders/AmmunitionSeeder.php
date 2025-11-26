<?php

namespace Database\Seeders;

use App\Models\Ammunition;
use Illuminate\Database\Seeder;

class AmmunitionSeeder extends Seeder
{
    public function run(): void
    {
        Ammunition::truncate();
        Ammunition::insert([
            [
                'name' => 'Strzały',
                'amount' => 5,
                'price' => 12,
                'loading' => 10
            ],
            [
                'name' => 'Bełty',
                'amount' => 5,
                'price' => 24,
                'loading' => 10
            ],
            [
                'name' => 'Kule do broni palnej',
                'amount' => 10,
                'price' => 12,
                'loading' => 10
            ],
            [
                'name' => 'Proch strzelniczy',
                'amount' => 1,
                'price' => 36,
                'loading' => 1
            ],
        ]);
    }
}
