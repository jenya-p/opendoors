<?php

namespace App\Http\Requests\Quiz;

use App\Models\Quiz\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProbeRequest extends FormRequest {


    public function rules(): array {

        $rules = [
            'question_id' => ['required', Rule::exists('quiz_questions', 'id')],
            'locale' => ['required', Rule::in('ru', 'en')],
            'solution' => 'required|array',
        ];

        if($this->type == Question::TYPE_ONE){
            $rules += [
                'solution.choice' => 'required|array|min:1|max:1',
                'solution.choice.0' => ['required', 'integer', 'min:0',]
            ];
        } else if($this->type == Question::TYPE_MANY){
            $rules += [

            ];
        } else if($this->type == Question::TYPE_MULTI){
            $rules += [

            ];
        } else if($this->type == Question::TYPE_NUMBER){
            $rules += [

            ];
        } else if($this->type == Question::TYPE_WORDS){
            $rules += [

            ];
        } else if($this->type == Question::TYPE_FREE){
            $rules += [

            ];
        }

        return  $rules;
    }




}
