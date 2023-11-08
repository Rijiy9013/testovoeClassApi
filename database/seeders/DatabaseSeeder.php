<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LecturesSeeder::class,
            ClassRoomsSeeder::class, // Этот сидер должен идти после LecturesSeeder
            StudentsSeeder::class,
        ]);
    }
}
