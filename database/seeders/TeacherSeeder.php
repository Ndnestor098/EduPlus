<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = Teacher::factory(9)->create();

        foreach($teachers as $teacher){
            User::create([
                'name' => $teacher->name,
                'email' => $teacher->email,
                'password' => bcrypt('cronos'),
            ])->role()->sync(2);
        }
    }
}
