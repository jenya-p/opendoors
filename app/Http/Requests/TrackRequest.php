<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackRequest extends FormRequest {


    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'required_edu_level_ids' => 'nullable|array',
            'required_edu_level_ids.*' => 'required|exists:edu_levels,id',
        ];
    }
}
