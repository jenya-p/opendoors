<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest {


    public function rules(): array {

        $rules = [
            'groups' => 'required|array',
            'groups.*' => 'required|array',
            'groups.*.id' => 'nullable|integer|exists:quiz_groups',
            'groups.*.weight' => 'required|numeric|min:0|max:10000',
            'groups.*.theme' => 'nullable|array',
            'groups.*.theme.id' => 'nullable|integer|exists:quiz_themes',
            'groups.*.theme.name' => 'nullable|string|max:255',
        ];

        return  $rules;
    }
}
