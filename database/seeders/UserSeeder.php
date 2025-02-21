<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@scholarpath.test'],
            [
            'name' => 'Admin User',
            'email' => 'admin@scholarpath.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create test students if not exist
        $students = [
            ['name' => 'John Smith', 'email' => 'john@scholarpath.test'],
            ['name' => 'Maria Garcia', 'email' => 'maria@scholarpath.test'],
            ['name' => 'David Chen', 'email' => 'david@scholarpath.test'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah@scholarpath.test'],
            ['name' => 'Michael Lee', 'email' => 'michael@scholarpath.test'],
            ['name' => 'Emily Brown', 'email' => 'emily@scholarpath.test'],
            ['name' => 'James Wilson', 'email' => 'james@scholarpath.test'],
            ['name' => 'Sofia Rodriguez', 'email' => 'sofia@scholarpath.test'],
            ['name' => 'Lucas Kim', 'email' => 'lucas@scholarpath.test'],
            ['name' => 'Isabella Martinez', 'email' => 'isabella@scholarpath.test'],
            ['name' => 'William Taylor', 'email' => 'william@scholarpath.test'],
            ['name' => 'Olivia Anderson', 'email' => 'olivia@scholarpath.test'],
            ['name' => 'Alexander Nguyen', 'email' => 'alexander@scholarpath.test'],
            ['name' => 'Ava Thompson', 'email' => 'ava@scholarpath.test'],
            ['name' => 'Daniel Park', 'email' => 'daniel@scholarpath.test']
        ];

        foreach ($students as $student) {
            User::firstOrCreate(
                ['email' => $student['email']],
                [
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
        }
    }
}
