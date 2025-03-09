<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProfessionSeeder::class,
            WeaponSeeder::class,
            WeaponTraitSeeder::class,
            ArmorsSeeder::class,
            SkillsSeeder::class,
            TalentsSeeder::class,
        ]);
    }
}
