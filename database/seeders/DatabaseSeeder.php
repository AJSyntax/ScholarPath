<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\StudentProfileSeeder;
use Database\Seeders\ScholarshipSeeder;
use Database\Seeders\ScholarshipApplicationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            StudentProfileSeeder::class,
            ScholarshipSeeder::class,
            ScholarshipApplicationSeeder::class,
        ]);
    }
}
