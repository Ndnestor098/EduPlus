<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Traits\UniqueSubjects;  // Asegúrate de importar el trait

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    use UniqueSubjects;

    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cellphone' => $this->faker->phoneNumber(),
            'subject' => static::getUniqueSubject(),  // Llama al método estático del trait
            'salary' => $this->faker->randomElement([1200, 1100, 1400]),
            'started' => $this->faker->date(),
            'password' => Hash::make('cronos'),
        ];
    }
}
