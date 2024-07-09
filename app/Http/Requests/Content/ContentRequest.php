<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class ContentRequest extends FormRequest {


    public function rules(): array {
        return [
            'title' => 'required|string|max:512',
            'title_en' => 'nullable|string|max:512',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
        ];
    }
}
