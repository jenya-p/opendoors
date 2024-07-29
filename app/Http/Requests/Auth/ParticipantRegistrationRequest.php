<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ParticipantRegistrationRequest extends FormRequest {
    public function rules(): array {
        return [
            'locale' => ['required', Rule::in('ru', 'en')],
            'citizenship_id' => 'required|integer|exists:dir_citizenships,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'sex' => ['required', Rule::in('m', 'f')],
            'birthdate' => 'required|date_format:Y-m-d',
            'phone' => 'required|string|max:32|min:4|unique:participants,phone',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(6)],

            'edu_levels' => 'required|array',
            'edu_levels.*' => 'required|integer|exists:edu_levels,id',

            'membership' => 'required|array',
            'membership.*' => 'required|array',
            'membership.*.track_id' => 'required|integer|exists:tracks,id',
            'membership.*.profile_id' => 'required|integer|exists:profiles,id',
        ];
    }


    public function messages(){
        return [
            'password.min' => 'Количество символов в пароле должно быть не меньше :min.'
        ];
    }

}
