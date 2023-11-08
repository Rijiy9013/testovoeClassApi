<?php

namespace App\Http\Requests\ClassRoom;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;

class UpdateClassRoomPlanRequest extends FormRequest
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
                'required',
                'array',
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
}
