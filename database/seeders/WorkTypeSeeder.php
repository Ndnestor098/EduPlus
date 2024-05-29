<?php

namespace Database\Seeders;

use App\Models\WorkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkType::create([
            'name' => 'examen escrito'
        ]);
        WorkType::create([
            'name' => 'examen oral'
        ]);
        WorkType::create([
            'name' => 'exposicion'
        ]);
        WorkType::create([
            'name' => 'proyecto'
        ]);
        WorkType::create([
            'name' => 'tarea'
        ]);
        WorkType::create([
            'name' => 'participacion'
        ]);
        WorkType::create([
            'name' => 'conducta'
        ]);
        
    }
}
