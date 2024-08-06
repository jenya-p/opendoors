<?php

namespace App\Http\Requests\Lk;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

class StatementRequest extends FormRequest {



    public function rules(): array {

        return [
            'member_id' => 'required|exists:members,id',
            'area_ids' => 'required|array|min:1|max:3',
            'area_ids.*' => 'required|exists:dir_knowledge_areas,id',
            'interests' => 'required|string',
            'goals' => 'required|string',
            'achievements' => 'required|string',
            'motive' => 'required|string',
            'country' => 'required|string',
        ];


    }




}
