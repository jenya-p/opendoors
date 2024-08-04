<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class FaqCategoryRequest extends FormRequest {


    public function rules(): array {
        return [
            'name' => 'required|string',
            'name_en' => 'nullable|string',
        ];
    }
}
