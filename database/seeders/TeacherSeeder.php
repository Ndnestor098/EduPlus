<?php

namespace Database\Seeders;

use App\Models\Percentages;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $teachers = Teacher::factory(9)->create();

        foreach($teachers as $teacher){
            User::create([
                'name' => $teacher->name,
                'email' => $teacher->email,
                'password' => bcrypt('cronos'),
                'timezone' => 'Europe/Rome'
            ])->role()->sync(2);

            Percentages::create([
                'percentage' => 50,
                'subject' => $teacher->subject,
                'course' => 1, 
                'work_type_id' => 5,
                'teacher_id' => $teacher->id
            ]);

            $name = $faker->sentence();

            Work::create([
                'title' => $name,
                'slug' => $name,
                'description' => $faker->paragraph(40),
                'scored' => 50,
                'mtcf' => 'Tarea',
                'course' => 1,
                'file' => $faker->randomElement(['/storage/files/6659fa036711e.pdf', null]),
                'image' => $faker->randomElement(['/storage/image/6659ecd408f16.jpeg', null]),
                'subject' => $teacher->subject,
                'deliver' => '2024-07-04',
                'teacher_id' => $teacher->id,
                'public' => 1
            ]);
        }
    }
}
