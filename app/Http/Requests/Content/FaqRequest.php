<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest {


    public function rules(): array {
        return [
            'category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string',
            'answer' => 'required|string',
            'question_en' => 'required|string',
            'answer_en' => 'required|string',
        ];
    }
}
