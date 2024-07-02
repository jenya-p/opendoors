<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest {


    public function rules(): array {

        $rules = [
            'groups' => 'required|array',
            'groups.*' => 'required|array',
            'groups.*.id' => 'nullable|integer|exists:quiz_groups',
            'groups.*.weight' => 'required|numeric|min:0|max:10000'
        ];

        return  $rules;
    }
}
