<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EduLevelRequest extends FormRequest {


    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ];
    }
}
