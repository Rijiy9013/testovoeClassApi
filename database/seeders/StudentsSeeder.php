<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Проверяем, есть ли классы в базе данных
        if (\App\Models\ClassRoom::count() == 0) {
            $this->command->info('Пожалуйста, сначала создайте классы.');
            return;
        }

        Student::factory(50)->create();
    }
}
