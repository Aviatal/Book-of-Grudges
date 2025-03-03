<?php

namespace Database\Factories;

use App\Models\Hero;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class HeroFactory extends Factory
{
    protected $model = Hero::class;

    public function definition(): array
    {
        $races = ['Krasnolud', 'Niziołek', 'Człowiek', 'Elf'];
        return [
            'name' => $this->faker->name(),
            'race' => $races[array_rand(['Krasnolud', 'Niziołek', 'Człowiek', 'Elf'])],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => 1,
            'current_profession_id' => $this->faker->randomNumber(),
            'previous_profession_id' => $this->faker->randomNumber(),
        ];
    }
}
