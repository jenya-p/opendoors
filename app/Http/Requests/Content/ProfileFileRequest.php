<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileFileRequest extends FormRequest {


    public function rules(): array {
        return [
            'profile_id' => 'required|integer|exists:profiles',
            'status' => ['required', Rule::in(['active', 'disabled'])],
            'order' => ['required', 'integer'],
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'summary' => 'required|string',
            'summary_en' => 'nullable|string',
        ];
    }
}
