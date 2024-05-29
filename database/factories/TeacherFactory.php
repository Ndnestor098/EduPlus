<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    protected static ?string $password;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * 
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'cellphone' => $this->faker->phoneNumber(),
            'subject' => $this->faker->randomElement(['matematicas', 'fisica', 'ciencia', 'edu_fisica', 'historia', 'ingles', 'literatura', 'arte', 'computacion', 'quimica']),
            'salary' => $this->faker->randomElement([1200, 1100, 1400]),
            'started' => $this->faker->date(),
            'password' => Hash::make('cronos'),
        ];
    }
}
