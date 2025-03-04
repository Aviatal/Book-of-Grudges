<?php

namespace Database\Factories;

use App\Models\HeroDescription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class HeroDescriptionFactory extends Factory
{
    protected $model = HeroDescription::class;

    public function definition(): array
    {
        return [
            'age' => $this->faker->randomNumber(),
            'gender' => $this->faker->randomElement(['K', 'M']),
            'eye_color' => $this->faker->word(),
            'hair_color' => $this->faker->word(),
            'star_sign' => $this->faker->word(),
            'weight' => $this->faker->randomNumber(),
            'height' => $this->faker->randomNumber(),
            'siblings' => $this->faker->word(),
            'place_of_birth' => $this->faker->word(),
            'distinguishing_signs' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'hero_id' => 1,
        ];
    }
}
