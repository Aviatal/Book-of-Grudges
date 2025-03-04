<?php

namespace Database\Seeders;

use App\Models\HeroDescription;
use Illuminate\Database\Seeder;

class HeroDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HeroDescription::factory()->count(1)->create();
    }
}
