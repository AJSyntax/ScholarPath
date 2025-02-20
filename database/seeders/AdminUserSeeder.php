<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin user already exists
        $adminEmail = 'admin@scholarpath.test';
        
        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        }

        // Create a test student user
        User::create([
            'name' => 'Student User',
            'email' => 'student@scholarpath.test',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
    }
}
