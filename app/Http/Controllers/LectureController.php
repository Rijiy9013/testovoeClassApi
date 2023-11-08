<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lecture\StoreLectureRequest;
use App\Http\Requests\Lecture\UpdateLectureRequest;
use App\Models\Lecture;
use Illuminate\Http\JsonResponse;

class LectureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $lectures = Lecture::all();
        return response()->json($lectures);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLectureRequest $request): JsonResponse
    {
        $lecture = Lecture::create($request->validated());
        return response()->json($lecture, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lecture $lecture): JsonResponse
    {
        return response()->json($lecture->load('classrooms.students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLectureRequest $request, Lecture $lecture): JsonResponse
    {
        $lecture->update($request->validated());
        return response()->json($lecture);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecture $lecture): JsonResponse
    {
        $lecture->delete();
        return response()->json(["status" => true], 204);
    }
}
