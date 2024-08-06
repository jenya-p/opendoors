<?php

namespace App\Http\Requests\Lk;

use App\Models\EduLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

class DegreeRequest extends FormRequest {



    public function rules(): array {

        return [
            'edu_level_id' => 'required|exists:edu_levels,id',
            'country_id' => 'required|exists:dir_countries,id',
            'name' => 'required|string',
            'graduation_year' => 'required|integer|min:1950|max:' . date('Y'),
            'diploma_title' => Rule::requiredIf(function(){
                $el = EduLevel::find($this->edu_level_id);
                return !empty($el) && $el->diploma;
            }),
            'is_study_russian' => 'required|boolean',
            'is_study_english' => 'required|boolean',
            'attachments' => 'required|array',
            'attachments.*' => 'required|array',
            'attachments.*.id' => 'required|exists:attachments,id',
        ];


    }




}
