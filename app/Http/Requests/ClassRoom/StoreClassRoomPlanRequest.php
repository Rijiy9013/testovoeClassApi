<?php

namespace App\Http\Requests\ClassRoom;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StoreClassRoomPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Предполагаем, что 'lectures' - это массив с элементами, имеющими 'id' и 'order'
            'lectures' => [
                'array',
                'required',
                function ($attribute, $value, $fail) { // проверка на уникальность лекций, можно вынести в отдельное правило
                    if (count($value) !== count(array_unique(Arr::pluck($value, 'id')))) {
                        return $fail('Each lecture in the class room plan must be unique.');
                    }
                },
            ],
            'lectures.*.id' => 'required|exists:lectures,id',
            'lectures.*.order' => 'required|integer|min:1'
        ];
    }

    protected function failedValidation(Validator $validator): HttpResponseException
    {
        $response = response()->json([
            'errors' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Проверяем, есть ли уже учебный план для класса
            if ($this->classRoomHasPlan()) {
                $validator->errors()->add('class_room_id', 'Plan already exists');
            }
        });
    }

    /**
     * проверка, существует ли учебный план для класса.
     * @return bool
     */
    private function classRoomHasPlan(): bool
    {
        // Используем route() для извлечения переменной {classroom} из URL, если она там есть
        $classRoomId = $this->route('classroom');

        // Если класс не найден, вернём false
        if (!$classRoomId) {
            return false;
        }

        // Проверяем, есть ли связанные записи в таблице class_room_lecture
        $count = DB::table('class_room_lecture')
            ->where('class_room_id', $classRoomId)
            ->count();

        return $count > 0;
    }
}
