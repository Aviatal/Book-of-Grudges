<?php

namespace Database\Seeders;

use App\Models\Hero;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class HeroSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heroes = Hero::query()->with('skills')->get();
        $skills = Skill::all();
        foreach ($heroes as $hero) {
            foreach ($skills as $skill) {
                if (!$hero->skills()->where('skills.id', $skill->id)->exists()) {
                    $hero->skills()->syncWithoutDetaching($skill->id);
                } else {
                    $hero->skills()->updateExistingPivot($skill->id, ['hurdled' => 1]);
                }
            }
        }
    }
}
