<?php

namespace Database\Seeders;

use App\Models\student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::factory(100)->create();

        foreach($students as $student){
            $student->qualification()->create();

            User::create([
                'name' => $student->name,
                'email' => $student->email,
                'password' => bcrypt('cronos'),
                'timezone' => 'Europe/Rome'
            ])->role()->sync(3);
        }
    }
}
