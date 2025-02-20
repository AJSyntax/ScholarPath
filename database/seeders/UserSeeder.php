<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@scholarpath.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create test students
        $students = [
            [
                'name' => 'John Smith',
                'email' => 'john@scholarpath.test',
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria@scholarpath.test',
            ],
            [
                'name' => 'David Chen',
                'email' => 'david@scholarpath.test',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@scholarpath.test',
            ],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
        }
    }
}
