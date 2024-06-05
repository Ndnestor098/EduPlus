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
                'course' => 1, 
                'work_type_id' => 5,
                'subject' => $teacher->subject
            ]);

            $name = $faker->sentence();

            Work::create([
                'title' => $name,
                'slug' => $name,
                'description' => $faker->paragraph(40),
                'scored' => 50,
                'course' => 1,
                'file' => json_encode(["/storage/files/665f2cef81a0f.pdf","/storage/files/665f2cef849f8.pdf","/storage/files/665f2cef868fa.pdf"]),
                'image' => json_encode(["/storage/image/665f2d0d8bd8c.webp","/storage/image/665f2d0d8d697.webp","/storage/image/665f2d0d8d81b.webp"]),
                'deliver' => '2024-07-04',
                'subject' => $teacher->subject,
                'work_type_id' => 5,
                'public' => 1
            ]);
        }
    }
}
