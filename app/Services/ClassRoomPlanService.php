<?php

namespace App\Services;

use App\Models\ClassRoom;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class ClassRoomPlanService
{
    /**
     * Функция для синхронизации лекций с порядком
     * @param ClassRoom $classroom
     * @param array $lecturesWithOrder
     * @return void
     */
    public function syncLecturesWithOrder(ClassRoom $classroom, array $lecturesWithOrder): void
    {
        try {
            DB::beginTransaction();
            $syncData = [];
            foreach ($lecturesWithOrder as $lecture) {
                $syncData[$lecture['id']] = ['order' => $lecture['order']];
            }
            $classroom->lectures()->sync($syncData);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw new HttpResponseException(response()->json([
                'errors' => $e,
            ], 422));
        }
    }
}
