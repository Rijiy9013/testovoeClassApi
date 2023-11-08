<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;

class CheckController extends Controller
{
    public function check()
    {
        $classroom = ClassRoom::with('students')->find(1);
        dd($classroom->students);
    }
}
