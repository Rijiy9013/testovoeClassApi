<?php

namespace App\Http\Requests\ClassRoom;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateClassRoomRequest extends FormRequest
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
            'name' => 'required|unique:class_rooms,name,' . $this->classroom->id,
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'errors' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}