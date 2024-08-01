<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class WidgetRequest extends FormRequest {


    public function rules(): array {
        return [
            'content' => 'nullable'
        ];
    }
}
