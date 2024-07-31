<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Lk\ParticipantRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ParticipantRegistrationRequest extends ParticipantRequest {
    public function rules(): array {

        return parent::rules() + [
            'password' => ['required', 'confirmed', Password::min(6)],
        ];

    }


    public function messages(){
        return parent::messages() + [
            'password.min' => 'Количество символов в пароле должно быть не меньше :min.'
        ];
    }

}
