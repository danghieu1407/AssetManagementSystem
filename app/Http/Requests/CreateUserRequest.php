<?php

namespace App\Http\Requests;

use App\Rules\JoinedDateWeekend;
use App\Rules\LatinName;
use App\Rules\Over18;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:128', new LatinName()],
            'last_name' => ['required', 'string', 'max:128', new LatinName()],
            'date_of_birth' => ['required', 'date', new Over18()],
            'joined_date' => ['required', 'date', 'after:date_of_birth', new JoinedDateWeekend()],
            'admin' => ['required', 'bool', Rule::in([0, 1])],
            'gender' => ['required', 'integer', Rule::in([0, 1])],
        ];
    }
    public function messages()
    {
        return [
            'joined_date.after' => 'Joined date is not later than Date of Birth. Please select a different date',
            'first_name.required' => 'Please input first name',
            'last_name.required' => 'Please input last name',
        ];
    }
    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(
            [
                'message' => $errors,
            ],
            400
        ));
    }
}
