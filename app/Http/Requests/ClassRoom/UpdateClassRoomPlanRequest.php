<?php

namespace App\Http\Requests\ClassRoom;

use App\Rules\UniqueLectureRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
                new UniqueLectureRule(),
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
