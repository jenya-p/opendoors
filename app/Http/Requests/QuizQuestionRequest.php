<?php

namespace App\Http\Requests;

use App\Models\QuizQuestion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizQuestionRequest extends FormRequest {


    public function rules(): array {

        $rules = [
            'group_id' => ['required', Rule::exists('quiz_groups', 'id')],
            'type' => ['required', Rule::in(array_keys(QuizQuestion::TYPE_NAMES))],

            'order' => 'nullable|integer',
            'weight' => 'nullable|numeric',

            'text' => 'required|string',
            'text_en' => 'required|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',



        ];

        if($this->type == QuizQuestion::TYPE_ONE){
            $rules += [
                'options' => 'required|array',
                'options.options' => 'required|array|min:2|max:100',
                'options.options.*' => 'required|array',
                'options.options.*.text' => 'required|string',
                'options.options.*.text_en' => 'required|string',
                'verification' => 'required|array',
                'verification.correct' => 'required|integer',
            ];
        } else if($this->type == QuizQuestion::TYPE_MANY){
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
        } else if($this->type == QuizQuestion::TYPE_MULTI){
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
        } else if($this->type == QuizQuestion::TYPE_NUMBER){
            $rules += [
                'options.min' => 'nullable|numeric',
                'options.max' => 'nullable|numeric',
                'options.step' => 'nullable|numeric',
                'verification' => 'required|numeric',
            ];
        } else if($this->type == QuizQuestion::TYPE_WORDS){
            $rules += [
                'verification.pattern' => 'required|string',
                'verification.pattern_en' => 'required|string',
            ];
        } else if($this->type == QuizQuestion::TYPE_FREE){
            $rules += [
                'options.max' => 'nullable|numeric',
                'verification.guide' => 'nullable|string'
            ];
        }

        return  $rules;
    }
}
