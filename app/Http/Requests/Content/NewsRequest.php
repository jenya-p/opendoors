<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest {


    public function rules(): array {
        return [
            'date' => 'required|date_format:Y-m-d',
            'title' => 'required|string|max:512',
            'title_en' => 'nullable|string|max:512',
            'summary' => 'required|string',
            'summary_en' => 'nullable|string',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
        ];
    }
}
