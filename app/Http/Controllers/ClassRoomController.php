<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRoom\StoreClassRoomPlanRequest;
use App\Http\Requests\ClassRoom\StoreClassRoomRequest;
use App\Http\Requests\ClassRoom\UpdateClassRoomPlanRequest;
use App\Http\Requests\ClassRoom\UpdateClassRoomRequest;
use App\Models\ClassRoom;
use App\Services\ClassRoomPlanService;
use Illuminate\Http\JsonResponse;

class ClassRoomController extends Controller
{

    protected ClassRoomPlanService $classRoomService;

    public function __construct(ClassRoomPlanService $classRoomService)
    {
        $this->classRoomService = $classRoomService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $classrooms = ClassRoom::with('students')->get();
        return response()->json($classrooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassRoomRequest $request): JsonResponse
    {
        $classroom = ClassRoom::create($request->validated());
        return response()->json($classroom, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassRoom $classroom): JsonResponse
    {
        return response()->json($classroom->load('students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassRoomRequest $request, ClassRoom $classroom): JsonResponse
    {
        $classroom->update($request->validated());
        return response()->json($classroom);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $classroom): JsonResponse
    {
        $classroom->students()->update(['class_room_id' => null]);
        $classroom->delete();
        return response()->json(["status" => true], 204);
    }

    /**
     * создаем учебный план
     * @param StoreClassRoomPlanRequest $request
     * @param ClassRoom $classroom
     * @return JsonResponse
     */
    public function createPlan(StoreClassRoomPlanRequest $request, ClassRoom $classroom): JsonResponse
    {

        // Создаем новый учебный план
        $this->classRoomService->syncLecturesWithOrder($classroom, $request->validated()['lectures']);

        return response()->json([
            'message' => 'Plan created successfully',
            'classroom_id' => $classroom->id,
            'lectures' => $classroom->lectures()->orderBy('pivot_order')->get()
        ]);
    }

    /**
     * показываем конкретный план
     * @param ClassRoom $classroom
     * @return JsonResponse
     */
    public function showPlan(ClassRoom $classroom): JsonResponse
    {
        $lectures = $classroom->lectures()
            ->orderBy('pivot_order')
            ->get(['lectures.*', 'class_room_lecture.order as order']);

        return response()->json([
            'class_name' => $classroom->name, // указано, что нужен только список лекций
            'lectures' => $lectures
        ]);
    }

    /**
     * обновляем учебный план
     * @param UpdateClassRoomPlanRequest $request
     * @param ClassRoom $classroom
     * @return JsonResponse
     */
    public function updatePlan(UpdateClassRoomPlanRequest $request, ClassRoom $classroom): JsonResponse
    {
        // Обновляем учебный план
        $this->classRoomService->syncLecturesWithOrder($classroom, $request->validated()['lectures']);

        return response()->json([
            'message' => 'Plan updated successfully',
            'classroom_id' => $classroom->id,
            'lectures' => $classroom->lectures()->orderBy('pivot_order')->get()
        ]);
    }
}
