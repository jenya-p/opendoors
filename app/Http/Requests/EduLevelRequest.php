<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EduLevelRequest extends FormRequest {


    public function rules(): array {
        return [
            'order' => 'nullable|int|min:0',
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'multiple' => 'required|boolean',
            'diploma' => 'required|boolean',
        ];
    }
}
