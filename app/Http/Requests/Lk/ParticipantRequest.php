<?php

namespace App\Http\Requests\Lk;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

class ParticipantRequest extends FormRequest {
    public function rules(): array {

        $phoneExclRule = '';
        $emailExclRule = '';
        if(!empty(\Auth::id())){
            $phoneExclRule = ','.\Auth::id().',user_id';
            $emailExclRule = ','.\Auth::id().',id';
        }


        return [
            'locale' => ['required', Rule::in('ru', 'en')],
            'citizenship_id' => 'required|integer|exists:dir_citizenships,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'sex' => ['required', Rule::in('m', 'f')],
            'birthdate' => 'required|date_format:Y-m-d',
            'phone' => ['required', 'string', 'max:32', 'min:4', 'unique:participants,phone' . $phoneExclRule],
            'email' => 'required|string|lowercase|email|max:255|unique:users,email' . $emailExclRule ,

            'edu_level_ids' => 'required|array|min:1',
            'edu_level_ids.*' => 'required|integer|exists:edu_levels,id',

            'members' => 'required|array|min:1',
            'members.*' => 'required|array',
            'members.*.track_id' => 'required|integer|exists:tracks,id',
            'members.*.profile_id' => 'required|integer|exists:profiles,id',
        ];
    }




}
