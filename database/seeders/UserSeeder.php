<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Nestor Daniel',
            'email' => 'trabajo.nestor.098@gmail.com',
            'password' => bcrypt('cronos098'),
        ]);

        $user->role()->sync(1);
    }
}
