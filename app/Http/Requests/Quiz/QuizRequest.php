<?php

namespace App\Http\Requests\Quiz;

use App\Models\Quiz\Quiz;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizRequest extends FormRequest {


    public function rules(): array {

        $rules = [
            'groups' => 'nullable|array',
            'groups.*' => 'required|array',
            'groups.*.id' => 'nullable|integer|exists:quiz_groups',
            'groups.*.weight' => 'required|numeric|min:0|max:10000',
            'groups.*.theme' => 'nullable|array',
            'groups.*.theme.id' => 'nullable|integer|exists:quiz_themes',
            'groups.*.theme.name' => 'nullable|string|max:255',

            'roles' =>  'nullable|array',
            'roles.*' =>  'required|array',
            'roles.*.user_id' => 'required|exists:users,id',
            'roles.*.role' =>  ['required', Rule::in(array_keys(Quiz::ROLE_NAMES))],
        ];

        return  $rules;
    }
}
