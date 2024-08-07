<?php

namespace App\Http\Requests\Lk;

use App\Models\EduLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

class AchievementRequest extends FormRequest {



    public function rules(): array {

        return [
            'type_id' => 'required|exists:dir_achievements,id',
            'content' => 'required|array',

        ];


    }




}
