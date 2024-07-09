<?php

namespace App\Http\Requests\Quiz;

use App\Models\Quiz\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest {


    public function rules(): array {

        $rules = [
            'quiz_id' => ['required', Rule::exists('quizzes', 'id')],
            'group_id' => ['required', Rule::exists('quiz_groups', 'id')],
            'type' => ['required', Rule::in(array_keys(Question::TYPE_NAMES))],

            'text' => 'required|string',
            'text_en' => 'required|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
        ];

        if($this->type == Question::TYPE_ONE){
            $rules += [
                'options' => 'required|array',
                'options.options' => 'required|array|min:2|max:100',
                'options.options.*' => 'required|array',
                'options.options.*.text' => 'required|string',
                'options.options.*.text_en' => 'required|string',
                'verification' => 'required|array',
                'verification.correct' => 'required|integer',
            ];
        } else if($this->type == Question::TYPE_MANY){
            $rules += [
                'options' => 'required|array',
                'options.options' => 'required|array|min:2|max:100',
                'options.options.*' => 'required|array',
                'options.options.*.text' => 'required|string',
                'options.options.*.text_en' => 'required|string',
                'verification' => 'required|array',
                'verification.correct' => 'required|array',
                'verification.correct.*' => 'required|integer',
            ];
        } else if($this->type == Question::TYPE_MULTI){
            $rules += [
                'options' => 'required|array',
                'options.options' => 'required|array|min:2|max:100',
                'options.options.*' => 'required|array',
                'options.options.*.text' => 'required|string',
                'options.options.*.text_en' => 'required|string',
                'verification' => 'required|array',
                'verification.correct' => 'required|array',
                'verification.correct.*' => 'required|integer',
                'verification.weightsTable' => 'required|array',
                'verification.weightsTable.*' => 'required|array',
                'verification.weightsTable.*.*' => 'required|integer',
            ];
        } else if($this->type == Question::TYPE_NUMBER){
            $rules += [
                'options.min' => 'nullable|numeric',
                'options.max' => 'nullable|numeric',
                'options.step' => 'nullable|numeric',
                'options.type' => ['required', Rule::in(['single', 'range'])]
                ];
            if($this->type == 'range'){
                $rules['verification'] = ['required|array|min:2|max:2'];
                $rules['verification.*'] = ['required|numeric'];
            } else if($this->type == 'single'){
                $rules['verification'] = ['required|numeric'];
            }

        } else if($this->type == Question::TYPE_WORDS){
            $rules += [
                'verification.pattern' => 'required|string',
                'verification.pattern_en' => 'required|string',
            ];
        } else if($this->type == Question::TYPE_FREE){
            $rules += [
                'options.max' => 'nullable|numeric',
                'options.uploads' => 'required|boolean',
                'verification.guide' => 'nullable|string'
            ];
        } else if($this->type == Question::TYPE_MATCH){
            $rules += [
                'options' => 'required|array',
                'options.options' => 'required|array|min:2|max:100',
                'options.options.*' => 'required|array',
                'options.options.*.text' => 'required|string',
                'options.options.*.text_en' => 'required|string',
                'options.categories' => 'required|array|min:2|max:100',
                'options.categories.*' => 'required|array',
                'options.categories.*.text' => 'required|string',
                'options.categories.*.text_en' => 'required|string',
                'verification' => 'required|array',
                'verification.matches' => 'required|array',
                'verification.matches.*' => 'required|integer',
                'verification.weights' => 'required|array',
                'verification.weights.*' => 'required|integer',
            ];
        }

        return  $rules;
    }

    public function messages() {
        $ret = parent::messages();

        if($this->type == Question::TYPE_MATCH) {

        }
        return $ret;
    }

}
