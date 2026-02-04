<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'password' => Hash::make('adi123'),
                'full_name' => 'Adi Cita Sinrawa (Administrator)',
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'username' => 'Rezka',
                'password' => Hash::make('reskares9'),
                'full_name' => 'Rezka (DEVELOPER)',
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'username' => 'security1',
                'password' => Hash::make('password'),
                'full_name' => 'Budi Santoso',
                'role' => 'security',
                'is_active' => true,
            ],
            [
                'username' => 'security2',
                'password' => Hash::make('password'),
                'full_name' => 'Andi Wijaya',
                'role' => 'security',
                'is_active' => true,
            ],
            [
                'username' => 'maintenance1',
                'password' => Hash::make('password'),
                'full_name' => 'Joko Susilo',
                'role' => 'maintenance',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}