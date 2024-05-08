<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'matematicas' => $this->faker->numberBetween(1, 10),
            'fisica' => $this->faker->numberBetween(1, 10), 
            'ciencia' => $this->faker->numberBetween(1, 10), 
            'historia' => $this->faker->numberBetween(1, 10), 
            'ingles' => $this->faker->numberBetween(1, 10), 
            'literatura' => $this->faker->numberBetween(1, 10), 
            'arte' => $this->faker->numberBetween(1, 10), 
            'computacion' => $this->faker->numberBetween(1, 10)
        ];
    }
}
