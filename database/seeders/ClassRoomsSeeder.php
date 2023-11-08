<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Lecture;
use Illuminate\Database\Seeder;

class ClassRoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lectures = Lecture::all();

        ClassRoom::factory(10)->create()->each(function ($classroom) use ($lectures) {
            $lecturesOrder = [];
            $selectedLectures = $lectures->random(rand(5, 15));

            $orders = range(1, $lectures->count()); // Создаём диапазон возможных порядков
            shuffle($orders); // Перемешиваем его для случайности

            foreach ($selectedLectures as $lecture) {
                $lecturesOrder[$lecture->id] = ['order' => array_pop($orders)];
            }

            $classroom->lectures()->sync($lecturesOrder);
        });
    }
}
