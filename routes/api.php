<?php

use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('student')->group(function () {
    // 1) получить список всех студентов
    Route::get('/', [StudentController::class, 'index']);
    // 2) получить информацию о конкретном студенте (имя, email + класс + прослушанные лекции)
    Route::get('/{student}', [StudentController::class, 'show']);
    // 3) создать студента
    Route::post('/', [StudentController::class, 'store']);
    // 4) обновить студента (имя, принадлежность к классу)
    Route::put('/{student}', [StudentController::class, 'update']);
    // 5) удалить студента
    Route::delete('/{student}', [StudentController::class, 'destroy']);
});

Route::prefix('lecture')->group(function () {
    // 13) получить список всех лекций
    Route::get('/', [LectureController::class, 'index']);
    // 14) получить информацию о конкретной лекции (тема, описание + какие классы прослушали лекцию + какие студенты прослушали лекцию)
    Route::get('/{lecture}', [LectureController::class, 'show']);
    // 15) создать лекцию
    Route::post('/', [LectureController::class, 'store']);
    // 16) обновить лекцию (тема, описание)
    Route::put('/{lecture}', [LectureController::class, 'update']);
    // 17) удалить лекцию
    Route::delete('/{lecture}', [LectureController::class, 'destroy']);
});

Route::prefix('classroom')->group(function () {
    // 6) получить список всех классов
    Route::get('/', [ClassRoomController::class, 'index']);
    // 7) получить информацию о конкретном классе (название + студенты класса)
    Route::get('/{classroom}', [ClassRoomController::class, 'show']);
    // 10) создать класс
    Route::post('/', [ClassRoomController::class, 'store']);
    // 11) обновить класс (название)
    Route::put('/{classroom}', [ClassRoomController::class, 'update']);
    // 12) удалить класс (при удалении класса, привязанные студенты должны открепляться от класса, но не удаляться полностью из системы)
    Route::delete('/{classroom}', [ClassRoomController::class, 'destroy']);

    // 8) получить учебный план (список лекций) для конкретного класса
    Route::get('/{classroom}/plan', [ClassRoomController::class, 'showPlan']);
    // 9.1) создать учебный план (очередность и состав лекций) для конкретного класса
    Route::post('/{classroom}/plan', [ClassRoomController::class, 'createPlan']);
    // 9.2) обновить учебный план (очередность и состав лекций) для конкретного класса
    Route::put('/{classroom}/plan', [ClassRoomController::class, 'updatePlan']);
});

